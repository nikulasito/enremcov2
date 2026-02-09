@php
    $user = auth()->user();
    $fullName = $user->name ?? 'Member';
    $memberId = $user->employee_ID ?? $user->employees_id ?? $user->employee_id ?? 'N/A';

    $isDashboard = request()->routeIs('dashboard');
    $isProfile = request()->routeIs('member.profile');
    $isContributions = request()->routeIs('member.contributions');
@endphp

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>@yield('title', 'ENREMCO Member Portal')</title>

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
                        "secondary": "#2563eb",
                        "background-light": "#f8fafc",
                        "background-dark": "#112119",
                        "sidebar-dark": "#0d1a14",
                    },
                    fontFamily: { "display": ["Public Sans", "sans-serif"] },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "2xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
      body { @apply font-display text-[#1e293b] bg-background-light; }
    }
    .nav-item-active { @apply bg-primary/10 text-primary border-r-4 border-primary; }
    .card-shadow { box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05); }
  </style>

    @stack('styles')
</head>

<body class="flex h-screen overflow-hidden">

    {{-- SIDEBAR (same as NEW member dashboard) --}}
    <aside class="w-72 bg-sidebar-dark text-white flex flex-col shrink-0">
        <div class="p-8 flex items-center gap-3">
            <h2 class="text-xl font-black tracking-tight uppercase">ENREMCO</h2>
        </div>

        <nav class="flex-1 mt-4">
            <ul class="space-y-1">
                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all {{ $isDashboard ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('dashboard') }}">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="{{ $isDashboard ? 'font-bold' : 'font-medium' }}">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all {{ $isProfile ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('member.profile') }}">
                        <span class="material-symbols-outlined">person</span>
                        <span class="{{ $isProfile ? 'font-bold' : 'font-medium' }}">Profile</span>
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-4 px-8 py-4 transition-all {{ $isContributions ? 'nav-item-active' : 'text-[#a0b0a8] hover:text-white hover:bg-white/5' }}"
                        href="{{ route('member.contributions') }}">
                        <span class="material-symbols-outlined">account_balance_wallet</span>
                        <span class="{{ $isContributions ? 'font-bold' : 'font-medium' }}">Contributions</span>
                    </a>
                </li>

                <li>
                    <a class="flex items-center gap-4 px-8 py-4 text-[#a0b0a8] hover:text-white hover:bg-white/5 transition-all"
                        href="#">
                        <span class="material-symbols-outlined">payments</span>
                        <span class="font-medium">Loans</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="p-8 border-t border-white/10">
            <div class="flex items-center gap-3">
                <div
                    class="size-10 rounded-full bg-primary/20 flex items-center justify-center text-primary overflow-hidden">
                    <span class="material-symbols-outlined">person</span>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold truncate">{{ $fullName }}</p>
                    <p class="text-xs text-[#a0b0a8] truncate">Member</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="mt-6 flex w-full items-center justify-center gap-2 rounded-lg bg-white/5 py-2 text-xs font-bold text-[#a0b0a8] hover:text-white hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined text-sm">logout</span>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- MAIN --}}
    <main class="flex-1 overflow-y-auto">
        @yield('header')
        <div class="p-10">
            @yield('content')
        </div>
    </main>

</body>

</html>