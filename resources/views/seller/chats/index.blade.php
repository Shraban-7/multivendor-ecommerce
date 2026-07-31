@extends('seller.layouts.app')
@section('title', 'Chat List')
@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #8b5cf6, #a78bfa, #c4b5fd);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="message-square" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Support</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Chats</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Chats</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-violet-50 text-violet-700">
                        <i data-lucide="message-square" style="width:11px;height:11px;" class="me-1"></i> {{ $chats->count() }} Conversations
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Talk directly with your customers in real time.</p>
            </div>
        </div>
    </div>
</section>

<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
    <div class="col-span-full md:col-span-1">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
            <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                <h5 class="mb-0 font-semibold text-ink">Conversations</h5>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-violet-50 text-violet-700">{{ $chats->count() }}</span>
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