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

<body class="bg-gray-100 min-h-screen">
    <main class="min-h-screen flex items-center justify-center p-4 sm:p-6 lg:p-8">
        <div class="w-full max-w-6xl grid lg:grid-cols-2 gap-0 bg-white rounded-2xl shadow-xl overflow-hidden">

            <div class="p-6 sm:p-8 lg:p-10 xl:p-12 flex flex-col justify-center">
                <div class="mb-6">
                    <a href="{{ route('home') }}" class="inline-block">
                        <img src="{{ storage_url($settings->logo_white) }}"
                            alt="{{ $settings->app_name }}"
                            class="h-16 sm:h-16 object-contain" />
                    </a>
                </div>
                
                <div class="mb-6">
                    <h1 class="text-xl sm:text-3xl font-bold text-gray-900 mb-2">
                        Welcome back
                    </h1>
                    <p class="text-sm sm:text-base text-gray-600">
                        Sign in to your account to continue
                    </p>
                </div>
                
                @if (session('error') || session('success') || session('warning'))
                <div id="alert-border" class="mb-6 flex items-start gap-3 p-4 rounded-lg border-l-4 
                        @if (session('error')) bg-red-50 border-red-500 text-red-800
                        @elseif (session('success')) bg-green-50 border-green-500 text-green-800
                        @elseif (session('warning')) bg-amber-50 border-amber-500 text-amber-800 @endif">
                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                    </svg>
                    <span class="text-sm flex-1">{{ session('error') ?? (session('success') ?? session('warning')) }}</span>
                    <button type="button"
                        class="text-current hover:opacity-70 transition-opacity ml-2"
                        data-dismiss-target="#alert-border">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" />
                        </svg>
                    </button>
                </div>
                @endif

               
                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf                    
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Email address
                        </label>
                        <input
                            required
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg 
                                   focus:ring-2 focus:ring-yellow-400 focus:border-transparent 
                                   transition-all duration-200 placeholder:text-gray-400" />
                    </div>
                   
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Password
                        </label>
                        <input
                            required
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            class="w-full px-4 py-2.5 text-sm border border-gray-300 rounded-lg 
                                   focus:ring-2 focus:ring-yellow-400 focus:border-transparent 
                                   transition-all duration-200 placeholder:text-gray-400" />
                    </div>

                    
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                id="remember"
                                type="checkbox"
                                class="w-4 h-4 text-yellow-500 border-gray-300 rounded 
                                       focus:ring-2 focus:ring-yellow-400 cursor-pointer" />
                            <span class="text-sm text-gray-700 select-none">Remember me</span>
                        </label>
                        <a href="{{ route('password.forgot') }}"
                            class="text-sm font-medium text-yellow-600 hover:text-yellow-500 transition-colors">
                            Forgot password?
                        </a>
                    </div>

                    <button
                        type="submit"
                        class="w-full py-3 px-4 bg-yellow-500 hover:bg-yellow-400 
                               text-white font-medium rounded-lg text-sm
                               transition-colors duration-200 
                               focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
                        Sign in
                    </button>
                </form>

                <div class="flex items-center my-5">
                    <div class="flex-1 border-t border-gray-300"></div>
                    <span class="px-4 text-xs text-gray-500 font-medium">OR</span>
                    <div class="flex-1 border-t border-gray-300"></div>
                </div>

                <div class="text-center mb-5">
                    <p class="text-sm text-gray-600">
                        Don't have an account? <a href="{{ route('signup') }}"
                            class="text-sm font-medium text-yellow-600 hover:text-yellow-500 transition-colors">
                            Signup
                        </a>
                    </p>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-5 border-t border-gray-200">
                    <a href="{{ route('seller.signup') }}"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 
                              bg-gray-100 hover:bg-gray-200 text-gray-700 
                              font-medium rounded-lg text-sm transition-colors duration-200
                              focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Become a seller
                    </a>
                    <a href="{{ route('signup') }}?role={{ App\Enums\UserRole::AFFILIATE->label() }}"
                        class="flex items-center justify-center gap-2 py-2.5 px-4 
                              bg-gray-100 hover:bg-gray-200 text-gray-700 
                              font-medium rounded-lg text-sm transition-colors duration-200
                              focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Join as affiliate
                    </a>
                </div>
            </div>

            <!-- Right Section - Banner -->
            <div class="hidden md:block relative bg-gradient-to-br from-yellow-400 to-yellow-500">
                <!-- Text Layer -->
                <div class="absolute inset-0 flex items-center justify-center p-12 z-20">
                    <div class="text-white text-center">
                        <h2 class="text-3xl xl:text-4xl font-bold mb-4">
                            Start Your Journey
                        </h2>
                        <p class="text-lg opacity-90">
                            Join thousands of users on {{ $settings->app_name }}
                        </p>
                    </div>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-400 to-yellow-500 opacity-80 z-10"></div>
                <img
                    src="{{ asset('assets/frontend/images/login-banner.png') }}"
                    alt="Login Banner"
                    class="w-full h-full object-cover z-0 opacity-80" />
            </div>

        </div>
    </main>

    @vite('resources/js/app.js')
</body>

</html>