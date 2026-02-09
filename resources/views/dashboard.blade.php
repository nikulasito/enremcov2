<x-member-layout title="ENREMCO Member Dashboard Overview">
    @php
        $sharesAmount = isset($totalShares) && $totalShares !== null ? $totalShares : 0;
        $sharesMonths = isset($totalEntries) && $totalEntries !== null ? $totalEntries : null;

        $savingsAmount = isset($totalSavingsDisplayed) && $totalSavingsDisplayed !== null ? $totalSavingsDisplayed : 0;
        $savingsMonths = isset($totalSavingsEntries) && $totalSavingsEntries !== null ? $totalSavingsEntries : null;
      @endphp

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
            </div>
        </div>
    </section>

    {{-- Loan Services UI (buttons still placeholders) --}}
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
                    ['title' => 'Regular Loan', 'icon' => 'account_balance', 'desc' => 'Multi-purpose loan for your diverse financial needs.', 'style' => 'primary'],
                    ['title' => 'Educational Loan', 'icon' => 'school', 'desc' => 'Support for tuition and school expenses.', 'style' => 'secondary'],
                    ['title' => 'Appliance Loan', 'icon' => 'kitchen', 'desc' => 'Upgrade your home with flexible installment plans.', 'style' => 'primary'],
                    ['title' => 'Grocery Loan', 'icon' => 'shopping_cart', 'desc' => 'Immediate assistance for your daily essentials.', 'style' => 'secondary'],
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

                    <button class="mt-6 w-full py-3 px-4 rounded-xl
                    {{ $loan['style'] === 'secondary' ? 'bg-secondary text-white shadow-md shadow-secondary/10' : 'bg-primary text-background-dark shadow-md shadow-primary/10' }}
                    font-black text-sm transition-all hover:brightness-105 active:scale-95" disabled>
                        Apply Now
                    </button>
                </div>
            @endforeach
        </div>
    </section>
</x-member-layout>