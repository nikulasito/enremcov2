{{-- resources/views/layouts/register.blade.php (or components/register-layout.blade.php) --}}
<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'ENREMCO - Membership Registration' }}</title>

    {{-- Tailwind + Fonts (from your design) --}}
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap"
        rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#19e680",
                        "background-light": "#f6f8f7",
                        "background-dark": "#112119",
                    },
                    fontFamily: { display: ["Public Sans", "sans-serif"] },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        full: "9999px"
                    },
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            input[type="text"], input[type="date"], input[type="number"], input[type="email"], input[type="password"], select {
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

    {{-- If your other pages use Bootstrap modals, make sure bootstrap css/js are included here too --}}
    {{--
    <link rel="stylesheet" href="{{ asset('...bootstrap.css') }}"> --}}
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#111814] dark:text-[#f0f4f2] antialiased">
    <div class="relative flex h-auto min-h-screen w-full flex-col overflow-x-hidden">

        {{-- Header (shared like other pages) --}}
        <header
            class="sticky top-0 z-50 w-full border-b border-solid border-[#dce5e0] dark:border-[#2a3a32] bg-white/90 dark:bg-background-dark/90 backdrop-blur-md">
            <div class="mx-auto flex max-w-[1280px] items-center justify-between px-6 py-4 lg:px-10">
                <div class="flex items-center gap-3 text-primary">
                    <h2
                        class="text-[#111814] dark:text-white text-xl font-black leading-tight tracking-tight uppercase">
                        ENREMCO</h2>
                </div>

                <nav class="hidden flex-1 justify-center gap-8 md:flex">
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Home</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">About Us</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Services</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Loan Products</a>
                    <a class="text-sm font-semibold hover:text-primary transition-colors" href="#">Contact</a>
                </nav>

                <div class="flex items-center gap-3">
                    <a href="{{ route('login') }}"
                        class="flex min-w-[100px] items-center justify-center rounded-lg h-10 px-4 bg-primary text-[#112119] text-sm font-bold tracking-tight hover:brightness-110 transition-all">
                        Member Login
                    </a>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        {{ $slot }}

        {{-- Footer (shared like other pages) --}}
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
                    <p class="text-[#638875] dark:text-[#a0b0a8] text-xs">© 2026 ENREMCO Multi-Purpose Cooperative. All
                        rights reserved.</p>

                    <div class="flex items-center gap-2 grayscale opacity-60">
                        <span class="text-[10px] font-bold uppercase text-[#638875] dark:text-[#a0b0a8]">Member
                            of</span>
                        <div
                            class="px-2 py-1 border border-[#dce5e0] dark:border-[#2a3a32] rounded font-black text-[10px] dark:text-[#f0f4f2]">
                            DENR
                        </div>
                    </div>
                </div>
            </div>
        </footer>


    </div>

    {{-- If bootstrap modals are used, bootstrap JS must be loaded in layout --}}
    {{--
    <script src="{{ asset('...bootstrap.bundle.js') }}"></script> --}}
</body>

</html>