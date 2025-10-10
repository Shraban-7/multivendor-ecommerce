@extends('frontend.auth.layout')
@section('title', 'login')

@section('content')

@php
$settings = settings();
@endphp

<div class="w-full max-w-lg bg-white shadow-lg rounded-2xl border border-gray-200 p-6 sm:p-8 space-y-6">
    <div class="text-center space-y-2">
        <a href="{{ route('home') }}">
            <img src="{{ storage_url($settings->logo_white) }}" alt="Logo"
                class="mx-auto h-10 sm:h-12 object-contain" />
        </a>
        <h1 class="text-xl sm:text-3xl font-bold text-gray-900">
            Create Your Account
        </h1>
        <p class="text-gray-600 text-sm">Join our community and unlock exclusive features.</p>
    </div>

    <form action="{{ route('signup') }}" method="POST" class="space-y-5">
        @csrf
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" name="name" id="name" required class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
        </div>

        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" required class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
        </div>

        <div>
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
            <input type="text" name="phone" id="phone" required placeholder="01XXXXXXXXXX"
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" id="password" required placeholder="••••••••"
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required
                placeholder="••••••••"
                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-400 focus:border-yellow-400 text-sm" />
        </div>

        <input type="hidden" name="role"
            value="{{ request('role') === App\Enums\UserRole::AFFILIATE->label()
              ? App\Enums\UserRole::AFFILIATE->label()
              : App\Enums\UserRole::CUSTOMER->label() }}">

        <div class="flex items-start text-sm text-gray-700">
            <input type="checkbox" id="terms" required
                class="h-4 w-4 text-yellow-500 focus:ring-yellow-400 border-gray-300 rounded" />
            <label for="terms" class="ml-2">
                I agree to the
                <a href="#" class="text-yellow-600 hover:underline">Terms</a>
                and
                <a href="#" class="text-yellow-600 hover:underline">Privacy Policy</a>.
            </label>
        </div>

        
        <button type="submit" class="w-full py-3 px-4 bg-yellow-500 hover:bg-yellow-400 text-white font-medium rounded-lg text-sm transition-colors duration-200 
            focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-offset-2">
            Register
        </button>
    </form>

    <!-- Divider -->
    <!-- <div class="flex items-center my-6">
        <span class="flex-1 h-px bg-gray-300"></span>
        <span class="px-3 text-gray-400 text-sm">OR</span>
        <span class="flex-1 h-px bg-gray-300"></span>
    </div>
    <div class="flex gap-3">
        <button
            class="flex-1 flex items-center justify-center gap-2 border border-gray-300 rounded-lg py-2 hover:bg-gray-50 transition">
            <img src="{{ asset('assets/frontend/images/google-icon.png') }}" class="h-5 w-5" />
            <span class="text-sm">Sign up with Google</span>
        </button>
    </div> -->

    <div class="text-center text-sm text-gray-600">
        Already have an account?
        <a href="{{ route('login') }}" class="text-yellow-600 hover:underline">Login here</a>
    </div>
</div>
@endsection