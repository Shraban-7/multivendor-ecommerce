@extends('frontend.layouts.app')
@section('title', 'Thank You')

@section('content')
    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden mx-auto text-center p-10 sm:p-12 lg:p-16 mt-12">
        <div class="flex flex-col items-center justify-center space-y-4">
            <div class="bg-green-100 text-green-700 p-4 rounded-full inline-flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Thank You for Registering!</h1>

            <p class="text-gray-600 text-sm sm:text-base max-w-md mt-2">
                We’ve sent a confirmation email to your registered address.  
                Please check your inbox to complete your account approval process.
            </p>

            <div class="pt-6">
                <a href="{{ route('home') }}"
                    class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-yellow-500 text-white font-medium text-sm rounded-lg hover:bg-yellow-400 transition-colors duration-200">
                    Go to Home
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
@endsection
