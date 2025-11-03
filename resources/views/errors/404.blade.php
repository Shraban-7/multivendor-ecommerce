@extends('frontend.layouts.app')

@section('title', 'Page Not Found')

@section('content')
<main class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-b from-white to-gray-50 text-center px-5 py-10">
    <!-- Illustration -->
    <div class="w-56 h-56 mb-6">
        <img 
            src="{{ asset('assets/frontend/images/404-illustration.png') }}" 
            alt="Page Not Found Illustration" 
            class="object-contain w-full h-full"
            onerror="this.src='{{ asset('assets/frontend/images/assistant-robot.png') }}'"
        />
    </div>

    <!-- Title -->
    <h1 class="text-6xl md:text-8xl font-bold bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 bg-clip-text text-transparent mb-2">
        404
    </h1>

    <!-- Message -->
    <h2 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-3">
        Oops! Page Not Found
    </h2>

    <p class="text-gray-600 max-w-md mb-8">
        The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.
    </p>

    <!-- Action Buttons -->
    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
        <a href="{{ url('/') }}"
           class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg shadow transition-all duration-200">
            Go Back Home
        </a>
        <a href="{{ url()->previous() }}"
           class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg shadow transition-all duration-200">
            Go Back
        </a>
    </div>
</main>
@endsection
