@extends('seller.layouts.app')
@section('title', 'Chat with User')
@section('content')

    <div class="mb-3 flex justify-between items-end">
        <h4 class="font-bold mb-0 text-ink">Chat with User</h4>
        <a href="{{ route('seller.chat.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" class="icon-xs"></i> Back
        </a>
    </div>

    <div class="grid grid-cols-1">
        <div class="col-span-full">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden" style="border-radius: 12px;">
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
                            <input type="text" name="message" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Type your message..." required>
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