@php
    $user = auth()->user();

    $memberName = $user->name ?? 'Member';
    $memberId = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? 'N/A';

    // ACTIVE STATES (based on your routes/web.php)
    $isDashboard = request()->routeIs('dashboard');
    $isProfile = request()->routeIs('member.profile');
    $isContributionsMain = request()->routeIs('member.contributions');
    $isContributionsShares = request()->routeIs('member.contributions.shares');
    $isContributionsSavings = request()->routeIs('member.contributions.savings');
    $isContributions = $isContributionsMain || $isContributionsShares || $isContributionsSavings;

    // Security routes
    $isSecurity = request()->routeIs('password.edit') || request()->routeIs('password.update');

    // Member loans routes
    $isLoans = request()->is('member/loans*') || request()->routeIs('member.loans.*');

    // Named slot support (optional)
    // If a page provides <x-slot name="header"> ... </x-slot>, we show it.
    $hasCustomHeader = isset($header) && method_exists($header, 'isNotEmpty') && $header->isNotEmpty();
@endphp

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $title ?? 'ENREMCO Member Portal' }}</title>

    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#19e680",
                        secondary: "#2563eb",
                        "background-light": "#f8fafc",
                        "background-dark": "#112119",
                        "sidebar-dark": "#0d1a14",
                    },
                    fontFamily: { display: ["Public Sans", "sans-serif"] },
                    borderRadius: {
                        DEFAULT: "0.25rem",
                        lg: "0.5rem",
                        xl: "0.75rem",
                        "2xl": "1rem",
                        full: "9999px",
                    },
                },
            },
        };
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body { @apply font-display text-[#1e293b] bg-background-light; }
        }
        .nav-item-active { @apply bg-primary/10 text-primary border-r-4 border-primary; }
        .card-shadow { box-shadow: 0 4px 20px -2px rgba(0,0,0,0.05); }
        #applicationModal,#loanRecordModal {    margin-top: 0;}
        .main-header {z-index: 99;}
    </style>

    @stack('head')
</head>

<body class="flex h-screen overflow-hidden">

    <div id="memberSidebarOverlay" class="fixed inset-0 bg-black/40 z-40 hidden lg:hidden"></div>

    {{-- Sidebar --}}
    <aside id="memberSidebar"
        class="w-72 bg-sidebar-dark text-white flex flex-col shrink-0 fixed inset-y-0 left-0 z-50 transform -translate-x-full transition-transform duration-200 lg:static lg:translate-x-0">
        <div class="p-8 flex items-center gap-3">
            <h2 class="text-xl font-black tracking-tight uppercase">ENREMCO</h2>
        </div>

        <nav class="flex-1 mt-4">
            <ul class="space-y-1">

                {{-- Dashboard --}}
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all
                        {{ $isDashboard ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="{{ $isDashboard ? 'font-bold' : 'font-medium' }}">Dashboard</span>
                    </a>
                </li>

                {{-- Profile --}}
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all
                        {{ $isProfile ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('member.profile') }}">
                        <span class="material-symbols-outlined">person</span>
                        <span class="{{ $isProfile ? 'font-bold' : 'font-medium' }}">Profile</span>
                    </a>
                </li>

                {{-- Contributions + Submenu --}}
                <li>
                    <div
                        class="flex items-center gap-2 px-8 py-4 transition-all {{ $isContributions ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}">
                        <a id="contributionsTrigger" class="flex min-w-0 flex-1 items-center gap-4" href="#">
                            <span class="material-symbols-outlined">account_balance_wallet</span>
                            <span class="{{ $isContributions ? 'font-bold' : 'font-medium' }}">Contributions</span>
                        </a>
                        <button type="button" id="contributionsToggle"
                            class="inline-flex size-7 items-center justify-center rounded-md transition-colors {{ $isContributions ? 'text-primary hover:bg-primary/10' : 'text-[#6f8479] hover:bg-white/10' }}"
                            aria-controls="contributionsSubmenu"
                            aria-expanded="{{ $isContributions ? 'true' : 'false' }}"
                            aria-label="Toggle contributions submenu">
                            <span id="contributionsChevron"
                                class="material-symbols-outlined text-[18px] transition-transform {{ $isContributions ? 'rotate-180' : '' }}">
                                expand_more
                            </span>
                        </button>
                    </div>
                    <ul id="contributionsSubmenu"
                        class="mx-4 mt-1 mb-2 pl-5 border-l border-white/10 space-y-1 {{ $isContributions ? '' : 'hidden' }}">
                        <li>
                            <a href="{{ route('member.contributions.shares') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-s font-medium transition-all {{ $isContributionsShares ? 'bg-primary/15 text-primary' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}">
                                <span class="material-symbols-outlined text-[16px]">pie_chart</span>
                                Shares
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('member.contributions.savings') }}"
                                class="flex items-center gap-2 px-3 py-2 rounded-lg text-s font-medium transition-all {{ $isContributionsSavings ? 'bg-primary/15 text-primary' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}">
                                <span class="material-symbols-outlined text-[16px]">savings</span>
                                Savings
                            </a>
                        </li>
                    </ul>
                </li>

                {{-- Loans --}}
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all
        {{ $isLoans ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('member.loans.index') }}">
                        <span class="material-symbols-outlined">payments</span>
                        <span class="{{ $isLoans ? 'font-bold' : 'font-medium' }}">View Loans</span>
                    </a>
                </li>

                {{-- Security --}}
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all
                        {{ $isSecurity ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('password.edit') }}">
                        <span class="material-symbols-outlined">shield</span>
                        <span class="{{ $isSecurity ? 'font-bold' : 'font-medium' }}">Security</span>
                    </a>
                </li>

            </ul>
        </nav>

        <div class="p-8 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div
                    class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary overflow-hidden">
                    <img alt="User Avatar" class="w-full h-full object-cover"
                        src="https://ui-avatars.com/api/?name={{ urlencode($memberName) }}&background=19e680&color=112119" />
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ $memberName }}</p>
                    <p class="text-xs text-[#a0b0a8] truncate">Member</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-white/5 py-2 text-xs font-bold text-[#a0b0a8] hover:text-white hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 overflow-y-auto min-w-0">

        {{-- Header (custom per page OR default Welcome header) --}}
        <header
            class="bg-white border-b border-slate-200 px-4 sm:px-6 lg:px-10 py-4 sm:py-6 lg:py-8 sticky top-0 z-10 main-header">
            <div class="flex items-start gap-3">
                <button id="memberSidebarBtn" type="button"
                    class="lg:hidden inline-flex items-center justify-center size-10 rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-50 transition-colors"
                    aria-controls="memberSidebar" aria-expanded="false" aria-label="Toggle member menu">
                    <span class="material-symbols-outlined">menu</span>
                </button>

                <div class="w-full">
                    @if($hasCustomHeader)
                        {{ $header }}
                    @else
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <h1 class="text-2xl sm:text-3xl font-black text-slate-900">Welcome, {{ $memberName }}</h1>
                                <div class="mt-2 flex items-center gap-2 text-slate-500">
                                    <span class="text-sm font-medium uppercase tracking-wider">Member ID:</span>
                                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-900 font-bold text-sm">
                                        {{ $memberId }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <div class="p-4 sm:p-6 lg:p-10 space-y-8 lg:space-y-10">
            {{ $slot }}
        </div>
    </main>

    @stack('scripts')
    <script>
        (function () {
            const sidebar = document.getElementById('memberSidebar');
            const overlay = document.getElementById('memberSidebarOverlay');
            const btn = document.getElementById('memberSidebarBtn');
            if (!sidebar || !overlay || !btn) return;

            const closeMenu = () => {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            };

            const openMenu = () => {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');
            };

            btn.addEventListener('click', () => {
                if (sidebar.classList.contains('-translate-x-full')) {
                    openMenu();
                } else {
                    closeMenu();
                }
            });

            overlay.addEventListener('click', closeMenu);

            document.querySelectorAll('#memberSidebar a').forEach((a) => {
                a.addEventListener('click', () => {
                    if (window.innerWidth < 1024) closeMenu();
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 1024) {
                    overlay.classList.add('hidden');
                    sidebar.classList.remove('-translate-x-full');
                    btn.setAttribute('aria-expanded', 'false');
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            });

            const contributionsToggle = document.getElementById('contributionsToggle');
            const contributionsTrigger = document.getElementById('contributionsTrigger');
            const contributionsSubmenu = document.getElementById('contributionsSubmenu');
            const contributionsChevron = document.getElementById('contributionsChevron');
            if (contributionsToggle && contributionsSubmenu && contributionsChevron) {
                const toggleContributionsSubmenu = () => {
                    const willExpand = contributionsSubmenu.classList.contains('hidden');
                    contributionsSubmenu.classList.toggle('hidden', !willExpand);
                    contributionsChevron.classList.toggle('rotate-180', willExpand);
                    contributionsToggle.setAttribute('aria-expanded', willExpand ? 'true' : 'false');
                };

                contributionsToggle.addEventListener('click', (e) => {
                    e.preventDefault();
                    toggleContributionsSubmenu();
                });

                contributionsTrigger?.addEventListener('click', (e) => {
                    e.preventDefault();
                    toggleContributionsSubmenu();
                });
            }

            // Make member-page tables mobile-friendly by ensuring horizontal scroll.
            document.querySelectorAll('main table').forEach((table) => {
                const parent = table.parentElement;
                if (!parent) return;

                if (!parent.classList.contains('overflow-x-auto')) {
                    const wrap = document.createElement('div');
                    wrap.className = 'overflow-x-auto w-full';
                    parent.insertBefore(wrap, table);
                    wrap.appendChild(table);
                }

                if (window.innerWidth < 640) {
                    table.style.minWidth = '720px';
                }
            });
        })();
    </script>
</body>

</html>