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
    <title>Login | {{ $settings->app_name }}</title>
</head>

<body class="bg-gray-50">
    <!-- Login Page -->
    <main class="min-h-screen flex items-center justify-center px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-6xl bg-white shadow-lg rounded-xl overflow-hidden">

            <!-- Left Section (Form) -->
            <div class="flex flex-col justify-center px-6 sm:px-12 py-10">
                <!-- Logo -->
                <div class="mb-6">
                    <a href="{{ route('home') }}">
                        <img src="{{ storage_url($settings->logo_white) }}" alt="Logo" class="h-10 sm:h-12 object-contain" />
                    </a>
                </div>

                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Welcome Back</h1>
                <p class="text-gray-500 mt-2">Login to continue to {{ $settings->app_name }}</p>

                @if (session('error') || session('success') || session('warning'))
                <div id="alert-border"
                    class="mt-4 flex items-center gap-2 px-4 py-3 rounded-lg text-sm shadow-md
                        @if (session('error')) bg-red-100 text-red-700 border-l-4 border-red-500
                        @elseif (session('success')) bg-green-100 text-green-700 border-l-4 border-green-500
                        @elseif (session('warning')) bg-yellow-100 text-yellow-700 border-l-4 border-yellow-500 @endif">
                    <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="flex-1">{{ session('error') ?? (session('success') ?? session('warning')) }}</span>
                    <button type="button" class="hover:text-black" data-dismiss-target="#alert-border">
                        ✕
                    </button>
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST" class="mt-6 space-y-5">
                    @csrf
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input required id="email" type="email" name="email" value="{{ old('email') }}"
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input required type="password" id="password" name="password"
                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
                    </div>
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center gap-2">
                            <input id="remember" type="checkbox"
                                class="h-4 w-4 text-yellow-500 focus:ring-yellow-400 border-gray-300 rounded" />
                            Remember Me
                        </label>
                        <a href="#" class="text-yellow-600 hover:underline">Forgot Password?</a>
                    </div>
                    <button type="submit"
                        class="w-full py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-400 transition">
                        Login
                    </button>
                </form>

                <div class="flex items-center my-6">
                    <span class="flex-1 h-px bg-gray-300"></span>
                    <span class="px-3 text-gray-400 text-sm">OR</span>
                    <span class="flex-1 h-px bg-gray-300"></span>
                </div>

                <!-- <div class="flex gap-3">
                    <button
                        class="flex-1 flex items-center justify-center gap-2 border border-gray-300 rounded-lg py-2 hover:bg-gray-50 transition">
                        <img src="{{ asset('assets/frontend/images/google-icon.png') }}" class="h-5 w-5" />
                        <span class="text-sm">Google</span>
                    </button>
                </div> -->

                <!-- Links -->
                <div class="text-center text-sm text-gray-600 space-y-2">
                    <p>
                        New to {{ $settings->app_name }}?
                        <a href="{{ route('signup') }}" class="text-yellow-600 hover:underline">Sign Up</a>
                    </p>
                    <p>
                        Want to sell products?
                        <a href="{{ route('seller.signup') }}" class="text-yellow-600 hover:underline">Become a Seller</a>
                    </p>
                    <p>
                        Earn by referring people?
                        <a href="{{ route('signup') }}?role={{ App\Enums\UserRole::AFFILIATE->label() }}"
                            class="text-yellow-600 hover:underline">Become an Affiliate</a>
                    </p>
                </div>
            </div>

            <!-- Right Section (Banner) -->
            <div class="hidden md:block relative">
                <img src="{{ asset('assets/frontend/images/login-banner.png') }}" alt="Login Banner"
                    class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-r from-white/70 to-transparent"></div>
            </div>
        </div>
    </main>
    @vite('resources/js/app.js')
</body>

</html>