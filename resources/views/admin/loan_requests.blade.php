<x-admin-v2-layout title="ENREMCO - Loan Requests" pageTitle="Loan Requests" :showSearch="false">
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
                    <input type="hidden" name="status" value="{{ $status ?? request('status', 'all') }}">
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

                <div class="flex flex-col gap-1.5">
                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider ml-1">Status</label>
                    <select name="status"
                        class="h-10 pl-4 pr-10 bg-white border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-primary/20 focus:border-primary cursor-pointer min-w-[160px]">
                        <option value="all" @selected(($status ?? request('status', 'all')) === 'all')>All Status</option>
                        <option value="pending" @selected(($status ?? request('status')) === 'pending')>Pending</option>
                        <option value="for_review" @selected(($status ?? request('status')) === 'for_review')>In Review
                        </option>
                        <option value="for_approval" @selected(($status ?? request('status')) === 'for_approval')>For
                            Approval</option>
                        <option value="approved" @selected(($status ?? request('status')) === 'approved')>Approved
                        </option>
                        <option value="rejected" @selected(($status ?? request('status')) === 'rejected')>Rejected
                        </option>
                    </select>
                </div>

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
                                'for_review' => 'In Review',
                                'for_approval' => 'For Approval',
                                'for_printing' => 'For Printing',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                                default => ucwords((string) $app->status),
                            };

                            $statusClass = match ($app->status) {
                                'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                'for_review', 'for_approval' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                'for_printing' => 'bg-purple-100 text-purple-700 border border-purple-200',
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


                                    {{-- Approve --}}
                                    <form method="POST" action="{{ route('admin.loan-requests.approve', $app->id) }}"
                                        onsubmit="return confirm('Approve this application?');">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="flex items-center justify-center p-2 rounded-lg text-emerald-600 hover:bg-emerald-50 transition-all"
                                            title="Approve">
                                            <span class="material-symbols-outlined text-[20px]">check_circle</span>
                                        </button>
                                    </form>

                                    {{-- Reject --}}
                                    <form method="POST" action="{{ route('admin.loan-requests.reject', $app->id) }}"
                                        onsubmit="return confirm('Reject this application?');">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="remarks" value="Rejected by admin.">
                                        <button type="submit"
                                            class="flex items-center justify-center p-2 rounded-lg text-red-600 hover:bg-red-50 transition-all"
                                            title="Reject">
                                            <span class="material-symbols-outlined text-[20px]">cancel</span>
                                        </button>
                                    </form>
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
                    <h2 class="text-xl font-black text-white">Review Regular Loan Application</h2>
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
                                            type="number" step="0.01" min="0" value="0">
                                    </td>
                                </tr>

                                <tr class="bg-slate-50/50">
                                    <td class="px-6 py-2 text-[10px] font-black text-slate-400 uppercase" colspan="2">
                                        Less: Deductions
                                    </td>
                                </tr>

                                <tr>
                                    <td class="px-6 py-3 pl-10 text-slate-600">Balance (Old Bal)</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="old_balance" name="old_balance" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-3 pl-10 text-slate-600">LPP</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="lpp" name="lpp" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-3 pl-10 text-slate-600">Interest (amount)</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="interest" name="interest" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-3 pl-10 text-slate-600">Handling Fee</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="handling_fee" name="handling_fee" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-3 pl-10 text-slate-600">Petty Cash Loan</td>
                                    <td class="px-6 py-3 text-right">
                                        <input id="petty_cash_loan" name="petty_cash_loan" form="approveForm"
                                            class="w-40 text-right rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                            type="number" step="0.01" value="0">
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
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Terms
                                (months)</label>
                            <input id="terms" name="terms" form="approveForm"
                                class="mt-2 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                type="number" min="1" value="24">
                        </div>

                        <div>
                            <label class="text-[10px] font-black text-slate-500 uppercase tracking-wider">Monthly
                                Installment</label>
                            <input id="monthly_payment" name="monthly_payment" form="approveForm"
                                class="mt-2 w-full rounded-lg border-slate-200 bg-white px-3 py-2 font-black"
                                type="number" step="0.01" value="0">
                        </div>

                        {{-- computed hidden fields --}}
                        <input type="hidden" id="total_deduction_input" name="total_deduction" form="approveForm"
                            value="0">
                        <input type="hidden" id="total_net_input" name="total_net" form="approveForm" value="0">
                    </div>
                </div>

                {{-- Forms --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                    <form id="approveForm" method="POST" action="#">
                        @csrf
                        @method('PATCH')
                        <div class="rounded-xl border border-slate-200 bg-white p-6">
                            <p class="text-xs font-black text-slate-500 uppercase tracking-widest mb-3">Approval Notes
                                (optional)</p>
                            {{-- Approve --}}
                            <textarea id="approve_remarks" name="remarks"
                                class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium"
                                rows="3" placeholder="Notes for approval (optional)..."></textarea>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer buttons --}}
            <div class="p-6 bg-slate-50 border-t border-slate-100 flex justify-end gap-3 shrink-0">
                <button type="submit" form="rejectForm"
                    class="px-8 py-3 rounded-xl border-2 border-red-500 text-red-500 text-sm font-black hover:bg-red-50 transition-all flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">cancel</span>
                    Reject Application
                </button>

                <button type="submit" form="approveForm"
                    class="px-8 py-3 rounded-xl bg-primary text-[#0d1a14] text-sm font-black hover:bg-[#15c26b] transition-all shadow-lg shadow-primary/20 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[20px]">check_circle</span>
                    Save
                </button>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            (function () {
                const modal = document.getElementById('loanReviewModal');
                const closeBtn = document.getElementById('closeLoanReviewModal');

                const approveForm = document.getElementById('approveForm');
                const rejectForm = document.getElementById('rejectForm');

                // Route templates (replace __ID__ dynamically)
                const approveUrlTpl = "{{ route('admin.loan-requests.approve', '__ID__') }}";
                const rejectUrlTpl = "{{ route('admin.loan-requests.reject', '__ID__') }}";
                const showUrlTpl = "{{ route('admin.loan-requests.show', '__ID__') }}";

                // modal fields
                const m = (id) => document.getElementById(id);

                let currentLoanAmount = 0;

                function fmt(n) {
                    const v = Number.isFinite(n) ? n : 0;
                    return v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                }
                function num(v) {
                    const n = parseFloat(v);
                    return Number.isFinite(n) ? n : 0;
                }

                function recalc() {
                    currentLoanAmount = num(m('approved_amount').value);
                    const oldBal = num(m('old_balance').value);
                    const lpp = num(m('lpp').value);
                    const interest = num(m('interest').value);
                    const handling = num(m('handling_fee').value);
                    const petty = num(m('petty_cash_loan').value);
                    const terms = Math.max(1, Math.floor(num(m('terms').value) || 1));

                    const totalDeduction = oldBal + lpp + interest + handling + petty;
                    const netCash = currentLoanAmount - totalDeduction;

                    m('total_deduction').textContent = fmt(totalDeduction);
                    m('gross_total').textContent = '₱' + fmt(currentLoanAmount);
                    m('net_cash').textContent = fmt(netCash);

                    // simple monthly
                    m('monthly_payment').value = (currentLoanAmount / terms).toFixed(2);

                    // hidden submit values
                    m('total_deduction_input').value = totalDeduction.toFixed(2);
                    m('total_net_input').value = netCash.toFixed(2);
                }

                function openModal(payload) {
                    // fill text
                    m('m_app_no').textContent = payload.applicationNo || 'â€”';
                    m('m_name').textContent = payload.fullName || 'â€”';
                    m('m_address').textContent = payload.address || 'â€”';
                    m('m_member_key').textContent = payload.memberKey || 'â€”';
                    m('m_loan_type').textContent = payload.loanTypeLabel || 'â€”';

                    const requestedLoanAmount = payload.loanAmount || 0;
                    currentLoanAmount = payload.approvedAmount ?? requestedLoanAmount;
                    m('m_amount').textContent = '₱' + fmt(requestedLoanAmount);
                    m('m_amount_row').textContent = fmt(requestedLoanAmount);
                    m('approved_amount').value = currentLoanAmount.toFixed(2);
                    m('m_date').textContent = payload.created || 'â€”';

                    // set form actions
                    approveForm.action = approveUrlTpl.replace('__ID__', payload.id);
                    rejectForm.action = rejectUrlTpl.replace('__ID__', payload.id);

                    // reset inputs (optional)
                    m('old_balance').value = payload.old_balance ?? 0;
                    m('lpp').value = payload.lpp ?? 0;
                    m('interest').value = payload.interest ?? 0;
                    m('handling_fee').value = payload.handling_fee ?? 0;
                    m('petty_cash_loan').value = payload.petty_cash_loan ?? 0;
                    m('terms').value = payload.terms ?? 24;
                    const approveRemarksEl = m('approve_remarks');
                    if (approveRemarksEl) approveRemarksEl.value = payload.remarks ?? '';

                    const rejectRemarksEl = m('reject_remarks');
                    if (rejectRemarksEl) rejectRemarksEl.value = (payload.status === 'rejected') ? (payload.remarks ?? '') : '';


                    recalc();

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

                            // âœ… this is the saved notes/remarks
                            remarks: data.remarks || '',
                            status: data.status || ''
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
            })();
        </script>
    @endpush

</x-admin-v2-layout>
