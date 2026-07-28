@extends('seller.layouts.app')
@section('title', 'Ticket '.$ticket->ticket_number)
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">{{ $ticket->subject }}</h4>
            <small class="text-muted">{{ $ticket->ticket_number }} · created {{ $ticket->created_at->diffForHumans() }}</small>
        </div>
        <a href="{{ route('seller.support.index') }}" class="btn btn-sm btn-light border">← Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <span class="text-muted small">Status</span>
                <h5 class="fw-bold mb-0 mt-1"><span class="badge bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <span class="text-muted small">Priority</span>
                <h5 class="fw-bold mb-0 mt-1"><span class="badge bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span></h5>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <span class="text-muted small">Categorised as</span>
                <h6 class="fw-semibold mb-0 mt-1">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</h6>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm p-3">
                <span class="text-muted small">SLA Due</span>
                <h6 class="fw-semibold mb-0 mt-1 {{ $ticket->isOverdue() ? 'text-danger' : '' }}">
                    {{ optional($ticket->sla_due_at)->format('d/m/Y H:i') ?? '—' }}
                </h6>
                @if ($ticket->isOverdue()) <small class="text-danger">Overdue</small> @endif
            </div>
        </div>
    </div>

    @if (! $ticket->isClosed())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Reply</h6>
                <form method="POST" action="{{ route('seller.support.reply', $ticket) }}" enctype="multipart/form-data">
                    @csrf
                    <textarea name="body" class="form-control" rows="4" required maxlength="10000" placeholder="Type your reply..."></textarea>
                    <div class="d-flex gap-2 mt-2 align-items-center">
                        <input type="file" name="attachments[]" multiple class="form-control form-control-sm" style="max-width: 280px;">
                        <small class="text-muted">Max 10MB per file.</small>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-primary btn-sm">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Conversation</h6>
            <ul class="list-unstyled mb-0">
                @forelse ($ticket->messages as $message)
                    @if ($message->is_internal_note)
                        @continue
                    @endif
                    <li class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">
                                @if ($message->isFromSeller())
                                    You
                                @elseif ($message->isFromAdmin())
                                    {{ $message->adminSender()?->name ?? 'Admin' }}
                                @else
                                    System
                                @endif
                            </span>
                            <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-1 mt-1 small" style="white-space: pre-line;">{{ $message->body }}</p>
                        @if ($message->attachments->isNotEmpty())
                            <div class="mt-2">
                                @foreach ($message->attachments as $att)
                                    <a href="{{ $att->url() }}" target="_blank" class="badge bg-light text-dark border me-1">
                                        <i data-feather="paperclip" class="icon-xs"></i> {{ $att->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="text-muted small">No messages yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    @if ($ticket->isOpen() && config('marketplace.support.allow_self_resolve', true))
        <form method="POST" action="{{ route('seller.support.resolve', $ticket) }}" class="d-inline">
            @csrf
            <button class="btn btn-success btn-sm">Mark as resolved</button>
        </form>
    @endif

    @if ($ticket->isClosed())
        <form method="POST" action="{{ route('seller.support.reopen', $ticket) }}" class="d-inline">
            @csrf
            <button class="btn btn-warning btn-sm">Reopen ticket</button>
        </form>
    @endif
@endsection
