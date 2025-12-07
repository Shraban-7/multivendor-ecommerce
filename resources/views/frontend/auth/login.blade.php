@extends('frontend.layouts.app')
@section('title', 'Login')

<?php
$settings = settings();
?>

@section('content')
    <!-- ==================== LOGIN SECTION ==================== -->
    <main class="flex-1 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row h-full md:h-auto max-h-[800px]">

            <!-- Left Side: Form -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <div class="text-center md:text-left mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back!</h2>
                    <p class="text-gray-500 text-sm">Please enter your details to sign in.</p>
                </div>

                <form action="{{ route('login') }}" method="POST" class="space-y-5">
                    @csrf
                    <!-- Email Input -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400"><i class="far fa-envelope"></i></span>
                            <input type="email" name="email" placeholder="john@example.com" class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none transition text-sm">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <a href="{{ route('password.forgot') }}" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Forgot Password?</a>
                        </div>
                        <div class="relative">
                            <span class="absolute left-4 top-3.5 text-gray-400"><i class="fas fa-lock"></i></span>
                            <input type="password" name="password" placeholder="••••••••" class="w-full pl-10 pr-12 py-3 rounded-lg border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-100 outline-none transition text-sm" id="passwordInput">
                            <button type="button" class="absolute right-4 top-3.5 text-gray-400 hover:text-gray-600 cursor-pointer" onclick="togglePassword()">
                                <i class="far fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                        <label for="remember" class="ml-2 block text-sm text-gray-600 cursor-pointer">Remember me for 30 days</label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-primary-600 text-white font-bold py-3 rounded-lg hover:bg-primary-700 transition shadow-lg shadow-primary-500/30">
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="my-6 flex items-center gap-4">
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-xs text-gray-400 font-medium">OR CONTINUE WITH</span>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>

                <!-- Social Login -->
                <div class="grid grid-cols-2 gap-4">
                    <button class="flex items-center justify-center gap-2 border border-gray-200 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-700">
                        <i class="fab fa-google text-red-500"></i> Google
                    </button>
                    <button class="flex items-center justify-center gap-2 border border-gray-200 py-2.5 rounded-lg hover:bg-gray-50 transition text-sm font-medium text-gray-700">
                        <i class="fab fa-facebook text-blue-600"></i> Facebook
                    </button>
                </div>

                <!-- Sign Up Link -->
                <p class="text-center text-sm text-gray-600 mt-8">
                    Don't have an account?
                    <a href="{{ route('home') }}" class="text-primary-600 font-bold hover:underline">Create Account</a>
                </p>
            </div>

            <!-- Right Side: Decorative Image (Hidden on Mobile) -->
            <div class="hidden md:block w-1/2 bg-gray-900 relative">
                <img src="https://images.unsplash.com/photo-1555529669-e69e7aa0ba9a?q=80&w=800&auto=format&fit=crop" class="absolute inset-0 w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>

                <div class="absolute bottom-12 left-12 right-12 text-white">
                    <div class="w-12 h-1 bg-primary-500 mb-6 rounded-full"></div>
                    <h3 class="text-3xl font-bold mb-4 leading-tight">Shop the latest trends with <br> <span class="text-primary-500">Confidence</span></h3>
                    <p class="text-gray-300 text-sm leading-relaxed">Join over 2 million customers enjoying the best shopping experience in Bangladesh. Fast delivery, secure payment, and easy returns.</p>
                </div>
            </div>
        </div>
    </main>    

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>



<!-- <div class="flex items-center justify-center bg-gray-50 sm:px-4">
    <div class="w-full max-w-6xl grid lg:grid-cols-2 bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="hidden lg:block">
            <img src="{{ asset('assets/frontend/images/login-illustration.png') }}" alt="Illustration" class="object-cover w-full h-full" />
        </div>
        <div class="p-6 sm:p-8 lg:p-10 xl:p-12 flex flex-col justify-center">
            <div class="mb-8 text-center lg:text-left">
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Sign in to your account</h1>
                <p class="text-sm sm:text-base text-gray-600">Welcome back! Please enter your details.</p>
            </div>

            <x-frontend.flash-message />

            <form action="{{ route('home') }}" method="POST" class="space-y-5">
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
                    <a href="{{ route('home') }}" class="text-yellow-600 hover:underline font-medium">Sign up</a>
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
                    href="{{ route('home') }}?role={{ App\Enums\UserRole::AFFILIATE->label() }}"
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
</div> -->
@endsection