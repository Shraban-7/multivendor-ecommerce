@extends('frontend.layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Notifications</h1>

    @if($notifications->count())
    <ul class="space-y-4">
        @foreach($notifications as $notification)
        <li class="p-4 rounded-lg shadow-sm border @if(!$notification->is_read) bg-blue-50 border-blue-200 @else bg-white @endif">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-800">
                    {{ $notification->title }}
                </h2>
                @if(!$notification->is_read)
                <span class="text-xs px-2 py-0.5 bg-blue-600 text-white rounded-full">New</span>
                @endif
            </div>

            <p class="text-gray-600 mt-1">
                {{ $notification->message }}
            </p>

            <!-- @if($notification->target_type && $notification->target_id)
            <a href=""
                class="text-sm text-blue-600 hover:underline mt-2 inline-block">
                View Details
            </a>
            @endif -->

            <div class="text-xs text-gray-400 mt-2">
                {{ $notification->created_at->diffForHumans() }}
            </div>
        </li>
        @endforeach
    </ul>
    @else
    <div class="text-center text-gray-500">
        <i class="fas fa-bell-slash text-4xl mb-2"></i>
        <p>No notifications found.</p>
    </div>
    @endif
</div>

@endsection