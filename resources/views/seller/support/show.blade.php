@extends('seller.layouts.app')
@section('title', 'Ticket '.$ticket->ticket_number)
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">{{ $ticket->subject }}</h4>
            <small class="text-ink-tertiary">{{ $ticket->ticket_number }} · created {{ $ticket->created_at->diffForHumans() }}</small>
        </div>
        <a href="{{ route('seller.support.index') }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors gap-1">← Back</a>
    </div>

    @if (session('success'))
        <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm alert-dismissible fade show">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 border border-feedback-danger/20 text-feedback-danger text-sm alert-dismissible fade show">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3">
                <span class="text-ink-tertiary text-sm">Status</span>
                <h5 class="font-bold mb-0 mt-1"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></h5>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3">
                <span class="text-ink-tertiary text-sm">Priority</span>
                <h5 class="font-bold mb-0 mt-1"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span></h5>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3">
                <span class="text-ink-tertiary text-sm">Categorised as</span>
                <h6 class="font-semibold mb-0 mt-1">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</h6>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3">
                <span class="text-ink-tertiary text-sm">SLA Due</span>
                <h6 class="font-semibold mb-0 mt-1 {{ $ticket->isOverdue() ? 'text-feedback-danger' : '' }}">
                    {{ optional($ticket->sla_due_at)->format('d/m/Y H:i') ?? '—' }}
                </h6>
                @if ($ticket->isOverdue()) <small class="text-feedback-danger">Overdue</small> @endif
            </div>
        </div>
    </div>

    @if (! $ticket->isClosed())
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
            <div class="p-5">
                <h6 class="font-bold mb-3">Reply</h6>
                <form method="POST" action="{{ route('seller.support.reply', $ticket) }}" enctype="multipart/form-data">
                    @csrf
                    <textarea name="body" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="4" required maxlength="10000" placeholder="Type your reply..."></textarea>
                    <div class="flex gap-2 mt-2 items-center">
                        <input type="file" name="attachments[]" multiple class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" style="max-width: 280px;">
                        <small class="text-ink-tertiary text-sm">Max 10MB per file.</small>
                    </div>
                    <div class="mt-3">
                        <button class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors gap-1">Send Reply</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 mb-4">
        <div class="p-5">
            <h6 class="font-bold mb-3">Conversation</h6>
            <ul class="list-none pl-0 mb-0">
                @forelse ($ticket->messages as $message)
                    @if ($message->is_internal_note)
                        @continue
                    @endif
                    <li class="border-b pb-3 mb-3">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">
                                @if ($message->isFromSeller())
                                    You
                                @elseif ($message->isFromAdmin())
                                    {{ $message->adminSender()?->name ?? 'Admin' }}
                                @else
                                    System
                                @endif
                            </span>
                            <small class="text-ink-tertiary">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <p class="mb-1 mt-1 text-sm" style="white-space: pre-line;">{{ $message->body }}</p>
                        @if ($message->attachments->isNotEmpty())
                            <div class="mt-2">
                                @foreach ($message->attachments as $att)
                                    <a href="{{ $att->url() }}" target="_blank" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink border border-border me-1">
                                        <i data-feather="paperclip" class="icon-xs"></i> {{ $att->original_name }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </li>
                @empty
                    <li class="text-ink-tertiary text-sm">No messages yet.</li>
                @endforelse
            </ul>
        </div>
    </div>

    @if ($ticket->isOpen() && config('marketplace.support.allow_self_resolve', true))
        <form method="POST" action="{{ route('seller.support.resolve', $ticket) }}" class="inline">
            @csrf
            <button class="inline-flex items-center justify-center px-3 py-1.5 bg-feedback-success text-white text-sm font-medium rounded-xs hover:bg-feedback-success/90 focus:outline-none focus:ring-2 focus:ring-feedback-success/30 disabled:opacity-50 transition-colors gap-1">Mark as resolved</button>
        </form>
    @endif

    @if ($ticket->isClosed())
        <form method="POST" action="{{ route('seller.support.reopen', $ticket) }}" class="inline">
            @csrf
            <button class="inline-flex items-center justify-center px-3 py-1.5 bg-feedback-warning text-white text-sm font-medium rounded-xs hover:bg-feedback-warning/90 focus:outline-none focus:ring-2 focus:ring-feedback-warning/30 disabled:opacity-50 transition-colors gap-1">Reopen ticket</button>
        </form>
    @endif
@endsection
