<x-admin-v2-layout title="ENREMCO Executive Admin Dashboard" pageTitle="Executive Admin Dashboard"
    pageSubtitle="High-level financial oversight & governance" :showSearch="false">
    @php
        $peso = fn($v) => '₱' . number_format((float) $v, 2);
        $pct = fn($v) => number_format((float) $v, 1) . '%';
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-8 mb-10">
        <div
            class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="size-14 rounded-2xl bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[32px]">account_balance</span>
                </div>
            </div>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Total Share Capital</p>
            <p class="text-4xl font-black text-slate-900 mt-2">{{ $peso($totalShares ?? 0) }}</p>
            <p class="text-x=s font-bold text-slate-600 mt-2">
                Total Withdraw (Shares): {{ $peso($totalWithdrawShares ?? 0) }}
            </p>
            <p class="text-s font-medium text-slate-600 mt-2">
                Remaining Share Balance: <b class="text-primary">{{ $peso($totalRemainingShareBalance ?? 0) }}</b>
            </p>
            <!-- <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-400 font-medium">Aggregate cooperative share value</p>
            </div> -->
        </div>

        <div
            class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="size-14 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined text-[32px]">savings</span>
                </div>
            </div>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Total Member Savings</p>
            <p class="text-4xl font-black text-slate-900 mt-2">{{ $peso($totalSavings ?? 0) }}</p>
            <p class="text-s font-bold text-slate-600 mt-2">
                Total Withdraw (Savings): {{ $peso($totalWithdrawSavings ?? 0) }}
            </p>
            <p class="text-s font-medium text-slate-600 mt-2">
                Remaining Savings Balance: <b class="text-primary">{{ $peso($totalRemainingSavingsBalance ?? 0) }}</b>
            </p>
            <!-- <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-400 font-medium">Current system-wide reserve balance</p>
            </div> -->
        </div>

        <div
            class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="size-14 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-600">
                    <span class="material-symbols-outlined text-[32px]">payments</span>
                </div>
            </div>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Total Withdraw</p>
            <p class="text-4xl font-black text-slate-900 mt-2">{{ $peso($totalWithdraw ?? 0) }}</p>
            <!-- <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-400 font-medium">Aggregate member withdrawal amount</p>
            </div> -->
        </div>

        <div
            class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-6">
                <div class="size-14 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <span class="material-symbols-outlined text-[32px]">account_balance_wallet</span>
                </div>
            </div>
            <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Total Remaining Balance</p>
            <p class="text-4xl font-black text-slate-900 mt-2">{{ $peso($totalRemainingBalance ?? 0) }}</p>
            <!-- <div class="mt-4 pt-4 border-t border-slate-50">
                <p class="text-xs text-slate-400 font-medium">Remaining shares + remaining savings</p>
            </div> -->
        </div>
    </div>

    <!-- <div class="grid grid-cols-1 xl:grid-cols-3 gap-8 mb-10">
        <div class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-lg font-black text-slate-900">Financial Performance</h3>
                    <p class="text-xs text-slate-500 font-medium">Capital growth vs Withdrawal frequency (Annual)</p>
                </div>
                <select class="text-xs font-bold text-slate-600 border-slate-200 rounded-lg focus:ring-primary focus:border-primary">
                    <option>Fiscal Year {{ now()->format('Y') }}</option>
                    <option>Fiscal Year {{ now()->subYear()->format('Y') }}</option>
                </select>
            </div>

            <div class="h-64 flex items-end justify-between gap-4 px-4">
                @foreach([
                    ['label' => 'Jan-Mar', 'a' => '60%', 'b' => '30%'],
                    ['label' => 'Apr-Jun', 'a' => '75%', 'b' => '25%'],
                    ['label' => 'Jul-Sep', 'a' => '85%', 'b' => '40%'],
                    ['label' => 'Oct-Dec', 'a' => '65%', 'b' => '15%'],
                ] as $q)
                    <div class="flex flex-col items-center flex-1 gap-2">
                        <div class="w-full flex gap-1 justify-center items-end h-full">
                            <div class="w-1/3 bg-primary rounded-t-lg" style="height: {{ $q['a'] }};"></div>
                            <div class="w-1/3 bg-slate-200 rounded-t-lg" style="height: {{ $q['b'] }};"></div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $q['label'] }}</span>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 flex gap-6">
                <div class="flex items-center gap-2">
                    <span class="size-3 rounded-full bg-primary"></span>
                    <span class="text-xs font-bold text-slate-600">Capital Growth</span>
                </div>
                <div class="flex items-center gap-2">
                    <span class="size-3 rounded-full bg-slate-200"></span>
                    <span class="text-xs font-bold text-slate-600">Withdrawal Ratio</span>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="px-8 py-6 border-b border-slate-100">
                <h3 class="text-lg font-black text-slate-900">Executive Oversight</h3>
                <p class="text-xs text-slate-500 font-medium">Critical system operations</p>
            </div>
            <div class="flex-1 p-8 space-y-6">
                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                        <span class="text-slate-500">Loan Portfolio Health</span>
                        <span class="text-primary-dark">{{ $pct($portfolioHealth ?? 0) }}</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full">
                        <div class="h-full bg-primary rounded-full" style="width: {{ (float) ($portfolioHealth ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                        <span class="text-slate-500">Reserve Coverage Ratio</span>
                        <span class="text-blue-600">{{ $pct($reserveCoverage ?? 0) }}</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full">
                        <div class="h-full bg-blue-500 rounded-full" style="width: {{ (float) ($reserveCoverage ?? 0) }}%"></div>
                    </div>
                </div>

                <div class="space-y-2">
                    <div class="flex justify-between text-xs font-bold uppercase tracking-wider">
                        <span class="text-slate-500">Delinquency Rate</span>
                        <span class="text-red-500">{{ $pct($delinquencyRate ?? 0) }}</span>
                    </div>
                    <div class="h-2 bg-slate-100 rounded-full">
                        <div class="h-full bg-red-400 rounded-full" style="width: {{ min(100, (float) ($delinquencyRate ?? 0)) }}%"></div>
                    </div>
                </div>

                <a href="{{ route('admin.loan-requests.index') }}"
                    class="w-full inline-flex items-center justify-center py-3 bg-slate-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-slate-800 transition-colors mt-4">
                    Review Loan Approvals
                </a>
            </div>
        </div>
    </div> -->

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden mb-10">
        <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-black text-slate-900">Recent Activity</h3>
                <p class="text-xs text-slate-500 font-medium">Strategic financial transactions and approvals</p>
            </div>
            <a href="{{ route('admin.loan-requests.index') }}"
                class="flex items-center gap-2 px-4 py-2 text-sm font-bold text-primary hover:bg-primary/5 rounded-xl transition-all">
                Executive Logs
                <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">
                            Transaction</th>
                        <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Entity
                        </th>
                        <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Capital
                            Impact</th>
                        <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Timestamp
                        </th>
                        <th class="px-8 py-4 text-[10px] font-bold uppercase tracking-[0.15em] text-slate-500">Auth
                            Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentActivities as $activity)
                        @php
                            $status = strtolower((string) ($activity->status ?? 'pending'));
                            $statusText = match ($status) {
                                'approved' => 'Verified',
                                'for_approval' => 'For Approval.',
                                'reviewed' => 'Reviewed',
                                'for_review', 'for_processing' => 'Under Review',
                                'rejected' => 'Rejected',
                                default => 'Pending',
                            };
                            $statusClass = match ($status) {
                                'approved' => 'bg-emerald-50 text-emerald-600 border border-emerald-100',
                                'for_approval' => 'bg-amber-50 text-amber-600 border border-amber-100',
                                'reviewed' => 'bg-sky-50 text-sky-600 border border-sky-100',
                                'for_review', 'for_processing' => 'bg-blue-50 text-blue-600 border border-blue-100',
                                'rejected' => 'bg-red-50 text-red-600 border border-red-100',
                                default => 'bg-slate-100 text-slate-700 border border-slate-200',
                            };
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="size-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500">
                                        <span class="material-symbols-outlined text-[18px]">account_balance_wallet</span>
                                    </div>
                                    <span class="text-sm font-bold text-slate-900">Loan Application Update</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-slate-900">{{ $activity->full_name ?? 'Member' }}</span>
                                    <span class="text-[11px] text-slate-400">ID: {{ $activity->member_key ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-sm font-extrabold text-slate-900">
                                {{ $peso($activity->loan_amount ?? 0) }}
                            </td>
                            <td class="px-8 py-5 text-xs font-medium text-slate-500">
                                {{ optional($activity->created_at)->diffForHumans() ?? '—' }}
                            </td>
                            <td class="px-8 py-5">
                                <span
                                    class="px-2.5 py-0.5 text-[11px] font-bold uppercase tracking-wider rounded-full {{ $statusClass }}">
                                    {{ $statusText }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-8 text-sm text-slate-500">No recent executive activity found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-v2-layout>
