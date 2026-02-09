@props([
    'title' => 'Admin Control Center - ENREMCO',
    'pageTitle' => 'Dashboard',
    'pageSubtitle' => 'System Overview',
    'showSearch' => true,
    'searchAction' => null,
    'searchPlaceholder' => 'Search Member (Name or ID)...',
])

@php
    $searchAction = $searchAction ?? route('admin.members');
    $admin = auth()->user();
    $adminName = $admin->name ?? 'Administrator';
    $adminRole = ($admin->is_admin ?? 1) ? 'Super Admin' : 'Admin';

    $navClass = fn (bool $active) => $active ? 'nav-item nav-item-active' : 'nav-item';
@endphp

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700&display=swap" rel="stylesheet" />
    
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(25,230,128,0.2); border-radius: 10px; }
    </style>

    @stack('styles')
</head>

<body class="h-screen overflow-hidden flex bg-background-light text-slate-900">
    {{-- Sidebar --}}
    <aside class="w-64 h-full bg-sidebar-green flex flex-col shrink-0">
        <div class="p-8 flex items-center gap-3">
            <h2 class="text-xl font-extrabold tracking-tight text-white">ENREMCO</h2>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar">
            <div class="mb-4">
                <p class="px-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600 mb-3">Main Navigation</p>
                <a class="{{ $navClass(request()->routeIs('admin.dashboard')) }}" href="{{ route('admin.dashboard') }}">
                    <span class="material-symbols-outlined text-[22px]">grid_view</span>
                    Dashboard
                </a>
            </div>

            <div class="mb-4">
                <p class="px-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600 mb-3">Membership</p>
                <a class="{{ $navClass(request()->routeIs('admin.new-members')) }}" href="{{ route('admin.new-members') }}">
                    <span class="material-symbols-outlined text-[22px]">person_add</span>
                    New Members
                </a>
                <a class="{{ $navClass(request()->routeIs('admin.members')) }}" href="{{ route('admin.members') }}">
                    <span class="material-symbols-outlined text-[22px]">group</span>
                    View Members
                </a>
            </div>

            <div class="mb-4">
                <p class="px-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600 mb-3">Ledger & Finance</p>
                <a class="{{ $navClass(request()->routeIs('admin.shares')) }}" href="{{ route('admin.shares') }}">
                    <span class="material-symbols-outlined text-[22px]">account_balance_wallet</span>
                    Shares
                </a>
                <a class="{{ $navClass(request()->routeIs('admin.savings')) }}" href="{{ route('admin.savings') }}">
                    <span class="material-symbols-outlined text-[22px]">savings</span>
                    Savings
                </a>
                <a class="{{ $navClass(request()->routeIs('admin.withdraw')) }}" href="{{ route('admin.withdraw') }}">
                    <span class="material-symbols-outlined text-[22px]">request_quote</span>
                    Withdrawals
                </a>
                <a class="{{ $navClass(request()->routeIs('admin.loan-payments')) }}" href="{{ route('admin.loan-payments') }}">
                    <span class="material-symbols-outlined text-[22px]">payments</span>
                    Loan Payments
                </a>
            </div>

            <div class="mb-4">
                <p class="px-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600 mb-3">Loans</p>
                <a class="{{ $navClass(request()->routeIs('admin.loans')) }}" href="{{ route('admin.loans') }}">
                    <span class="material-symbols-outlined text-[22px]">description</span>
                    Loan Details
                </a>
            </div>
        </nav>

        <div class="p-6 border-t border-white/5 bg-black/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center gap-3 text-sm font-semibold text-slate-400 hover:text-red-400 transition-colors w-full group">
                    <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">logout</span>
                    Logout Session
                </button>
            </form>
        </div>
    </aside>

    {{-- Main --}}
    <main class="flex-1 flex flex-col min-w-0 bg-white">
        <header class="h-20 border-b border-slate-100 flex items-center justify-between px-10 bg-white sticky top-0 z-10">
            <div class="flex items-center gap-12 flex-1">
                <div>
                    <h1 class="text-xl font-bold text-slate-900">{{ $pageTitle }}</h1>
                    <p class="text-xs font-medium text-slate-500 uppercase tracking-tighter">{{ $pageSubtitle }}</p>
                </div>

                @if($showSearch)
                    <form class="max-w-md w-full relative" method="GET" action="{{ $searchAction }}">
                        <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                        <input
                            name="search"
                            value="{{ request('search') }}"
                            class="w-full h-11 pl-11 pr-4 bg-slate-50 border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all placeholder:text-slate-400 font-medium"
                            placeholder="{{ $searchPlaceholder }}"
                            type="text"
                        />
                    </form>
                @endif
            </div>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-2">
                    <button type="button" class="relative p-2.5 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-xl transition-all">
                        <span class="material-symbols-outlined">notifications</span>
                        <span class="absolute top-2.5 right-2.5 size-2 bg-primary rounded-full ring-2 ring-white"></span>
                    </button>
                    <a href="{{ route('profile.edit') }}" class="p-2.5 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-xl transition-all">
                        <span class="material-symbols-outlined">settings</span>
                    </a>
                </div>

                <div class="h-8 w-px bg-slate-100"></div>

                <div class="flex items-center gap-3 pl-2">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-bold text-slate-900">{{ $adminName }}</p>
                        <p class="text-[10px] font-bold text-primary uppercase tracking-wider">{{ $adminRole }}</p>
                    </div>
                    <div class="size-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden">
                        <span class="material-symbols-outlined text-slate-400">person</span>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-10 custom-scrollbar">
            {{ $slot }}
        </div>

        <footer class="px-10 py-5 border-t border-slate-100 flex items-center justify-between bg-white text-slate-400">
            <p class="text-[11px] font-medium">© {{ now()->format('Y') }} ENREMCO Cooperative Loan Management System.</p>
            <div class="flex gap-6">
                <a class="text-[11px] font-bold uppercase tracking-widest hover:text-primary transition-colors" href="#">Support</a>
                <a class="text-[11px] font-bold uppercase tracking-widest hover:text-primary transition-colors" href="#">System Status</a>
                <a class="text-[11px] font-bold uppercase tracking-widest hover:text-primary transition-colors" href="#">Documentation</a>
            </div>
        </footer>
    </main>

    @stack('scripts')
</body>
</html>
