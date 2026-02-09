<x-member-layout :title="'ENREMCO My Contributions Ledger'">
    @php
        $u = Auth::user();

        // Safe numeric formatter
        $money = fn($v) => is_numeric($v) ? number_format((float) $v, 2) : number_format(0, 2);

        // Compute totals (fallback if controller didn't pass them)
        $totalShareAmount = $totalDisplayed ?? 0;
        $totalSavingsAmount = $totalSavingsDisplayed ?? 0;

        // Latest month contribution (best-effort: last year row, last non-zero month)
        $monthKeys = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];

        $latestContribution = function ($rows) use ($monthKeys) {
            if (!$rows || $rows->isEmpty())
                return null;
            $sorted = $rows->sortByDesc('year');

            foreach ($sorted as $row) {
                foreach (array_reverse($monthKeys) as $m) {
                    if (isset($row->$m) && is_numeric($row->$m) && (float) $row->$m > 0) {
                        return ['year' => $row->year, 'month' => strtoupper($m), 'amount' => (float) $row->$m];
                    }
                }
            }
            return null;
        };

        $latestShare = $latestContribution($shares ?? collect());
        $latestSavings = $latestContribution($savings ?? collect());

        $initials = collect(explode(' ', trim($u->name ?? 'User')))
            ->filter()
            ->map(fn($p) => mb_substr($p, 0, 1))
            ->take(2)
            ->implode('');

        // Basic "this month" delta: use current year row and current month value
        $now = now();
        $curYear = (int) $now->format('Y');
        $curMonthKey = strtolower($now->format('M')); // jan, feb, etc.

        $curShareRow = ($shares ?? collect())->firstWhere('year', $curYear);
        $curSavingsRow = ($savings ?? collect())->firstWhere('year', $curYear);

        $shareThisMonth = ($curShareRow && isset($curShareRow->$curMonthKey)) ? (float) $curShareRow->$curMonthKey : 0;
        $savingsThisMonth = ($curSavingsRow && isset($curSavingsRow->$curMonthKey)) ? (float) $curSavingsRow->$curMonthKey : 0;

        // Optional: your member id field
        $memberId = $u->employees_id ?? ($u->member_id ?? '—');
        $memberType = $u->member_type ?? 'Regular Member';
        $status = $u->status ?? 'Active';
    @endphp

    <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">analytics</span>
                My Contributions
            </h3>
        </div>

    <div class="space-y-8">

        <!-- Summary cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-2xl card-shadow border border-[#dce5e0] relative overflow-hidden group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-[#638875] uppercase tracking-wider">Total Share Capital</p>
                        <h3 class="text-3xl font-black text-background-dark mt-2">₱{{ $money($totalShareAmount) }}</h3>
                        <div class="mt-4 flex items-center gap-2 text-primary-dark">
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                            <span class="text-xs font-bold">+₱{{ $money($shareThisMonth) }} this month</span>
                        </div>
                    </div>
                    <div class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">account_balance</span>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-2xl card-shadow border border-[#dce5e0] relative overflow-hidden group">
                <div class="relative z-10 flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-[#638875] uppercase tracking-wider">Total Savings Deposit</p>
                        <h3 class="text-3xl font-black text-background-dark mt-2">₱{{ $money($totalSavingsAmount) }}
                        </h3>
                        <div class="mt-4 flex items-center gap-2 text-primary-dark">
                            <span class="material-symbols-outlined text-sm">trending_up</span>
                            <span class="text-xs font-bold">+₱{{ $money($savingsThisMonth) }} this month</span>
                        </div>
                    </div>
                    <div class="size-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-2xl">savings</span>
                    </div>
                </div>
            </div>

            <div class="bg-background-dark p-6 rounded-2xl card-shadow text-white lg:col-span-1 md:col-span-2">
                <p class="text-sm font-bold text-primary uppercase tracking-wider">Latest Contribution</p>

                @php
                    // pick whichever latest exists and bigger "recency": just show share if exists else savings
                    $latest = $latestShare ?? $latestSavings;
                @endphp

                <h3 class="text-3xl font-black mt-2">
                    @if($latest)
                        ₱{{ $money($latest['amount']) }}
                    @else
                        ₱{{ $money(0) }}
                    @endif
                </h3>

                <div class="mt-4 flex items-center gap-4">
                    <div class="flex-1">
                        <p class="text-[10px] text-white/50 uppercase">Reference</p>
                        <p class="text-sm font-bold">
                            @if($latest)
                                {{ $latest['month'] }} {{ $latest['year'] }}
                            @else
                                —
                            @endif
                        </p>
                    </div>
                    <a href="#history"
                        class="bg-primary text-background-dark px-4 py-2 rounded-lg text-xs font-bold hover:brightness-105 active:scale-95 transition-all">
                        View History
                    </a>
                </div>
            </div>
        </div>

        <!-- History / Tabs -->
        <div id="history" class="bg-white rounded-2xl card-shadow border border-[#dce5e0] overflow-hidden">
            <div class="p-6 border-b border-[#dce5e0] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h4 class="text-lg font-black text-background-dark uppercase">Contribution History</h4>
                    <p class="text-sm text-[#638875]">Yearly breakdown of shares and savings</p>
                </div>

                <!-- Tabs -->
                <div class="flex items-center gap-2">
                    <button type="button"
                        class="tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-primary text-background-dark"
                        data-tab="sharesTab">
                        Shares
                    </button>
                    <button type="button"
                        class="tab-btn px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider bg-[#f6f8f7] border border-[#dce5e0] text-background-dark"
                        data-tab="savingsTab">
                        Savings
                    </button>
                </div>
            </div>

            <!-- Shares Tab -->
            <div id="sharesTab" class="tab-panel">
                <div class="overflow-x-auto">
                    @if(($shares ?? collect())->isEmpty())
                        <div class="p-10 text-center text-sm text-[#638875] font-bold">No share contributions available.
                        </div>
                    @else
                        <table class="w-full text-left">
                            <thead class="bg-[#f6f8f7] text-[#638875] text-xs font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Year</th>
                                    @foreach(['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'] as $m)
                                        <th class="px-6 py-4">{{ $m }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#dce5e0]">
                                @foreach($shares as $row)
                                    <tr class="hover:bg-[#f6f8f7]/50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-black text-background-dark">{{ $row->year }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->jan) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->feb) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->mar) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->apr) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->may) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->jun) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->jul) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->aug) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->sep) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->oct) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->nov) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->dec) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="p-6 bg-[#f6f8f7]/30 border-t border-[#dce5e0]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-xl border border-[#dce5e0] p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#638875]">Total Months
                                        Contributed</p>
                                    <p class="text-2xl font-black text-background-dark mt-1">{{ $totalEntries ?? 0 }}</p>
                                </div>
                                <div class="bg-white rounded-xl border border-[#dce5e0] p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#638875]">Total Share
                                        Capital</p>
                                    <p class="text-2xl font-black text-background-dark mt-1">
                                        ₱{{ $money($totalShareAmount) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Savings Tab -->
            <div id="savingsTab" class="tab-panel hidden">
                <div class="overflow-x-auto">
                    @if(($savings ?? collect())->isEmpty())
                        <div class="p-10 text-center text-sm text-[#638875] font-bold">No savings contributions available.
                        </div>
                    @else
                        <table class="w-full text-left">
                            <thead class="bg-[#f6f8f7] text-[#638875] text-xs font-black uppercase tracking-widest">
                                <tr>
                                    <th class="px-6 py-4">Year</th>
                                    @foreach(['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'] as $m)
                                        <th class="px-6 py-4">{{ $m }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#dce5e0]">
                                @foreach($savings as $row)
                                    <tr class="hover:bg-[#f6f8f7]/50 transition-colors">
                                        <td class="px-6 py-4 text-sm font-black text-background-dark">{{ $row->year }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->jan) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->feb) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->mar) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->apr) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->may) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->jun) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->jul) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->aug) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->sep) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->oct) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->nov) }}</td>
                                        <td class="px-6 py-4 text-sm font-bold text-[#111814]">₱{{ $money($row->dec) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="p-6 bg-[#f6f8f7]/30 border-t border-[#dce5e0]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-white rounded-xl border border-[#dce5e0] p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#638875]">Total Months
                                        Contributed</p>
                                    <p class="text-2xl font-black text-background-dark mt-1">
                                        {{ $totalSavingsEntries ?? ($totalEntries ?? 0) }}</p>
                                </div>
                                <div class="bg-white rounded-xl border border-[#dce5e0] p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-[#638875]">Total Savings
                                        Deposit</p>
                                    <p class="text-2xl font-black text-background-dark mt-1">
                                        ₱{{ $money($totalSavingsAmount) }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Policy + Help cards (same as your new design bottom section) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-[#dce5e0] card-shadow">
                <h5 class="text-sm font-black text-background-dark uppercase mb-4">Contribution Policy</h5>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">info</span>
                        <p class="text-xs text-[#638875] leading-relaxed">
                            Monthly share capital contributions are mandatory for active membership. A minimum amount
                            may apply.
                        </p>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-primary text-xl">info</span>
                        <p class="text-xs text-[#638875] leading-relaxed">
                            Dividends and patronage refunds may depend on total share capital and participation.
                        </p>
                    </li>
                </ul>
            </div>

            <div class="bg-white p-6 rounded-2xl border border-[#dce5e0] card-shadow flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-black text-background-dark uppercase">Need help?</h5>
                    <p class="text-xs text-[#638875] mt-1">Questions about your contributions?</p>
                    <button
                        class="mt-4 text-primary text-xs font-black uppercase tracking-wider hover:underline flex items-center gap-1">
                        Contact Cooperative Office
                        <span class="material-symbols-outlined text-sm">arrow_forward</span>
                    </button>
                </div>
                <div class="size-20 bg-primary/5 rounded-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-4xl text-primary">support_agent</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="pt-6">
            <div class="border-t border-[#dce5e0] pt-8 flex flex-col md:flex-row items-center justify-between gap-4">
                <p class="text-xs text-[#a0b0a8]">© {{ now()->format('Y') }} ENREMCO Cooperative Member Portal. All
                    rights reserved.</p>
                <div class="flex gap-6">
                    <a class="text-xs font-bold text-[#638875] hover:text-primary transition-colors" href="#">Privacy
                        Policy</a>
                    <a class="text-xs font-bold text-[#638875] hover:text-primary transition-colors" href="#">Terms of
                        Service</a>
                    <a class="text-xs font-bold text-[#638875] hover:text-primary transition-colors" href="#">Help
                        Center</a>
                </div>
            </div>
        </footer>
    </div>

    <!-- Simple Tabs Script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btns = document.querySelectorAll('.tab-btn');
            const panels = document.querySelectorAll('.tab-panel');

            btns.forEach(btn => {
                btn.addEventListener('click', () => {
                    btns.forEach(b => b.classList.remove('bg-primary', 'text-background-dark'));
                    btns.forEach(b => b.classList.add('bg-[#f6f8f7]', 'border', 'border-[#dce5e0]', 'text-background-dark'));

                    btn.classList.add('bg-primary', 'text-background-dark');
                    btn.classList.remove('bg-[#f6f8f7]');

                    panels.forEach(p => p.classList.add('hidden'));
                    document.getElementById(btn.dataset.tab).classList.remove('hidden');
                });
            });
        });
    </script>
</x-member-layout>