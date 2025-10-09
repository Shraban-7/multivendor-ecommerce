@extends('frontend.layouts.app')
@section('title', 'Verify Account | ' . $settings->app_name)
@section('content')
<main class="min-h-full bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full max-w-md space-y-8">
        <!-- Card Container -->
        <div class="bg-white shadow-xl rounded-2xl border border-gray-200 p-8 sm:p-10">
            <!-- Header -->
            <div class="text-center space-y-3 mb-6">
                <a href="{{ route('home') }}">
                    <img src="{{ storage_url($settings->logo_white) }}" alt="Logo" class="mx-auto h-12 sm:h-16 object-contain" />
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                    Verify Your Account
                </h1>
                <p class="text-gray-500 text-sm sm:text-base">
                    Enter the 6-character verification code sent to your email.
                </p>
            </div>

            <!-- Verification Form -->
            <form action="{{ route('verify') }}" method="POST" class="space-y-6">
                @csrf

                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <!-- Verification Code -->
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Verification Code</label>
                    <input type="text" name="code" id="code" maxlength="6" required
                        placeholder="Enter 6-character code"
                        class="mt-2 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-center uppercase tracking-widest text-lg font-semibold text-gray-800 placeholder-gray-400 transition duration-200" />
                    @error('code')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="w-full py-3 bg-yellow-500 text-white font-semibold rounded-lg hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-400 transition">
                    Verify Account
                </button>
            </form>

            <!-- Footer -->
            <div class="text-center text-sm text-gray-500 mt-6">
                Already verified your account?
                <a href="{{ route('login') }}" class="text-yellow-600 font-medium hover:underline">
                    Back to Login
                </a>
            </div>
        </div>
    </div>
</main>
@endsection
