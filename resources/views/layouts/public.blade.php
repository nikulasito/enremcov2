<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'ENREMCO')</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
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
                    fontFamily: { "display": ["Public Sans", "sans-serif"] },
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
        @layer utilities {
            .glass-card {
                @apply bg-white/80 dark:bg-[#1a2e24]/80 backdrop-blur-md border border-[#dce5e0] dark:border-[#2a3a32];
            }
        }
        @layer base {
            input[type="text"], input[type="date"], input[type="number"], input[type="email"], input[type="password"], select, textarea {
                @apply block w-full rounded-lg border-[#dce5e0] bg-white text-sm
                focus:border-primary focus:ring-primary
                dark:border-[#2a3a32] dark:bg-[#1a2e24] dark:text-white;
            }
            label {
                @apply mb-1.5 block text-xs font-bold uppercase tracking-wider
                text-[#638875] dark:text-[#a0b0a8];
            }
        }
    </style>

    @stack('head')
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#111814] dark:text-[#f0f4f2] antialiased">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">

        {{-- HEADER --}}
        <header
            class="sticky top-0 z-50 w-full border-b border-solid border-[#dce5e0] dark:border-[#2a3a32] bg-white/90 dark:bg-background-dark/90 backdrop-blur-md">
            <div class="mx-auto flex max-w-[1280px] items-center justify-between px-6 py-4 lg:px-10">
                <div class="flex items-center gap-3 text-primary">
                    <a class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('home') }}">
                        <h2
                            class="text-[#111814] dark:text-white text-xl font-black leading-tight tracking-tight uppercase">
                            ENREMCO</h2>
                    </a>
                </div>

                <nav class="hidden flex-1 justify-center gap-8 md:flex">
                    <a class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('home') }}">Home</a>

                    <a class="text-sm font-semibold {{ request()->routeIs('about') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('about') }}">About Us</a>

                    <a class="text-sm font-semibold {{ request()->routeIs('services') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('services') }}">Services</a>

                    <a class="text-sm font-semibold {{ request()->routeIs('loan-products') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('loan-products') }}">Loan Products</a>

                    <a class="text-sm font-semibold {{ request()->routeIs('contact') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('contact') }}">Contact</a>
                </nav>

                <div class="flex items-center gap-3">
                    <button id="mobileMenuBtn" type="button"
                        class="md:hidden inline-flex items-center justify-center size-10 rounded-lg border border-[#dce5e0] dark:border-[#2a3a32] text-[#111814] dark:text-white hover:bg-slate-50 dark:hover:bg-[#1a2e24] transition-all"
                        aria-expanded="false" aria-controls="mobileMenu" aria-label="Toggle navigation menu">
                        <span class="material-symbols-outlined">menu</span>
                    </button>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="hidden sm:flex min-w-[120px] items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
                            Dashboard
                        </a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}"
                                class="hidden sm:flex min-w-[120px] items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
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

            <div id="mobileMenu"
                class="md:hidden hidden border-t border-[#dce5e0] dark:border-[#2a3a32] bg-white dark:bg-background-dark px-6 py-4">
                <nav class="flex flex-col gap-3">
                    <a class="text-sm font-semibold {{ request()->routeIs('home') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('home') }}">Home</a>
                    <a class="text-sm font-semibold {{ request()->routeIs('about') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('about') }}">About Us</a>
                    <a class="text-sm font-semibold {{ request()->routeIs('services') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('services') }}">Services</a>
                    <a class="text-sm font-semibold {{ request()->routeIs('loan-products') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('loan-products') }}">Loan Products</a>
                    <a class="text-sm font-semibold {{ request()->routeIs('contact') ? 'text-primary' : 'text-[#111814] dark:text-[#f0f4f2] hover:text-primary transition-colors' }}"
                        href="{{ route('contact') }}">Contact</a>

                    <div class="pt-2 flex flex-col gap-2">
                        @auth
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
                                Dashboard
                            </a>
                        @else
                            @if (Route::has('login'))
                                <a href="{{ route('login') }}"
                                    class="flex items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
                                    Member Login
                                </a>
                            @endif

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="flex items-center justify-center rounded-lg h-10 px-4 bg-[#f0f4f2] dark:bg-[#2a3a32] text-[#111814] dark:text-white text-sm font-bold tracking-tight hover:bg-[#e2e8e5] dark:hover:bg-[#354a3f] transition-all">
                                    Apply
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            </div>
        </header>

        {{-- PAGE CONTENT --}}
        @yield('content')

        {{-- FOOTER (the one you requested earlier, plus regulated block from services design) --}}
        <footer class="bg-white dark:bg-[#0a1410] pt-16 pb-10 border-t border-[#dce5e0] dark:border-[#2a3a32]">
            <div class="mx-auto max-w-[1280px] px-6 lg:px-10">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                    <div class="flex flex-col gap-6">
                        <div class="flex items-center gap-3 text-primary">
                            <h2 class="text-2xl font-black tracking-tight uppercase">ENREMCO</h2>
                        </div>
                        <p class="text-[#638875] dark:text-[#a0b0a8] text-sm leading-relaxed">
                            Supporting DENR X employees with financial services and community programs designed for
                            growth and success.
                        </p>
                    </div>

                    <div class="flex flex-col gap-6">
                        <h3 class="text-sm font-black uppercase tracking-widest text-[#111814] dark:text-white">Quick
                            Links</h3>
                        <nav class="flex flex-col gap-3">
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="{{ route('about') }}">About</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="{{ route('services') }}">Services</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="{{ route('loan-products') }}">Loan Products</a>
                            <a class="text-[#638875] dark:text-[#a0b0a8] text-sm hover:text-primary transition-colors"
                                href="{{ route('contact') }}">Contact</a>
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
                                    Department of Environment and Natural Resources 10<br />
                                    Puntod, Cagayan de Oro City, 9000
                                </p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">call</span>
                                <p class="text-[#638875] dark:text-[#a0b0a8] text-sm">+63 (02) 8689-5372</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-xl">mail</span>
                                <p class="text-[#638875] dark:text-[#a0b0a8] text-sm">support@enremco.com</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div
                    class="pt-8 border-t border-[#dce5e0] dark:border-[#2a3a32] flex flex-col md:flex-row justify-between items-center gap-4">
                    <p class="text-[#638875] dark:text-[#a0b0a8] text-xs">© {{ date('Y') }} ENREMCO. All rights
                        reserved.</p>
                </div>
            </div>
        </footer>

    </div>
    <script>
            (function () {
                const btn = document.getElementById('mobileMenuBtn');
                const menu = document.getElementById('mobileMenu');
                if (!btn || !menu) return;

                btn.addEventListener('click', () => {
                    const isHidden = menu.classList.toggle('hidden');
                    btn.setAttribute('aria-expanded', (!isHidden).toString());
                });
            })();

        function updateCalculator() {
            const amount = parseInt(document.getElementById('loan-amount').value, 10);
            const term = parseInt(document.getElementById('loan-term').value, 10);
            const annualRate = parseFloat(document.getElementById('loan-type').value); // uses selected

            const totalInterest = amount * annualRate * (term / 12);
            const totalRepayment = amount + totalInterest;
            const monthlyAmortization = totalRepayment / term;

            document.getElementById('amount-display').innerText = amount.toLocaleString();
            document.getElementById('term-display').innerText = term;
            document.getElementById('monthly-amortization').innerText = Math.round(monthlyAmortization).toLocaleString();
            document.getElementById('total-interest').innerText = '₱' + totalInterest.toLocaleString(undefined, { minimumFractionDigits: 2 });
            document.getElementById('total-repayment').innerText = '₱' + totalRepayment.toLocaleString(undefined, { minimumFractionDigits: 2 });

            document.getElementById('rate-display').innerText = (annualRate * 100).toFixed(1) + '%';
        }

        window.addEventListener('DOMContentLoaded', updateCalculator);
    </script>
</body>

</html>