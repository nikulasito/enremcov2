<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ENREMCO Management System</title>

    <!-- Favicon -->
        <link rel="icon" href="{{ asset('assets/images/favicon.ico') }}" type="image/x-icon">
        
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles & Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/style.css'])
           
        @endif

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

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
                                    @auth
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
                                    @endauth
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

        <!-- 🔹 MAIN CONTENT -->
    <main class="flex-grow-1 faqs-page">
        <div class="main-container content-wrapper pad-none">
            <div class="home-template content-inner">
                <h2 class="text-center mb-4">Frequently Asked Questions</h2>

                        <!-- FAQ Accordion -->
                        <!-- <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                        What is ENREMCO?
                                    </button>
                                </h2>
                                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        ENREMCO is a multipurpose cooperative that provides financial services and community programs for DENR employees.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                        How can I become a member?
                                    </button>
                                </h2>
                                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can apply for membership by clicking the link <a href="{{ route('register') }}" class="url-link">here</a> and submitting the required documents.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                        What services does ENREMCO provide?
                                    </button>
                                </h2>
                                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        ENREMCO offers savings and loan services, investment opportunities, and financial assistance for DENR employees.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq4">
                                        How can I apply for a loan?
                                    </button>
                                </h2>
                                <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can apply for a loan by logging into your ENREMCO account, navigating to the loan application section, and submitting the required documents.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq5">
                                        How can I contact customer support?
                                    </button>
                                </h2>
                                <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        You can reach us through our email at <b>support@enremco.com</b> or call our hotline at <b>+123 456 7890</b>.
                                    </div>
                                </div>
                            </div>

                        </div> -->
                        <!-- End FAQ Accordion -->

                                <h4>What is ENREMCO?</h4>
                                <p>ENREMCO is a multipurpose cooperative that provides financial services and community programs for DENR employees.</p>

                                <h4>How can I become a member?</h4>
                                <p>You can apply for membership by clicking the link <a href="{{ route('register') }}" class="url-link">here</a> and submitting the required documents.</p>

                                <h4>What services does ENREMCO provide?</h4>
                                <p>ENREMCO offers savings and loan services, investment opportunities, and financial assistance for DENR employees.</p>

                                <h4>How can I apply for a loan?</h4>
                                <p>You can apply for a loan by logging into your ENREMCO account, navigating to the loan application section, and submitting the required documents.</p>

                                <h4>How can I contact customer support?</h4>
                                <p>You can reach us through our email at <b>support@enremco.com</b> or call our hotline at <b>+123 456 7890</b>.</p>

                        </div>
            </div>
        </div>
    </main>

    <div class="footer">
            <footer class="py-16 text-center text-sm text-black dark:text-white/70">
            <!-- Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }}) -->
             <span>ENREMCO Management System V 1.0.12</span>
            </footer>
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

<script>
    const burgerMenu = document.getElementById('burger-menu');
    const mobileMenu = document.getElementById('mobile-menu');

    burgerMenu.addEventListener('click', () => {
        // Toggle visibility and animation
        mobileMenu.classList.toggle('hidden');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.style.maxHeight = '0';
            mobileMenu.style.opacity = '0';
        } else {
            mobileMenu.style.maxHeight = '500px'; // Adjust this value depending on the height of your menu
            mobileMenu.style.opacity = '1';
        }
    });
</script>

</html>
