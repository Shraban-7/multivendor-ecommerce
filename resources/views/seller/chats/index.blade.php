@extends('seller.layouts.app')
@section('title', 'Chat List')
@section('content')

<div class="row">
    <div class="col-md-6 col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">Chats</h5>
            </div>

            <div class="list-group list-group-flush">
                @forelse ($chats as $chat)
                    @php
                        $lastMessage = $chat->messages->first();
                        $user = $chat->user;
                        $avatar = $user->avatar ?? null;
                        $initials = strtoupper(substr($user->name, 0, 1));
                    @endphp

                    <a href="{{ route('seller.chat.messages', ['user_id' => $user->id]) }}"
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 px-3">

                        @if ($avatar)
                            <img src="{{ $avatar }}" alt="Avatar"
                                 class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        @else
                            <div class="icon-bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; font-weight: bold;">
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between">
                                <strong class="text-truncate">{{ $user->name }}</strong>
                                <small class="text-muted flex-shrink-0">{{ $lastMessage?->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-muted small text-truncate">
                                {{ $lastMessage ? Str::limit($lastMessage->message, 60) : 'No messages yet' }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-muted">
                        No chats found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
