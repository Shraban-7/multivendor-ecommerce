@extends('frontend.layouts.app')
@section('title', session('message_data.title', 'Message'))

@section('content')
    <?php
        $data = session('message_data', []);

        $title = $data['title'] ?? 'Notice';
        $message = $data['message'] ?? 'No message provided.';
        $buttonText = $data['buttonText'] ?? 'Back to Home';
        $buttonUrl = $data['buttonUrl'] ?? route('home');
        $type = $data['type'] ?? 'info';

        $styles = [
            'success' => [
                'bg' => 'bg-green-100 text-green-700',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>'
            ],
            'error' => [
                'bg' => 'bg-red-100 text-red-700',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>'
            ],
            'warning' => [
                'bg' => 'bg-yellow-100 text-yellow-700',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 9v2m0 4h.01" /></svg>'
            ],
            'info' => [
                'bg' => 'bg-blue-100 text-blue-700',
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 9v2m0 4h.01" /></svg>'
            ],
        ];

        $icon = $styles[$type]['icon'] ?? '';
        $iconBg = $styles[$type]['bg'] ?? 'bg-gray-100 text-gray-700';
    ?>

    <div class="w-full max-w-3xl bg-white rounded-2xl shadow-xl overflow-hidden mx-auto text-center p-10 sm:p-12 lg:p-16 mt-12">
        <div class="flex flex-col items-center justify-center space-y-4">

            <div class="{{ $iconBg }} p-4 rounded-full inline-flex items-center justify-center">{!! $icon !!}</div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">{{ $title }}</h1>
            <p class="text-gray-600 text-sm sm:text-base max-w-md mt-2">{{ $message }}</p>

            <div class="pt-6">
                <a href="{{ $buttonUrl }}"
                   class="inline-flex items-center justify-center gap-2 px-6 py-3 bg-yellow-500 text-white font-medium text-sm rounded-lg hover:bg-yellow-400 transition-colors duration-200">
                    {{ $buttonText }}
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

        </div>
    </div>
@endsection
