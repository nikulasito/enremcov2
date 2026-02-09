<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Favicon -->
        <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-9/assets/css/registration-9.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/style.css'])
    </head>
<body class="d-flex flex-column min-vh-100 font-sans text-gray-900 antialiased">
    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
            <div class="header-inner header-1">      
                <div class="main-menu">
                        <header class="container">
                            <div class="flex lg:justify-center lg:col-start-2">
                                <a href="{{ url('/') }}" class="dash-logo"><img src="{{ asset('assets/images/enremco_logo2.png') }}" 
                            alt="Admin Logo" class="img-fluid" style="max-width: 300px; height: auto;"></a>
                            </div>
                            @if (Route::has('login'))
                                <nav class="-mx-3 flex flex-1 justify-end desktop-menu">
                                <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Home</a>
                                <a href="{{ route('faqs') }}" class="active rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                FAQs</a>
                                <!-- <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                                Contact Us</a> -->
                                    <!-- @auth
                                            <a
                                                href="{{ Auth::user()->is_admin ? url('/admin/dashboard') : url('/dashboard') }}"
                                                class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                            >
                                                Dashboard
                                            </a>
                                    @else
                                        <a
                                            href="{{ route('login') }}"
                                            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
                                        >
                                            Log in
                                        </a>
                                    @endauth -->
                                </nav>
                            @endif
                        </header>
                    </div>

                    <!-- Burger Menu Icon (Visible on small screens) -->
                    <div class="mobile-menu">
                        <button id="burger-menu" class="text-black dark:text-white">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                    <!-- Mobile Menu (Hidden on desktop) -->
                    <div id="mobile-menu" class="lg:hidden hidden flex-col space-y-4 py-4 px-6 bg-gray-50 dark:bg-black text-black dark:text-white">
                        <a href="{{ url('/') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            Home
                        </a>
                        <a href="{{ route('faqs') }}" class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
                            FAQs
                        </a>
                    </div>
            </div>
        </div>

    <main class="flex-grow-1 align-items-center justify-content-center w-100">
            <div class="registration">
                <section class="form-holder py-3 py-md-5 w-100">
                    <div class="container">
                        <div class="row gy-4 align-items-center">
                            <div class="col-12 col-md-6 col-xl-7 center-reg" style="margin:auto;">
                                <div class="card border-0 rounded-4 login-form-holder">
                                    <div class="card-body p-3 p-md-4 p-xl-4">
                                        {{ $slot }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
    </main>

        <div class="footer">
            <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <!-- Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) -->
             <span>ENREMCO Management System V 1.0.12</span>
            </footer>
        </div>
    </body>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
             updateDateTime();
            setInterval(updateDateTime, 1000); // Update every second

            setTimeout(() => {
                document.getElementById("preloader").style.display = "none";
                document.getElementById("content").style.display = "block";
            }, 1000); // Simulated delay for smooth transition
        });

function updateDateTime() {
    const now = new Date();
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
    document.getElementById("currentDateTime").textContent = now.toLocaleDateString('en-US', options);
}


    </script>
</html>
