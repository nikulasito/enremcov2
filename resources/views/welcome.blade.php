{{-- resources/views/welcome.blade.php --}}

<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>{{ config('app.name', 'ENREMCO') }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#19e680",
                        "background-light": "#f6f8f7",
                        "background-dark": "#112119",
                    },
                    fontFamily: {
                        "display": ["Public Sans", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        input[type='range'] {
            @apply h-2 w-full cursor-pointer appearance-none rounded-lg bg-[#dce5e0] accent-primary dark:bg-[#2a3a32];
        }
        input[type='range']::-webkit-slider-thumb {
            @apply h-5 w-5 appearance-none rounded-full bg-primary border-4 border-white dark:border-[#1a2e24] shadow-md;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#111814] dark:text-[#f0f4f2] antialiased">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">
        <header
            class="sticky top-0 z-50 w-full border-b border-solid border-[#dce5e0] dark:border-[#2a3a32] bg-white/90 dark:bg-background-dark/90 backdrop-blur-md">
            <div class="mx-auto flex max-w-[1280px] items-center justify-between px-6 py-4 lg:px-10">
                <a href="{{ url('/') }}" class="flex items-center gap-3 text-primary">
                    <h2
                        class="text-[#111814] dark:text-white text-xl font-black leading-tight tracking-tight uppercase">
                        ENREMCO
                    </h2>
                </a>

                <nav class="hidden flex-1 justify-center gap-8 md:flex">
                    <a class="text-sm font-semibold text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors"
                        href="#">Home</a>
                    <a class="text-sm font-semibold text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors"
                        href="#">About Us</a>
                    <a class="text-sm font-semibold text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors"
                        href="#">Services</a>
                    <a class="text-sm font-semibold text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors"
                        href="#">Loan Products</a>
                    <a class="text-sm font-semibold text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors"
                        href="#">Contact</a>
                </nav>

                <div class="flex items-center gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="flex min-w-[120px] items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
                            Dashboard
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="flex min-w-[120px] items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
                                Member Login
                            </a>
                        @endif

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="hidden lg:flex min-w-[120px] items-center justify-center rounded-lg h-10 px-4 bg-[#f0f4f2] dark:bg-[#2a3a32] text-[#111814] dark:text-white text-sm font-bold tracking-tight hover:bg-[#e2e8e5] dark:hover:bg-[#354a3f] transition-all">
                                Apply
                            </a>
                        @else
                            <a href="#"
                                class="hidden lg:flex min-w-[120px] items-center justify-center rounded-lg h-10 px-4 bg-[#f0f4f2] dark:bg-[#2a3a32] text-[#111814] dark:text-white text-sm font-bold tracking-tight hover:bg-[#e2e8e5] dark:hover:bg-[#354a3f] transition-all">
                                Apply
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </header>

        <main class="flex-1">
            <section class="relative w-full overflow-hidden">
                <div class="mx-auto max-w-[1280px] p-4 lg:p-10">
                    <div class="relative flex min-h-[580px] flex-col justify-center rounded-xl bg-cover bg-center px-6 py-12 lg:px-20"
                        style='background-image: linear-gradient(rgba(17, 33, 25, 0.7) 0%, rgba(17, 33, 25, 0.9) 100%), url("https://lh3.googleusercontent.com/aida-public/AB6AXuCdL2Qs6VYWFhcev68fcbZifnBtE4QulKa6GS5tZ1dhQhWdGwnKp7E_uXxwf-iUK4qH7Taf2aGhheje46nq4aVJAEjuhURxoOn9chj_m03I5n4UeiDpk0e_mXDUYtcikAfE2zbxNPKhirn_v-arsOFp0EXXeGaWyMsFp0xrwNpQQ4TSphS8FfPyaCDYawgDeTQT0VBU2fsq80OMmU13skRxaEjKESthJ87xIj0Lk6RrxC79JtILjxuydUvONrH8k2Z27at-4Za3h0H_");'>
                        <div class="max-w-2xl">
                            <h1
                                class="text-4xl font-black leading-tight tracking-tight text-white sm:text-5xl lg:text-6xl">
                                Empowering Our Members Through Financial Excellence
                            </h1>
                            <p class="mt-6 text-base font-normal leading-relaxed text-[#dce5e0] lg:text-lg">
                                Experience competitive low-interest rates and exclusive member-owned benefits tailored
                                specifically for Department of Environment and Natural Resources 10 employees.
                            </p>

                            <div class="mt-10 flex flex-wrap gap-4">
                                @auth
                                    <a href="{{ route('dashboard') }}"
                                        class="flex min-w-[180px] cursor-pointer items-center justify-center rounded-lg h-14 px-6 bg-primary text-[#112119] text-base font-extrabold tracking-tight hover:scale-105 transition-transform">
                                        Go to Dashboard
                                    </a>
                                @else
                                    @if (Route::has('login'))
                                        <a href="{{ route('login') }}"
                                            class="flex min-w-[180px] cursor-pointer items-center justify-center rounded-lg h-14 px-6 bg-primary text-[#112119] text-base font-extrabold tracking-tight hover:scale-105 transition-transform">
                                            Member Login
                                        </a>
                                    @endif

                                    @if (Route::has('register'))
                                        <a href="{{ route('register') }}"
                                            class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-lg h-14 px-6 bg-white/10 backdrop-blur-md border border-white/20 text-white text-base font-extrabold tracking-tight hover:bg-white/20 transition-all">
                                            Apply for Membership
                                        </a>
                                    @else
                                        <a href="#"
                                            class="flex min-w-[200px] cursor-pointer items-center justify-center rounded-lg h-14 px-6 bg-white/10 backdrop-blur-md border border-white/20 text-white text-base font-extrabold tracking-tight hover:bg-white/20 transition-all">
                                            Apply for Membership
                                        </a>
                                    @endif
                                @endauth
                            </div>
                        </div>

                    </div>
                </div>
            </section>

            {{-- (The rest of your content below is unchanged except button links) --}}

            <section class="relative py-12 bg-white dark:bg-[#0d1a14]">
                <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                    <div class="mb-10 text-center">
                        <span class="text-primary font-bold text-sm uppercase tracking-widest">Plan Your Future</span>
                        <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white mt-2">Interactive Loan
                            Calculator</h2>
                        <p class="text-[#638875] dark:text-[#a0b0a8] mt-3">Calculate your estimated repayment with our
                            5.0% annual interest rate for Multi-Purpose Loans.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                        <div
                            class="lg:col-span-7 bg-[#f6f8f7] dark:bg-[#1a2e24] p-8 lg:p-10 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32]">
                            <div class="flex flex-col gap-10">
                                <div class="flex flex-col gap-6">
                                    <div class="flex items-center justify-between">
                                        <label class="text-lg font-bold text-[#111814] dark:text-white">Loan
                                            Amount</label>
                                        <span class="text-2xl font-black text-primary">₱ <span
                                                id="amount-display">100,000</span></span>
                                    </div>
                                    <input id="loan-amount" max="1000000" min="10000" oninput="updateCalculator()"
                                        step="5000" type="range" value="100000" />
                                    <div
                                        class="flex justify-between text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        <span>₱10,000</span>
                                        <span>₱1,000,000</span>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-6">
                                    <div class="flex items-center justify-between">
                                        <label class="text-lg font-bold text-[#111814] dark:text-white">Repayment
                                            Term</label>
                                        <span class="text-2xl font-black text-primary"><span id="term-display">12</span>
                                            Months</span>
                                    </div>
                                    <input id="loan-term" max="36" min="6" oninput="updateCalculator()" step="6"
                                        type="range" value="12" />
                                    <div
                                        class="flex justify-between text-xs font-bold text-[#638875] dark:text-[#a0b0a8] uppercase tracking-wider">
                                        <span>6 Months</span>
                                        <span>36 Months</span>
                                    </div>
                                </div>

                                <div
                                    class="pt-4 border-t border-[#dce5e0] dark:border-[#2a3a32] flex items-center gap-3">
                                    <span class="material-symbols-outlined text-primary">info</span>
                                    <p class="text-sm text-[#638875] dark:text-[#a0b0a8]">Calculation is based on a
                                        standard 5.0% annual interest rate. Actual rates may vary based on loan type.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="lg:col-span-5 bg-background-dark rounded-2xl p-8 lg:p-10 flex flex-col justify-between relative overflow-hidden">
                            <div
                                class="absolute top-0 right-0 size-40 bg-primary/10 rounded-full blur-3xl -mr-20 -mt-20">
                            </div>

                            <div class="relative z-10">
                                <h3 class="text-white/60 text-sm font-bold uppercase tracking-widest mb-2">Estimated
                                    Monthly Amortization</h3>
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
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}"
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

            {{-- SERVICES SECTION --}}
            <section class="mx-auto max-w-[1280px] px-6 py-16 lg:px-10">
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-10 gap-4">
                    <div class="max-w-xl">
                        <span class="text-primary font-bold text-sm uppercase tracking-widest">Our Expertise</span>
                        <h2 class="text-3xl lg:text-4xl font-black text-[#111814] dark:text-white mt-2 leading-tight">
                            Comprehensive Services for Your Growth
                        </h2>
                    </div>
                    <a class="text-primary font-bold flex items-center gap-2 hover:gap-3 transition-all" href="#">
                        View All Services <span class="material-symbols-outlined">arrow_forward</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div
                        class="group flex flex-col gap-6 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-background-dark p-8 hover:shadow-2xl transition-all duration-300">
                        <div
                            class="flex size-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-3xl">account_balance_wallet</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h3 class="text-xl font-bold leading-tight dark:text-white">High-Yield Savings</h3>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-base leading-relaxed">
                                Secure your future with deposit accounts that offer superior returns compared to
                                traditional banks.
                            </p>
                        </div>
                    </div>

                    <div
                        class="group flex flex-col gap-6 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-background-dark p-8 hover:shadow-2xl transition-all duration-300">
                        <div
                            class="flex size-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-3xl">pie_chart</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h3 class="text-xl font-bold leading-tight dark:text-white">Cooperative Shares</h3>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-base leading-relaxed">
                                Become a co-owner of ENREMCO. Earn annual dividends and exercise your right to vote.
                            </p>
                        </div>
                    </div>

                    <div
                        class="group flex flex-col gap-6 rounded-2xl border border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-background-dark p-8 hover:shadow-2xl transition-all duration-300">
                        <div
                            class="flex size-14 items-center justify-center rounded-xl bg-primary/10 text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                            <span class="material-symbols-outlined text-3xl">payments</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h3 class="text-xl font-bold leading-tight dark:text-white">Flexible Loans</h3>
                            <p class="text-[#638875] dark:text-[#a0b0a8] text-base leading-relaxed">
                                From multi-purpose to emergency funding, access capital with minimal paperwork and low
                                interest.
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- LOAN HIGHLIGHTS SECTION --}}
            <section class="bg-background-dark py-20 overflow-hidden relative">
                <div class="absolute top-0 right-0 size-96 bg-primary/10 rounded-full blur-[120px] -mr-48 -mt-48"></div>
                <div class="absolute bottom-0 left-0 size-96 bg-primary/5 rounded-full blur-[100px] -ml-48 -mb-48">
                </div>

                <div class="mx-auto max-w-[1280px] px-6 lg:px-10 relative z-10">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl lg:text-4xl font-black text-white leading-tight mb-4">Current Loan
                            Highlights</h2>
                        <p class="text-[#a0b0a8] max-w-2xl mx-auto">
                            Competitive rates designed to support the financial well-being of ERC employees.
                        </p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        @php
                            $applyHref = Route::has('register') ? route('register') : '#';
                        @endphp

                        <div
                            class="bg-[#1a2e24] border border-[#2a3a32] rounded-xl p-6 flex flex-col items-center text-center">
                            <span
                                class="text-xs font-bold text-primary uppercase tracking-[0.2em] mb-4">Multi-Purpose</span>
                            <div class="text-4xl font-black text-white mb-2">
                                5.0% <span class="text-sm font-normal text-[#a0b0a8]">p.a.</span>
                            </div>
                            <p class="text-sm text-[#a0b0a8] mb-6">Up to 24 months term</p>
                            <a href="{{ $applyHref }}"
                                class="w-full py-3 rounded-lg bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-[#112119] transition-all text-center">
                                Apply Now
                            </a>
                        </div>

                        <div
                            class="bg-[#1a2e24] border border-[#2a3a32] rounded-xl p-6 flex flex-col items-center text-center">
                            <span
                                class="text-xs font-bold text-primary uppercase tracking-[0.2em] mb-4">Educational</span>
                            <div class="text-4xl font-black text-white mb-2">
                                4.5% <span class="text-sm font-normal text-[#a0b0a8]">p.a.</span>
                            </div>
                            <p class="text-sm text-[#a0b0a8] mb-6">Per semester basis</p>
                            <a href="{{ $applyHref }}"
                                class="w-full py-3 rounded-lg bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-[#112119] transition-all text-center">
                                Apply Now
                            </a>
                        </div>

                        <div
                            class="bg-[#1a2e24] border border-[#2a3a32] rounded-xl p-6 flex flex-col items-center text-center">
                            <span
                                class="text-xs font-bold text-primary uppercase tracking-[0.2em] mb-4">Emergency</span>
                            <div class="text-4xl font-black text-white mb-2">
                                3.0% <span class="text-sm font-normal text-[#a0b0a8]">p.a.</span>
                            </div>
                            <p class="text-sm text-[#a0b0a8] mb-6">24-hour processing</p>
                            <a href="{{ $applyHref }}"
                                class="w-full py-3 rounded-lg bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-[#112119] transition-all text-center">
                                Apply Now
                            </a>
                        </div>

                        <div
                            class="bg-[#1a2e24] border border-[#2a3a32] rounded-xl p-6 flex flex-col items-center text-center">
                            <span class="text-xs font-bold text-primary uppercase tracking-[0.2em] mb-4">Calamity</span>
                            <div class="text-4xl font-black text-white mb-2">
                                2.5% <span class="text-sm font-normal text-[#a0b0a8]">p.a.</span>
                            </div>
                            <p class="text-sm text-[#a0b0a8] mb-6">Government declared</p>
                            <a href="{{ $applyHref }}"
                                class="w-full py-3 rounded-lg bg-primary/10 text-primary text-sm font-bold hover:bg-primary hover:text-[#112119] transition-all text-center">
                                Apply Now
                            </a>
                        </div>
                    </div>
                </div>
            </section>


        </main>

        <section class="py-16">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div
                    class="bg-primary rounded-2xl p-10 lg:p-16 flex flex-col lg:flex-row items-center justify-between gap-10">
                    <div class="max-w-xl text-[#112119]">
                        <h2 class="text-3xl lg:text-4xl font-black leading-tight">Ready to join the cooperative?</h2>
                        <p class="mt-4 text-lg font-medium opacity-90">
                            Start your journey toward financial freedom. Membership is open to all permanent employees
                            of the Department of Environment and Natural Resources 10.
                        </p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}"
                                class="px-10 py-5 bg-[#112119] text-white rounded-xl font-black text-lg hover:shadow-xl transition-all text-center">
                                Join ENREMCO
                            </a>
                        @else
                            <button
                                class="px-10 py-5 bg-[#112119] text-white rounded-xl font-black text-lg hover:shadow-xl transition-all">
                                Join ENREMCO
                            </button>
                        @endif

                        <a href="#"
                            class="px-10 py-5 bg-white text-[#112119] rounded-xl font-black text-lg border-2 border-transparent hover:border-[#112119] transition-all text-center">
                            Learn More
                        </a>
                    </div>
                </div>
            </div>
        </section>

        <footer class="bg-white dark:bg-[#0a1410] pt-16 pb-10 border-t border-[#dce5e0] dark:border-[#2a3a32]">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-3 text-primary">
                            <h2 class="text-2xl font-black tracking-tight uppercase">ENREMCO</h2>
                        </div>
                        <p class="text-[#638875] dark:text-[#a0b0a8] text-sm leading-relaxed">
                            Providing sustainable financial solutions and fostering cooperative growth for Energy
                            Regulatory Commission employees since 1995.
                        </p>

                        <div class="flex gap-4">
                            <a class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all"
                                href="#">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z">
                                    </path>
                                </svg>
                            </a>

                            <a class="size-10 rounded-full bg-primary/10 flex items-center justify-center text-primary hover:bg-primary hover:text-white transition-all"
                                href="#">
                                <svg class="size-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col gap-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-[#111814] dark:text-white">Quick
                            Links</h3>
                        <nav class="flex flex-col gap-3">
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Our Story</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Loan Products</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Member Dividends</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Annual Reports</a>
                        </nav>
                    </div>

                    <div class="flex flex-col gap-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-[#111814] dark:text-white">Legal
                        </h3>
                        <nav class="flex flex-col gap-3">
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Privacy Policy</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Terms of Service</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">CDA Compliance</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="#">Bylaws</a>
                        </nav>
                    </div>

                    <div class="flex flex-col gap-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-[#111814] dark:text-white">Contact
                            Us</h3>
                        <div class="flex flex-col gap-4">
                            <div class="flex items-start gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                                <p class="text-[#638875] dark:text-[#a0b0a8] text-sm">
                                    Energy Regulatory Commission Office,<br />Pasig City, Metro Manila
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">call</span>
                                <p class="text-[#638875] dark:text-[#a0b0a8] text-sm">+63 (02) 8689-5372</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">mail</span>
                                <p class="text-[#638875] dark:text-[#a0b0a8] text-sm">info@enremco.coop</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="pt-8 border-t border-[#dce5e0] dark:border-[#2a3a32] flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[#638875] dark:text-[#a0b0a8] text-xs">© 2024 ENREMCO Multi-Purpose Cooperative. All
                        rights reserved.</p>

                    <div class="flex items-center gap-2 grayscale opacity-60">
                        <span class="text-[10px] font-bold uppercase text-[#638875] dark:text-[#a0b0a8]">Member
                            of</span>
                        <div
                            class="px-2 py-1 border border-[#dce5e0] dark:border-[#2a3a32] rounded font-black text-[10px] dark:text-[#f0f4f2]">
                            CDA PHILIPPINES
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>

    <script>
        function updateCalculator() {
            const amount = parseInt(document.getElementById('loan-amount').value);
            const term = parseInt(document.getElementById('loan-term').value);
            const annualRate = 0.05; // 5% fixed

            const totalInterest = amount * annualRate * (term / 12);
            const totalRepayment = amount + totalInterest;
            const monthlyAmortization = totalRepayment / term;

            document.getElementById('amount-display').innerText = amount.toLocaleString();
            document.getElementById('term-display').innerText = term;
            document.getElementById('monthly-amortization').innerText = Math.round(monthlyAmortization).toLocaleString();
            document.getElementById('total-interest').innerText = '₱' + totalInterest.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('total-repayment').innerText = '₱' + totalRepayment.toLocaleString(undefined, { minimumFractionDigits: 2 });
        }

        window.onload = updateCalculator;
    </script>
</body>

</html>