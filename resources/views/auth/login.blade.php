{{-- resources/views/auth/login.blade.php --}}

<!DOCTYPE html>
<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>ENREMCO Member &amp; Admin Login</title>

    {{-- Quick design way (CDN). If you prefer Vite Tailwind only, tell me and I’ll convert this to Vite-safe CSS. --}}
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
        @layer base {
            body { @apply font-display text-[#111814] bg-background-light; }
        }
        .login-gradient-overlay {
            background: linear-gradient(rgba(17, 33, 25, 0.6) 0%, rgba(17, 33, 25, 0.85) 100%);
        }
    </style>
</head>

<body class="h-screen overflow-hidden">
    <div class="flex h-full w-full flex-col lg:flex-row">

        {{-- LEFT PANEL --}}
        <div class="relative hidden h-full lg:flex lg:w-2/3 items-center justify-center overflow-hidden">
            <img alt="Office Background" class="absolute inset-0 h-full w-full object-cover"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdL2Qs6VYWFhcev68fcbZifnBtE4QulKa6GS5tZ1dhQhWdGwnKp7E_uXxwf-iUK4qH7Taf2aGhheje46nq4aVJAEjuhURxoOn9chj_m03I5n4UeiDpk0e_mXDUYtcikAfE2zbxNPKhirn_v-arsOFp0EXXeGaWyMsFp0xrwNpQQ4TSphS8FfPyaCDYawgDeTQT0VBU2fsq80OMmU13skRxaEjKESthJ87xIj0Lk6RrxC79JtILjxuydUvONrH8k2Z27at-4Za3h0H_" />
            <div class="absolute inset-0 login-gradient-overlay"></div>

            <div class="relative z-10 flex flex-col items-center px-12 text-center">
                <div class="mb-8 flex items-center gap-3 text-primary">
                    <h2 class="text-3xl font-black leading-tight tracking-tight uppercase">ENREMCO</h2>
                </div>

                <h1 class="text-4xl font-black text-white sm:text-5xl">Welcome back to your Cooperative portal
                </h1>
                <p class="mt-6 max-w-sm text-lg text-[#dce5e0]">Manage your loans, track your savings, and access
                    member-exclusive benefits with ease.</p>
            </div>
        </div>

        {{-- RIGHT PANEL --}}
        <div class="flex h-full w-full flex-col bg-white lg:w-1/2">

            {{-- Mobile header --}}

            <div class="flex flex-1 flex-col justify-center px-6 sm:px-12 lg:px-24 xl:px-32">
                <div class="w-full">
                    <h2 class="text-3xl font-black text-[#111814] sm:text-4xl">Login</h2>
                    <p class="mt-2 text-base text-[#638875]">Please enter your credentials to access the system.</p>

                    {{-- Breeze status message --}}
                    @if (session('status'))
                        <div class="mt-6 rounded-xl border border-[#dce5e0] bg-[#f6f8f7] p-4 text-sm text-[#111814]">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Validation summary (optional but helpful) --}}
                    @if ($errors->any())
                        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="mt-10 flex flex-col gap-6" method="POST" action="{{ route('login') }}">
                        @csrf

                        {{-- IMPORTANT: keep name="username" to match your existing Breeze LoginRequest --}}
                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-[#111814]" for="username">Member ID or Email</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#a0b0a8]">person</span>
                                <input
                                    class="h-14 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] pl-12 pr-4 text-base focus:border-primary focus:ring-primary @error('username') border-red-300 @enderror"
                                    id="username" name="username" value="{{ old('username') }}"
                                    placeholder="Enter your ID or email" type="text" autocomplete="username" required
                                    autofocus />
                            </div>
                            @error('username')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-sm font-bold text-[#111814]" for="password">Password</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-[#a0b0a8]">lock</span>
                                <input
                                    class="h-14 w-full rounded-xl border-[#dce5e0] bg-[#f6f8f7] pl-12 pr-4 text-base focus:border-primary focus:ring-primary @error('password') border-red-300 @enderror"
                                    id="password" name="password" placeholder="••••••••" type="password"
                                    autocomplete="current-password" required />
                            </div>
                            @error('password')
                                <p class="text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input class="size-5 rounded border-[#dce5e0] text-primary focus:ring-primary"
                                    type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }} />
                                <span class="text-sm font-medium text-[#638875]">Remember Me</span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-sm font-bold text-primary hover:underline"
                                    href="{{ route('password.request') }}">
                                    Forgot Password?
                                </a>
                            @endif
                        </div>

                        <button type="submit"
                            class="mt-4 flex h-14 w-full items-center justify-center rounded-xl bg-primary text-lg font-black text-background-dark shadow-lg shadow-primary/20 transition-all hover:brightness-105 active:scale-[0.98]">
                            Login
                        </button>
                    </form>

                    <div class="mt-8 text-center">
                        <p class="text-sm text-[#638875]">
                            Don't have an account?
                            @if (Route::has('register'))
                                <a class="font-bold text-primary hover:underline" href="{{ route('register') }}">
                                    Apply for Membership
                                </a>
                            @else
                                <a class="font-bold text-primary hover:underline" href="#">
                                    Apply for Membership
                                </a>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <footer class="p-8">
                <div
                    class="mx-auto flex max-w-md flex-col items-center justify-between gap-4 border-t border-[#dce5e0] pt-8 sm:flex-row">
                    <p class="text-xs text-[#a0b0a8]">© {{ date('Y') }} ENREMCO. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a class="text-xs font-bold text-[#638875] hover:text-primary transition-colors"
                            href="#">Support</a>
                        <a class="text-xs font-bold text-[#638875] hover:text-primary transition-colors"
                            href="#">Privacy</a>
                    </div>
                </div>
            </footer>

        </div>
    </div>
</body>

</html>