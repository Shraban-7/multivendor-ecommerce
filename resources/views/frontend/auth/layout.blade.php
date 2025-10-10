<!DOCTYPE html>
<html lang="en">

<?php $settings = settings(); ?>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset($settings->favicon) }}">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/style.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/frontend/styles/responsive.css') }}" />
    <title>@yield('title') | {{ $settings->app_name }}</title>
</head>

<body class="bg-gray-100 min-h-screen">
    <main class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>
    @vite('resources/js/app.js')
</body>

</html>