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
                        <img src="{{ storage_url($settings->logo_white) }}" alt="Logo"
                            class="mx-auto h-12 sm:h-16 object-contain" />
                    </a>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">
                        Verify Your Account
                    </h1>
                    <p class="text-gray-500 text-sm sm:text-base">
                        Enter the 6-character verification code sent to your email.
                    </p>
                </div>

                <!-- Verification Form -->
                <form action="{{ route('password.reset') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <div>
                        <label>Verification Code</label>
                        <input type="text" name="code" maxlength="6" required
                            class="w-full px-4 py-2 border rounded uppercase text-center tracking-widest">
                    </div>
                    <div>
                        <label>New Password</label>
                        <input type="password" name="password" required class="w-full px-4 py-2 border rounded">
                    </div>
                    <div>
                        <label>Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                            class="w-full px-4 py-2 border rounded">
                    </div>
                    <button type="submit" class="w-full py-2 bg-yellow-500 text-white rounded hover:bg-yellow-400">Reset
                        Password</button>
                </form>


                <!-- Footer -->
                <div class="text-center text-sm text-gray-500 mt-6">
                    <a href="{{ route('login') }}" class="text-yellow-600 font-medium hover:underline">
                        Back to Login
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
