@extends('seller.layouts.app')
@section('title', 'Chat with User')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="fw-bold mb-0 text-dark">Chat with User</h4>
        <a href="{{ route('seller.chat.index') }}" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" class="icon-xs"></i> Back
        </a>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-box">
                    @forelse ($messages as $msg)
                        <div class="d-flex {{ $msg->seller_id ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                            <div class="p-3 rounded-3 {{ $msg->seller_id ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 75%;">
                                <p class="mb-1">{{ $msg->message }}</p>
                                <small class="{{ $msg->seller_id ? 'text-white-50' : 'text-muted' }}">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted mt-4">No messages yet. Start the conversation!</p>
                    @endforelse
                </div>

                <div class="card-footer bg-white">
                    <form id="send-message-form" method="POST" action="{{ route('seller.chat.send') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                            <button class="btn btn-primary d-inline-flex align-items-center gap-1" type="submit">
                                <i data-feather="send" class="icon-xs"></i> Send
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
