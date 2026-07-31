@extends('seller.layouts.app')
@section('title', 'Chat with User')
@section('content')

    <section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
        <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #8b5cf6, #a78bfa, #c4b5fd);"></div>
        <div class="p-5 lg:p-6 pt-6">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="min-w-0">
                    <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                        <i data-lucide="message-square" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                        <a href="{{ route('seller.chat.index') }}" class="hover:text-ink transition-colors">Chats</a>
                        <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                        <span class="text-ink-soft font-semibold">Conversation</span>
                    </nav>
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <h1 class="text-xl font-bold text-ink-emphasis mb-0">Chat with User</h1>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-violet-50 text-violet-700">
                            <i data-lucide="message-square" style="width:11px;height:11px;" class="me-1"></i> Live Chat
                        </span>
                    </div>
                    <p class="text-sm text-ink-secondary mb-0">Reply to your customer and keep the conversation going.</p>
                </div>
                <div class="flex flex-wrap gap-2 shrink-0">
                    <a href="{{ route('seller.chat.index') }}" class="btn btn-light btn-sm">
                        <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1">
        <div class="col-span-full">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
                <div class="px-4 py-3 border-b border-border bg-surface-muted">
                    <h5 class="text-sm font-semibold text-ink mb-0">Messages</h5>
                </div>
                <div class="p-5" style="height: 400px; overflow-y: auto;" id="chat-box">
                    @forelse ($messages as $msg)
                        <div class="flex {{ $msg->seller_id ? 'justify-end' : 'justify-start' }} mb-2">
                            <div class="p-3 rounded-md {{ $msg->seller_id ? 'bg-brand-deep text-white' : 'bg-surface-muted' }}" style="max-width: 75%;">
                                <p class="mb-1">{{ $msg->message }}</p>
                                <small class="{{ $msg->seller_id ? 'text-white/50' : 'text-ink-tertiary' }}">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-ink-tertiary mt-4">No messages yet. Start the conversation!</p>
                    @endforelse
                </div>

                <div class="px-5 py-3 border-t border-border bg-white">
                    <form id="send-message-form" method="POST" action="{{ route('seller.chat.send') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <div class="flex">
                            <input type="text" name="message" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Type your message..." required>
                            <button class="btn btn-primary" type="submit">
                                <i data-lucide="send" class="icon-xs"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection