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
    <main class="min-h-screen my-auto flex items-center justify-center px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 w-full 2xl:max-w-6xl lg:max-w-4xl bg-white shadow-lg rounded-xl overflow-hidden">
            <!-- Left Section (Form) -->
            <div
                class="flex flex-col justify-center px-3 sm:px-4 md:px-6 lg:px-8 xl:px-10 2xl:px-12">
                <!-- Logo -->
                <div class="mb-3 sm:mb-4 md:mb-4 lg:mb-5 xl:mb-6">
                    <a href="{{ route('home') }}">
                        <img src="{{ storage_url($settings->logo_white) }}" alt="Logo"
                            class="h-6 sm:h-8 md:h-12 object-contain" />
                    </a>
                </div>

                <h1
                    class="text-xl sm:text-2xl md:text-2xl xl:text-4xl 2xl:text-4xl font-bold text-gray-900">
                    Welcome Back</h1>
                <p class="text-gray-500 mt-1 sm:mt-1.5 md:mt-1.5 lg:mt-2 text-xs sm:text-sm md:text-sm lg:text-base">
                    Login to continue to {{ $settings->app_name }}
                </p>

                @if (session('error') || session('success') || session('warning'))
                    <div id="alert-border"
                        class="mt-2 sm:mt-3 md:mt-3 lg:mt-4 flex items-center gap-1.5 sm:gap-2 px-2 sm:px-3 md:px-3 lg:px-4 py-1.5 sm:py-2 md:py-2 lg:py-3 rounded-lg text-xs sm:text-sm shadow-md
                @if (session('error')) bg-red-100 text-red-700 border-l-4 border-red-500
                @elseif (session('success')) bg-green-100 text-green-700 border-l-4 border-green-500
                @elseif (session('warning')) bg-yellow-100 text-yellow-700 border-l-4 border-yellow-500 @endif">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 md:w-4 md:h-4 lg:w-5 lg:h-5 shrink-0" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                        </svg>
                        <span class="flex-1">{{ session('error') ?? (session('success') ?? session('warning')) }}</span>
                        <button type="button" class="hover:text-black text-xs sm:text-sm"
                            data-dismiss-target="#alert-border">
                            ✕
                        </button>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST"
                    class="mt-3 sm:mt-4 md:mt-4 xl:mt-6 space-y-3 sm:space-y-4 md:space-y-4 lg:space-y-5">
                    @csrf
                    <div>
                        <label for="email"
                            class="block text-xs sm:text-sm md:text-sm lg:text-sm font-medium text-gray-700">Email</label>
                        <input required id="email" type="email" name="email" value="{{ old('email') }}"
                            class="mt-1 w-full px-2 sm:px-3 md:px-3 lg:px-4 py-1.5 sm:py-2 md:py-2 lg:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-xs sm:text-sm md:text-sm lg:text-sm" />
                    </div>
                    <div>
                        <label for="password"
                            class="block text-xs sm:text-sm md:text-sm lg:text-sm font-medium text-gray-700">Password</label>
                        <input required type="password" id="password" name="password"
                            class="mt-1 w-full px-2 sm:px-3 md:px-3 lg:px-4 py-1.5 sm:py-2 md:py-2 lg:py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-xs sm:text-sm md:text-sm lg:text-sm" />
                    </div>
                    <div class="flex items-center justify-between text-xs sm:text-sm md:text-sm">
                        <label class="flex items-center gap-1.5 sm:gap-2">
                            <input id="remember" type="checkbox"
                                class="h-3 w-3 sm:h-4 sm:w-4 md:h-4 md:w-4 text-yellow-500 focus:ring-yellow-400 border-gray-300 rounded" />
                            <span class="text-xs sm:text-sm md:text-sm">Remember Me</span>
                        </label>
                        <a href="{{ route('password.forgot') }}" class="text-yellow-600 hover:underline text-xs sm:text-sm md:text-sm">Forgot
                            Password?</a>
                    </div>
                    <button type="submit"
                        class="w-full py-2 sm:py-2.5 md:py-2.5 lg:py-3 xl:py-3 bg-yellow-500 text-white font-medium rounded-lg hover:bg-yellow-400 transition text-xs sm:text-sm md:text-sm lg:text-base">
                        Login
                    </button>
                </form>

                <div class="flex items-center my-3 sm:my-4 md:my-4 xl:my-6">
                    <span class="flex-1 h-px bg-gray-300"></span>
                    <span class="px-2 sm:px-3 text-gray-400 text-xs sm:text-sm md:text-sm">OR</span>
                    <span class="flex-1 h-px bg-gray-300"></span>
                </div>

                <!-- Links -->
                <div
                    class="text-center text-xs sm:text-sm md:text-sm lg:text-sm text-gray-600 space-y-1 sm:space-y-1.5 md:space-y-2 lg:space-y-2">
                    <p>
                        New to {{ $settings->app_name }}?
                        <a href="{{ route('signup') }}" class="text-yellow-600 hover:underline">Sign Up</a>
                    </p>
                    <p class="hidden xs:block sm:block">
                        Want to sell products?
                        <a href="{{ route('seller.signup') }}" class="text-yellow-600 hover:underline">Become a
                            Seller</a>
                    </p>
                    <p class="hidden xs:block sm:block">
                        Earn by referring people?
                        <a href="{{ route('signup') }}?role={{ App\Enums\UserRole::AFFILIATE->label() }}"
                            class="text-yellow-600 hover:underline">Become an Affiliate</a>
                    </p>
                    <!-- Condensed version for very small screens -->
                    <div class="block xs:hidden sm:hidden space-y-1">
                        <p>
                            <a href="{{ route('seller.signup') }}" class="text-yellow-600 hover:underline">Become a
                                Seller</a>
                        </p>
                        <p>
                            <a href="{{ route('signup') }}?role={{ App\Enums\UserRole::AFFILIATE->label() }}"
                                class="text-yellow-600 hover:underline">Become an Affiliate</a>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Right Section (Banner) -->
            <div class="hidden sm:hidden md:block relative overflow-hidden">
                <img src="{{ asset('assets/frontend/images/login-banner.png') }}" alt="Login Banner"
                    class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-r from-white/70 to-transparent"></div>
            </div>
        </div>
    </main>
    @vite('resources/js/app.js')
</body>

</html>
