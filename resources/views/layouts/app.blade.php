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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @vite('resources/css/style.css')


        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>
    <body class="d-flex flex-column min-vh-100 font-sans text-gray-900 antialiased">

            <!-- 🔔 System Notice -->
            <div class="notice-container text-center bg-warning text-dark py-2">
                <strong>⚠ System Notice:</strong> This system is under development. Please report any issues you encounter. Thank you!
            </div>
            
            <!-- Preloader -->
            <div id="preloader">
                <div class="spinner"></div>
            </div>

            <!-- 🧩 Header -->
            <header class="bg-white main-header py-3">
                <div class="container d-flex justify-content-center align-items-center">
                    <img src="{{ asset('assets/images/enremco_logo2.png') }}" alt="ENREMCO Logo" class="img-fluid" style="max-width: 300px;">
                </div>
            </header>

        <div class="content-member">
            @include('layouts.navigation')
            <main class="flex-grow-1 align-items-center justify-content-center w-100">
                <div class="container">
                    {{ $slot }}
                </div>
            </main>
        </div>

    <div class="footer">
        <footer class="py-16 text-center text-sm text-black dark:text-white/70 mt-auto">
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
