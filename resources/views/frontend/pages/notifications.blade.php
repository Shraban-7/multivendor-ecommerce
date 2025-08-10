@extends('frontend.layouts.app')

@section('content')
<div>
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Notifications</h1>

    @if ($notifications->count() > 0)
    <div class="bg-white shadow-md rounded-lg divide-y divide-gray-200">
        @foreach ($notifications as $notification)
        <div class="p-5 flex items-start gap-4 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }}">
            <div class="flex-shrink-0 mt-1">
                @if(!$notification->is_read)
                <span class="inline-block w-3 h-3 bg-blue-500 rounded-full"></span>
                @else
                <span class="inline-block w-3 h-3 bg-gray-300 rounded-full"></span>
                @endif
            </div>

            <div class="flex-1">
                <h2 class="text-md font-semibold text-gray-800">
                    {{ $notification->title }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">
                    {{ $notification->message }}
                </p>
                <div class="text-xs text-gray-400 mt-2">
                    {{ $notification->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $notifications->links('pagination::tailwind') }}
    </div>
    @else
    <div class="text-center text-gray-500 py-12">
        <p>No notifications yet.</p>
    </div>
    @endif
</div>
@endsection