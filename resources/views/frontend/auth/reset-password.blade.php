@extends('frontend.layouts.app')
@section('title', 'Verify Account | ' . $settings->app_name)
@section('content')

<div class="max-w-md mx-auto mt-10 bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-orange-600 mb-4">Reset Password</h2>
    
    <div class="mb-4 px-4 py-3 bg-yellow-100 border border-yellow-300 text-yellow-800 text-sm rounded-md">
        A verification code was sent to: <strong>{{ $email }}</strong>
    </div>

    <p class="text-sm text-gray-600 mb-6">
        Enter your verification code and new password below to reset your password.
    </p>

    <form action="{{ route('password.reset') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="verification_code" class="block text-sm font-medium text-gray-700 mb-1">Verification Code</label>
            <input type="text" id="verification_code" name="verification_code" required autocomplete="one-time-code"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
        </div>

        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
            <input type="password" id="password" name="password" required autocomplete="new-password"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
        </div>

        <button type="submit"
            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded-md transition">
            Reset Password
        </button>

        <x-frontend.flash-message />
    </form>
</div>

@endsection