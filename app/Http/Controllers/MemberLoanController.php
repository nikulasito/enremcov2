<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LoanApplication;
use App\Models\LoanDetail;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Notifications\NewLoanApplicationNotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class MemberLoanController extends Controller
{
    private function coMakerActiveLoanCount(int $userId): int
    {
        $hasLoansTable = Schema::hasTable('loan_applications');
        $hasStatusCol = $hasLoansTable && Schema::hasColumn('loan_applications', 'status');
        $hasCm1Col = $hasLoansTable && Schema::hasColumn('loan_applications', 'comaker1_user_id');
        $hasCm2Col = $hasLoansTable && Schema::hasColumn('loan_applications', 'comaker2_user_id');

        if (!$hasLoansTable || !$hasStatusCol || (!$hasCm1Col && !$hasCm2Col)) {
            return 0;
        }

        $activeStatuses = ['pending', 'in_review', 'for_review', 'for_approval', 'for_processing', 'approved'];

        return (int) LoanApplication::query()
            ->whereIn(DB::raw('LOWER(status)'), $activeStatuses)
            ->where(function ($q) use ($userId, $hasCm1Col, $hasCm2Col) {
                if ($hasCm1Col) {
                    $q->where('comaker1_user_id', $userId);
                }
                if ($hasCm2Col) {
                    $hasCm1Col
                        ? $q->orWhere('comaker2_user_id', $userId)
                        : $q->where('comaker2_user_id', $userId);
                }
            })
            ->count();
    }

    private function resolveMemberKeys($user): array
    {
        if (!$user) {
            return [];
        }

        return collect([
            $user->employee_ID ?? null,
            $user->employees_id ?? null,
            $user->employee_id ?? null,
            (string) $user->id,
        ])
            ->filter(fn($v) => $v !== null && $v !== '')
            ->map(fn($v) => (string) $v)
            ->unique()
            ->values()
            ->all();
    }

    private function resolveMemberColumn(string $table): ?string
    {
        foreach (['employee_ID', 'employees_id', 'employee_id', 'user_id'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                return $col;
            }
        }

        return null;
    }

    private function getLoanEligibilityData($user): array
    {
        $minContributions = 5000;
        $maxLoanCount = 3;

        if (!$user) {
            return [
                'can_apply' => false,
                'reasons' => ['You must be logged in to apply for a loan.'],
                'total_contributions' => 0.0,
                'loan_count' => 0,
                'min_contributions' => $minContributions,
                'max_loan_count' => $maxLoanCount,
            ];
        }

        $memberKeys = collect($this->resolveMemberKeys($user));

        $totalShares = 0.0;
        if (Schema::hasTable('shares') && Schema::hasColumn('shares', 'amount')) {
            $sharesMemberColumn = $this->resolveMemberColumn('shares');
            if ($sharesMemberColumn && $memberKeys->isNotEmpty()) {
                $totalShares = (float) DB::table('shares')
                    ->whereIn($sharesMemberColumn, $memberKeys->all())
                    ->sum('amount');
            }
        }

        $totalSavings = 0.0;
        if (Schema::hasTable('savings') && Schema::hasColumn('savings', 'amount')) {
            $savingsMemberColumn = $this->resolveMemberColumn('savings');
            if ($savingsMemberColumn && $memberKeys->isNotEmpty()) {
                $totalSavings = (float) DB::table('savings')
                    ->whereIn($savingsMemberColumn, $memberKeys->all())
                    ->sum('amount');
            }
        }

        $loanCount = 0;
        if (Schema::hasTable('loan_details')) {
            $loanMemberColumn = $this->resolveMemberColumn('loan_details');
            if ($loanMemberColumn && $memberKeys->isNotEmpty()) {
                $loanCount = (int) DB::table('loan_details')
                    ->whereIn($loanMemberColumn, $memberKeys->all())
                    ->count();
            }
        }

        $totalContributions = (float) ($totalShares + $totalSavings);
        $reasons = [];

        if ($totalContributions < $minContributions) {
            $reasons[] = 'Loan application is locked. Minimum combined Shares + Savings is P' . number_format($minContributions, 0) . '.';
        }

        if ($loanCount >= $maxLoanCount) {
            $reasons[] = 'Loan application is locked. Members with 3 or more loans cannot apply for a new loan.';
        }

        return [
            'can_apply' => empty($reasons),
            'reasons' => $reasons,
            'total_contributions' => $totalContributions,
            'loan_count' => $loanCount,
            'min_contributions' => $minContributions,
            'max_loan_count' => $maxLoanCount,
        ];
    }

    private function canAccessApplication(LoanApplication $application): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        if ((int) $application->user_id === (int) $user->id) {
            return true;
        }

        $memberKeys = collect([
            $user->employee_ID ?? null,
            $user->employees_id ?? null,
            $user->employee_id ?? null,
            (string) $user->id,
        ])
            ->filter(fn($v) => $v !== null && $v !== '')
            ->map(fn($v) => (string) $v)
            ->unique();

        return $memberKeys->contains((string) ($application->member_key ?? ''));
    }

    public function index(Request $request)
    {
        $user = auth()->user();
        $historySearch = trim((string) $request->query('q', ''));
        $perPage = 10;
        $page = max(1, (int) $request->query('page', 1));
        $loanEligibility = $this->getLoanEligibilityData($user);

        $memberKeys = collect($this->resolveMemberKeys($user));

        $loanApplications = collect();
        $approvedApplications = collect();
        if (Schema::hasTable('loan_applications')) {
            $loanApplications = LoanApplication::query()
                ->where(function ($q) use ($user, $memberKeys) {
                    $q->where('user_id', $user->id);

                    if ($memberKeys->isNotEmpty()) {
                        $q->orWhereIn('member_key', $memberKeys->all());
                    }
                })
                ->orderByDesc('created_at')
                ->get();

            $approvedApplications = $loanApplications
                ->filter(fn($application) => strtolower((string) ($application->status ?? '')) === 'approved')
                ->values();

            // Approved items should leave the application queue and appear under Active Loans.
            $loanApplications = $loanApplications
                ->reject(fn($application) => strtolower((string) ($application->status ?? '')) === 'approved')
                ->values();
        }

        $activeLoans = collect();
        $historyItems = collect();

        if (Schema::hasTable('loan_details')) {
            $loanMemberColumn = null;
            foreach (['employee_ID', 'employees_id', 'employee_id'] as $col) {
                if (Schema::hasColumn('loan_details', $col)) {
                    $loanMemberColumn = $col;
                    break;
                }
            }

            $query = LoanDetail::query()
                ->with('latestPayment')
                ->withSum('loanPayments as total_paid', 'total_payments');

            if ($loanMemberColumn !== null) {
                if ($memberKeys->isNotEmpty()) {
                    $query->whereIn($loanMemberColumn, $memberKeys->all());
                } else {
                    $query->whereRaw('1 = 0');
                }
            } else {
                $query->whereRaw('1 = 0');
            }

            if ($historySearch !== '') {
                $canSearchLoanId = Schema::hasColumn('loan_details', 'loan_id');
                $canSearchLoanType = Schema::hasColumn('loan_details', 'loan_type');

                if ($canSearchLoanId || $canSearchLoanType) {
                    $query->where(function ($q) use ($historySearch, $canSearchLoanId, $canSearchLoanType) {
                        if ($canSearchLoanId) {
                            $q->where('loan_id', 'like', "%{$historySearch}%");
                        }

                        if ($canSearchLoanType) {
                            $canSearchLoanId
                                ? $q->orWhere('loan_type', 'like', "%{$historySearch}%")
                                : $q->where('loan_type', 'like', "%{$historySearch}%");
                        }
                    });
                }
            }

            $orderBy = Schema::hasColumn('loan_details', 'date_approved')
                ? 'date_approved'
                : (Schema::hasColumn('loan_details', 'created_at') ? 'created_at' : 'loan_id');

            $historyItems = $query
                ->orderByDesc($orderBy)
                ->get()
                ->map(function ($loan) {
                    $loanAmount = (float) ($loan->loan_amount ?? 0);
                    $totalPaid = (float) ($loan->total_paid ?? 0);
                    $latestOutstanding = optional($loan->latestPayment)->outstanding_balance;

                    $remainingBalance = $latestOutstanding === null
                        ? max($loanAmount - $totalPaid, 0)
                        : max((float) $latestOutstanding, 0);

                    $approvedAt = $loan->date_approved
                        ? Carbon::parse($loan->date_approved)
                        : null;

                    $maturityAt = null;
                    if (!empty($loan->last_payment)) {
                        $maturityAt = Carbon::parse($loan->last_payment);
                    } elseif ($approvedAt && is_numeric($loan->terms) && (int) $loan->terms > 0) {
                        $maturityAt = (clone $approvedAt)->addMonths((int) $loan->terms);
                    }

                    $latestRemit = optional($loan->latestPayment)->date_of_remittance;
                    $nextDueAt = $latestRemit ? Carbon::parse($latestRemit)->addMonth() : null;

                    $loan->remaining_balance = $remainingBalance;
                    $loan->is_paid = $remainingBalance <= 0.009;
                    $loan->loan_type_label = ucwords(str_replace('_', ' ', strtolower((string) $loan->loan_type)));
                    $loan->approved_date_label = $approvedAt ? $approvedAt->format('M d, Y') : 'N/A';
                    $loan->maturity_date_label = $maturityAt ? $maturityAt->format('M d, Y') : 'N/A';
                    $loan->next_due_date_label = $nextDueAt ? $nextDueAt->format('M d, Y') : 'N/A';

                    return $loan;
                })
                ->values();

            $activeLoans = $historyItems
                ->filter(fn($loan) => !$loan->is_paid)
                ->values();
        }

        if ($approvedApplications->isNotEmpty()) {
            $approvedAsActive = $approvedApplications->map(function ($application) {
                $approvedAt = $application->reviewed_at
                    ? Carbon::parse($application->reviewed_at)
                    : (optional($application->created_at) ? Carbon::parse($application->created_at) : null);

                $approvedAmount = (float) ($application->approved_amount ?? $application->loan_amount ?? 0);
                $netCash = (float) ($application->total_net ?? $approvedAmount);

                return (object) [
                    'loan_id' => $application->application_no ?? ('APP-' . $application->id),
                    'loan_type' => $application->loan_type,
                    'loan_type_label' => ucwords(str_replace('_', ' ', strtolower((string) $application->loan_type))),
                    'loan_amount' => $approvedAmount,
                    'total_net' => $netCash,
                    'remaining_balance' => $approvedAmount,
                    'monthly_payment' => (float) ($application->monthly_payment ?? 0),
                    'next_due_date_label' => 'N/A',
                    'approved_date_label' => $approvedAt ? $approvedAt->format('M d, Y') : 'N/A',
                    'maturity_date_label' => 'N/A',
                    'is_paid' => false,
                ];
            });

            $activeLoans = $approvedAsActive->concat($activeLoans)->values();
        }

        $historyTotal = $historyItems->count();
        $historyPageItems = $historyItems->forPage($page, $perPage)->values();

        $loanHistory = new LengthAwarePaginator(
            $historyPageItems,
            $historyTotal,
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('member.loans.index', [
            'loanApplications' => $loanApplications,
            'activeLoans' => $activeLoans,
            'loanHistory' => $loanHistory,
            'historySearch' => $historySearch,
            'loanEligibility' => $loanEligibility,
        ]);
    }

    public function apply(Request $request)
    {
        $eligibility = $this->getLoanEligibilityData(auth()->user());

        if (!$eligibility['can_apply']) {
            return redirect()
                ->route('member.loans.index')
                ->withErrors([
                    'loan_apply' => implode(' ', $eligibility['reasons']),
                ]);
        }

        $allowedTypes = ['regular', 'educational', 'appliance', 'grocery'];
        $selectedLoanType = strtolower((string) $request->query('type', ''));
        if (!in_array($selectedLoanType, $allowedTypes, true)) {
            $selectedLoanType = '';
        }

        return view('member.loans.apply', [
            'selectedLoanType' => $selectedLoanType,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'loan_type' => ['required', 'in:regular,educational,appliance,grocery'],
            'loan_amount' => ['required', 'numeric', 'min:1'],

            'comaker1_name' => ['required', 'string', 'max:255'],
            'comaker2_name' => ['required', 'string', 'max:255'],

            'comaker1_user_id' => ['required', 'integer', 'exists:users,id'],
            'comaker2_user_id' => ['required', 'integer', 'exists:users,id', 'different:comaker1_user_id'],

            'comaker1_position' => ['nullable', 'string', 'max:255'],
            'comaker2_position' => ['nullable', 'string', 'max:255'],
        ]);

        $user = auth()->user();

        $memberKey = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? (string) $user->id;

        $eligibility = $this->getLoanEligibilityData($user);
        if (!$eligibility['can_apply']) {
            return back()
                ->withInput()
                ->withErrors([
                    'loan_amount' => implode(' ', $eligibility['reasons']),
                ]);
        }

        $cm1Count = $this->coMakerActiveLoanCount((int) $data['comaker1_user_id']);
        if ($cm1Count >= 3) {
            return back()
                ->withInput()
                ->withErrors([
                    'comaker1_name' => 'Selected co-maker already reached the 3-loan limit. Please choose another co-maker.',
                ]);
        }

        $cm2Count = $this->coMakerActiveLoanCount((int) $data['comaker2_user_id']);
        if ($cm2Count >= 3) {
            return back()
                ->withInput()
                ->withErrors([
                    'comaker2_name' => 'Selected co-maker already reached the 3-loan limit. Please choose another co-maker.',
                ]);
        }



        // 1) SAVE as pending
        $loan = LoanApplication::create([
            'user_id' => $user->id,
            'application_no' => null, // set after create
            'full_name' => $data['full_name'],
            'member_key' => $memberKey,
            'address' => $data['address'] ?? null,

            'loan_type' => $data['loan_type'],
            'loan_amount' => $data['loan_amount'],

            'comaker1_user_id' => $data['comaker1_user_id'],
            'comaker1_name' => $data['comaker1_name'],
            'comaker1_position' => $data['comaker1_position'] ?? null,

            'comaker2_user_id' => $data['comaker2_user_id'],
            'comaker2_name' => $data['comaker2_name'],
            'comaker2_position' => $data['comaker2_position'] ?? null,

            'status' => 'pending',
        ]);

        // Create readable ref like APP-202602111234-0001
        $loan->application_no = 'APP-' . now()->format('YmdHis') . '-' . str_pad((string) $loan->id, 4, '0', STR_PAD_LEFT);
        $loan->save();

        // 2) NOTIFY ADMINS
        // Adjust this admin query to match your schema (is_admin/role/etc.)
        $admins = User::query()
            ->when(Schema::hasColumn('users', 'is_admin'), fn($q) => $q->where('is_admin', 1))
            ->when(Schema::hasColumn('users', 'role'), fn($q) => $q->orWhere('role', 'admin'))
            ->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewLoanApplicationNotification($loan));
        }

        // 3) Return with modal data
        return redirect()
            ->route('member.loans.apply')
            ->with('loan_submitted', [
                'application_no' => $loan->application_no,
                'loan_type' => $loan->loan_type,
                'loan_amount' => $loan->loan_amount,
            ]);
    }

    public function searchComakers(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        if ($q === '' || mb_strlen($q) < 2) {
            return response()->json([]);
        }

        $userId = auth()->id();

        // ✅ Only use columns that actually exist
        $hasEmployeeID = Schema::hasColumn('users', 'employee_ID');
        $hasEmployeesId = Schema::hasColumn('users', 'employees_id');
        $hasEmployeeId = Schema::hasColumn('users', 'employee_id');
        $hasPosition = Schema::hasColumn('users', 'position');

        $query = User::query();

        if ($userId) {
            $query->where('id', '!=', $userId);
        }

        $query->where(function ($sub) use ($q, $hasEmployeeID, $hasEmployeesId, $hasEmployeeId) {
            $sub->where('name', 'like', "%{$q}%");

            if ($hasEmployeeID)
                $sub->orWhere('employee_ID', 'like', "%{$q}%");
            if ($hasEmployeesId)
                $sub->orWhere('employees_id', 'like', "%{$q}%");
            if ($hasEmployeeId)
                $sub->orWhere('employee_id', 'like', "%{$q}%");
        });

        // ✅ Select position only if it exists; otherwise return empty string
        $select = ['id', 'name'];
        if ($hasPosition) {
            $select[] = 'position';
        } else {
            $select[] = DB::raw("'' as position");
        }

        $users = $query
            ->select($select)
            ->orderBy('name')
            ->limit(10)
            ->get();

        // ==========================
        // ✅ Co-maker limit check
        // ==========================
        $counts = collect(); // id => count

        $hasLoansTable = Schema::hasTable('loan_applications');
        $hasStatusCol = $hasLoansTable && Schema::hasColumn('loan_applications', 'status');
        $hasCm1Col = $hasLoansTable && Schema::hasColumn('loan_applications', 'comaker1_user_id');
        $hasCm2Col = $hasLoansTable && Schema::hasColumn('loan_applications', 'comaker2_user_id');

        if ($hasLoansTable && $hasStatusCol && ($hasCm1Col || $hasCm2Col) && $users->isNotEmpty()) {
            $ids = $users->pluck('id')->values();

            $activeStatuses = ['pending', 'in_review', 'for_review', 'for_approval', 'for_processing', 'approved'];
            $activeStatuses = array_map('strtolower', $activeStatuses);

            $loanRows = LoanApplication::query()
                ->whereIn(DB::raw('LOWER(status)'), $activeStatuses)
                ->where(function ($q) use ($ids, $hasCm1Col, $hasCm2Col) {
                    if ($hasCm1Col) {
                        $q->whereIn('comaker1_user_id', $ids);
                    }
                    if ($hasCm2Col) {
                        $hasCm1Col
                            ? $q->orWhereIn('comaker2_user_id', $ids)
                            : $q->whereIn('comaker2_user_id', $ids);
                    }
                })
                ->get(array_values(array_filter([
                    $hasCm1Col ? 'comaker1_user_id' : null,
                    $hasCm2Col ? 'comaker2_user_id' : null,
                ])));


            $counts = $loanRows
                ->flatMap(fn($r) => [
                    $hasCm1Col ? $r->comaker1_user_id : null,
                    $hasCm2Col ? $r->comaker2_user_id : null,
                ])
                ->filter()
                ->countBy(); // returns collection: [user_id => count]
        }

        $results = $users->map(function ($u) use ($counts) {
            $c = (int) ($counts[$u->id] ?? 0);

            return [
                'id' => $u->id,
                'name' => $u->name,
                'position' => $u->position ?? '',
                'co_maker_count' => $c,
                'limit_reached' => $c >= 3,
            ];
        });

        return response()->json($results->values());
    }


    public function print(LoanApplication $application)
    {
        // ✅ security: only the owner can view
        abort_unless($this->canAccessApplication($application), 403);

        // Optional: allow only when for_processing
        abort_unless($application->status === 'for_processing', 403);

        $html = view('member.loans.print_regular', [
            'app' => $application,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $filename = 'Loan-' . ($application->application_no ?? $application->id) . '.pdf';

        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="' . $filename . '"');
    }

    public function details(LoanApplication $application)
    {
        abort_unless($this->canAccessApplication($application), 403);
        $approvedAmount = (float) ($application->approved_amount ?? $application->loan_amount ?? 0);
        $netCash = (float) ($application->total_net ?? $approvedAmount);

        return response()->json([
            'id' => $application->id,
            'application_no' => $application->application_no,
            'full_name' => $application->full_name,
            'address' => $application->address,
            'member_key' => $application->member_key,
            'loan_type' => $application->loan_type,
            'loan_amount' => (float) $application->loan_amount,
            'approved_amount' => $approvedAmount,
            'total_net' => $netCash,
            'status' => $application->status,
            'created_at' => optional($application->created_at)?->format('M d, Y'),
            'remarks' => $application->remarks, // ✅ admin note
            'pdf_url' => route('member.loans.print', $application->id), // ✅ PDF viewer source
        ]);
    }
}
