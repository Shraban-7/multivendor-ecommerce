@extends('seller.layouts.app')
@section('title', 'Notifications')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 justify-start">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h4 class="font-bold mb-0 text-ink">Notifications</h4>
                </div>

                <div class="p-5">
                    @if ($notifications->count())
                        <ul class="list-none mb-0">
                            @foreach ($notifications as $notification)
                                <li
                                    class="border rounded-xs mb-3 p-3
                                           @if (!$notification->is_read) bg-surface-muted border-brand @else bg-white @endif">
                                    <div class="flex justify-between items-start">
                                        <h5 class="mb-1 text-ink">
                                            {{ $notification->title }}
                                        </h5>
                                        @if (!$notification->is_read)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">New</span>
                                        @endif
                                    </div>

                                    <p class="mb-2 text-ink-tertiary">
                                        {{ $notification->message }}
                                    </p>

                                    {{-- Uncomment if linking to a target --}}
                                    {{-- @if ($notification->target_type && $notification->target_id)
                                        <a href="{{ route('target.route', [$notification->target_type, $notification->target_id]) }}"
                                           class="no-underline text-brand text-sm">
                                           View Details
                                        </a>
                                    @endif --}}

                                    <div class="text-ink-tertiary text-sm mt-2">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-ink-tertiary py-5">
                            <i data-lucide="bell-off" class="icon-lg mb-3"></i>
                            <p class="mb-0">No notifications found.</p>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
