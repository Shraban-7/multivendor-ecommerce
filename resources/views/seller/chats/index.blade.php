@extends('seller.layouts.app')
@section('title', 'Chat List')
@section('content')

<div class="row">
    <div class="col-md-6 col-12">
        <div class="card shadow-sm">
            <div class="card-header py-2 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Chats</h5>
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
                       class="list-group-item list-group-item-action d-flex align-items-center gap-3 py-2 px-3">

                        @if ($avatar)
                            <img src="{{ $avatar }}" alt="Avatar"
                                 class="rounded-circle" width="40" height="40" style="object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px; font-weight: bold;">
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <strong class="text-truncate" style="max-width: 180px;">{{ $user->name }}</strong>
                                <small class="text-muted">{{ $lastMessage?->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-muted small text-truncate" style="max-width: 250px;">
                                {{ $lastMessage ? Str::limit($lastMessage->message, 60) : 'No messages yet' }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-3 text-center text-muted">
                        No chats found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection
