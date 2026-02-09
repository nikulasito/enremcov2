@php
    $user = auth()->user();

    $memberName = $user->name ?? 'Member';
    $memberId = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? 'N/A';

    // ACTIVE STATES (based on your routes/web.php)
    $isDashboard = request()->routeIs('dashboard');
    $isProfile = request()->routeIs('member.profile');
    $isContributions = request()->routeIs('member.contributions');

    // ✅ Security routes you already have:
    $isSecurity = request()->routeIs('password.edit') || request()->routeIs('password.update');

    // You don't have member.loans route yet
    $isLoans = request()->is('member/loans');

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
    </style>

    @stack('head')
</head>

<body class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <aside class="w-72 bg-sidebar-dark text-white flex flex-col shrink-0">
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

                {{-- Contributions --}}
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all
                        {{ $isContributions ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('member.contributions') }}">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                        <span class="{{ $isContributions ? 'font-bold' : 'font-medium' }}">Contributions</span>
                    </a>
                </li>

                {{-- Loans link placeholder (no route yet) --}}
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all
                        {{ $isLoans ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="#" title="Loans page not added yet">
                        <span class="material-symbols-outlined">payments</span>
                        <span class="{{ $isLoans ? 'font-bold' : 'font-medium' }}">Loans</span>
                    </a>
                </li>

                <hr>
                {{-- ✅ NEW: Security --}}
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
    <main class="flex-1 overflow-y-auto">

        {{-- Header (custom per page OR default Welcome header) --}}
        <header class="bg-white border-b border-slate-200 px-10 py-8">
            @if($hasCustomHeader)
                {{ $header }}
            @else
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-black text-slate-900">Welcome, {{ $memberName }}</h1>
                        <div class="mt-2 flex items-center gap-2 text-slate-500">
                            <span class="text-sm font-medium uppercase tracking-wider">Member ID:</span>
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-900 font-bold text-sm">
                                {{ $memberId }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </header>

        <div class="p-10 space-y-10">
            {{ $slot }}
        </div>
    </main>

    @stack('scripts')
</body>

</html>