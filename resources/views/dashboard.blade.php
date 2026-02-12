<x-member-layout title="ENREMCO Member Dashboard Overview">
    @php
        $sharesAmount = $totalShares ?? 0;
        $sharesMonths = $totalEntries ?? null;

        $loanStatus = $pendingLoan?->status;
        $loanStatusLabel = match ($loanStatus) {
            'pending' => 'Pending',
            'for_review' => 'In Review',
            'for_approval' => 'For Approval',
            'for_printing' => 'For Printing',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
            default => $loanStatus ? ucwords(str_replace('_', ' ', $loanStatus)) : null,
        };
        $loanStatusClass = match ($loanStatus) {
            'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
            'for_review', 'for_approval' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'for_printing' => 'bg-purple-100 text-purple-700 border border-purple-200',
            'approved' => 'bg-green-100 text-green-700 border border-green-200',
            'rejected' => 'bg-red-100 text-red-700 border border-red-200',
            default => 'bg-slate-100 text-slate-700 border border-slate-200',
        };

        $savingsAmount = $totalSavingsDisplayed ?? 0;
        $savingsMonths = $totalSavingsEntries ?? null;

        $latestShareDate = $latestShareDate ?? null;
        $latestSavingsDate = $latestSavingsDate ?? null;

        $shareLatest = $latestShareDate
            ? \Illuminate\Support\Carbon::parse($latestShareDate)->format('M d, Y')
            : 'N/A';

        $savingLatest = $latestSavingsDate
            ? \Illuminate\Support\Carbon::parse($latestSavingsDate)->format('M d, Y')
            : 'N/A';


        $today = now()->format('F d, Y');

        $loanTypeLabel = $pendingLoan?->loan_type ?? $pendingLoan?->type ?? null;
        $loanTypeLabel = $loanTypeLabel ? ucwords(str_replace('_', ' ', $loanTypeLabel)) : null;

        $loanRef = $pendingLoan?->application_no ?? $pendingLoan?->loan_id ?? $pendingLoan?->id ?? null;
        $loanAmt = (float) ($pendingLoan?->loan_amount ?? $pendingLoan?->amount ?? 0);
        $loanDate = $pendingLoan?->created_at ?? $pendingLoan?->date_created ?? null;
        $loanDateFmt = $loanDate ? \Illuminate\Support\Carbon::parse($loanDate)->format('F d, Y') : null;
    @endphp

    {{-- Custom header to match your new design --}}
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-900">Welcome, {{ auth()->user()->name }}</h1>
                <div class="mt-2 flex items-center gap-2 text-slate-500">
                    <span class="text-sm font-medium uppercase tracking-wider">Member ID:</span>
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-900 font-bold text-sm">
                        {{ auth()->user()->employee_ID ?? auth()->user()->employees_id ?? auth()->user()->employee_id ?? 'N/A' }}
                    </span>
                </div>
            </div>

            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-bold text-slate-400 uppercase">Current Date</p>
                    <p class="text-sm font-bold text-slate-700">{{ $today }}</p>
                </div>
                <!-- <button
                    class="flex size-10 items-center justify-center rounded-full bg-slate-100 text-slate-600 hover:bg-slate-200 transition-colors"
                    type="button">
                    <span class="material-symbols-outlined">notifications</span>
                </button> -->
            </div>
        </div>
    </x-slot>

    {{-- Summary of Contributions --}}
    <section>
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">analytics</span>
                Summary of Contributions
            </h3>
            <a href="{{ route('member.contributions') }}" class="text-sm font-bold text-secondary hover:underline">
                View History
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-8 rounded-2xl border border-slate-200 card-shadow">
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Share Capital</h4>
                        <p class="mt-2 text-4xl font-black text-slate-900">₱{{ number_format($sharesAmount, 2) }}</p>
                    </div>
                    <div class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">pie_chart</span>
                    </div>
                </div>

                <div class="flex items-center justify-between py-4 border-t border-slate-50">
                    <span class="text-slate-500 font-medium">Total Months Contributed</span>
                    <span
                        class="text-slate-900 font-bold">{{ $sharesMonths !== null ? $sharesMonths . ' Months' : 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between py-4 border-t border-slate-50">
                    <span class="text-slate-500 font-medium">Latest Contribution</span>
                    <span class="text-slate-900 font-bold">{{ $shareLatest }}</span>
                </div>
            </div>

            <div class="bg-white p-8 rounded-2xl border border-slate-200 card-shadow">
                <div class="flex items-start justify-between mb-8">
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Savings Deposit</h4>
                        <p class="mt-2 text-4xl font-black text-slate-900">₱{{ number_format($savingsAmount, 2) }}</p>
                    </div>
                    <div class="size-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                        <span class="material-symbols-outlined text-3xl">savings</span>
                    </div>
                </div>

                <div class="flex items-center justify-between py-4 border-t border-slate-50">
                    <span class="text-slate-500 font-medium">Total Months Contributed</span>
                    <span
                        class="text-slate-900 font-bold">{{ $savingsMonths !== null ? $savingsMonths . ' Months' : 'N/A' }}</span>
                </div>
                <div class="flex items-center justify-between py-4 border-t border-slate-50">
                    <span class="text-slate-500 font-medium">Latest Contribution</span>
                    <span class="text-slate-900 font-bold">{{ $savingLatest }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Loan Application Status --}}
    <section>
        <div class="mb-6">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-500">pending_actions</span>
                Loan Application Status
            </h3>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
            @if($pendingLoan)
                <div class="p-6 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="flex items-center gap-5 w-full md:w-auto">
                        <div
                            class="size-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 shrink-0">
                            <span class="material-symbols-outlined text-2xl">description</span>
                        </div>
                        <div>
                            <div class="flex items-center gap-3 mb-1">
                                <h4 class="text-lg font-bold text-slate-900">{{ $loanTypeLabel ?? 'Loan' }}</h4>
                                @if($loanStatusLabel)
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $loanStatusClass }}">
                                        {{ $loanStatusLabel }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-500">
                                Application Reference:
                                <span class="font-semibold">{{ $loanRef ?? '—' }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-8 w-full md:w-auto md:flex-1 md:justify-end md:px-8">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Amount Requested
                            </p>
                            <p class="text-lg font-black text-slate-900">₱{{ number_format($loanAmt, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date Applied</p>
                            <p class="text-sm font-bold text-slate-700">{{ $loanDateFmt ?? '—' }}</p>
                        </div>
                        <div class="col-span-2 md:col-span-1 flex items-center md:justify-end">
                            @if($pendingLoan?->status === 'for_printing')
                                <button type="button" onclick="openLoanDetailsModal({{ $pendingLoan->id }})"
                                    class="inline-flex items-center gap-2 text-sm font-bold text-secondary hover:text-blue-700 transition-colors">
                                    View Details
                                    <span class="material-symbols-outlined text-lg">chevron_right</span>
                                </button>
                            @else
                                <span class="text-sm font-bold text-slate-400">View Details</span>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="p-6 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="size-14 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-2xl">check_circle</span>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold text-slate-900">No Pending Loan</h4>
                            <p class="text-sm text-slate-500">You currently have no loan applications under review.</p>
                        </div>
                    </div>

                    <a href="{{ route('member.loans.apply') }}"
                        class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-primary text-background-dark font-black text-sm hover:brightness-105 active:scale-95 transition-all">
                        Apply Now
                        <span class="material-symbols-outlined text-lg">arrow_forward</span>
                    </a>
                </div>
            @endif
        </div>
    </section>

    {{-- Available Loan Services --}}
    <section>
        <div class="mb-6">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-secondary">handshake</span>
                Available Loan Services
            </h3>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            @php
                $loans = [
                    ['title' => 'Regular Loan', 'icon' => 'account_balance', 'desc' => 'Multi-purpose loan for your diverse financial needs.', 'style' => 'primary', 'type' => 'regular'],
                    ['title' => 'Educational Loan', 'icon' => 'school', 'desc' => 'Support for tuition and school expenses.', 'style' => 'secondary', 'type' => 'educational'],
                    ['title' => 'Appliance Loan', 'icon' => 'kitchen', 'desc' => 'Upgrade your home with flexible installment plans.', 'style' => 'primary', 'type' => 'appliance'],
                    ['title' => 'Grocery Loan', 'icon' => 'shopping_cart', 'desc' => 'Immediate assistance for your daily essentials.', 'style' => 'secondary', 'type' => 'grocery'],
                ];
            @endphp

            @foreach($loans as $loan)
                <div
                    class="bg-white p-6 rounded-2xl border border-slate-200 card-shadow flex flex-col items-center text-center">
                    <div class="size-16 rounded-full bg-slate-50 text-slate-400 flex items-center justify-center mb-4">
                        <span class="material-symbols-outlined text-3xl">{{ $loan['icon'] }}</span>
                    </div>

                    <h4 class="text-lg font-bold text-slate-900">{{ $loan['title'] }}</h4>
                    <p class="mt-2 text-sm text-slate-500 leading-relaxed min-h-[40px]">{{ $loan['desc'] }}</p>

                    <a href="{{ route('member.loans.apply', ['type' => $loan['type']]) }}"
                        class="mt-6 w-full py-3 px-4 rounded-xl font-black text-sm transition-all hover:brightness-105 active:scale-95 shadow-md
                                                                                                {{ $loan['style'] === 'secondary' ? 'bg-secondary text-white shadow-secondary/10' : 'bg-primary text-background-dark shadow-primary/10' }}">
                        Apply Now
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Recent Transactions --}}
    <section class="pb-10">
        <div class="bg-white rounded-2xl border border-slate-200 card-shadow overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-800">Recent Transactions</h3>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Showing last 3 activities</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Transaction
                                Type</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">
                                Amount</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentTransactions as $tx)
                            <tr>
                                <td class="px-6 py-4 text-sm font-medium text-slate-600">{{ $tx['date']->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="size-2 rounded-full {{ $tx['dotClass'] }}"></span>
                                        <span class="text-sm font-bold text-slate-900">{{ $tx['type'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $tx['statusClass'] }}">
                                        {{ $tx['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-black text-slate-900 text-right">
                                    ₱{{ number_format((float) $tx['amount'], 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-6 text-sm text-slate-500" colspan="4">No recent transactions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    {{-- Loan Details Modal --}}

    <script>
        function openLoanDetailsModal(id) {
            const modal = document.getElementById('loanDetailsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // reset
            document.getElementById('ld_app_no').textContent = '—';
            document.getElementById('ld_loan_type').textContent = '—';
            document.getElementById('ld_amount').textContent = '—';
            document.getElementById('ld_date').textContent = '—';
            document.getElementById('ld_remarks').textContent = 'Loading…';
            document.getElementById('ld_pdf').src = '';

            fetch(`{{ url('member/loans') }}/${id}/details`, {
                headers: { 'Accept': 'application/json' }
            })
                .then(r => r.json())
                .then(data => {
                    document.getElementById('ld_app_no').textContent = data.application_no ?? '—';
                    document.getElementById('ld_loan_type').textContent = (data.loan_type ?? '—').toString().replaceAll('_', ' ');
                    document.getElementById('ld_amount').textContent = `₱${Number(data.loan_amount ?? 0).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                    document.getElementById('ld_date').textContent = data.created_at ?? '—';

                    // ✅ show admin approval notes
                    document.getElementById('ld_remarks').textContent = data.remarks && data.remarks.trim()
                        ? data.remarks
                        : 'No approval notes were provided.';

                    // ✅ PDF viewer
                    document.getElementById('ld_pdf').src = data.pdf_url;
                    document.getElementById('ld_open_new').href = data.pdf_url;
                })
                .catch(() => {
                    document.getElementById('ld_remarks').textContent = 'Failed to load details.';
                });
        }

        function closeLoanDetailsModal() {
            const modal = document.getElementById('loanDetailsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');

            // stop PDF streaming when closed
            document.getElementById('ld_pdf').src = '';
        }
    </script>

</x-member-layout>
<div id="loanDetailsModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4">
    {{-- blur overlay --}}
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeLoanDetailsModal()"></div>

    <div class="relative w-full max-w-5xl overflow-hidden rounded-2xl bg-white shadow-2xl max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="bg-sidebar-green p-6 flex items-start justify-between shrink-0 bg-[#fcfdfc]">
            <div>
                <h2 class="text-xl font-black">Loan Details</h2>
                <p class="text-[10px] text-primary font-bold uppercase tracking-[0.2em] mt-1">
                    Application No: <span id="ld_app_no">—</span>
                </p>
            </div>

            <button type="button" class="text-white/60 hover:text-white transition-colors"
                onclick="closeLoanDetailsModal()">
                <span class="material-symbols-outlined text-slate-400">close</span>
            </button>
        </div>

        {{-- Body --}}
        <div class="flex-1 overflow-y-auto p-6 md:p-8 space-y-6">
            {{-- Top info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Loan Type</p>
                    <p id="ld_loan_type" class="mt-1 text-lg font-black text-slate-900">—</p>

                    <div class="mt-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Amount</p>
                        <p id="ld_amount" class="mt-1 text-2xl font-black text-slate-900">—</p>
                    </div>

                    <div class="mt-4">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-500">Date Applied</p>
                        <p id="ld_date" class="mt-1 text-sm font-bold text-slate-700">—</p>
                    </div>
                </div>

                {{-- Admin note --}}
                <div class="rounded-2xl border border-primary/20 bg-primary/5 p-5">
                    <div class="flex items-center justify-between">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-600">Admin Approval
                            Notes</p>
                        <span id="ld_status"
                            class="px-3 py-1 rounded-full text-xs font-black bg-purple-100 text-purple-700 border border-purple-200">
                            For Printing
                        </span>
                    </div>

                    <p id="ld_remarks" class="mt-3 text-sm font-medium text-slate-700 leading-relaxed">
                        —
                    </p>
                </div>
            </div>

            {{-- PDF Viewer --}}
            <div class="rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                    <p class="text-xs font-black text-slate-600 uppercase tracking-widest">Document Preview</p>
                    <a id="ld_open_new" href="#" target="_blank"
                        class="text-xs font-black text-secondary hover:underline">
                        Open in new tab
                    </a>
                </div>

                <div class="h-[70vh] bg-white">
                    <iframe id="ld_pdf" src="" class="w-full h-full" style="border:0;" title="Loan PDF Viewer"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>