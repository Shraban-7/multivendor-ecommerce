@extends('frontend.layouts.app')
@section('title', 'login')

<?php
$settings = settings();
?>

@section('content')

<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="hidden lg:block">
            <img src="{{ asset('assets/frontend/images/login-illustration.png') }}" alt="Illustration" class="object-cover w-full h-full" />
        </div>
        <div class="p-6 sm:p-8 lg:p-10 xl:p-12 flex flex-col justify-center">
            <div class="mb-8 text-center lg:text-left">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Sign in to your account</h1>
                <p class="text-sm sm:text-base text-gray-600">Welcome back! Please enter your details.</p>
            </div>

            <x-frontend.flash-message />

            <form action="{{ route('login') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <x-frontend.form-label for="email">Email address</x-frontend.form-label>
                    <x-frontend.input type="email" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" required />
                </div>

                <div>
                    <x-frontend.form-label for="password">Password</x-frontend.form-label>
                    <x-frontend.input type="password" id="password" name="password" placeholder="Enter your password" required />
                </div>

                <div class="flex items-center justify-between text-sm">
                    <x-frontend.checkbox id="remember" type="checkbox">Remember me</x-frontend.form-label>
                        <a href="{{ route('password.forgot') }}" class="font-medium text-yellow-600 hover:underline">
                            Forgot password?
                        </a>
                </div>

                <x-frontend.button type="submit" :color="'yellow'">Sign in</x-frontend.button>
            </form>

            <div class="flex items-center my-6">
                <div class="flex-1 border-t border-gray-300"></div>
                <span class="px-4 text-xs text-gray-500 font-medium">OR</span>
                <div class="flex-1 border-t border-gray-300"></div>
            </div>

            <div class="text-center mb-4">
                <p class="text-sm text-gray-600">
                    Don't have an account?
                    <a href="{{ route('signup') }}" class="text-yellow-600 hover:underline font-medium">Sign up</a>
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-5 border-t border-gray-200">
                <a
                    href="{{ route('seller.signup') }}"
                    class="flex items-center justify-center gap-2 py-2.5 px-4
                           bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm
                           transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    Become a seller
                </a>

                <a
                    href="{{ route('signup') }}?role={{ App\Enums\UserRole::AFFILIATE->label() }}"
                    class="flex items-center justify-center gap-2 py-2.5 px-4
                           bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg text-sm
                           transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Join as affiliate
                </a>
            </div>
        </div>
    </div>
</div>
@endsection