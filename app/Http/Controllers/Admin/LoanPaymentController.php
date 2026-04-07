<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LoanPayment;
use App\Models\LoanDetail;
use App\Models\LoanApplication;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class LoanPaymentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = LoanDetail::with('loanPayments', 'user');

            if ($request->has('employee_id') && !empty($request->employee_id)) {
                $query->where('employee_ID', $request->employee_id);
            }

            $loans = $query->get()->map(function ($loan) {
                return [
                    'loan_id' => $loan->loan_id,
                    'employee_ID' => $loan->employee_ID,
                    'employee_name' => $loan->user->name,
                    'loan_amount' => $loan->loan_amount,
                    'paid_amount' => $loan->loanPayments->total_payments ?? 0,
                    'outstanding_balance' => $loan->loanPayments->outstanding_balance ?? $loan->loan_amount,
                    'latest_payment_date' => $loan->loanPayments->latest_payment ?? null,
                ];
            });

            return response()->json($loans);
        }

        // Load the Blade template when not using AJAX
        return view('admin.loan_payments');
    }

    public function storeLoanPayment(Request $request)
    {
        $validated = $request->validate([
            'loan_id' => 'required|string|exists:loan_details,loan_id',
            'total_payments' => 'required|numeric|min:1',
            'remittance_no' => 'required|string',
            'date_of_remittance' => 'required|date',
            'date_covered_month' => 'required|integer|min:1|max:12',
            'date_covered_year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        // ✅ Get the latest remittance from existing records
        $latestRemittance = LoanPayment::where('loan_id', $validated['loan_id'])
            ->latest('date_of_remittance')
            ->value('latest_remittance');

        // ✅ If no previous remittance exists, set it to the new `date_of_remittance`
        if (!$latestRemittance) {
            $latestRemittance = $validated['date_of_remittance'];
        }

        // ✅ Check if a payment for the same month and year already exists
        $existingPayment = LoanPayment::where('loan_id', $validated['loan_id'])
            ->where('date_covered_month', $validated['date_covered_month'])
            ->where('date_covered_year', $validated['date_covered_year'])
            ->exists();

        if ($existingPayment) {
            return response()->json([
                'success' => false,
                'message' => 'A payment for this month and year already exists.'
            ], 409); // HTTP 409 Conflict
        }



        // ✅ Fetch Loan Details
        $loan = LoanDetail::where('loan_id', $validated['loan_id'])->firstOrFail();

        // ✅ Fetch Previous Payments and Calculate New Outstanding Balance
        $previousTotalPayments = LoanPayment::where('loan_id', $validated['loan_id'])->sum('total_payments');
        $newTotalPayments = $previousTotalPayments + $validated['total_payments'];
        $latestOutstandingBalance = max(0, $loan->loan_amount - $newTotalPayments); // ✅ Prevents negative balances

        // Log values for debugging
        \Log::info("Loan ID: {$validated['loan_id']} | Previous Total Payments: {$previousTotalPayments} | New Total Payments: {$newTotalPayments} | New Outstanding Balance: {$latestOutstandingBalance}");

        // ✅ Log for debugging
        \Log::info("Loan ID: {$validated['loan_id']} | Latest Remittance: {$latestRemittance}");

        // ✅ Prevent saving 0-payment records
        if ($validated['total_payments'] > 0) {
            // ✅ Save New Payment Entry
            $loanPayment = new LoanPayment([
                'loan_id' => $validated['loan_id'],
                'total_payments' => $validated['total_payments'],
                'total_payments_count' => LoanPayment::where('loan_id', $validated['loan_id'])->count() + 1,
                'outstanding_balance' => $latestOutstandingBalance,
                'latest_remittance' => $latestRemittance,
                'remittance_no' => $validated['remittance_no'],
                'date_of_remittance' => $validated['date_of_remittance'],
                'date_covered_month' => $validated['date_covered_month'],
                'date_covered_year' => $validated['date_covered_year'],
            ]);
            $loanPayment->save();
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Invalid payment amount. Payment must be greater than 0.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'loan_payment' => [
                'total_payments' => number_format($newTotalPayments, 2),
                'latest_outstanding_balance' => number_format($latestOutstandingBalance, 2),
                'latest_remittance' => $loanPayment->latest_remittance, // ✅ Send correct value
                'date_of_remittance' => $loanPayment->date_of_remittance,
                'date_covered_month' => $loanPayment->date_covered_month,
                'date_covered_year' => $loanPayment->date_covered_year,
            ]
        ]);
    }


    public function storeBulkLoanPayments(Request $request)
    {
        $validated = $request->validate([
            'loans' => 'required|array',
            'loans.*.loan_id' => 'required|string|exists:loan_details,loan_id',
            'loans.*.total_payments' => 'required|numeric|min:1',
            'remittance_no' => 'required|string',
            'date_of_remittance' => 'required|date',
            'date_covered_month' => 'required|integer|min:1|max:12',
            'date_covered_year' => 'required|integer|min:2000|max:' . date('Y'),
        ]);

        $loanPayments = [];

        foreach ($validated['loans'] as $loanData) {
            $loan = LoanDetail::where('loan_id', $loanData['loan_id'])->firstOrFail();

            // Get previous total payments
            $previousTotalPayments = LoanPayment::where('loan_id', $loanData['loan_id'])->sum('total_payments');
            $latestOutstandingBalance = $loan->loan_amount - $previousTotalPayments;

            // 🚨 Prevent overpayment
            if ($loanData['total_payments'] > $latestOutstandingBalance) {
                return response()->json([
                    'success' => false,
                    'message' => "Payment for Loan ID {$loanData['loan_id']} cannot exceed the outstanding balance of " . number_format($latestOutstandingBalance, 2) . "."
                ], 400);
            }

            $newTotalPayments = $previousTotalPayments + $loanData['total_payments'];
            $latestOutstandingBalance = max(0, $loan->loan_amount - $newTotalPayments);

            // Get the current count of payments for this loan
            $totalPaymentsCount = LoanPayment::where('loan_id', $loanData['loan_id'])->count() + 1;

            // Save the new loan payment with total_payments_count
            $loanPayment = LoanPayment::create([
                'loan_id' => $loanData['loan_id'],
                'total_payments' => $loanData['total_payments'],
                'total_payments_count' => $totalPaymentsCount, // ✅ Now saving the total count of payments
                'outstanding_balance' => $latestOutstandingBalance,
                'latest_remittance' => $validated['date_of_remittance'], // ✅ Save Latest Remittance
                'remittance_no' => $validated['remittance_no'], // ✅ Save Remittance No.
                'date_of_remittance' => $validated['date_of_remittance'],
                'date_covered_month' => $validated['date_covered_month'],
                'date_covered_year' => $validated['date_covered_year'],
            ]);

            // Store the updated values for frontend update
            $loanPayments[$loanData['loan_id']] = [
                'total_payments' => number_format($newTotalPayments, 2),
                'latest_outstanding_balance' => number_format($latestOutstandingBalance, 2),
                'total_payments_count' => $totalPaymentsCount,
                'latest_remittance' => $loanPayment->latest_remittance,
                'remittance_no' => $loanPayment->remittance_no,
                'date_of_remittance' => $loanPayment->date_of_remittance,
                'date_covered_month' => $loanPayment->date_covered_month,
                'date_covered_year' => $loanPayment->date_covered_year,
            ];
        }

        return response()->json([
            'success' => true,
            'loan_payments' => $loanPayments
        ]);
    }


    public function getByRemittance($remittanceNo, $loanId)
    {
        $payment = LoanPayment::where('remittance_no', $remittanceNo)
            ->where('loan_id', $loanId)
            ->first();

        if (!$payment) {
            return response()->json(['success' => false]);
        }

        $loan = LoanDetail::where('loan_id', $loanId)->first();
        $user = $loan->user; // assuming the relationship is defined

        return response()->json([
            'success' => true,
            'payment' => $payment,
            'employee_id' => $user->employee_ID ?? null,
            'employee_name' => $user->name ?? null,
            'loan_type' => $loan->loan_type ?? null,
        ]);
    }
    public function updateLoanPayment(Request $request)
    {
        $request->validate([
            'remittance_no' => 'required|string',
            'total_payments' => 'required|numeric|min:0',
            'outstanding_balance' => 'required|numeric|min:0',
            'latest_remittance' => 'required|date',
        ]);

        $loanPayment = LoanPayment::where('remittance_no', $request->remittance_no)->first();

        if (!$loanPayment) {
            return response()->json(['success' => false, 'message' => 'Remittance not found.']);
        }

        $loanPayment->update([
            'total_payments' => $request->total_payments,
            'outstanding_balance' => $request->outstanding_balance,
            'latest_remittance' => $request->latest_remittance,
        ]);

        return response()->json(['success' => true, 'message' => 'Loan payment updated successfully.', 'data' => $loanPayment]);
    }


    public function loanPayments()
    {
        $offices = User::query()->select('office')->distinct()->pluck('office')->toArray();

        $this->syncApprovedLoanApplicationsToLedger();

        $loans = LoanDetail::query()
            ->with(['user', 'latestPayment'])
            ->withSum('loanPayments as total_payments_sum', 'total_payments')
            ->get()
            ->map(function ($loan) {
                $totalPaid = (float) ($loan->total_payments_sum ?? 0);

                $latest = $loan->latestPayment;
                $balance = $latest
                    ? (float) $latest->outstanding_balance
                    : max(0, (float) $loan->loan_amount - $totalPaid);

                // attach computed values used by Blade
                $loan->total_paid = $totalPaid;
                $loan->current_balance = $balance;

                return $loan;
            })
            // Prefer base LN-APP-xxxxxx rows over legacy suffixed duplicates.
            ->sortBy(function ($loan) {
                $loanId = (string) ($loan->loan_id ?? '');
                return preg_match('/^LN-APP-\d{6}-[A-Z0-9]{4}$/', $loanId) ? 1 : 0;
            })
            // Collapse duplicate synthetic app rows to one visible row.
            ->unique(function ($loan) {
                $loanId = (string) ($loan->loan_id ?? '');
                if (preg_match('/^(LN-APP-\d{6})(?:-[A-Z0-9]{4})?$/', $loanId, $m)) {
                    return $m[1];
                }
                return $loanId;
            })
            ->values();

        return view('admin.loan_payments', compact('offices', 'loans'));
    }


    public function viewLoan($loanId)
    {
        // ✅ Loan info comes from loan_details
        $loanDetail = LoanDetail::with(['user', 'latestPayment'])
            ->where('loan_id', $loanId)
            ->firstOrFail();

        // ✅ Payment history comes from loan_payments
        $paymentRows = LoanPayment::where('loan_id', $loanId)
            ->orderByDesc('date_of_remittance')
            ->get();

        $latestPayment = $paymentRows->first();

        $totalPaid = (float) $paymentRows->sum('total_payments');

        // ✅ Outstanding: prefer the stored latest outstanding_balance, else compute
        $outstanding = $latestPayment
            ? (float) $latestPayment->outstanding_balance
            : max(0, (float) $loanDetail->loan_amount - $totalPaid);

        // ✅ Other loans must come from loan_details (same employee_ID)
        $otherLoans = LoanDetail::with('latestPayment')
            ->where('employee_ID', $loanDetail->employee_ID)
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($l) {
                $out = optional($l->latestPayment)->outstanding_balance;
                if ($out === null)
                    $out = (float) $l->loan_amount;

                return [
                    'loan_id' => $l->loan_id,
                    'loan_type' => $l->loan_type,
                    'loan_amount' => $l->loan_amount,
                    'latest_outstanding_balance' => $out,
                ];
            })
            ->values();

        return response()->json([
            'success' => true,

            // optional, if you want to use it in JS
            'paid' => $outstanding <= 0,

            'loan' => [
                'loan_id' => $loanDetail->loan_id,
                'employee_ID' => $loanDetail->employee_ID,
                'employee_name' => optional($loanDetail->user)->name ?? 'NA',
                'office' => optional($loanDetail->user)->office ?? 'NA',

                'loan_type' => $loanDetail->loan_type,
                'loan_amount' => $loanDetail->loan_amount,
                'terms' => $loanDetail->terms,
                'total_deduction' => $loanDetail->total_deduction,
                'total_net' => $loanDetail->total_net,
                'monthly_payment' => $loanDetail->monthly_payment,

                // ✅ the key your JS uses
                'latest_outstanding_balance' => $outstanding,

                // latest payment meta
                'latest_remittance' => $latestPayment->latest_remittance ?? null,
                'remittance_no' => $latestPayment->remittance_no ?? null,
                'date_of_remittance' => $latestPayment->date_of_remittance ?? null,
                'date_covered_month' => $latestPayment->date_covered_month ?? null,
                'date_covered_year' => $latestPayment->date_covered_year ?? null,
            ],

            // ✅ JS expects p.amount
            'payments' => $paymentRows->map(function ($p) {
                return [
                    'remittance_no' => $p->remittance_no,
                    'date_of_remittance' => $p->date_of_remittance,
                    'date_covered_month' => $p->date_covered_month,
                    'date_covered_year' => $p->date_covered_year,
                    'amount' => $p->total_payments,
                ];
            })->values(),

            'other_loans' => $otherLoans,
        ]);
    }

    private function syncApprovedLoanApplicationsToLedger(): void
    {
        if (!Schema::hasTable('loan_applications') || !Schema::hasTable('loan_details')) {
            return;
        }

        $approvedApps = LoanApplication::query()
            ->with('user')
            ->whereRaw('LOWER(COALESCE(status, "")) = ?', ['approved'])
            ->get();

        foreach ($approvedApps as $application) {
            $this->createLoanDetailFromApplicationIfMissing($application);
        }
    }

    private function createLoanDetailFromApplicationIfMissing(LoanApplication $application): void
    {
        $employeeId = $this->resolveEmployeeId($application);
        if ($employeeId === '') {
            return;
        }

        $approvedAmount = (float) ($application->approved_amount ?? $application->loan_amount ?? 0);
        if ($approvedAmount <= 0) {
            return;
        }

        $oldBalance = (float) ($application->old_balance ?? 0);
        $lpp = (float) ($application->lpp ?? 0);
        $interest = (float) ($application->interest ?? 0);
        $handlingFee = (float) ($application->handling_fee ?? 0);
        $pettyCashLoan = (float) ($application->petty_cash_loan ?? 0);
        $totalDeduction = ($application->total_deduction !== null)
            ? (float) $application->total_deduction
            : ($oldBalance + $lpp + $interest + $handlingFee + $pettyCashLoan);
        $totalNet = ($application->total_net !== null)
            ? (float) $application->total_net
            : max($approvedAmount - $totalDeduction, 0);
        $terms = max(1, (int) ($application->terms ?? 24));
        $monthlyPayment = ($application->monthly_payment !== null)
            ? (float) $application->monthly_payment
            : ($terms > 0 ? ($approvedAmount / $terms) : 0);

        $appliedDate = optional($application->created_at)?->toDateString() ?? now()->toDateString();
        $approvedDate = $application->reviewed_at
            ? \Carbon\Carbon::parse($application->reviewed_at)->toDateString()
            : now()->toDateString();
        $loanTypeLabel = $this->mapLoanTypeForLedger($application->loan_type);

        $baseLoanId = $this->makeLedgerLoanId($application);

        // If synthetic rows for this application already exist, keep one canonical row
        // and remove extra zero-payment duplicates created by previous sync runs.
        $syntheticRows = LoanDetail::query()
            ->withCount('loanPayments')
            ->where(function ($q) use ($baseLoanId) {
                $q->where('loan_id', $baseLoanId)
                    ->orWhere('loan_id', 'like', $baseLoanId . '-%');
            })
            ->orderByRaw('CASE WHEN loan_id = ? THEN 0 ELSE 1 END', [$baseLoanId])
            ->get();

        if ($syntheticRows->isNotEmpty()) {
            $canonical = $syntheticRows->firstWhere('loan_id', $baseLoanId) ?? $syntheticRows->first();

            if (Schema::hasColumn('loan_applications', 'lv_no')) {
                $application->forceFill(['lv_no' => $canonical->loan_id])->save();
            }

            $syntheticRows
                ->where('loan_id', '!=', $canonical->loan_id)
                ->each(function ($row) use ($canonical) {
                    // Preserve payment history by moving rows to the canonical loan_id first.
                    if ((int) ($row->loan_payments_count ?? 0) > 0) {
                        LoanPayment::where('loan_id', $row->loan_id)
                            ->update(['loan_id' => $canonical->loan_id]);
                    }
                    $row->delete();
                });

            return;
        }

        $loanId = null;
        if (Schema::hasColumn('loan_applications', 'lv_no')) {
            $loanId = trim((string) ($application->lv_no ?? ''));
            if ($loanId !== '' && LoanDetail::where('loan_id', $loanId)->exists()) {
                return;
            }
        }

        if ($loanId === null || $loanId === '') {
            $maybeExisting = LoanDetail::query()
                ->when(Schema::hasColumn('loan_details', 'employee_ID'), fn($q) => $q->where('employee_ID', $employeeId))
                ->when(Schema::hasColumn('loan_details', 'loan_amount'), fn($q) => $q->where('loan_amount', $approvedAmount))
                ->when(Schema::hasColumn('loan_details', 'date_approved'), fn($q) => $q->whereDate('date_approved', $approvedDate))
                ->when(Schema::hasColumn('loan_details', 'loan_type'), fn($q) => $q->where('loan_type', $loanTypeLabel))
                ->orderByDesc('created_at')
                ->first();

            if ($maybeExisting && Schema::hasColumn('loan_applications', 'lv_no')) {
                $application->forceFill(['lv_no' => $maybeExisting->loan_id])->save();
                return;
            }
        }

        if ($loanId === null || $loanId === '') {
            $loanId = $baseLoanId;
        }

        $insert = [];
        $set = function (string $column, $value) use (&$insert) {
            if (Schema::hasColumn('loan_details', $column)) {
                $insert[$column] = $value;
            }
        };

        $set('loan_id', $loanId);
        $set('employee_ID', $employeeId);
        $set('loan_type', $loanTypeLabel);
        $set('loan_amount', $approvedAmount);
        $set('interest_rate', 0);
        $set('interest', $interest);
        $set('date_applied', $appliedDate);
        $set('date_approved', $approvedDate);
        $set('total_net', $totalNet);
        $set('terms', $terms);
        $set('monthly_payment', $monthlyPayment);
        $set('total_deduction', $totalDeduction);
        $set('old_balance', $oldBalance);
        $set('lpp', $lpp);
        $set('handling_fee', $handlingFee);
        $set('petty_cash_loan', $pettyCashLoan);
        $set('co_maker_name', $application->comaker1_name ?? null);
        $set('co_maker_position', $application->comaker1_position ?? null);
        $set('co_maker2_name', $application->comaker2_name ?? null);
        $set('co_maker2_position', $application->comaker2_position ?? null);
        $set('remarks', 'Approved Loan Request');

        if (!empty($insert)) {
            LoanDetail::create($insert);
        }

        if (Schema::hasColumn('loan_applications', 'lv_no')) {
            $application->forceFill(['lv_no' => $loanId])->save();
        }
    }

    private function resolveEmployeeId(LoanApplication $application): string
    {
        $user = $application->user;
        return collect([
            $application->member_key,
            $user->employee_ID ?? null,
            $user->employees_id ?? null,
            $user->employee_id ?? null,
        ])
            ->map(fn($v) => trim((string) $v))
            ->first(fn($v) => $v !== '' && strtolower($v) !== 'n/a') ?? '';
    }

    private function makeLedgerLoanId(LoanApplication $application): string
    {
        return 'LN-APP-' . str_pad((string) $application->id, 6, '0', STR_PAD_LEFT);
    }

    private function mapLoanTypeForLedger(?string $loanType): string
    {
        $raw = strtolower(trim((string) $loanType));
        $raw = str_replace(['_', '-'], ' ', $raw);
        $raw = preg_replace('/\s+/', ' ', $raw) ?? '';

        if (str_contains($raw, 'education')) {
            return 'Educational Loan';
        }
        if (str_contains($raw, 'appliance')) {
            return 'Appliance Loan';
        }
        if (str_contains($raw, 'grocery')) {
            return 'Grocery Loan';
        }

        return 'Regular Loan';
    }



}
