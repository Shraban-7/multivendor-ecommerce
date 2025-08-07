@extends('seller.layouts.app')
@section('title', 'Chat with User')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Chat with User</h4>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm h-100">
                <div class="card-body" style="height: 400px; overflow-y: auto;" id="chat-box">
                    @forelse ($messages as $msg)
                        <div class="d-flex {{ $msg->seller_id ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                            <div class="p-2 rounded bg-{{ $msg->seller_id ? 'primary text-white' : 'light' }} w-75">
                                <p class="mb-1">{{ $msg->message }}</p>
                                <small class="text-{{ $msg->seller_id ? 'white' : 'muted' }}">{{ $msg->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    @empty
                        <p class="text-center text-muted">No messages yet.</p>
                    @endforelse
                </div>

                <div class="card-footer bg-white">
                    <form id="send-message-form" method="POST" action="{{ route('seller.chat.send') }}">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                        <div class="input-group">
                            <input type="text" name="message" class="form-control" placeholder="Type your message..." required>
                            <button class="btn btn-primary" type="submit">Send</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
