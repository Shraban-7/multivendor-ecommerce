@extends('frontend.layouts.app')
@section('title', 'Sign Up')

@section('content')
    <!-- Registration Page -->
    <main class="flex items-center justify-center px-4 py-10 bg-white">
        <div class="w-full max-w-lg bg-white border border-gray-200 shadow-md rounded-2xl p-6 sm:p-8 space-y-6">
            <!-- Header -->
            <div class="text-center space-y-2">
                <h1 class="text-3xl font-bold text-gray-900">Create Your Account</h1>
                <p class="text-gray-600 text-sm">Join the Tesko Community and unlock exclusive features.</p>
            </div>

            <!-- Form -->
            <form action="{{ route('signup') }}" method="POST" class="space-y-5">
                @csrf

                <!-- Full Name -->
                <div class="form-ctrl space-y-1">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input type="text" name="fullname" id="name" required placeholder="John Doe"
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
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                        placeholder="••••••••"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-1 focus:ring-light-yellow focus:border-light-yellow text-sm" />
                </div>

                <!-- Terms Agreement -->
                <div class="flex items-start text-sm text-gray-700">
                    <input type="checkbox" id="terms" required
                        class="h-4 w-4 text-light-yellow focus:ring-light-yellow border-gray-300 rounded" />
                    <label for="terms" class="ml-2">I agree to Tesko's
                        <a href="#" class="text-butterfly-blue hover:underline">Terms</a>
                        and
                        <a href="#" class="text-butterfly-blue hover:underline">Privacy Policy</a>.
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-2 text-white bg-light-yellow rounded-lg hover:bg-yellow-400 transition-all text-sm font-medium shadow-sm">
                    Register
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center pt-4 border-t text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('login') }}" class="text-butterfly-blue hover:underline">Login here</a>
            </div>
        </div>
    </main>
@endsection
