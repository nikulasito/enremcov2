@props([
    'title' => 'Admin Control Center - ENREMCO',
    'pageTitle' => 'Dashboard',
    'pageSubtitle' => 'System Overview',
    'showSearch' => true,
    'searchAction' => null,
    'searchPlaceholder' => 'Search Member (Name or ID)...',
])

@php

use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Schema;
    $searchAction = $searchAction ?? route('admin.members');
    $admin = auth()->user();
    $isExecAdmin = strtolower((string) ($admin->role ?? '')) === 'exec-admin';
    $adminName = $admin->name ?? 'Administrator';
    $adminRole = $isExecAdmin ? 'Exec Admin' : (($admin->is_admin ?? 1) ? 'Super Admin' : 'Admin');

    $navClass = fn (bool $active) => $active ? 'nav-item nav-item-active' : 'nav-item';

    // ✅ Notifications (counts)
    $pendingMemberCount = $isExecAdmin
        ? 0
        : \App\Models\User::query()
            ->where('is_admin', 0)
            ->whereIn(DB::raw('LOWER(status)'), [
                'awaiting approval',  // your newMembers() uses this
                'pending',
            ])
            ->count();

    $pendingLoanCount = Schema::hasTable('loan_applications')
        ? \App\Models\LoanApplication::query()
            ->when($isExecAdmin, function ($q) {
                $q->whereRaw('LOWER(status) = ?', ['for_approval']);
            }, function ($q) {
                $q->whereIn(DB::raw('LOWER(status)'), [
                    'pending',
                    'for_review',
                    'for_processing',
                ]);
            })
            ->count()
        : 0;

    $notifCount = $pendingMemberCount + $pendingLoanCount;

    $notifItems = [];

    if (!$isExecAdmin && $pendingMemberCount > 0) {
        $notifItems[] = [
            'title' => 'New Member Requests',
            'message' => $pendingMemberCount . ' pending membership approval',
            'count' => $pendingMemberCount,
            'href' => route('admin.new-members'),
            'icon' => 'person_add',
            'pill' => 'bg-amber-100 text-amber-700 border border-amber-200',
            'iconBg' => 'bg-amber-50',
            'iconText' => 'text-amber-600',
        ];
    }

    if ($pendingLoanCount > 0) {
        $notifItems[] = [
            'title' => $isExecAdmin ? 'For Approval Queue' : 'Loan Requests',
            'message' => $isExecAdmin
                ? ($pendingLoanCount . ' loans awaiting your approval')
                : ($pendingLoanCount . ' loans pending action'),
            'count' => $pendingLoanCount,
            'href' => route('admin.loan-requests.index'), // or add ?status=pending if your page supports it
            'icon' => 'checkbook',
            'pill' => 'bg-blue-100 text-blue-700 border border-blue-200',
            'iconBg' => 'bg-blue-50',
            'iconText' => 'text-blue-600',
        ];
    }
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
            @if($isExecAdmin)
                <div class="mb-4">
                    <p class="px-6 text-[10px] font-bold uppercase tracking-[0.2em] text-slate-600 mb-3">Executive Panel</p>
                    <a class="{{ $navClass(request()->routeIs('admin.exec-dashboard')) }}" href="{{ route('admin.exec-dashboard') }}">
                        <span class="material-symbols-outlined text-[22px]">dashboard</span>
                        Dashboard
                    </a>
                    <a class="{{ $navClass(request()->routeIs('admin.loan-requests.*')) }}" href="{{ route('admin.loan-requests.index') }}">
                        <span class="material-symbols-outlined text-[22px]">checkbook</span>
                        Loan Approvals
                    </a>
                </div>
            @else
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
                    <a class="{{ $navClass(request()->routeIs('admin.loan-requests.*')) }}" href="{{ route('admin.loan-requests.index') }}">
                        <span class="material-symbols-outlined text-[22px]">checkbook</span>
                        Loan Requests
                    </a>
                    <a class="{{ $navClass(request()->routeIs('admin.loans')) }}" href="{{ route('admin.loans') }}">
                        <span class="material-symbols-outlined text-[22px]">description</span>
                        Loan Details
                    </a>
                </div>
            @endif
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
                    <div class="relative">
    <button id="notifBtn" type="button"
        class="relative p-2.5 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-xl transition-all">
        <span class="material-symbols-outlined">notifications</span>

        @if($notifCount > 0)
            <span
                class="absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1.5 rounded-full bg-primary text-[#0d1a14]
                       text-[10px] font-black flex items-center justify-center ring-2 ring-white">
                {{ $notifCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div id="notifMenu"
        class="hidden absolute right-0 mt-3 w-[360px] bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden">
        <div class="px-4 py-3 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
            <p class="text-[11px] font-black uppercase tracking-widest text-slate-600">Notifications</p>
            <p class="text-[11px] font-bold text-slate-500">{{ $notifCount }} new</p>
        </div>

        <div class="max-h-[320px] overflow-y-auto custom-scrollbar">
            @forelse($notifItems as $n)
                <a href="{{ $n['href'] }}" class="block px-4 py-3 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start gap-3">
                        <div class="size-9 rounded-xl {{ $n['iconBg'] }} flex items-center justify-center {{ $n['iconText'] }} shrink-0">
                            <span class="material-symbols-outlined text-[20px]">{{ $n['icon'] }}</span>
                        </div>

                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-black text-slate-900">{{ $n['title'] }}</p>
                            <p class="text-xs font-medium text-slate-500">{{ $n['message'] }}</p>
                        </div>

                        <span class="px-2 py-0.5 text-[10px] font-black rounded-full {{ $n['pill'] }}">
                            {{ $n['count'] }}
                        </span>
                    </div>
                </a>
            @empty
                <div class="px-4 py-6 text-sm font-medium text-slate-500">
                    No new notifications.
                </div>
            @endforelse
        </div>
    </div>
</div>

                    <!-- <a href="{{ route('profile.edit') }}" class="p-2.5 text-slate-400 hover:text-primary hover:bg-slate-50 rounded-xl transition-all">
                        <span class="material-symbols-outlined">settings</span>
                    </a> -->
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

@once
<script>
(function () {
    const btn = document.getElementById('notifBtn');
    const menu = document.getElementById('notifMenu');
    if (!btn || !menu) return;

    btn.addEventListener('click', (e) => {
        e.stopPropagation();
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', () => menu.classList.add('hidden'));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') menu.classList.add('hidden');
    });
})();
</script>
@endonce

</body>
</html>
