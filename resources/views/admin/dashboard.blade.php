<x-admin-v2-layout
    title="ENREMCO Admin Dashboard | Main Overview"
    pageTitle="Dashboard"
    pageSubtitle="System Overview"
>
    @php
        $peso = fn($v) => '₱' . number_format((float) $v, 2);

        $initialsOf = function (?string $name) {
            $name = trim((string) $name);
            if ($name === '')
                return '—';
            return collect(preg_split('/\s+/', $name))
                ->filter()
                ->map(fn($p) => mb_substr($p, 0, 1))
                ->take(2)
                ->implode('');
        };
    @endphp

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined text-[28px]">person_pin</span>
                </div>
                <div class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                    +{{ (int) ($newMembersLast30Days ?? 0) }} New
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending Memberships</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ (int) ($pendingMemberships ?? 0) }}</p>
        </div>

        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[28px]">account_balance</span>
                </div>
                <div class="flex items-center gap-1 text-[11px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    —
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Share Capital</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ $peso($totalShares ?? 0) }}</p>
        </div>

        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[28px]">savings</span>
                </div>
                <div class="flex items-center gap-1 text-[11px] font-bold text-slate-500 bg-slate-50 px-2 py-0.5 rounded-full">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    —
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Member Savings</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ $peso($totalSavings ?? 0) }}</p>
        </div>

        <div class="summary-card">
            <div class="flex items-center justify-between mb-5">
                <div class="size-12 rounded-2xl bg-amber-50 flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined text-[28px]">description</span>
                </div>
                <div class="text-[11px] font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">
                    Urgent
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Loan Items Pending</p>
            <p class="text-3xl font-black text-slate-900 mt-1">{{ (int) ($pendingLoans ?? 0) }}</p>
        </div>
    </div>

    {{-- Tables --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        {{-- Recent Membership Requests --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-md font-black text-slate-900">Recent Membership Requests</h3>
                    <p class="text-[11px] text-slate-500 font-medium">New applicants awaiting approval</p>
                </div>
                <a href="{{ route('admin.new-members') }}" class="p-2 hover:bg-slate-50 rounded-lg text-primary">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Applicant Name</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Office</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse(($recentMembershipRequests ?? collect()) as $m)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="size-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 text-xs font-bold">
                                            {{ $initialsOf($m->name ?? '') }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-900">{{ $m->name ?? '—' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 font-medium">{{ $m->office ?? '—' }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-400">
                                    {{ $m->created_at ? $m->created_at->format('M d, Y') : '—' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-6 text-sm text-slate-500" colspan="3">No pending membership requests found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Loan Entries --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-md font-black text-slate-900">Recent Loan Entries</h3>
                    <p class="text-[11px] text-slate-500 font-medium">Latest loan applications processed</p>
                </div>
                <a href="{{ route('admin.loans') }}" class="p-2 hover:bg-slate-50 rounded-lg text-primary">
                    <span class="material-symbols-outlined">open_in_new</span>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Borrower</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Loan Type</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Amount</th>
                            <th class="px-6 py-3 text-[10px] font-bold uppercase tracking-wider text-slate-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse(($recentLoanEntries ?? collect()) as $loan)
                            @php
                                $emp = $loan->employee_ID ?? null;
                                $borrower = ($emp && isset($borrowerNames[$emp])) ? $borrowerNames[$emp] : ($emp ?? '—');
                                $status = empty($loan->date_approved) ? 'Pending' : 'Approved';
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4 text-sm font-bold text-slate-900">{{ $borrower }}</td>
                                <td class="px-6 py-4 text-xs font-medium text-slate-600">{{ $loan->loan_type ?? '—' }}</td>
                                <td class="px-6 py-4 text-sm font-extrabold text-slate-900">
                                    {{ isset($loan->loan_amount) ? $peso($loan->loan_amount) : '—' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($status === 'Approved')
                                        <span class="status-pill bg-emerald-50 text-emerald-600 border border-emerald-100">Approved</span>
                                    @else
                                        <span class="status-pill bg-amber-50 text-amber-600 border border-amber-100">Pending</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td class="px-6 py-6 text-sm text-slate-500" colspan="4">No loan entries found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-v2-layout>
