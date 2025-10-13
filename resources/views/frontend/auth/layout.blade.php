<!DOCTYPE html>
<html lang="en">

<?php $settings = settings(); ?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    @vite('resources/css/app.css')
    <script src="{{ asset('assets/libs/jquery/jquery-3.7.1.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/responsive.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
        integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>@yield('title') | {{ $settings->app_name }}</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <main class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>
    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
