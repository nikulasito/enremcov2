<x-member-layout :title="'ENREMCO - View Loans'">
    @php
        $typeLabel = function ($loanType) {
            $raw = strtolower((string) $loanType);
            return match ($raw) {
                'regular' => 'Regular Loan',
                'educational' => 'Educational Loan',
                'appliance' => 'Appliance Loan',
                'grocery' => 'Grocery Loan',
                default => ucwords(str_replace('_', ' ', $raw)),
            };
        };

        $typeIcon = function ($loanType) {
            $raw = strtolower((string) $loanType);
            if (str_contains($raw, 'educational')) {
                return 'school';
            }
            if (str_contains($raw, 'appliance')) {
                return 'kitchen';
            }
            if (str_contains($raw, 'grocery')) {
                return 'shopping_cart';
            }
            return 'account_balance';
        };

        $typeDot = function ($loanType) {
            $raw = strtolower((string) $loanType);
            if (str_contains($raw, 'regular')) {
                return 'bg-secondary';
            }
            return 'bg-primary';
        };

        $statusLabel = function ($status) {
            $raw = strtolower((string) $status);
            return match ($raw) {
                'pending' => 'Pending Approval',
                'for_review', 'in_review' => 'In Review',
                'for_approval' => 'For Approval',
                'for_printing' => 'For Printing',
                'approved' => 'Approved',
                'rejected' => 'Rejected',
                default => ucwords(str_replace('_', ' ', $raw)),
            };
        };

        $statusClass = function ($status) {
            $raw = strtolower((string) $status);
            return match ($raw) {
                'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                'for_review', 'for_approval' => 'bg-blue-100 text-blue-700 border border-blue-200',
                'for_printing' => 'bg-purple-100 text-purple-700 border border-purple-200',
                'approved' => 'bg-green-100 text-green-700 border border-green-200',
                'rejected' => 'bg-red-100 text-red-700 border border-red-200',
                default => 'bg-slate-100 text-slate-700 border border-slate-200',
            };
        };

        $canApplyForLoan = (bool) data_get($loanEligibility ?? [], 'can_apply', true);
        $applyDisabledReason = collect(data_get($loanEligibility ?? [], 'reasons', []))
            ->filter()
            ->implode(' ');
        $applyDisabledReason = $applyDisabledReason !== ''
            ? $applyDisabledReason
            : 'You are currently not eligible to apply for a new loan.';
    @endphp

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">View Loans</h1>
                <p class="text-slate-500 mt-1">Manage and track your active and historical loan records.</p>
            </div>
            <div class="flex items-center gap-4">
                @if($canApplyForLoan)
                    <a href="{{ route('member.loans.apply') }}"
                        class="flex items-center gap-2 px-6 py-3 bg-primary text-background-dark font-black text-sm rounded-xl hover:brightness-105 transition-all shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Apply for New Loan
                    </a>
                @else
                    <button type="button"
                        class="flex items-center gap-2 px-6 py-3 bg-slate-200 text-slate-500 font-black text-sm rounded-xl cursor-not-allowed"
                        disabled title="{{ $applyDisabledReason }}">
                        <span class="material-symbols-outlined text-lg">add_circle</span>
                        Apply for New Loan
                    </button>
                @endif
            </div>
        </div>
    </x-slot>

    @if($errors->has('loan_apply'))
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
            {{ $errors->first('loan_apply') }}
        </div>
    @endif

    <section>
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">pending_actions</span>
                Loan Application Status
            </h3>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
            @if(($loanApplications ?? collect())->isNotEmpty())
                @foreach($loanApplications as $application)
                    @php
                        $appLoanType = $typeLabel($application->loan_type ?? 'loan');
                        $appStatusRaw = strtolower((string) ($application->status ?? ''));
                        $appIsPending = $appStatusRaw === 'pending';
                        $appStatusText = $statusLabel($appStatusRaw);
                        $appStatusClasses = $statusClass($appStatusRaw);
                        $appRef = $application->application_no ?? ('APP-' . str_pad((string) $application->id, 4, '0', STR_PAD_LEFT));
                        $appDate = optional($application->created_at)?->format('F d, Y') ?? 'N/A';
                        $appAmount = (float) ($application->loan_amount ?? 0);
                    @endphp

                    <div
                        class="p-6 flex flex-col md:flex-row items-center justify-between gap-6 {{ !$loop->last ? 'border-b border-slate-100' : '' }}">
                        <div class="flex items-center gap-5 w-full md:w-auto">
                            <div
                                class="size-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0 border border-slate-100">
                                <span class="material-symbols-outlined text-2xl">description</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h4 class="text-lg font-bold text-slate-900">{{ $appLoanType }}</h4>
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $appStatusClasses }}">
                                        {{ $appStatusText }}
                                    </span>
                                </div>
                                <p class="text-sm text-slate-500">
                                    Application Reference:
                                    <span class="font-semibold">{{ $appRef }}</span>
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-8 w-full md:w-auto md:flex-1 md:justify-end md:px-8">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Amount Requested
                                </p>
                                <p class="text-lg font-black text-slate-900">&#8369;{{ number_format($appAmount, 2) }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date Applied</p>
                                <p class="text-sm font-bold text-slate-700">{{ $appDate }}</p>
                            </div>
                            <div class="col-span-2 md:col-span-1 flex items-center md:justify-end">
                                @if($appIsPending)
                                    <button type="button"
                                        class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 cursor-not-allowed"
                                        disabled title="Details are not available while this loan is pending.">
                                        View Details
                                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                                    </button>
                                @else
                                    <button type="button"
                                        class="js-view-application-btn inline-flex items-center gap-2 text-sm font-bold text-secondary hover:text-blue-700 transition-colors"
                                        data-app-id="{{ $application->id }}">
                                        View Details
                                        <span class="material-symbols-outlined text-lg">chevron_right</span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="p-6 flex items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="size-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-2xl">check_circle</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">No Loan Applications Yet</h4>
                            <p class="text-sm text-slate-500">You have not submitted a loan application.</p>
                        </div>
                    </div>
                    @if($canApplyForLoan)
                        <a href="{{ route('member.loans.apply') }}"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-background-dark font-black text-sm hover:brightness-105 transition-all">
                            Apply Now
                        </a>
                    @else
                        <button type="button"
                            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-200 text-slate-500 font-black text-sm cursor-not-allowed"
                            disabled title="{{ $applyDisabledReason }}">
                            Apply Now
                        </button>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <section>
        <div class="mb-6">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">account_balance</span>
                Active Loans
            </h3>
        </div>

        @if($activeLoans->isNotEmpty())
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach($activeLoans as $loan)
                    @php
                        $loanTypeText = $loan->loan_type_label ?? $typeLabel($loan->loan_type ?? '');
                        $loanId = $loan->loan_id ?? 'N/A';
                        $remaining = (float) ($loan->remaining_balance ?? 0);
                        $monthly = (float) ($loan->monthly_payment ?? 0);
                        $nextDue = $loan->next_due_date_label ?? 'N/A';
                        $approved = $loan->approved_date_label ?? 'N/A';
                        $maturity = $loan->maturity_date_label ?? 'N/A';
                    @endphp

                    <div class="bg-white p-8 rounded-2xl border-l-4 border-l-primary border border-slate-200 card-shadow">
                        <div class="flex items-start justify-between mb-8">
                            <div>
                                <div class="flex items-center gap-3 mb-1">
                                    <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ $loanTypeText }}
                                    </h4>
                                    <span
                                        class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-[10px] font-bold uppercase">Ongoing</span>
                                </div>
                                <p class="text-sm font-medium text-slate-500 mb-2">Loan ID: {{ $loanId }}</p>
                                <p class="text-3xl font-black text-slate-900">&#8369;{{ number_format($remaining, 2) }}</p>
                                <p class="text-xs font-bold text-slate-400 mt-1 uppercase">Remaining Balance</p>
                            </div>
                            <div class="size-12 rounded-xl bg-slate-50 text-slate-400 flex items-center justify-center">
                                <span class="material-symbols-outlined text-3xl">{{ $typeIcon($loanTypeText) }}</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4 pt-6 border-t border-slate-50">
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Next Due Date</p>
                                <p class="text-sm font-bold text-slate-800">{{ $nextDue }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Monthly Installment
                                </p>
                                <p class="text-sm font-black text-primary">&#8369;{{ number_format($monthly, 2) }}</p>
                            </div>
                        </div>

                        <button type="button"
                            class="js-loan-record-btn mt-6 w-full py-2.5 text-sm font-bold text-secondary bg-secondary/5 rounded-lg hover:bg-secondary/10 transition-colors"
                            data-loan-id="{{ $loanId }}" data-loan-type="{{ $loanTypeText }}"
                            data-principal="{{ (float) ($loan->loan_amount ?? 0) }}" data-remaining="{{ $remaining }}"
                            data-approved="{{ $approved }}" data-maturity="{{ $maturity }}" data-monthly="{{ $monthly }}"
                            data-status="Ongoing">
                            View Loan Breakdown
                        </button>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-2xl border border-slate-200 card-shadow p-8">
                <p class="text-sm font-bold text-slate-500">No active loans at the moment.</p>
            </div>
        @endif
    </section>

    <section class="pb-10">
        <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">history</span>
                Loan History
            </h3>

            <form method="GET" action="{{ route('member.loans.index') }}" class="relative w-full md:w-72">
                <span
                    class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xl">search</span>
                <input name="q" value="{{ $historySearch }}"
                    class="w-full pl-10 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all outline-none"
                    placeholder="Search loan history..." type="text" />
            </form>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">No.</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Loan Type
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Principal
                                Amount
                            </th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date
                                Approved</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Maturity
                                Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($loanHistory as $loan)
                            @php
                                $statusText = !empty($loan->is_paid) ? 'Paid' : 'Ongoing';
                                $statusPill = !empty($loan->is_paid)
                                    ? 'bg-slate-100 text-slate-600 border border-slate-200'
                                    : 'bg-green-100 text-green-700 border border-green-200';
                                $rowType = $loan->loan_type_label ?? $typeLabel($loan->loan_type ?? '');
                                $rowId = $loan->loan_id ?? 'N/A';
                                $rowPrincipal = (float) ($loan->loan_amount ?? 0);
                                $rowRemaining = (float) ($loan->remaining_balance ?? 0);
                                $rowMonthly = (float) ($loan->monthly_payment ?? 0);
                                $rowApproved = $loan->approved_date_label ?? 'N/A';
                                $rowMaturity = $loan->maturity_date_label ?? 'N/A';
                                $rowNo = (($loanHistory->currentPage() - 1) * $loanHistory->perPage()) + $loop->iteration;
                            @endphp
                            <tr>
                                <td class="px-6 py-4 text-sm font-bold text-slate-400">
                                    {{ str_pad((string) $rowNo, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <span class="size-2 rounded-full {{ $typeDot($rowType) }}"></span>
                                        <span class="text-sm font-bold text-slate-900">{{ $rowType }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm font-black text-slate-900">
                                    &#8369;{{ number_format($rowPrincipal, 2) }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $rowApproved }}</td>
                                <td class="px-6 py-4 text-sm text-slate-600">{{ $rowMaturity }}</td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $statusPill }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button type="button"
                                        class="js-loan-record-btn text-xs font-bold text-secondary hover:underline"
                                        data-loan-id="{{ $rowId }}" data-loan-type="{{ $rowType }}"
                                        data-principal="{{ $rowPrincipal }}" data-remaining="{{ $rowRemaining }}"
                                        data-approved="{{ $rowApproved }}" data-maturity="{{ $rowMaturity }}"
                                        data-monthly="{{ $rowMonthly }}" data-status="{{ $statusText }}">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-sm font-medium text-slate-500">
                                    No loan records found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                    @if($loanHistory->total() > 0)
                        Showing {{ $loanHistory->firstItem() }} to {{ $loanHistory->lastItem() }} of
                        {{ $loanHistory->total() }}
                        records
                    @else
                        Showing 0 records
                    @endif
                </p>

                <div class="flex items-center gap-2">
                    @if($loanHistory->onFirstPage())
                        <span
                            class="size-8 flex items-center justify-center rounded bg-white border border-slate-200 text-slate-300">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </span>
                    @else
                        <a href="{{ $loanHistory->previousPageUrl() }}"
                            class="size-8 flex items-center justify-center rounded bg-white border border-slate-200 text-slate-400 hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined text-lg">chevron_left</span>
                        </a>
                    @endif

                    @php
                        $currentPage = $loanHistory->currentPage();
                        $lastPage = max($loanHistory->lastPage(), 1);
                        $startPage = max(1, $currentPage - 1);
                        $endPage = min($lastPage, $currentPage + 1);
                    @endphp

                    @for($p = $startPage; $p <= $endPage; $p++)
                        @if($p === $currentPage)
                            <span
                                class="size-8 flex items-center justify-center rounded bg-primary text-background-dark font-bold text-xs">{{ $p }}</span>
                        @else
                            <a href="{{ $loanHistory->url($p) }}"
                                class="size-8 flex items-center justify-center rounded bg-white border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors">
                                {{ $p }}
                            </a>
                        @endif
                    @endfor

                    @if($loanHistory->hasMorePages())
                        <a href="{{ $loanHistory->nextPageUrl() }}"
                            class="size-8 flex items-center justify-center rounded bg-white border border-slate-200 text-slate-400 hover:text-slate-600 transition-colors">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </a>
                    @else
                        <span
                            class="size-8 flex items-center justify-center rounded bg-white border border-slate-200 text-slate-300">
                            <span class="material-symbols-outlined text-lg">chevron_right</span>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <div id="loanRecordModal" class="fixed inset-0 z-[90] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-close-loan-modal></div>
        <div
            class="relative w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl max-h-[90vh] flex flex-col">
            <div class="bg-sidebar-green p-6 flex items-start justify-between shrink-0 bg-[#fcfdfc]">
                <h4 class="text-lg font-black text-slate-900">Loan Record Details</h4>
                <button type="button" class="text-slate-400 hover:text-slate-700" data-close-loan-modal>
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6 grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Loan ID</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_id">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Loan Type</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_type">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Principal</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_principal">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Remaining Balance</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_remaining">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date Approved</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_approved">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Maturity Date</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_maturity">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Monthly Installment</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_monthly">N/A</p>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status</p>
                    <p class="font-black text-slate-900 mt-1" id="loan_modal_status">N/A</p>
                </div>
            </div>
        </div>
    </div>

    <div id="applicationModal" class="fixed inset-0 z-[95] hidden items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" data-close-application-modal></div>
        <div
            class="relative w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl max-h-[90vh] flex flex-col">
            <div class="bg-sidebar-green p-6 flex items-start justify-between shrink-0 bg-[#fcfdfc]">
                <div>
                    <h2 class="text-lg font-black text-slate-900">Application Details</h2>
                    <p class="text-[10px] text-primary font-bold uppercase tracking-[0.2em] mt-1">
                        Application No: <span id="app_modal_ref">—</span>
                    </p>
                </div>
                <button type="button" class="text-slate-400 hover:text-slate-700" data-close-application-modal>
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Loan Type</p>
                        <p id="app_modal_type" class="mt-1 text-lg font-black text-slate-900">—</p>

                        <div class="mt-4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Amount</p>
                            <p id="app_modal_amount" class="mt-1 text-2xl font-black text-slate-900">—</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Date Applied</p>
                            <p id="app_modal_date" class="mt-1 text-sm font-bold text-slate-700">—</p>
                        </div>
                    </div>

                    {{-- Admin note --}}
                    <div class="rounded-2xl border border-primary/20 bg-primary/5 p-5">
                        <div class="flex items-center justify-between">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-600">Admin Notes</p>
                            <span id="app_modal_status"
                                class="px-3 py-1 rounded-full text-xs font-black bg-slate-100 text-slate-700 border border-slate-200">
                                For Printing
                            </span>
                        </div>

                        <p id="app_modal_remarks" class="mt-3 text-sm font-medium text-slate-700 leading-relaxed">
                            —
                        </p>

                    </div>

                </div>
                <a id="app_modal_print" href="#" target="_blank"
                    class="hidden mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-primary text-background-dark font-black text-xs hover:brightness-105 transition-all">
                    <span class="material-symbols-outlined text-base">picture_as_pdf</span>
                    Open Printable Form
                </a>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            (function () {
                const toCurrency = (value) =>
                    `\u20B1${Number(value || 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                const applicationStatusClass = (status) => {
                    const raw = (status || '').toString().toLowerCase();
                    if (raw === 'pending') return 'bg-amber-100 text-amber-700 border border-amber-200';
                    if (raw === 'for_review' || raw === 'in_review' || raw === 'for_approval')
                        return 'bg-blue-100 text-blue-700 border border-blue-200';
                    if (raw === 'for_printing') return 'bg-purple-100 text-purple-700 border border-purple-200';
                    if (raw === 'approved') return 'bg-green-100 text-green-700 border border-green-200';
                    if (raw === 'rejected') return 'bg-red-100 text-red-700 border border-red-200';
                    return 'bg-slate-100 text-slate-700 border border-slate-200';
                };
                const statusBaseClass = 'px-3 py-1 rounded-full text-xs font-black';

                const loanModal = document.getElementById('loanRecordModal');
                const loanButtons = document.querySelectorAll('.js-loan-record-btn');
                const loanClose = document.querySelectorAll('[data-close-loan-modal]');

                function openLoanModal(btn) {
                    document.getElementById('loan_modal_id').textContent = btn.dataset.loanId || 'N/A';
                    document.getElementById('loan_modal_type').textContent = btn.dataset.loanType || 'N/A';
                    document.getElementById('loan_modal_principal').textContent = toCurrency(btn.dataset.principal);
                    document.getElementById('loan_modal_remaining').textContent = toCurrency(btn.dataset.remaining);
                    document.getElementById('loan_modal_approved').textContent = btn.dataset.approved || 'N/A';
                    document.getElementById('loan_modal_maturity').textContent = btn.dataset.maturity || 'N/A';
                    document.getElementById('loan_modal_monthly').textContent = toCurrency(btn.dataset.monthly);
                    document.getElementById('loan_modal_status').textContent = btn.dataset.status || 'N/A';
                    loanModal.classList.remove('hidden');
                    loanModal.classList.add('flex');
                }

                function closeLoanModal() {
                    loanModal.classList.add('hidden');
                    loanModal.classList.remove('flex');
                }

                loanButtons.forEach((btn) => btn.addEventListener('click', () => openLoanModal(btn)));
                loanClose.forEach((el) => el.addEventListener('click', closeLoanModal));

                const appButtons = document.querySelectorAll('.js-view-application-btn');
                const appModal = document.getElementById('applicationModal');
                const appClose = document.querySelectorAll('[data-close-application-modal]');

                async function openApplicationModal(appId) {
                    if (!appId || !appModal) return;

                    appModal.classList.remove('hidden');
                    appModal.classList.add('flex');

                    document.getElementById('app_modal_ref').textContent = 'Loading...';
                    document.getElementById('app_modal_type').textContent = 'Loading...';
                    document.getElementById('app_modal_amount').textContent = 'Loading...';
                    document.getElementById('app_modal_date').textContent = 'Loading...';
                    document.getElementById('app_modal_status').textContent = 'Loading...';
                    document.getElementById('app_modal_status').className =
                        `${statusBaseClass} ${applicationStatusClass('')}`;
                    document.getElementById('app_modal_remarks').textContent = 'Loading...';
                    document.getElementById('app_modal_print').classList.add('hidden');

                    try {
                        const res = await fetch(`{{ url('member/loans') }}/${appId}/details`, {
                            headers: { Accept: 'application/json' }
                        });
                        if (!res.ok) throw new Error('Unable to load');

                        const data = await res.json();
                        document.getElementById('app_modal_ref').textContent = data.application_no || 'N/A';
                        document.getElementById('app_modal_type').textContent = (data.loan_type || 'N/A')
                            .toString()
                            .replaceAll('_', ' ');
                        document.getElementById('app_modal_amount').textContent = toCurrency(data.loan_amount || 0);
                        document.getElementById('app_modal_date').textContent = data.created_at || 'N/A';
                        document.getElementById('app_modal_status').textContent = (data.status || 'N/A')
                            .toString()
                            .replaceAll('_', ' ');
                        document.getElementById('app_modal_status').className =
                            `${statusBaseClass} ${applicationStatusClass(data.status)}`;
                        document.getElementById('app_modal_remarks').textContent = (data.remarks || '').trim() ||
                            'No remarks provided.';

                        if ((data.status || '').toLowerCase() === 'for_printing' && data.pdf_url) {
                            const printLink = document.getElementById('app_modal_print');
                            printLink.href = data.pdf_url;
                            printLink.classList.remove('hidden');
                        }
                    } catch (error) {
                        document.getElementById('app_modal_ref').textContent = 'N/A';
                        document.getElementById('app_modal_type').textContent = 'N/A';
                        document.getElementById('app_modal_amount').textContent = 'N/A';
                        document.getElementById('app_modal_date').textContent = 'N/A';
                        document.getElementById('app_modal_status').textContent = 'N/A';
                        document.getElementById('app_modal_status').className =
                            `${statusBaseClass} ${applicationStatusClass('')}`;
                        document.getElementById('app_modal_remarks').textContent = 'Unable to load application details.';
                    }
                }

                function closeApplicationModal() {
                    if (!appModal) return;
                    appModal.classList.add('hidden');
                    appModal.classList.remove('flex');
                }

                appButtons.forEach((btn) => {
                    btn.addEventListener('click', () => openApplicationModal(btn.dataset.appId));
                });
                appClose.forEach((el) => el.addEventListener('click', closeApplicationModal));

                document.addEventListener('keydown', (e) => {
                    if (e.key !== 'Escape') return;
                    closeLoanModal();
                    closeApplicationModal();
                });
            })();
        </script>
    @endpush

</x-member-layout>