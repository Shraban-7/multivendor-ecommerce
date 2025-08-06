@extends('frontend.layouts.app')

@section('title', 'Payment Response')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100 px-4">
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full text-center">
        @if($status === \App\Models\Payment::SUCCESSFUL)
        <div class="flex flex-col items-center space-y-4">
            <div class="text-green-500">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-green-600">Payment Successful!</h2>
            <p class="text-gray-600">You will be redirected shortly...</p>
        </div>
        @else
        <div class="flex flex-col items-center space-y-4">
            <div class="text-red-500">
                <svg class="w-20 h-20" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
            <h2 class="text-2xl font-semibold text-red-600">Payment Failed!</h2>
            <p class="text-gray-600">You will be redirected shortly...</p>
        </div>
        @endif
    </div>
</div>

<script>
    setTimeout(function() {
        window.location.href = "{{ $return_url }}";
    }, 5000);
</script>
@endsection