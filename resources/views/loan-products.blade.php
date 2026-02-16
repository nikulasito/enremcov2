@extends('layouts.public')

@section('title', 'ENREMCO Loan Products Catalog')

@push('head')
    <style>
        /* Range styling (works without Tailwind @apply) */
        input[type='range'] {
            height: .5rem;
            width: 100%;
            cursor: pointer;
            -webkit-appearance: none;
            appearance: none;
            border-radius: .5rem;
            background: #dce5e0;
        }

        html.dark input[type='range'] {
            background: #2a3a32;
        }

        input[type='range']::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            height: 1.25rem;
            width: 1.25rem;
            border-radius: 9999px;
            background: #19e680;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
        }

        html.dark input[type='range']::-webkit-slider-thumb {
            border-color: #1a2e24;
        }

        input[type='range']::-moz-range-thumb {
            height: 1.25rem;
            width: 1.25rem;
            border-radius: 9999px;
            background: #19e680;
            border: 4px solid #ffffff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
        }

        html.dark input[type='range']::-moz-range-thumb {
            border-color: #1a2e24;
        }
    </style>
@endpush

@section('content')
    <main class="flex-1">
        <section class="bg-background-dark py-12 lg:py-16">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="max-w-2xl">
                    <span class="text-primary font-bold text-sm uppercase tracking-[0.2em] mb-4 block">Cooperative
                        Loans</span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-6">
                        Loan Products <span class="text-primary">Catalog</span>
                        <p class="mt-4 text-lg text-[#a0b0a8]">
                            Find the perfect financial solution tailored for DENR 10 employees.
                            Competitive
                            rates and flexible terms for all your needs.
                        </p>
                </div>
            </div>
        </section>

        <section class="relative py-12 bg-white dark:bg-[#0d1a14] border-b border-[#dce5e0] dark:border-[#2a3a32]">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="mb-10 text-center">
                    <span class="text-primary font-bold text-sm uppercase tracking-widest">Plan Your Loan</span>
                    <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white mt-2">
                        Interactive Loan Calculator
                    </h2>
                    <p class="text-[#638875] dark:text-[#a0b0a8] mt-3">
                        Select a loan product below to see specific rates or use this estimator.
                    </p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    <div
                        class="lg:col-span-7 bg-[#f6f8f7] dark:bg-[#1a2e24] p-8 lg:p-10 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32]">
                        <div class="flex flex-col gap-8">

                            <div class="flex flex-col gap-6">
                                <div class="flex items-center justify-between">
                                    <label class="text-lg font-bold text-[#111814] dark:text-white">Loan Type</label>

                                    <select
                                        class="bg-white dark:bg-[#2a3a32] border border-[#dce5e0] dark:border-[#354a3f] rounded-lg text-sm font-semibold p-2 focus:ring-primary focus:border-primary"
                                        id="loan-type" onchange="updateCalculator()">
                                        <option value="0.12">Regular (12.0%)</option>
                                        <option value="0.045">Educational (4.5%)</option>
                                        <option value="0.03">Appliance (3.0%)</option>
                                        <option value="0.025">Grocery (2.5%)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="flex flex-col gap-6">
                                <div class="flex items-center justify-between">
                                    <label class="text-lg font-bold text-[#111814] dark:text-white">Loan Amount</label>
                                    <span class="text-2xl font-black text-primary">₱ <span
                                            id="amount-display">100,000</span></span>
                                </div>

                                <input id="loan-amount" max="1000000" min="5000" oninput="updateCalculator()" step="5000"
                                    type="range" value="100000" />

                                <div
                                    class="flex justify-between text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                    <span>₱5,000</span>
                                    <span>₱1,000,000</span>
                                </div>
                            </div>

                            <div class="flex flex-col gap-6">
                                <div class="flex items-center justify-between">
                                    <label class="text-lg font-bold text-[#111814] dark:text-white">Repayment Term</label>
                                    <span class="text-2xl font-black text-primary"><span id="term-display">12</span>
                                        Months</span>
                                </div>

                                <input id="loan-term" max="36" min="6" oninput="updateCalculator()" step="6" type="range"
                                    value="12" />

                                <div
                                    class="flex justify-between text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                    <span>6 Months</span>
                                    <span>36 Months</span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary">info</span>
                                <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">
                                    Calculation is based on a <span class="font-bold" id="rate-display">12.0%</span> annual
                                    interest rate.
                                    Actual rates may vary based on loan type.
                                </p>
                            </div>

                        </div>
                    </div>

                    <div
                        class="lg:col-span-5 bg-background-dark rounded-2xl p-8 lg:p-10 flex flex-col justify-between relative overflow-hidden">
                        <div class="absolute top-0 right-0 size-40 bg-primary/10 rounded-full blur-3xl -mr-20 -mt-20"></div>

                        <div class="relative z-10">
                            <h3 class="text-white/60 text-sm font-bold uppercase tracking-widest mb-2">Estimated Monthly
                                Amortization</h3>
                            <div class="text-5xl lg:text-6xl font-black text-primary mb-10">
                                ₱<span id="monthly-amortization">8,750</span>
                            </div>

                            <div class="flex flex-col gap-6 mb-10">
                                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                    <span class="text-white/80 font-medium">Total Interest</span>
                                    <span class="text-white font-bold" id="total-interest">₱5,000.00</span>
                                </div>
                                <div class="flex items-center justify-between border-b border-white/10 pb-4">
                                    <span class="text-white/80 font-medium">Total Repayment</span>
                                    <span class="text-white font-bold" id="total-repayment">₱105,000.00</span>
                                </div>
                            </div>
                        </div>

                        <div class="relative z-10">
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                    class="w-full py-5 bg-primary text-[#112119] rounded-xl font-black text-lg hover:brightness-110 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 group">
                                    Apply Now
                                    <span
                                        class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </a>
                            @else
                                <button
                                    class="w-full py-5 bg-primary text-[#112119] rounded-xl font-black text-lg hover:brightness-110 shadow-lg shadow-primary/20 transition-all flex items-center justify-center gap-3 group">
                                    Apply Now
                                    <span
                                        class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-20">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    {{-- Multi-Purpose --}}
                    <div
                        class="bg-white dark:bg-[#1a2e24] rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-8 lg:p-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">Regular Loan</h3>
                                    <p class="text-primary font-bold text-sm uppercase tracking-widest mt-1">Versatile
                                        Financial Support</p>
                                </div>
                                <div class="size-14 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl">payments</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y border-[#dce5e0] dark:border-[#2a3a32]">
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Interest Rate</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">5.0% <span
                                            class="text-xs font-normal">p.a.</span></p>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Max Term</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">24 Months</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                                        Eligibility Criteria
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Regular employee of ERC for at least 1 year</li>
                                        <li>Paid-up share capital of at least ₱10,000</li>
                                        <li>Good credit standing with the cooperative</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">description</span>
                                        Required Documents
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Duly accomplished loan application form</li>
                                        <li>Latest two (2) months pay slips</li>
                                        <li>Photocopy of ERC Identification Card</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-10 pt-0 mt-auto">
                            <button
                                class="w-full py-4 bg-primary text-[#112119] rounded-xl font-bold hover:brightness-110 transition-all">
                                Apply for Regular Loan
                            </button>
                        </div>
                    </div>

                    {{-- Educational --}}
                    <div
                        class="bg-white dark:bg-[#1a2e24] rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-8 lg:p-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">Educational Loan</h3>
                                    <p class="text-primary font-bold text-sm uppercase tracking-widest mt-1">Investing in
                                        Future</p>
                                </div>
                                <div class="size-14 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl">school</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y border-[#dce5e0] dark:border-[#2a3a32]">
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Interest Rate</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">4.5% <span
                                            class="text-xs font-normal">p.a.</span></p>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Max Term</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">12 Months</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                                        Eligibility Criteria
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Regular employee of ERC</li>
                                        <li>Child or member themselves enrolled in school</li>
                                        <li>Maximum loan amount based on tuition fee</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">description</span>
                                        Required Documents
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Certificate of Enrollment or Registration</li>
                                        <li>Statement of Account from school</li>
                                        <li>Latest two (2) months pay slips</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-10 pt-0 mt-auto">
                            <button
                                class="w-full py-4 bg-primary text-[#112119] rounded-xl font-bold hover:brightness-110 transition-all">
                                Apply for Educational Loan
                            </button>
                        </div>
                    </div>

                    {{-- Emergency --}}
                    <div
                        class="bg-white dark:bg-[#1a2e24] rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-8 lg:p-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">Appliance Loan</h3>
                                    <p class="text-primary font-bold text-sm uppercase tracking-widest mt-1">Swift Support
                                        When Needed</p>
                                </div>
                                <div class="size-14 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl">google_home_devices</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y border-[#dce5e0] dark:border-[#2a3a32]">
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Interest Rate</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">3.0% <span
                                            class="text-xs font-normal">p.a.</span></p>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Max Term</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">10 Months</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                                        Eligibility Criteria
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Immediate medical or urgent needs</li>
                                        <li>Probationary or Regular ERC employee</li>
                                        <li>Available only once per 6-month period</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">description</span>
                                        Required Documents
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Proof of Emergency (Medical cert, etc.)</li>
                                        <li>Latest pay slip</li>
                                        <li>Signed letter of request</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-10 pt-0 mt-auto">
                            <button
                                class="w-full py-4 bg-primary text-[#112119] rounded-xl font-bold hover:brightness-110 transition-all">
                                Apply for Appliance Loan
                            </button>
                        </div>
                    </div>

                    {{-- Calamity --}}
                    <div
                        class="bg-white dark:bg-[#1a2e24] rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] overflow-hidden flex flex-col shadow-sm hover:shadow-md transition-shadow">
                        <div class="p-8 lg:p-10">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-black text-[#111814] dark:text-white">Grocery Loan</h3>
                                    <p class="text-primary font-bold text-sm uppercase tracking-widest mt-1">Disaster
                                        Recovery Aid</p>
                                </div>
                                <div class="size-14 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                    <span class="material-symbols-outlined text-3xl">grocery</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 mb-8 py-6 border-y border-[#dce5e0] dark:border-[#2a3a32]">
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Interest Rate</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">2.5% <span
                                            class="text-xs font-normal">p.a.</span></p>
                                </div>
                                <div>
                                    <p
                                        class="text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        Max Term</p>
                                    <p class="text-2xl font-black text-[#111814] dark:text-white">18 Months</p>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">check_circle</span>
                                        Eligibility Criteria
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Residence in area declared under state of calamity</li>
                                        <li>Member in good standing</li>
                                        <li>Maximum loan ₱50,000</li>
                                    </ul>
                                </div>
                                <div>
                                    <h4
                                        class="text-sm font-bold text-[#111814] dark:text-white mb-3 flex items-center gap-2">
                                        <span class="material-symbols-outlined text-primary text-lg">description</span>
                                        Required Documents
                                    </h4>
                                    <ul class="text-sm text-[#638875] dark:text-[#a0b0a8] space-y-2 list-disc list-inside">
                                        <li>Barangay Certificate of Residency</li>
                                        <li>Pictures of damaged property</li>
                                        <li>Latest two (2) months pay slips</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="p-8 lg:p-10 pt-0 mt-auto">
                            <button
                                class="w-full py-4 bg-primary text-[#112119] rounded-xl font-bold hover:brightness-110 transition-all">
                                Apply for Grocery Loan
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </main>
@endsection

@push('scripts')

@endpush