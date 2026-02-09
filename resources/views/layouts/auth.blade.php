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
                        full: "9999px",
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

    @stack('styles')
</head>

<body class="h-screen overflow-hidden">
    <div class="flex h-full w-full flex-col lg:flex-row">

        {{-- LEFT (image panel) --}}
        <div class="relative hidden h-full lg:flex lg:w-2/3 items-center justify-center overflow-hidden">
            {{-- TIP: Replace this with asset('images/forgot-bg.jpg') if you want local image --}}
            <img alt="Office Background" class="absolute inset-0 h-full w-full object-cover"
                src="https://lh3.googleusercontent.com/aida-public/AB6AXuCdL2Qs6VYWFhcev68fcbZifnBtE4QulKa6GS5tZ1dhQhWdGwnKp7E_uXxwf-iUK4qH7Taf2aGhheje46nq4aVJAEjuhURxoOn9chj_m03I5n4UeiDpk0e_mXDUYtcikAfE2zbxNPKhirn_v-arsOFp0EXXeGaWyMsFp0xrwNpQQ4TSphS8FfPyaCDYawgDeTQT0VBU2fsq80OMmU13skRxaEjKESthJ87xIj0Lk6RrxC79JtILjxuydUvONrH8k2Z27at-4Za3h0H_" />
            <div class="absolute inset-0 login-gradient-overlay"></div>

            <div class="relative z-10 flex flex-col items-center px-12 text-center">
                <div class="mb-8 flex items-center gap-3 text-primary">
                    <h2 class="text-3xl font-black leading-tight tracking-tight uppercase">ENREMCO</h2>
                </div>

                <h1 class="text-4xl font-black text-white sm:text-5xl">
                    @yield('left_title', 'Recover your account access')
                </h1>
                <p class="mt-6 max-w-sm text-lg text-[#dce5e0]">
                    @yield('left_desc', 'Follow the instructions to safely reset your password and regain access to your Cooperative dashboard.')
                </p>
            </div>
        </div>

        {{-- RIGHT (form panel) --}}
        <div class="flex h-full w-full flex-col bg-white lg:w-1/2">
            <div class="flex items-center gap-3 p-6 text-primary lg:hidden">
                <div class="size-8">
                    <svg fill="none" viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M42.1739 20.1739L27.8261 5.82609C29.1366 7.13663 28.3989 10.1876 26.2002 13.7654C24.8538 15.9564 22.9595 18.3449 20.6522 20.6522C18.3449 22.9595 15.9564 24.8538 13.7654 26.2002C10.1876 28.3989 7.13663 29.1366 5.82609 27.8261L20.1739 42.1739C21.4845 43.4845 24.5355 42.7467 28.1133 40.548C30.3042 39.2016 32.6927 37.3073 35 35C37.3073 32.6927 39.2016 30.3042 40.548 28.1133C42.7467 24.5355 43.4845 21.4845 42.1739 20.1739Z"
                            fill="currentColor"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-black tracking-tight uppercase text-background-dark">ENREMCO</h2>
            </div>

            <div class="flex flex-1 flex-col justify-center px-6 sm:px-12 lg:px-24 xl:px-32">
                @yield('content')
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

    @stack('scripts')
</body>

</html>