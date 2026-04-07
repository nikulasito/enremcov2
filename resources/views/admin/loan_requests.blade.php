<x-admin-v2-layout title="ENREMCO - Loan Requests" pageTitle="Loan Requests" :showSearch="false">
    @php
        $loanAdmin = auth()->user();
        $isExecAdmin = $loanAdmin?->isExecAdmin() ?? false;
        $isCreditOfficer = $loanAdmin?->isCreditOfficer() ?? false;
        $isRegularAdmin = $loanAdmin?->isRegularAdmin() ?? false;
        $canEditLoanFields = $isCreditOfficer;
    @endphp
    {{-- Page Header (matches how other pages do it) --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-900">Loan Requests Queue</h1>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Application Processing</p>
            </div>

            <div class="flex items-center gap-3 w-full md:w-auto">
                <form method="GET" action="{{ route('admin.loan-requests.index') }}"
                    class="w-full md:w-[420px] relative">
                    <span
                        class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>

                    <input name="q" value="{{ $q ?? request('q') }}"
                        class="w-full h-11 pl-11 pr-4 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium"
                        placeholder="Search (Name / Member ID / App No.)..." type="text" />

                    {{-- keep filters while searching --}}
                    <input type="hidden" name="loan_type" value="{{ $loanType ?? request('loan_type', 'all') }}">
                    <input type="hidden" name="status"
                        value="{{ $isExecAdmin ? 'for_approval' : ($status ?? request('status', 'all')) }}">
                </form>
            </div>
        </div>
    </x-slot>

    {{-- Flash --}}
    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-6 py-4 text-sm font-bold text-green-800">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="rounded-2xl border border-red-200 bg-red-50 px-6 py-4 text-sm font-bold text-red-800">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-6">
        {{-- Filters Row --}}
        <form method="GET" action="{{ route('admin.loan-requests.index') }}" class="space-y-6">

            <div class="flex flex-wrap items-end gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Loan Type</label>
                    <select name="loan_type"
                        class="h-10 pl-4 pr-10 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer min-w-[160px]">
                        <option value="all" @selected(($loanType ?? request('loan_type', 'all')) === 'all')>All Types
                        </option>
                        <option value="regular" @selected(($loanType ?? request('loan_type')) === 'regular')>Regular Loan
                        </option>
                        <option value="educational" @selected(($loanType ?? request('loan_type')) === 'educational')>
                            Educational Loan</option>
                        <option value="appliance" @selected(($loanType ?? request('loan_type')) === 'appliance')>Appliance
                            Loan</option>
                        <option value="grocery" @selected(($loanType ?? request('loan_type')) === 'grocery')>Grocery Loan
                        </option>
                    </select>
                </div>

                @if(!$isExecAdmin)
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Status</label>
                        <select name="status"
                            class="h-10 pl-4 pr-10 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer min-w-[160px]">
                            <option value="all" @selected(($status ?? request('status', 'all')) === 'all')>All Status</option>
                            <option value="pending" @selected(($status ?? request('status')) === 'pending')>Pending</option>
                            <option value="reviewed" @selected(($status ?? request('status')) === 'reviewed')>Reviewed</option>
                            <option value="for_processing" @selected(($status ?? request('status')) === 'for_processing')>For Processing</option>
                            <option value="for_approval" @selected(($status ?? request('status')) === 'for_approval')>For
                                Approval</option>
                            <option value="approved" @selected(($status ?? request('status')) === 'approved')>Approved
                            </option>
                            <option value="rejected" @selected(($status ?? request('status')) === 'rejected')>Rejected
                            </option>
                        </select>
                    </div>
                @else
                    <input type="hidden" name="status" value="for_approval">
                    <div
                        class="h-10 px-4 inline-flex items-center rounded-lg border border-indigo-200 bg-indigo-50 text-xs font-black text-indigo-700">
                        Showing: For Approval Only
                    </div>
                @endif

                <input type="hidden" name="q" value="{{ $q ?? request('q') }}">

                <button type="submit"
                    class="h-10 px-5 bg-white border border-slate-200 rounded-lg text-xs font-black text-slate-600 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Apply Filters
                </button>

                <a href="{{ route('admin.loan-requests.index') }}"
                    class="h-10 px-5 bg-white border border-slate-200 rounded-lg text-xs font-black text-slate-600 hover:bg-slate-50 hover:text-primary transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                    Clear
                </a>
            </div>

            {{-- optional: if you want a â€œNew Applicationâ€ button --}}
            {{-- <a href="#"
                class="h-11 px-6 bg-primary text-background-dark font-black rounded-xl transition-all shadow-sm flex items-center gap-2 hover:brightness-105">
                <span class="material-symbols-outlined text-[20px]">add</span>
                New Application
            </a> --}}
        </form>
    </div>
    {{-- Table Card --}}
    <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500">No.
                        </th>
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500">Member
                            ID</th>
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500">Member
                            Name</th>
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500">Loan
                            Type</th>
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            Requested Amount</th>
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500">
                            Application Date</th>
                        <th
                            class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500 text-center">
                            Status</th>
                        <th class="px-6 py-4 text-[11px] font-black uppercase tracking-wider text-slate-500 text-right">
                            Action</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse($applications as $app)
                        @php
                            $no = ($applications->firstItem() ?? 0) + $loop->index;

                            $initials = collect(explode(' ', trim($app->full_name ?? '')))
                                ->filter()
                                ->map(fn($p) => mb_substr($p, 0, 1))
                                ->take(2)
                                ->implode('');

                            $typeLabel = ucwords(str_replace('_', ' ', $app->loan_type ?? 'Loan'));

                            $statusLabel = match ($app->status) {
                                'pending' => 'Pending',
                                'reviewed' => 'Reviewed',
                                'for_approval' => 'For Approval',
                                'for_processing' => 'For Processing',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                default => ucwords((string) $app->status),
                            };

                            $statusClass = match ($app->status) {
                                'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                'reviewed' => 'bg-sky-100 text-sky-700 border border-sky-200',
                                'for_approval' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                'for_processing' => 'bg-purple-100 text-purple-700 border border-purple-200',
                                'approved' => 'bg-green-100 text-green-700 border border-green-200',
                                'rejected' => 'bg-red-100 text-red-700 border border-red-200',
                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                            };
                        @endphp

                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-5 text-sm font-medium text-slate-400">
                                {{ str_pad($no, 2, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="px-6 py-5 text-sm font-black text-slate-700">
                                {{ $app->member_key ?? 'â€”' }}
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="size-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-black text-[10px]">
                                        {{ $initials ?: 'â€”' }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-black text-slate-900">{{ $app->full_name }}</div>
                                        <div class="text-[11px] text-slate-500">
                                            App No: <span class="font-semibold">{{ $app->application_no }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="px-6 py-5 text-sm font-medium text-slate-600">{{ $typeLabel }}</td>

                            <td class="px-6 py-5 text-sm font-extrabold text-slate-900">
                                &#8369;{{ number_format((float) $app->loan_amount, 2) }}
                            </td>

                            <td class="px-6 py-5 text-sm font-medium text-slate-500">
                                {{ \Illuminate\Support\Carbon::parse($app->created_at)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-5 text-center">
                                <span
                                    class="px-2.5 py-1 text-[10px] font-black uppercase tracking-wider rounded-full {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>

                            <td class="px-6 py-5">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- Review --}}
                                    <button type="button"
                                        class="js-open-loan-modal flex items-center justify-center p-2 rounded-lg text-blue-600 hover:bg-blue-50 transition-all"
                                        title="Review" data-id="{{ $app->id }}"
                                        data-application-no="{{ $app->application_no }}"
                                        data-full-name="{{ e($app->full_name) }}"
                                        data-address="{{ e($app->address ?? '') }}"
                                        data-member-key="{{ e($app->member_key ?? '') }}"
                                        data-loan-type="{{ e($app->loan_type ?? '') }}"
                                        data-loan-amount="{{ (float) $app->loan_amount }}"
                                        data-created="{{ \Illuminate\Support\Carbon::parse($app->created_at)->format('M d, Y') }}">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </button>

                                    @if($isCreditOfficer && strtolower((string) $app->status) === 'pending')
                                        {{-- Reject --}}
                                        <form method="POST" action="{{ route('admin.loan-requests.reject', $app->id) }}"
                                            onsubmit="return confirm('Reject this application?');">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="remarks" value="Rejected by Credit Officer.">
                                            <button type="submit"
                                                class="flex items-center justify-center p-2 rounded-lg text-red-600 hover:bg-red-50 transition-all"
                                                title="Reject">
                                                <span class="material-symbols-outlined text-[20px]">cancel</span>
                                            </button>
                                        </form>
                                    @elseif(in_array(strtolower((string) $app->status), ['approved', 'rejected'], true))
                                        <div class="text-right text-xs font-bold text-slate-400">No actions</div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-6 py-10 text-sm text-slate-500" colspan="8">
                                No loan applications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Footer / Pagination --}}
        @php
            $from = $applications->firstItem() ?? 0;
            $to = $applications->lastItem() ?? 0;
            $total = $applications->total() ?? 0;
        @endphp

        <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
            <p class="text-xs font-black text-slate-500">
                Showing {{ $from }} to {{ $to }} of {{ $total }} applications
            </p>

            <div>
                {{-- Use Laravel pagination (tailwind) --}}
                {{ $applications->links() }}
            </div>
        </div>
    </div>
    {{-- Review Modal (inside Loan Requests page) --}}
    <div id="loanReviewModal"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-[#0d1a14]/70 backdrop-blur-sm p-4">

        <div class="bg-white w-full max-w-4xl rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">

            {{-- Header --}}
            <div class="bg-[#0d1a14] p-6 flex justify-between items-center shrink-0">
                <div>
                    <h2 class="text-xl font-black text-white">Review Loan Application</h2>
                    <p class="text-[10px] text-primary font-black uppercase tracking-[0.2em] mt-1">
                        Application ID: <span id="m_app_no">â€”</span>
                    </p>
                </div>
                <button type="button" id="closeLoanReviewModal"
                    class="text-white/60 hover:text-white transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            {{-- Body --}}
            <div class="flex-1 overflow-y-auto p-8 custom-scrollbar">

                {{-- Top Info --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-4 mb-8">
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-black text-slate-500 uppercase w-24">NAME:</span>
                            <span class="text-sm font-black text-slate-900" id="m_name">â€”</span>
                        </div>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-black text-slate-500 uppercase w-24">ADDRESS:</span>
                            <span class="text-sm font-medium text-slate-700" id="m_address">â€”</span>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-black text-slate-500 uppercase w-40">MEMBER ID:</span>
                            <span class="text-sm font-black text-slate-900" id="m_member_key">â€”</span>
                        </div>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-black text-slate-500 uppercase w-40">AMOUNT OF LOAN:</span>
                            <span class="text-sm font-extrabold text-[#15c26b]" id="m_amount">₱0.00</span>
                        </div>
                        <div class="flex items-center gap-2 border-b border-slate-100 pb-2">
                            <span class="text-[11px] font-black text-slate-500 uppercase w-40">DATE APPLIED:</span>
                            <span class="text-sm font-bold text-slate-700" id="m_date">â€”</span>
                        </div>
                    </div>
                </div>

                {{-- Loan-type specific details --}}
                <div class="mb-8 rounded-xl border border-slate-200 bg-white overflow-hidden">
                    <div class="px-5 py-3 border-b border-slate-100 bg-slate-50">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Loan-Specific Details
                        </p>
                    </div>
                    <div class="p-5">
                        <div data-loan-panel="regular" class="js-loan-type-panel hidden">
                            <p class="text-sm font-bold text-slate-800">Regular Loan Review</p>
                            <p class="text-xs text-slate-500 mt-1">Verify deductions, prior balances, and repayment
                                terms for salary-based regular loan processing.</p>
                            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                                <div>
                                    <label for="regular_run_term" class="font-black text-slate-600">1. This is to
                                        run:</label>
                                    <div class="mt-1 flex items-center gap-2">
                                        <input id="regular_run_term" name="run_term" form="approveForm"
                                            class="w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-semibold"
                                            type="text" placeholder="e.g. 24" @if(!$canEditLoanFields) readonly @endif>
                                        <span class="whitespace-nowrap text-slate-500">months/day</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="regular_first_installment_date" class="font-black text-slate-600">2.
                                        The first installment increased will be on:</label>
                                    <input id="regular_first_installment_date" name="first_installment_date"
                                        form="approveForm"
                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-semibold"
                                        type="date" @if(!$canEditLoanFields) readonly @endif>
                                </div>

                                <div>
                                    <label for="regular_installment_increased_to" class="font-black text-slate-600">3.
                                        Loan Installment increased to:</label>
                                    <input id="regular_installment_increased_to" name="installment_increased_to"
                                        form="approveForm"
                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-semibold"
                                        type="number" step="0.01" min="0" value="0" @if(!$canEditLoanFields) readonly @endif>
                                </div>

                                <div>
                                    <label for="regular_simple_annual_rate" class="font-black text-slate-600">4.
                                        Simple annual rate required to:</label>
                                    <input id="regular_simple_annual_rate" name="simple_annual_rate" form="approveForm"
                                        class="mt-1 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-semibold"
                                        type="text" placeholder="e.g. 12%" @if(!$canEditLoanFields) readonly @endif>
                                </div>
                            </div>
                            <p class="mt-4 text-xs font-black text-slate-600">5. Disclosed Under R.A. 365</p>
                        </div>

                        <div data-loan-panel="educational" class="js-loan-type-panel hidden">
                            <p class="text-sm font-bold text-slate-800">Educational Loan Review</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2 text-xs">
                                <div><span class="font-black text-slate-600">Beneficiary:</span> <span
                                        id="d_edu_beneficiary">—</span></div>
                                <div><span class="font-black text-slate-600">School:</span> <span
                                        id="d_edu_school">—</span></div>
                                <div><span class="font-black text-slate-600">Program/Course:</span> <span
                                        id="d_edu_program">—</span></div>
                                <div><span class="font-black text-slate-600">School Year/Sem:</span> <span
                                        id="d_edu_term">—</span></div>
                            </div>
                        </div>

                        <div data-loan-panel="appliance" class="js-loan-type-panel hidden">
                            <p class="text-sm font-bold text-slate-800">Appliance Loan Review</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2 text-xs">
                                <div class="md:col-span-2">
                                    <p class="font-black text-slate-600">Requested Items:</p>
                                    <p id="d_app_items_empty" class="text-slate-700 mt-1">—</p>
                                    <div id="d_app_items_wrap"
                                        class="hidden mt-2 overflow-x-auto rounded-lg border border-slate-200">
                                        <table class="w-full text-xs">
                                            <thead class="bg-slate-50 border-b border-slate-200">
                                                <tr>
                                                    <th class="px-3 py-2 text-left font-black text-slate-600">Item</th>
                                                    <th class="px-3 py-2 text-right font-black text-slate-600 w-20">Qty
                                                    </th>
                                                    <th class="px-3 py-2 text-right font-black text-slate-600 w-28">Unit
                                                        Price</th>
                                                    <th class="px-3 py-2 text-right font-black text-slate-600 w-28">
                                                        Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody id="d_app_items" class="divide-y divide-slate-100 bg-white"></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div><span class="font-black text-slate-600">Brand/Model:</span> <span
                                        id="d_app_brand">—</span></div>
                                <div><span class="font-black text-slate-600">Store/Supplier:</span> <span
                                        id="d_app_store">—</span></div>
                                <div><span class="font-black text-slate-600">Total Amount:</span> <span
                                        id="d_app_cash_price">—</span></div>
                                <div><span class="font-black text-slate-600">Downpayment:</span> <span
                                        id="d_app_downpayment">—</span></div>
                                <div><span class="font-black text-slate-600">Warranty (months):</span> <span
                                        id="d_app_warranty">—</span></div>
                            </div>
                        </div>

                        <div data-loan-panel="grocery" class="js-loan-type-panel hidden">
                            <p class="text-sm font-bold text-slate-800">Grocery Loan Review</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-2 text-xs">
                                <div><span class="font-black text-slate-600">Preferred Store:</span> <span
                                        id="d_gro_store">—</span></div>
                                <div><span class="font-black text-slate-600">Coverage Period:</span> <span
                                        id="d_gro_coverage">—</span></div>
                                <div><span class="font-black text-slate-600">Household Size:</span> <span
                                        id="d_gro_household">—</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Computation Table --}}
                <div class="mb-8">
                    <div class="bg-slate-50 border border-slate-200 rounded-xl overflow-hidden">
                        <table class="w-full text-sm">
                            <thead class="bg-slate-100 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3 text-left font-black text-slate-700">Kind of Loan</th>
                                    <th class="px-6 py-3 text-right font-black text-slate-700">Amount</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-slate-900">
                                        Requested Loan (<span id="m_loan_type">â€”</span>)
                                    </td>
                                    <td class="px-6 py-4 text-right font-black text-slate-900">
                                        &#8369;<span id="m_amount_row">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 font-semibold text-slate-900">
                                        Approved Amount
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <input id="approved_amount" name="approved_amount" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" min="0" value="0" @if(!$canEditLoanFields) readonly
                                            @endif>
                                    </td>
                                </tr>

                                <tr class="bg-slate-50/50">
                                    <td class="px-6 py-2 text-[10px] font-black text-slate-400 uppercase" colspan="2">
                                        Less: Deductions
                                    </td>
                                </tr>

                                <tr id="row_old_balance">
                                    <td class="px-6 py-3 pl-10 text-slate-600" id="label_old_balance">Balance (Old Bal)
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="old_balance" name="old_balance" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0" @if(!$canEditLoanFields) readonly @endif>
                                    </td>
                                </tr>
                                <tr id="row_lpp">
                                    <td class="px-6 py-3 pl-10 text-slate-600" id="label_lpp">LPP</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="lpp" name="lpp" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0" readonly>
                                    </td>
                                </tr>
                                <tr id="row_interest">
                                    <td class="px-6 py-3 pl-10 text-slate-600" id="label_interest">Interest (amount)
                                    </td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="interest" name="interest" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0" readonly>
                                    </td>
                                </tr>
                                <tr id="row_handling_fee">
                                    <td class="px-6 py-3 pl-10 text-slate-600" id="label_handling_fee">Handling Fee</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="handling_fee" name="handling_fee" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0" @if(!$canEditLoanFields) readonly @endif>
                                    </td>
                                </tr>
                                <tr id="row_petty_cash_loan">
                                    <td class="px-6 py-3 pl-10 text-slate-600" id="label_petty_cash_loan">Petty Cash
                                        Loan</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="petty_cash_loan" name="petty_cash_loan" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0" @if(!$canEditLoanFields) readonly @endif>
                                    </td>
                                </tr>

                                <tr class="bg-slate-50">
                                    <td class="px-6 py-4 font-black text-slate-900">Total Deduction</td>
                                    <td class="px-6 py-4 text-right font-extrabold text-red-500">
                                        &#8369;<span id="total_deduction">0.00</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Summary Card (right aligned like your design) --}}
                <div class="grid grid-cols-1 gap-8 mb-8">
                    <div class="space-y-3 bg-primary/5 p-6 rounded-2xl border border-primary/10">
                        <div class="flex justify-between items-center">
                            <span class="text-xs font-black text-slate-600">Total</span>
                            <span class="text-sm font-black text-slate-900" id="gross_total">&#8369;0.00</span>
                        </div>

                        <div class="pt-3 border-t border-primary/20 flex justify-between items-center">
                            <span class="text-sm font-extrabold text-[#0d1a14] uppercase">NET CASH RECEIVED</span>
                            <span class="text-xl font-black text-[#15c26b]">&#8369;<span
                                    id="net_cash">0.00</span></span>
                        </div>

                        <div class="pt-4 border-t border-slate-200">
                            <label id="terms_label"
                                class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Terms
                                (months)</label>
                            <input id="terms" name="terms" form="approveForm"
                                class="mt-2 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                type="number" min="1" value="24" @if(!$canEditLoanFields) readonly @endif>
                        </div>

                        <div>
                            <label id="monthly_label"
                                class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Monthly
                                Installment</label>
                            <input id="monthly_payment" name="monthly_payment" form="approveForm"
                                class="mt-2 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                type="number" step="0.01" value="0" @if(!$canEditLoanFields) readonly @endif>
                        </div>

                        {{-- computed hidden fields --}}
                        <input type="hidden" id="total_deduction_input" name="total_deduction" form="approveForm"
                            value="0">
                        <input type="hidden" id="total_net_input" name="total_net" form="approveForm" value="0">
                    </div>
                </div>

                {{-- Forms --}}
                <div class="grid grid-cols-1 {{ ($isCreditOfficer || $isExecAdmin) ? 'md:grid-cols-2' : '' }} gap-6">
                    @if($isCreditOfficer || $isExecAdmin)
                        <form id="rejectForm" method="POST" action="#">
                            @csrf
                            @method('PATCH')
                            <div class="rounded-xl border border-slate-200 bg-white p-6">
                                <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Rejection
                                    Remarks</p>
                                <textarea id="reject_remarks" name="remarks" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium"
                                    rows="3" placeholder="Reason for rejection..."></textarea>
                            </div>
                        </form>
                    @endif

                    <form id="approveForm" method="POST" action="#">
                        @csrf
                        @method('PATCH')
                        <div class="rounded-xl border border-slate-200 bg-white p-6">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">
                                {{ $isCreditOfficer ? 'Review Notes' : 'Approval Notes' }} (optional)
                            </p>
                            <textarea id="approve_remarks" name="remarks"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium"
                                rows="3"
                                placeholder="{{ $isCreditOfficer ? 'Notes for review (optional)...' : 'Notes for approval (optional)...' }}"></textarea>
                        </div>
                    </form>

                    <form id="statusForm" method="POST" action="#">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" id="status_action_value" name="status" value="">
                        <input type="hidden" id="status_action_remarks" name="remarks" value="">
                        <input type="hidden" id="status_approved_amount" name="approved_amount" value="">
                        <input type="hidden" id="status_old_balance" name="old_balance" value="">
                        <input type="hidden" id="status_lpp" name="lpp" value="">
                        <input type="hidden" id="status_interest" name="interest" value="">
                        <input type="hidden" id="status_handling_fee" name="handling_fee" value="">
                        <input type="hidden" id="status_petty_cash_loan" name="petty_cash_loan" value="">
                        <input type="hidden" id="status_total_deduction" name="total_deduction" value="">
                        <input type="hidden" id="status_total_net" name="total_net" value="">
                        <input type="hidden" id="status_terms" name="terms" value="">
                        <input type="hidden" id="status_monthly_payment" name="monthly_payment" value="">
                        <input type="hidden" id="status_run_term" name="run_term" value="">
                        <input type="hidden" id="status_first_installment_date" name="first_installment_date" value="">
                        <input type="hidden" id="status_installment_increased_to" name="installment_increased_to"
                            value="">
                        <input type="hidden" id="status_simple_annual_rate" name="simple_annual_rate" value="">
                    </form>
                </div>
            </div>

            {{-- Footer buttons --}}
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col gap-3 shrink-0">
                <p id="loan_action_helper" class="hidden text-xs font-bold text-amber-700">
                    Credit Officer review is required before admin processing or endorsement for approval.
                </p>

                <div class="flex justify-end gap-3">
                @if($isCreditOfficer)
                    <button type="button" data-loan-status="reviewed" data-loan-modal-action="true"
                        class="js-set-loan-status px-5 py-3 rounded-xl border border-sky-200 bg-sky-50 text-sky-700 text-sm font-black hover:bg-sky-100 transition-all">
                        Reviewed
                    </button>

                    <button type="submit" form="rejectForm" data-loan-modal-action="true"
                        class="px-8 py-3 rounded-xl border-2 border-red-500 text-red-500 text-sm font-black hover:bg-red-50 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">cancel</span>
                        Reject Application
                    </button>
                @elseif(!$isExecAdmin)
                    <button type="button" data-loan-status="for_processing" data-loan-modal-action="true"
                        class="js-set-loan-status px-5 py-3 rounded-xl border border-purple-200 bg-purple-50 text-purple-700 text-sm font-black hover:bg-purple-100 transition-all">
                        For Processing
                    </button>

                    <button type="button" data-loan-status="for_approval" data-loan-modal-action="true"
                        class="js-set-loan-status px-5 py-3 rounded-xl border border-indigo-200 bg-indigo-50 text-indigo-700 text-sm font-black hover:bg-indigo-100 transition-all">
                        For Approval
                    </button>
                @else
                    <button type="submit" form="rejectForm" data-loan-modal-action="true"
                        class="px-8 py-3 rounded-xl border-2 border-red-500 text-red-500 text-sm font-black hover:bg-red-50 transition-all flex items-center gap-2"
                        onclick="return confirm('Reject this application?');">
                        <span class="material-symbols-outlined text-[20px]">cancel</span>
                        Reject Application
                    </button>

                    <button type="submit" form="approveForm" data-loan-modal-action="true"
                        class="px-8 py-3 rounded-xl bg-primary text-[#0d1a14] text-sm font-black hover:bg-[#15c26b] transition-all shadow-lg shadow-primary/20 flex items-center gap-2"
                        onclick="return confirm('Approve this application?');">
                        <span class="material-symbols-outlined text-[20px]">check_circle</span>
                        Approve Application
                    </button>
                @endif
                </div>

                <!-- <button type="submit" form="approveForm"
                    class="px-8 py-3 rounded-xl bg-primary text-[#0d1a14] text-sm font-black hover:bg-[#15c26b] transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    Save
                </button> -->
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            (function () {
                const modal = document.getElementById('loanReviewModal');
                const closeBtn = document.getElementById('closeLoanReviewModal');
                const modalActionButtons = modal ? modal.querySelectorAll('[data-loan-modal-action="true"]') : [];

                const approveForm = document.getElementById('approveForm');
                const rejectForm = document.getElementById('rejectForm');
                const statusForm = document.getElementById('statusForm');

                // Route templates (replace __ID__ dynamically)
                const approveUrlTpl = "{{ route('admin.loan-requests.approve', '__ID__') }}";
                const rejectUrlTpl = "{{ route('admin.loan-requests.reject', '__ID__') }}";
                const statusUrlTpl = "{{ route('admin.loan-requests.status', '__ID__') }}";
                const showUrlTpl = "{{ route('admin.loan-requests.show', '__ID__') }}";
                const isExecAdmin = @json($isExecAdmin);
                const isCreditOfficer = @json($isCreditOfficer);
                const isRegularAdmin = @json($isRegularAdmin);

                // modal fields
                const m = (id) => document.getElementById(id);
                const loanTypePanels = modal ? modal.querySelectorAll('.js-loan-type-panel') : [];

                let currentLoanAmount = 0;
                let currentLoanType = 'regular';

                const loanTypeConfigs = {
                    regular: {
                        fields: ['old_balance', 'lpp', 'interest', 'handling_fee', 'petty_cash_loan'],
                        labels: {
                            old_balance: 'Balance (Old Bal)',
                            lpp: 'LPP (1.2%)',
                            interest: 'Interest (12%)',
                            handling_fee: 'Handling Fee',
                            petty_cash_loan: 'Petty Cash Loan',
                            terms: 'Terms (months)',
                            monthly: 'Monthly Installment',
                        },
                    },
                    educational: {
                        fields: ['old_balance', 'lpp', 'interest', 'handling_fee'],
                        labels: {
                            old_balance: 'Previous Balance',
                            lpp: 'LPP (1.2%)',
                            interest: 'Interest (10%)',
                            handling_fee: 'Processing Fee',
                            petty_cash_loan: 'Petty Cash Loan',
                            terms: 'Terms (sem/month)',
                            monthly: 'Monthly Installment',
                        },
                    },
                    appliance: {
                        fields: ['old_balance', 'lpp', 'interest', 'handling_fee'],
                        labels: {
                            old_balance: 'Previous Balance',
                            lpp: 'LPP (1.2%)',
                            interest: 'Interest (18%)',
                            handling_fee: 'Handling / Delivery Fee',
                            petty_cash_loan: 'Petty Cash Loan',
                            terms: 'Terms (months)',
                            monthly: 'Monthly Installment',
                        },
                    },
                    grocery: {
                        fields: ['old_balance', 'lpp', 'interest', 'handling_fee'],
                        labels: {
                            old_balance: 'Previous Balance',
                            lpp: 'LPP (1.2%)',
                            interest: 'Interest (10%)',
                            handling_fee: 'Service Fee',
                            petty_cash_loan: 'Petty Cash Loan',
                            terms: 'Terms (months)',
                            monthly: 'Monthly Installment',
                        },
                    },
                };
                const lppRate = 0.012;
                const loanTypeInterestRates = {
                    regular: 0.12,
                    educational: 0.10,
                    appliance: 0.18,
                    grocery: 0.10,
                };

                function fmt(n) {
                    const v = Number.isFinite(n) ? n : 0;
                    return v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                function num(v) {
                    const n = parseFloat(v);
                    return Number.isFinite(n) ? n : 0;
                }
                function escapeHtml(v) {
                    return String(v ?? '')
                        .replaceAll('&', '&amp;')
                        .replaceAll('<', '&lt;')
                        .replaceAll('>', '&gt;')
                        .replaceAll('"', '&quot;')
                        .replaceAll("'", '&#039;');
                }
                function normalizeLoanType(raw) {
                    const v = String(raw || '').toLowerCase().replaceAll('_', ' ').replaceAll('-', ' ').trim();
                    if (v.includes('education')) return 'educational';
                    if (v.includes('appliance')) return 'appliance';
                    if (v.includes('grocery')) return 'grocery';
                    return 'regular';
                }
                function statusLabel(rawStatus) {
                    const status = String(rawStatus || '').toLowerCase();
                    if (status === 'pending') return 'Pending';
                    if (status === 'reviewed') return 'Reviewed';
                    if (status === 'for_processing') return 'For Processing';
                    if (status === 'for_approval') return 'For Approval';
                    if (status === 'approved') return 'Approved';
                    if (status === 'rejected') return 'Rejected';
                    return String(rawStatus || '—').replaceAll('_', ' ');
                }
                function setButtonState(button, disabled) {
                    if (!button) return;
                    button.disabled = !!disabled;
                    button.classList.toggle('opacity-50', !!disabled);
                    button.classList.toggle('cursor-not-allowed', !!disabled);
                }
                function setText(id, value) {
                    const el = m(id);
                    if (el) el.textContent = value;
                }
                function setLoanTypePanel(typeKey) {
                    loanTypePanels.forEach((panel) => {
                        panel.classList.toggle('hidden', panel.dataset.loanPanel !== typeKey);
                    });
                }
                function setLoanTypeFields(typeKey) {
                    const config = loanTypeConfigs[typeKey] || loanTypeConfigs.regular;
                    const allFields = ['old_balance', 'lpp', 'interest', 'handling_fee', 'petty_cash_loan'];

                    allFields.forEach((field) => {
                        const row = m(`row_${field}`);
                        if (!row) return;
                        row.classList.toggle('hidden', !config.fields.includes(field));
                    });

                    setText('label_old_balance', config.labels.old_balance);
                    setText('label_lpp', config.labels.lpp);
                    setText('label_interest', config.labels.interest);
                    setText('label_handling_fee', config.labels.handling_fee);
                    setText('label_petty_cash_loan', config.labels.petty_cash_loan);
                    setText('terms_label', config.labels.terms);
                    setText('monthly_label', config.labels.monthly);
                }
                function computeInterest(amount, typeKey) {
                    const rate = loanTypeInterestRates[typeKey] ?? loanTypeInterestRates.regular;
                    return num(amount) * rate;
                }
                function computeMonthlyInstallment(amount, typeKey, terms) {
                    const principal = num(amount);
                    const n = Math.max(1, Math.floor(num(terms) || 1));
                    const interestAmount = computeInterest(principal, typeKey);
                    return (principal + interestAmount) / n;
                }
                function computeLpp(amount) {
                    return num(amount) * lppRate;
                }
                function setComputedLpp() {
                    const lppInput = m('lpp');
                    if (!lppInput) return;
                    lppInput.value = computeLpp(m('approved_amount')?.value).toFixed(2);
                }
                function setComputedInterest() {
                    const interestInput = m('interest');
                    if (!interestInput) return;
                    interestInput.value = computeInterest(m('approved_amount')?.value, currentLoanType).toFixed(2);
                }
                function visibleValue(fieldId) {
                    const row = m(`row_${fieldId}`);
                    if (row && row.classList.contains('hidden')) return 0;
                    return num(m(fieldId)?.value);
                }
                function renderApplianceItems(items, fallbackItem) {
                    const wrap = m('d_app_items_wrap');
                    const tbody = m('d_app_items');
                    const empty = m('d_app_items_empty');
                    if (!wrap || !tbody || !empty) return;

                    const rows = Array.isArray(items) ? items : [];
                    if (!rows.length) {
                        tbody.innerHTML = '';
                        wrap.classList.add('hidden');
                        empty.classList.remove('hidden');
                        empty.textContent = fallbackItem || '—';
                        return;
                    }

                    tbody.innerHTML = rows.map((row) => {
                        const itemName = String(row.item_name || '');
                        const qty = Math.max(0, parseInt(row.quantity || '0', 10) || 0);
                        const unitPrice = num(row.unit_price);
                        const amount = num(row.amount);

                        return `
                                <tr>
                                    <td class="px-3 py-2 text-slate-700">${escapeHtml(itemName || '—')}</td>
                                    <td class="px-3 py-2 text-right text-slate-700">${qty}</td>
                                    <td class="px-3 py-2 text-right text-slate-700">₱${fmt(unitPrice)}</td>
                                    <td class="px-3 py-2 text-right font-semibold text-slate-800">₱${fmt(amount)}</td>
                                </tr>
                            `;
                    }).join('');

                    empty.classList.add('hidden');
                    wrap.classList.remove('hidden');
                }
                function fillLoanTypeDetails(payload) {
                    setText('d_edu_beneficiary', payload.eduBeneficiary || payload.fullName || '—');
                    setText('d_edu_school', payload.eduSchool || '—');
                    setText('d_edu_program', payload.eduProgram || '—');
                    setText('d_edu_term', payload.eduTerm || '—');

                    renderApplianceItems(payload.appItems || [], payload.appItem || '');
                    setText('d_app_brand', payload.appBrand || '—');
                    setText('d_app_store', payload.appStore || '—');
                    setText('d_app_cash_price', payload.appTotalAmount !== null && payload.appTotalAmount !== undefined
                        ? ('₱' + fmt(num(payload.appTotalAmount)))
                        : (payload.appCashPrice !== null && payload.appCashPrice !== undefined
                            ? ('₱' + fmt(num(payload.appCashPrice)))
                            : '—'));
                    setText('d_app_downpayment', payload.appDownpayment !== null && payload.appDownpayment !== undefined
                        ? ('₱' + fmt(num(payload.appDownpayment)))
                        : '—');
                    setText('d_app_warranty', payload.appWarrantyMonths !== null && payload.appWarrantyMonths !== undefined && payload.appWarrantyMonths !== ''
                        ? String(payload.appWarrantyMonths)
                        : '—');

                    setText('d_gro_store', payload.groStore || '—');
                    setText('d_gro_coverage', payload.groCoverage || '—');
                    setText('d_gro_household', payload.groHousehold || '—');
                }
                function syncStatusFormValues() {
                    const fieldPairs = [
                        ['approved_amount', 'status_approved_amount'],
                        ['old_balance', 'status_old_balance'],
                        ['lpp', 'status_lpp'],
                        ['interest', 'status_interest'],
                        ['handling_fee', 'status_handling_fee'],
                        ['petty_cash_loan', 'status_petty_cash_loan'],
                        ['terms', 'status_terms'],
                        ['monthly_payment', 'status_monthly_payment'],
                        ['regular_run_term', 'status_run_term'],
                        ['regular_first_installment_date', 'status_first_installment_date'],
                        ['regular_installment_increased_to', 'status_installment_increased_to'],
                        ['regular_simple_annual_rate', 'status_simple_annual_rate'],
                    ];

                    fieldPairs.forEach(([sourceId, targetId]) => {
                        const source = m(sourceId);
                        const target = m(targetId);
                        if (!target) return;
                        target.value = source ? source.value : '';
                    });

                    const statusTotalDeduction = m('status_total_deduction');
                    if (statusTotalDeduction) statusTotalDeduction.value = m('total_deduction_input')?.value ?? '0';

                    const statusTotalNet = m('status_total_net');
                    if (statusTotalNet) statusTotalNet.value = m('total_net_input')?.value ?? '0';
                }
                function applyLoanTypeLayout(payload) {
                    currentLoanType = payload.loanTypeKey || normalizeLoanType(payload.loanTypeRaw || payload.loanTypeLabel);
                    setLoanTypePanel(currentLoanType);
                    setLoanTypeFields(currentLoanType);
                    fillLoanTypeDetails(payload);
                    setComputedLpp();
                    setComputedInterest();
                }
                function updateActionAvailability(payload) {
                    const status = String(payload.status || '').toLowerCase();
                    const isFinalized = status === 'approved' || status === 'rejected';
                    const creditReviewed = !!payload.creditReviewed;
                    const helper = m('loan_action_helper');
                    const reviewedBtn = modal?.querySelector('[data-loan-status="reviewed"]');
                    const forProcessingBtn = modal?.querySelector('[data-loan-status="for_processing"]');
                    const forApprovalBtn = modal?.querySelector('[data-loan-status="for_approval"]');
                    const rejectBtn = modal?.querySelector('button[form="rejectForm"]');
                    const approveBtn = modal?.querySelector('button[form="approveForm"]');

                    modalActionButtons.forEach((btn) => btn.classList.toggle('hidden', isFinalized));

                    if (helper) {
                        helper.classList.add('hidden');
                    }

                    if (isCreditOfficer) {
                        const canReject = status === 'pending';
                        setButtonState(reviewedBtn, status !== 'pending');
                        if (rejectBtn) {
                            rejectBtn.classList.toggle('hidden', !canReject);
                        }
                        setButtonState(rejectBtn, !canReject);
                        return;
                    }

                    if (isRegularAdmin) {
                        const canMoveForward = creditReviewed && !isFinalized;
                        setButtonState(forProcessingBtn, !canMoveForward);
                        setButtonState(forApprovalBtn, !canMoveForward);

                        if (helper && !canMoveForward) {
                            helper.textContent = 'Credit Officer review is required before admin processing or endorsement for approval.';
                            helper.classList.remove('hidden');
                        }
                        return;
                    }

                    setButtonState(rejectBtn, status !== 'for_approval');
                    setButtonState(approveBtn, status !== 'for_approval');
                }

                function recalc() {
                    currentLoanAmount = num(m('approved_amount').value);
                    setComputedLpp();
                    setComputedInterest();
                    const oldBal = visibleValue('old_balance');
                    const lpp = visibleValue('lpp');
                    const interest = visibleValue('interest');
                    const handling = visibleValue('handling_fee');
                    const petty = visibleValue('petty_cash_loan');
                    const terms = Math.max(1, Math.floor(num(m('terms').value) || 1));

                    const totalDeduction = oldBal + lpp + interest + handling + petty;
                    const netCash = currentLoanAmount - totalDeduction;

                    m('total_deduction').textContent = fmt(totalDeduction);
                    m('gross_total').textContent = '₱' + fmt(currentLoanAmount);
                    m('net_cash').textContent = fmt(netCash);

                    // monthly installment = (approved amount + interest by rate) / terms
                    m('monthly_payment').value = computeMonthlyInstallment(currentLoanAmount, currentLoanType, terms).toFixed(2);

                    // hidden submit values
                    m('total_deduction_input').value = totalDeduction.toFixed(2);
                    m('total_net_input').value = netCash.toFixed(2);
                }

                function openModal(payload) {
                    const status = String(payload.status || '').toLowerCase();
                    const isApproved = status === 'approved';
                    modalActionButtons.forEach((btn) => btn.classList.toggle('hidden', isApproved));

                    // fill text
                    m('m_app_no').textContent = payload.applicationNo || 'â€”';
                    m('m_name').textContent = payload.fullName || 'â€”';
                    m('m_address').textContent = payload.address || 'â€”';
                    m('m_member_key').textContent = payload.memberKey || 'â€”';
                    m('m_loan_type').textContent = payload.loanTypeLabel || 'â€”';
                    applyLoanTypeLayout(payload);

                    const requestedLoanAmount = payload.loanAmount || 0;
                    currentLoanAmount = payload.approvedAmount ?? requestedLoanAmount;
                    m('m_amount').textContent = '₱' + fmt(requestedLoanAmount);
                    m('m_amount_row').textContent = fmt(requestedLoanAmount);
                    m('approved_amount').value = currentLoanAmount.toFixed(2);
                    m('m_date').textContent = payload.created || 'â€”';

                    // set form actions
                    approveForm.action = approveUrlTpl.replace('__ID__', payload.id);
                    if (rejectForm) {
                        rejectForm.action = rejectUrlTpl.replace('__ID__', payload.id);
                    }
                    statusForm.action = statusUrlTpl.replace('__ID__', payload.id);

                    // reset inputs (optional)
                    m('old_balance').value = payload.old_balance ?? 0;
                    m('lpp').value = computeLpp(currentLoanAmount).toFixed(2);
                    m('interest').value = computeInterest(currentLoanAmount, currentLoanType).toFixed(2);
                    m('handling_fee').value = payload.handling_fee ?? 0;
                    m('petty_cash_loan').value = payload.petty_cash_loan ?? 0;
                    m('terms').value = payload.terms ?? 24;
                    if (payload.monthly_payment !== null && payload.monthly_payment !== undefined) {
                        m('monthly_payment').value = num(payload.monthly_payment).toFixed(2);
                    }
                    m('regular_run_term').value = payload.run_term ?? payload.terms ?? '';
                    m('regular_first_installment_date').value = payload.first_installment_date ?? '';
                    if (payload.installment_increased_to !== null && payload.installment_increased_to !== undefined) {
                        m('regular_installment_increased_to').value = num(payload.installment_increased_to).toFixed(2);
                    } else if (payload.monthly_payment !== null && payload.monthly_payment !== undefined) {
                        m('regular_installment_increased_to').value = num(payload.monthly_payment).toFixed(2);
                    } else {
                        m('regular_installment_increased_to').value = '0.00';
                    }
                    m('regular_simple_annual_rate').value = payload.simple_annual_rate
                        || (currentLoanType === 'regular' ? '12%' : '');
                    const approveRemarksEl = m('approve_remarks');
                    if (approveRemarksEl) approveRemarksEl.value = payload.remarks ?? '';

                    const rejectRemarksEl = m('reject_remarks');
                    if (rejectRemarksEl) rejectRemarksEl.value = (payload.status === 'rejected') ? (payload.remarks ?? '') : '';


                    recalc();
                    updateActionAvailability(payload);

                    modal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                }

                function closeModal() {
                    modal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                // open handler
                document.querySelectorAll('.js-open-loan-modal').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = btn.dataset.id;

                        const res = await fetch(showUrlTpl.replace('__ID__', id), {
                            headers: { 'Accept': 'application/json' }
                        });

                        const data = await res.json();

                        // map controller JSON -> your openModal payload keys
                        openModal({
                            id: data.id,
                            applicationNo: data.application_no,
                            fullName: data.full_name,
                            address: data.address,
                            memberKey: data.member_key,
                            loanTypeKey: data.loan_type_key || '',
                            loanTypeRaw: data.loan_type || '',
                            loanTypeLabel: (data.loan_type || '').replaceAll('_', ' ').replace(/\b\w/g, c => c.toUpperCase()),
                            loanAmount: parseFloat(data.loan_amount || '0'),
                            approvedAmount: data.approved_amount !== null && data.approved_amount !== undefined
                                ? parseFloat(data.approved_amount || '0')
                                : null,
                            created: data.created_at,

                            // âœ… bring back saved admin values (if you already store these)
                            old_balance: parseFloat(data.old_balance || '0'),
                            lpp: parseFloat(data.lpp || '0'),
                            interest: parseFloat(data.interest || '0'),
                            handling_fee: parseFloat(data.handling_fee || '0'),
                            petty_cash_loan: parseFloat(data.petty_cash_loan || '0'),
                            terms: parseInt(data.terms || '24'),
                            monthly_payment: parseFloat(data.monthly_payment || '0'),
                            run_term: data.run_term ?? '',
                            first_installment_date: data.first_installment_date ?? '',
                            installment_increased_to: data.installment_increased_to ?? null,
                            simple_annual_rate: data.simple_annual_rate ?? '',

                            // type-specific details
                            eduBeneficiary: data.beneficiary_name || data.full_name || '',
                            eduSchool: data.school_name || '',
                            eduProgram: data.school_program || '',
                            eduTerm: [data.school_year, data.semester].filter(Boolean).join(' ').trim(),

                            appItem: data.appliance_item || '',
                            appItems: Array.isArray(data.appliance_items) ? data.appliance_items : [],
                            appBrand: data.appliance_brand_model || '',
                            appStore: data.appliance_store || '',
                            appCashPrice: data.appliance_cash_price ?? null,
                            appTotalAmount: data.appliance_total_amount ?? null,
                            appDownpayment: data.appliance_downpayment ?? null,
                            appWarrantyMonths: data.appliance_warranty_months ?? null,

                            groStore: data.grocery_partner_store || '',
                            groCoverage: [data.grocery_period_from, data.grocery_period_to].filter(Boolean).join(' to ').trim(),
                            groHousehold: data.household_size || '',

                            // âœ… this is the saved notes/remarks
                            remarks: data.remarks || '',
                            status: data.status || '',
                            creditReviewed: !!data.credit_reviewed
                        });
                    });
                });


                // close handlers
                closeBtn?.addEventListener('click', closeModal);
                document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && !modal.classList.contains('hidden')) closeModal(); });

                // recalc on input
                ['approved_amount', 'old_balance', 'lpp', 'interest', 'handling_fee', 'petty_cash_loan', 'terms'].forEach(id => {
                    m(id)?.addEventListener('input', recalc);
                });

                document.querySelectorAll('.js-set-loan-status').forEach(btn => {
                    btn.addEventListener('click', () => {
                        if (btn.disabled) return;

                        const status = btn.dataset.loanStatus || '';
                        if (!status || !statusForm?.action || statusForm.action.endsWith('#'))
                            return;

                        const statusInput = m('status_action_value');
                        const remarksInput = m('status_action_remarks');
                        const approveRemarks = m('approve_remarks')?.value ?? '';

                        statusInput.value = status;
                        remarksInput.value = approveRemarks;
                        recalc();
                        syncStatusFormValues();
                        statusForm.submit();
                    });
                });
            })();
        </script>
    @endpush

</x-admin-v2-layout>
