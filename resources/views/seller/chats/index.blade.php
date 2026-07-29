@extends('seller.layouts.app')
@section('title', 'Chat List')
@section('content')

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div class="col-span-full md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                <h5 class="mb-0 font-semibold">Chats</h5>
            </div>

            <div class="flex flex-col">
                @forelse ($chats as $chat)
                    @php
                        $lastMessage = $chat->messages->first();
                        $user = $chat->user;
                        $avatar = $user->avatar ?? null;
                        $initials = strtoupper(substr($user->name, 0, 1));
                    @endphp

                    <a href="{{ route('seller.chat.messages', ['user_id' => $user->id]) }}"
                       class="list-group-item-action flex items-center gap-3 py-3 px-3 border-b border-border">

                        @if ($avatar)
                            <img src="{{ $avatar }}" alt="Avatar"
                                 class="rounded-full" width="40" height="40" style="object-fit: cover;">
                        @else
                            <div class="icon-bg-primary rounded-full flex items-center justify-center"
                                 style="width: 40px; height: 40px; font-weight: bold;">
                                {{ $initials }}
                            </div>
                        @endif

                        <div class="grow min-w-0">
                            <div class="flex justify-between">
                                <strong class="truncate">{{ $user->name }}</strong>
                                <small class="text-ink-tertiary shrink-0">{{ $lastMessage?->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-ink-tertiary text-sm truncate">
                                {{ $lastMessage ? Str::limit($lastMessage->message, 60) : 'No messages yet' }}
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-4 text-center text-ink-tertiary">
                        No chats found.
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection