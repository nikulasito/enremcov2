<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\LoanDetail;
use App\Models\LoanApplication;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\LoanPayment;
use App\Exports\LoanTemplateExport;
use App\Imports\LoanImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Schema;
use App\Notifications\LoanStatusUpdatedNotification;

class LoansController extends Controller
{

    public function index(Request $request) // Add Request $request
    {
        $loans = LoanDetail::with('loanPayments', 'user')->get(); // Eager Load LoanPayments & User


        //Search
        $query = LoanDetail::with('user', 'latestPayment');

        // Check if a search query is present
        if ($request->has('q') && !empty($request->q)) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('loan_id', 'LIKE', "%$search%")
                    ->orWhere('employee_ID', 'LIKE', "%$search%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'LIKE', "%$search%")
                            ->orWhere('office', 'LIKE', "%$search%");
                    });
            });
        }

        $loans = $query->get();

        // If request is AJAX, return JSON for frontend
        if ($request->ajax()) {
            return response()->json($loans->map(function ($loan) {
                return [
                    'loan_id' => $loan->loan_id,
                    'employee_ID' => $loan->employee_ID,
                    'employee_name' => optional($loan->user)->name ?? 'NA',
                    'office' => optional($loan->user)->office ?? 'NA',
                    'employment_status' => optional($loan->user)->status ?? 'NA',
                    'loan_type' => $loan->loan_type,
                    'loan_amount' => $loan->loan_amount,
                    'date_approved' => $loan->date_approved,
                    'total_net' => $loan->total_net,
                    'terms' => $loan->terms,
                    'monthly_payment' => $loan->monthly_payment,
                    'total_payments_count' => optional($loan->latestPayment)->total_payments_count ?? 0,
                    'total_payments' => optional($loan->latestPayment)->total_payments ?? '0.00',
                    'outstanding_balance' => optional($loan->latestPayment)->outstanding_balance ?? '0.00',
                    'latest_remittance' => optional($loan->latestPayment)->latest_remittance ?? 'No Remittance Yet',
                    'remarks' => $loan->remarks,
                ];
            }));
        }

        $loans = LoanDetail::with('user')
            ->withSum('loanPayments as total_paid', 'total_payments') // <-- change 'amount' if your column is amount_paid
            ->get()
            ->map(function ($loan) {
                $paid = (float) ($loan->total_paid ?? 0);
                $loan->computed_balance = max((float) $loan->loan_amount - $paid, 0);
                return $loan;
            });

        return view('admin.loans', compact('loans'));
    }

    public function requestsQueue(Request $request)
    {
        $q = $request->get('q');
        $loanType = $request->get('loan_type', 'all');
        $status = $request->get('status', 'all');

        $query = LoanApplication::query();

        if ($q) {
            $query->where(function ($qq) use ($q) {
                $qq->where('full_name', 'like', "%{$q}%")
                    ->orWhere('member_key', 'like', "%{$q}%")
                    ->orWhere('application_no', 'like', "%{$q}%");
            });
        }

        if ($loanType !== 'all') {
            $query->where('loan_type', $loanType);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $applications = $query->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.loans.requests', compact('applications', 'q', 'loanType', 'status'));
    }


    public function store(Request $request)
    {
        \Log::info('🏁 Store function triggered with request data:', $request->all());

        // Remove commas from all numeric fields before validation
        $numericFields = ['loan_amount', 'old_balance', 'lpp', 'interest', 'handling_fee', 'petty_cash_loan', 'total_net'];

        foreach ($numericFields as $field) {
            if ($request->has($field)) {
                $request->merge([$field => str_replace(',', '', $request->$field)]);
            }
        }

        // Calculate Total Deduction
        $totalDeduction = (
            ($request->old_balance ?? 0) +
            ($request->lpp ?? 0) +
            ($request->interest ?? 0) +
            ($request->handling_fee ?? 0) +
            ($request->petty_cash_loan ?? 0)
        );

        // Calculate Total Net
        $totalNet = ($request->loan_amount ?? 0) - $totalDeduction;

        // Merge Total Net into the request before validation
        $request->merge([
            'total_deduction' => $totalDeduction,
            'total_net' => $totalNet,
        ]);

        // Validation
        try {
            $validated = $request->validate([
                'employee_id' => 'required|string|exists:users,employee_ID',
                'loan_type' => 'required|string|in:Regular Loan,Educational Loan,Appliance Loan,Grocery Loan',
                'loan_amount' => 'required|numeric|min:1',
                'interest_rate' => 'required|numeric|min:0',
                'interest' => 'required|numeric|min:0',
                'terms' => 'required|integer|min:1',
                'monthly_payment' => 'required|numeric|min:1',
                'old_balance' => 'nullable|numeric|min:0',
                'lpp' => 'nullable|numeric|min:0',
                'handling_fee' => 'nullable|numeric|min:0',
                'petty_cash_loan' => 'nullable|numeric|min:0',
                'total_net' => 'required|numeric|min:0',
                'date_applied' => 'required|date',
                'date_approved' => 'required|date',
                'total_deduction' => 'required|numeric|min:0',
                'co_maker_name' => 'nullable|string',
                'co_maker_position' => 'nullable|string',
                'co_maker2_name' => 'nullable|string',
                'co_maker2_position' => 'nullable|string',
                'remarks' => 'required|string|in:New Loan,Re-Loan', // ✅ Add validation for remarks
            ]);
            \Log::info('✅ Validation Passed:', $validated);
        } catch (\Exception $e) {
            \Log::error('🚨 Validation Failed:', ['message' => $e->getMessage()]);
            return back()->with('error', 'Validation failed: ' . $e->getMessage());
        }

        // Find User
        $user = User::where('employee_ID', $validated['employee_id'])->first();
        if (!$user) {
            \Log::error('🚨 User Not Found:', ['employee_ID' => $validated['employee_id']]);
            return back()->with('error', 'User not found.');
        }
        \Log::info('👤 User found:', ['user' => $user]);

        // Format Dates
        // $date_applied = sprintf("%04d-%02d-%02d", $request->date_applied_year, $request->date_applied_month, $request->date_applied_day);
        // $date_approved = sprintf("%04d-%02d-%02d", $request->date_approved_year, $request->date_approved_month, $request->date_approved_day);

        // Generate Unique Loan ID
        // $loanID = uniqid('LN-');
        $loanID = 'LN-' . now()->format('Ymd') . '-' . rand(1000, 9999);

        try {
            \DB::beginTransaction(); // ✅ Start Transaction

            // Insert Loan Detail Record
            $loan = LoanDetail::create([
                'loan_id' => $loanID,
                'employee_ID' => $user->employee_ID,
                'loan_type' => $validated['loan_type'],
                'loan_amount' => $validated['loan_amount'],
                'interest_rate' => $validated['interest_rate'],
                'interest' => $validated['interest'],
                'terms' => $validated['terms'],
                'monthly_payment' => $validated['monthly_payment'],
                'date_applied' => $validated['date_applied'],
                'date_approved' => $validated['date_approved'],
                'total_net' => $validated['total_net'], // ✅ Now included
                'old_balance' => $validated['old_balance'],
                'lpp' => $validated['lpp'],
                'interest' => $validated['interest'],
                'handling_fee' => $validated['handling_fee'],
                'total_deduction' => $validated['total_deduction'],
                'petty_cash_loan' => $validated['petty_cash_loan'],
                'co_maker_name' => $request->co_maker_name,
                'co_maker_position' => $request->co_maker_position,
                'co_maker2_name' => $request->co_maker2_name,
                'co_maker2_position' => $request->co_maker2_position,
                'remarks' => $validated['remarks'], // ✅ Store remarks
            ]);

            \Log::info('📌 Loan Created:', $loan->toArray());

            // Insert Loan Payment Record
            $payment = LoanPayment::create([
                'loan_id' => $loanID,
                'total_payments_count' => 0,
                'total_payments' => 0,
                'outstanding_balance' => $validated['loan_amount'],
                'latest_payment' => null,
            ]);

            \Log::info('💰 Loan Payment Created:', $payment->toArray());

            \DB::commit(); // ✅ Commit transaction

            return redirect()->route('admin.loans')->with('success', 'Loan application submitted successfully!');
        } catch (\Exception $e) {
            \DB::rollBack(); // ❌ Rollback on failure
            \Log::error('🚨 Error Saving Loan:', ['message' => $e->getMessage()]);
            return back()->with('error', 'Error saving loan. Check logs.');
        }
    }


    public function getCoMakerDetails($name)
    {
        $users = User::where('name', 'LIKE', "%{$name}%")
            ->limit(5) // Limit results for better performance
            ->get(['name', 'position']); // Fetch only name & position

        if ($users->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'users' => $users
            ]);
        } else {
            return response()->json(['success' => false, 'users' => []]);
        }
    }




    public function getUserDetails($employee_id)
    {
        $user = User::where('employee_ID', $employee_id)->first();

        if ($user) {
            return response()->json([
                'success' => true,
                'name' => $user->name,
                'office' => $user->office,
                'employment_status' => $user->status,
            ]);
        } else {
            return response()->json(['success' => false]);
        }
    }





    public function updateLoan(Request $request, $loanId)
    {
        \Log::info('🛠 Incoming Loan Update Request:', $request->all());
        \Log::info('✅ HIT updateLoan route', ['loanId' => $loanId]);
        try {
            // 1) Sanitize numeric strings like "1,234.56"
            foreach (['loan_amount', 'update_monthly_payment', 'total_payments', 'no_of_payments'] as $f) {
                if ($request->has($f)) {
                    $request->merge([$f => str_replace(',', '', $request->input($f))]);
                }
            }

            // 2) Validate
            $validated = $request->validate([
                'loan_amount' => 'sometimes|nullable|numeric|min:1',
                'update_monthly_payment' => 'sometimes|nullable|numeric|min:0',
                'no_of_payments' => 'sometimes|nullable|integer|min:0',
                'total_payments' => 'sometimes|nullable|numeric|min:0',
                'latest_payment' => 'sometimes|nullable|date',
                'remarks' => 'sometimes|nullable|string|in:New Loan,Re-Loan',
            ]);

            $loan = LoanDetail::where('loan_id', $loanId)->firstOrFail();

            // 3) Update LoanDetail (only fields present)
            $loanData = array_filter([
                'loan_amount' => $validated['loan_amount'] ?? null,
                'monthly_payment' => $validated['update_monthly_payment'] ?? null,
                'remarks' => $validated['remarks'] ?? null,
            ], fn($v) => $v !== null);

            if (!empty($loanData)) {
                $loan->update($loanData);
            }

            // 4) Upsert LoanPayment (create if missing)
            $loanPayment = LoanPayment::updateOrCreate(
                ['loan_id' => $loanId],
                array_filter([
                    'total_payments_count' => $validated['no_of_payments'] ?? null,
                    'total_payments' => $validated['total_payments'] ?? null,
                    'latest_payment' => $validated['latest_payment'] ?? null,
                ], fn($v) => $v !== null)
            );

            return response()->json([
                'success' => true,
                'loan' => [
                    'loan_amount' => $loan->loan_amount,
                    'monthly_payment' => $loan->monthly_payment,
                    'no_of_payments' => $loanPayment->total_payments_count ?? 0,
                    'total_payments' => $loanPayment->total_payments ?? "0.00",
                    'latest_payment' => $loanPayment->latest_payment ?? null,
                    'remarks' => $loan->remarks,
                ]
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error("🚨 Loan Update Error: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }





    public function update(Request $request, $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->update([
            'loan_amount' => $request->loan_amount,
            'monthly_payment' => $request->monthly_payment,
        ]);

        return response()->json([
            'success' => true,
            'loan' => $loan
        ]);
    }


    public function viewAllLoans(Request $request)
    {
        $status = $request->get('status');

        $loans = Loan::with('member')
            ->when($status && $status !== 'all', function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10); // you can adjust the pagination

        return view('admin.loans.index', compact('loans', 'status'));
    }

    public function downloadTemplate()
    {
        return Excel::download(new LoanTemplateExport, 'loans_template.xlsx');
    }

    public function uploadLoanTemplate(Request $request)
    {
        try {
            Excel::import(new LoanImport, $request->file('file'));
            return back()->with('success', '✅ Loans data uploaded successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function loanRequestsIndex(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $loanType = $request->get('loan_type', 'all');
        $status = $request->get('status', 'all');

        $applications = LoanApplication::query()
            ->when($q !== '', function ($query) use ($q) {
                $query->where('full_name', 'like', "%{$q}%")
                    ->orWhere('member_key', 'like', "%{$q}%")
                    ->orWhere('application_no', 'like', "%{$q}%");
            })
            ->when($loanType !== 'all', fn($query) => $query->where('loan_type', $loanType))
            ->when($status !== 'all', fn($query) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.loan_requests', compact('applications', 'q', 'loanType', 'status'));
    }


    public function loanRequestsShow(LoanApplication $application, Request $request)
    {
        // used by the modal fetch()
        if ($request->wantsJson()) {
            return response()->json([
                'id' => $application->id,
                'application_no' => $application->application_no,
                'full_name' => $application->full_name,
                'address' => $application->address,
                'member_key' => $application->member_key,
                'loan_type' => $application->loan_type,
                'loan_amount' => (float) $application->loan_amount,
                'status' => $application->status,
                'created_at' => optional($application->created_at)?->format('M d, Y'),
                'remarks' => $application->remarks,
                'approved_amount' => $application->approved_amount ?? null,
                // If you add these columns later, modal will auto-fill
                'old_balance' => $application->old_balance ?? null,
                'lpp' => $application->lpp ?? null,
                'interest' => $application->interest ?? null,
                'handling_fee' => $application->handling_fee ?? null,
                'petty_cash_loan' => $application->petty_cash_loan ?? null,
                'total_deduction' => $application->total_deduction ?? null,
                'total_net' => $application->total_net ?? null,
                'terms' => $application->terms ?? null,
                'monthly_payment' => $application->monthly_payment ?? null,
                'lv_no' => $application->lv_no ?? null,
            ]);
        }

        return redirect()->route('admin.loan-requests.index');
    }

    public function loanRequestsApprove(Request $request, LoanApplication $application)
    {
        $validated = $request->validate([
            'remarks' => 'nullable|string|max:1000',
            'approved_amount' => 'nullable|numeric|min:0',
            'old_balance' => 'nullable|numeric|min:0',
            'lpp' => 'nullable|numeric|min:0',
            'interest' => 'nullable|numeric|min:0',
            'handling_fee' => 'nullable|numeric|min:0',
            'petty_cash_loan' => 'nullable|numeric|min:0',
            'total_deduction' => 'nullable|numeric',
            'total_net' => 'nullable|numeric',
            'terms' => 'nullable|integer|min:1',
            'monthly_payment' => 'nullable|numeric|min:0',
        ]);

        $approvedAmount = (float) ($validated['approved_amount'] ?? $application->loan_amount ?? 0);
        $oldBalance = (float) ($validated['old_balance'] ?? 0);
        $lpp = (float) ($validated['lpp'] ?? 0);
        $interest = (float) ($validated['interest'] ?? 0);
        $handlingFee = (float) ($validated['handling_fee'] ?? 0);
        $pettyCashLoan = (float) ($validated['petty_cash_loan'] ?? 0);
        $totalDeduction = $oldBalance + $lpp + $interest + $handlingFee + $pettyCashLoan;
        $totalNet = $approvedAmount - $totalDeduction;
        $terms = (int) ($validated['terms'] ?? 24);
        $monthlyPayment = $terms > 0 ? ($approvedAmount / $terms) : 0;

        $updates = [
            'status' => 'approved',
            'remarks' => $validated['remarks'] ?? null,
        ];

        if (Schema::hasColumn('loan_applications', 'reviewed_by')) {
            $updates['reviewed_by'] = auth()->id();
        }
        if (Schema::hasColumn('loan_applications', 'reviewed_at')) {
            $updates['reviewed_at'] = now();
        }
        if (Schema::hasColumn('loan_applications', 'approved_amount')) {
            $updates['approved_amount'] = $approvedAmount;
        }
        if (Schema::hasColumn('loan_applications', 'old_balance')) {
            $updates['old_balance'] = $oldBalance;
        }
        if (Schema::hasColumn('loan_applications', 'lpp')) {
            $updates['lpp'] = $lpp;
        }
        if (Schema::hasColumn('loan_applications', 'interest')) {
            $updates['interest'] = $interest;
        }
        if (Schema::hasColumn('loan_applications', 'handling_fee')) {
            $updates['handling_fee'] = $handlingFee;
        }
        if (Schema::hasColumn('loan_applications', 'petty_cash_loan')) {
            $updates['petty_cash_loan'] = $pettyCashLoan;
        }
        if (Schema::hasColumn('loan_applications', 'total_deduction')) {
            $updates['total_deduction'] = $totalDeduction;
        }
        if (Schema::hasColumn('loan_applications', 'total_net')) {
            $updates['total_net'] = $totalNet;
        }
        if (Schema::hasColumn('loan_applications', 'terms')) {
            $updates['terms'] = $terms;
        }
        if (Schema::hasColumn('loan_applications', 'monthly_payment')) {
            $updates['monthly_payment'] = $monthlyPayment;
        }

        $application->forceFill($updates)->save();
        $application->refresh();

        // Notify member (requires notifications table)
        if ($application->user) {
            try {
                $application->user->notify(new LoanStatusUpdatedNotification($application));
            } catch (\Throwable $e) {
                Log::warning('Loan approval notification failed', [
                    'application_id' => $application->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }



        return back()->with('success', 'Application approved.');
    }

    public function loanRequestsReject(Request $request, LoanApplication $application)
    {
        $request->validate([
            'remarks' => 'nullable|string|max:255',
        ]);

        $updates = [
            'status' => 'rejected',
            'remarks' => $request->input('remarks', 'Rejected by admin.'),
        ];

        if (Schema::hasColumn('loan_applications', 'reviewed_by')) {
            $updates['reviewed_by'] = auth()->id();
        }
        if (Schema::hasColumn('loan_applications', 'reviewed_at')) {
            $updates['reviewed_at'] = now();
        }

        $application->forceFill($updates)->save();
        $application->refresh();
        if ($application->user) {
            $application->user->notify(new LoanStatusUpdatedNotification($application));
        }

        return back()->with('success', 'Application rejected.');
    }


}
