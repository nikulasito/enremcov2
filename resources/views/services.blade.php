@extends('layouts.public')

@section('title', 'ENREMCO Services & Benefits')

@section('content')
    <main class="flex-1">
        <section class="bg-background-dark py-12 lg:py-16">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="max-w-2xl">
                    <span class="text-primary font-bold text-sm uppercase tracking-[0.2em] mb-4 block">Cooperative
                        Benefits</span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                        Services Tailored for <span class="text-primary">Your Financial Success</span>
                    </h1>
                    <p class="text-[#a0b0a8] max-w-2xl mx-auto text-lg leading-relaxed">
                        As a member-owned cooperative, ENREMCO provides exclusive savings, investment opportunities, and
                        welfare
                        support designed specifically for ERC employees.
                    </p>
                </div>
            </div>
        </section>

        <section class="bg-white dark:bg-[#0d1a14] py-16 lg:py-24">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- High-Yield Savings --}}
                    <div
                        class="flex flex-col bg-white dark:bg-[#1a2e24] rounded-3xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="p-8 lg:p-12 flex-1">
                            <div class="flex items-center justify-between mb-8">
                                <div
                                    class="size-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl">account_balance_wallet</span>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-widest">Interest
                                        Rate</span>
                                    <p class="text-3xl font-black text-primary">Up to 4.5% <span
                                            class="text-sm font-medium">p.a.</span></p>
                                </div>
                            </div>

                            <h2 class="text-3xl font-black mb-6 dark:text-white">High-Yield Savings</h2>
                            <p class="text-[#638875] dark:text-[#a0b0a8] mb-8 leading-relaxed">
                                Our savings program is designed to outperform traditional commercial bank rates, allowing
                                your
                                hard-earned money to grow faster while remaining fully accessible.
                            </p>

                            <div class="space-y-4 mb-10">
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Competitive daily interest accrual
                                    </p>
                                </div>
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Zero monthly maintenance fees</p>
                                </div>
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Automatic payroll deduction options
                                        available</p>
                                </div>
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Insured and secure cooperative fund
                                        management</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 lg:px-12 lg:pb-12 pt-0">
                            <button
                                class="w-full py-5 bg-primary text-[#112119] rounded-xl font-black text-lg hover:brightness-110 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3">
                                Inquire Now <span class="material-symbols-outlined">arrow_forward</span>
                            </button>
                        </div>
                    </div>

                    {{-- Cooperative Shares --}}
                    <div
                        class="flex flex-col bg-white dark:bg-[#1a2e24] rounded-3xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="p-8 lg:p-12 flex-1">
                            <div class="flex items-center justify-between mb-8">
                                <div
                                    class="size-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-4xl">pie_chart</span>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-widest">Historical
                                        Dividends</span>
                                    <p class="text-3xl font-black text-primary">6.0% - 8.0%</p>
                                </div>
                            </div>

                            <h2 class="text-3xl font-black mb-6 dark:text-white">Cooperative Shares</h2>
                            <p class="text-[#638875] dark:text-[#a0b0a8] mb-8 leading-relaxed">
                                Own a piece of the cooperative. Share capital is the backbone of ENREMCO, offering members
                                long-term investment growth and democratic participation.
                            </p>

                            <div class="space-y-4 mb-10">
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Annual dividend payouts based on net
                                        surplus</p>
                                </div>
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Patronage refunds for active loan
                                        borrowers</p>
                                </div>
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">One-member, one-vote democratic
                                        rights
                                    </p>
                                </div>
                                <div class="flex items-start gap-3"><span
                                        class="material-symbols-outlined text-primary">check_circle</span>
                                    <p class="text-sm font-medium dark:text-[#f0f4f2]">Equity growth through regular capital
                                        builds</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-8 lg:px-12 lg:pb-12 pt-0">
                            <button
                                class="w-full py-5 bg-primary text-[#112119] rounded-xl font-black text-lg hover:brightness-110 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3">
                                Join Now <span class="material-symbols-outlined">group_add</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Welfare --}}
        <section class="bg-[#f0f4f2] dark:bg-[#0d1a14] py-20">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="text-center mb-16">
                    <span class="text-primary font-bold text-sm uppercase tracking-widest">Beyond Financials</span>
                    <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white mt-2 leading-tight">Member
                        Welfare &amp; Protection</h2>
                    <p class="text-[#638875] dark:text-[#a0b0a8] mt-4 max-w-2xl mx-auto">
                        We look after our members beyond their balance sheets. ENREMCO provides a safety net for life's
                        unexpected moments.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div
                        class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-4xl text-primary mb-6">handshake</span>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Mutual Aid Fund</h3>
                        <p class="text-[#638875] dark:text-[#a0b0a8] text-sm leading-relaxed mb-6">
                            Financial assistance provided to members or their immediate families in the event of death or
                            severe hardship.
                        </p>
                        <ul class="text-sm space-y-2 dark:text-[#f0f4f2]">
                            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-primary"></span>
                                Immediate cash disbursement</li>
                            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-primary"></span>
                                Simple claim process</li>
                        </ul>
                    </div>

                    <div
                        class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-4xl text-primary mb-6">medical_services</span>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Medical Assistance</h3>
                        <p class="text-[#638875] dark:text-[#a0b0a8] text-sm leading-relaxed mb-6">
                            Support for hospitalization and critical medical needs to ensure our members prioritize their
                            health without financial stress.
                        </p>
                        <ul class="text-sm space-y-2 dark:text-[#f0f4f2]">
                            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-primary"></span>
                                Hospital bill subsidies</li>
                            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-primary"></span>
                                Diagnostic test support</li>
                        </ul>
                    </div>

                    <div
                        class="bg-white dark:bg-background-dark p-8 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] hover:border-primary transition-colors">
                        <span class="material-symbols-outlined text-4xl text-primary mb-6">school</span>
                        <h3 class="text-xl font-bold mb-4 dark:text-white">Educational Rewards</h3>
                        <p class="text-[#638875] dark:text-[#a0b0a8] text-sm leading-relaxed mb-6">
                            Recognizing the academic excellence of our members' children through special grants and
                            incentive programs.
                        </p>
                        <ul class="text-sm space-y-2 dark:text-[#f0f4f2]">
                            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-primary"></span>
                                Annual honor student grants</li>
                            <li class="flex items-center gap-2"><span class="size-1.5 rounded-full bg-primary"></span>
                                School supplies assistance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        {{-- CTA --}}
        <section class="py-20 relative overflow-hidden bg-primary">
            <div
                class="mx-auto max-w-[1280px] px-6 lg:px-10 relative z-10 flex flex-col lg:flex-row items-center justify-between gap-12">
                <div class="max-w-2xl text-[#112119]">
                    <h2 class="text-3xl lg:text-4xl font-black leading-tight">Unlock these benefits today. Become an ENREMCO
                        member.</h2>
                    <p class="mt-4 text-lg font-medium opacity-90">Experience the true value of being part of a cooperative
                        that cares for your growth and well-being.</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                    <a href="{{ route('register') }}"
                        class="px-10 py-5 bg-[#112119] text-white rounded-xl font-black text-lg hover:shadow-2xl transition-all text-center">
                        Start Application
                    </a>
                    <!-- <button
                            class="px-10 py-5 bg-white text-[#112119] rounded-xl font-black text-lg hover:shadow-xl transition-all">
                            Download Guide
                        </button> -->
                </div>
            </div>
        </section>
    </main>
@endsection