@extends('frontend.layouts.app')
@section('title', 'Verify Account | ' . $settings->app_name)
@section('content')

<div class="max-w-md mx-auto mt-10 bg-white rounded-lg shadow-lg p-6">
    <h2 class="text-2xl font-bold text-orange-600 mb-4">Forgot your password?</h2>
    <p class="text-sm text-gray-600 mb-6">Enter your email address and we'll send you a link to reset your password.</p>

    <form action="{{ route('password.forgot') }}" method="POST">
        @CSRF
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email address</label>
            <input type="email" id="email" name="email" required
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
        </div>

        <button type="submit"
            class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-medium py-2 px-4 rounded-md transition">
            Send Verification Code
        </button>

        <x-frontend.flash-message/>
    </form>
</div>

@endsection