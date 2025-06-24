@extends('frontend.layouts.app')
@section('title', 'Sign Up')

@section('content')
    <!-- Registration Page -->
    <main class="flex items-center justify-center px-4 py-10 bg-white">
        <div class="w-full max-w-lg bg-white border border-gray-200 shadow-md rounded-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-bold text-gray-900">Create Your Account</h1>
                <p class="text-gray-600 text-sm">Join Our Community and unlock exclusive features.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('signup') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Full Name -->
                <div class="form-ctrl space-y-1">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="name" id="name" required placeholder="John Doe"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-light-yellow focus:border-light-yellow text-sm" />
                </div>

                <!-- Email -->
                <div class="form-ctrl space-y-1">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" required placeholder="john@example.com"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-light-yellow focus:border-light-yellow text-sm" />
                </div>

                <!-- Password -->
                <div class="form-ctrl space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-light-yellow focus:border-light-yellow text-sm" />
                </div>

                <!-- Confirm Password -->
                <div class="form-ctrl space-y-1">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-light-yellow focus:border-light-yellow text-sm" />
                </div>

                <!-- Terms Agreement -->
                <div class="flex items-start text-sm text-gray-700">
                    <input type="checkbox" id="terms" required
                        class="h-4 w-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded" />
                    <label for="terms" class="ml-2">I agree to the
                        <a href="#" class="text-butterfly-blue hover:underline">Terms</a>
                        and
                        <a href="#" class="text-butterfly-blue hover:underline">Privacy Policy</a>.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="text-white bg-butterfly-blue py-2 md:py-3 w-full rounded-lg hover:bg-light-yellow eq">
                    Register
                </button>
            </form>

            <!-- Social Login -->
            <div class="social-login space-y-3">
                <div class="devider-sec flex flex-nowrap items-center justify-center text-davy-gray/80">
                    <span class="h-px bg-davy-gray/80 block flex-1"></span><span class="block px-3">OR</span><span
                        class="h-px bg-davy-gray/80 block flex-1"></span>
                </div>

                <div class="login-options flex flex-col lg:flex-row flex-wrap gap-5 text-davy-gray/80">
                    <button
                        class="px-1 py-2 flex-1 inline-flex gap-1 items-center justify-center border border-jet-gray/20 rounded hover:bg-jet-gray/10 eq">
                        <img src="{{ asset('assets/frontend/images/google-icon.png') }}" class="h-8 md:h-10 w-auto" />
                        <span>Login with Google</span>
                    </button>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center pt-4 border-t text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-butterfly-blue hover:underline">Login here</a>
            </div>
        </div>
    </main>
@endsection
