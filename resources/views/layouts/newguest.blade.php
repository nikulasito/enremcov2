<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://unpkg.com/bootstrap@5.3.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet"
    href="https://unpkg.com/bs-brain@2.0.4/components/registrations/registration-9/assets/css/registration-9.css">

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/css/style.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
  <div class="py-3 py-md-5">
    <div class="registration">
      <!-- Registration 9 - Bootstrap Brain Component -->
      <section class="py-3 py-md-5">
        <div class="container">
          <div class="row gy-4 align-items-center">

            <div class="col-12 col-md-6 col-xl-7 center-modal-text">
              <div class="card border-0 rounded-4">
                <div class="card-body p-3 p-md-4 p-xl-4">
                  <div class="row">
                  </div>
                  @yield('content') <!-- Content will be injected here -->
                </div>
              </div>
            </div>
          </div>

        </div>
    </div>
    </section>
  </div>
</body>

</html>