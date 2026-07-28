@extends('admin.layouts.app')
@section('title', 'Ticket '.$ticket->ticket_number)
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">{{ $ticket->subject }}</h4>
            <small class="text-muted">{{ $ticket->ticket_number }} ·
                raised {{ $ticket->created_at->diffForHumans() }} ·
                SLA due {{ optional($ticket->sla_due_at)->format('d/m/Y H:i') ?? '—' }}
                @if ($ticket->isOverdue())
                    <span class="badge bg-danger">Overdue</span>
                @endif
            </small>
        </div>
        <a href="{{ route('admin.support.index') }}" class="btn btn-sm btn-light border">← Back</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-2">Ticket info</h6>
                    <table class="table table-borderless table-sm mb-0">
                        <tr><td class="text-muted">Status</td><td><span class="badge bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></td></tr>
                        <tr><td class="text-muted">Priority</td><td><span class="badge bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span></td></tr>
                        <tr><td class="text-muted">Category</td><td>{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</td></tr>
                        <tr><td class="text-muted">Reporter</td>
                            <td>
                                @if ($ticket->seller)
                                    {{ $ticket->seller->business_name }}<br><small class="text-muted">@{{ $ticket->seller->username }}</small>
                                @elseif ($ticket->user)
                                    {{ $ticket->user->name }}
                                @else
                                    <em class="text-muted">N/A</em>
                                @endif
                            </td>
                        </tr>
                        @if ($ticket->order)
                            <tr><td class="text-muted">Related order</td><td>#{{ $ticket->order->invoice_id }}</td></tr>
                        @endif
                    </table>

                    <hr>

                    <form method="POST" action="{{ route('admin.support.priority', $ticket) }}" class="mb-2">
                        @csrf
                        <label class="form-label small text-muted">Change priority</label>
                        <select name="priority" class="form-select form-select-sm" onchange="this.form.submit()">
                            @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                                <option value="{{ $p->value }}" @selected($ticket->priority === $p)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                    </form>

                    <form method="POST" action="{{ route('admin.support.status', $ticket) }}" class="mb-2">
                        @csrf
                        <label class="form-label small text-muted">Change status</label>
                        <div class="d-flex gap-2">
                            <select name="status" class="form-select form-select-sm">
                                @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $s)
                                    <option value="{{ $s->value }}" @selected($ticket->status === $s)>{{ $s->label() }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-sm btn-light border">Set</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.support.assign', $ticket) }}" class="mb-2">
                        @csrf
                        <label class="form-label small text-muted">Assign admin (id)</label>
                        <div class="d-flex gap-2">
                            <input name="assigned_admin_id" type="number" class="form-control form-control-sm" placeholder="admin id" min="1" value="{{ $ticket->assigned_admin_id ?? '' }}">
                            <button class="btn btn-sm btn-light border">Assign</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.support.selfAssign', $ticket) }}" class="mb-2">
                        @csrf
                        <button class="btn btn-sm btn-primary w-100">Assign to me</button>
                    </form>

                    @if (! $ticket->isClosed())
                        <form method="POST" action="{{ route('admin.support.resolve', $ticket) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-sm btn-success w-100">Mark as Resolved</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.support.reopen', $ticket) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-sm btn-warning w-100">Reopen</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Conversation</h6>
                    <ul class="list-unstyled mb-0">
                        @forelse ($ticket->messages as $message)
                            @if ($message->is_internal_note && ! $message->is_status_change)
                                <li class="mb-2 small text-muted fst-italic border-start border-2 ps-2">
                                    <i class="bi bi-lock"></i> Internal note (#{{ $message->id }}): {{ Str::limit($message->body, 100) }}
                                </li>
                                @continue
                            @endif
                            <li class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold">
                                        @if ($message->isFromSeller())
                                            <i data-feather="user" class="icon-xs"></i> {{ $ticket->seller->business_name ?? 'Seller' }}
                                        @elseif ($message->isFromAdmin())
                                            <i data-feather="shield" class="icon-xs"></i> {{ $message->adminSender()?->name ?? 'Admin' }}
                                            @if ($message->sender_id === auth()->id())
                                                <span class="badge bg-primary ms-1 small">You</span>
                                            @endif
                                        @else
                                            <i data-feather="settings" class="icon-xs"></i> System
                                        @endif
                                        @if ($message->is_status_change)
                                            <span class="badge bg-secondary ms-1 small">status change</span>
                                        @endif
                                        @if ($message->is_internal_note)
                                            <span class="badge bg-warning text-dark ms-1 small">internal</span>
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

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="fw-bold mb-3">Reply</h6>
                    <form method="POST" action="{{ route('admin.support.reply', $ticket) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-check form-switch mb-2">
                            <input type="checkbox" name="is_internal" value="1" class="form-check-input" id="isInternal">
                            <label class="form-check-label" for="isInternal">Internal note (not visible to seller)</label>
                        </div>
                        <textarea name="body" class="form-control" rows="5" required maxlength="10000" placeholder="Reply to {{ $ticket->seller?->business_name ?? 'customer' }}..."></textarea>
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
        </div>
    </div>

    @if ($ticket->events->isNotEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Activity Log</h6>
            <ul class="list-unstyled mb-0 small">
                @foreach ($ticket->events as $event)
                    <li class="border-bottom py-2">
                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $event->type)) }}</span>
                        @if ($event->from_value || $event->to_value)
                            <span class="text-muted ms-2">{{ $event->from_value ?? '—' }} → {{ $event->to_value ?? '—' }}</span>
                        @endif
                        <span class="text-muted ms-2">· by {{ $event->actor_type }} #{{ $event->actor_id }} · {{ $event->created_at->diffForHumans() }}</span>
                        @if ($event->note) <div class="text-muted">{{ $event->note }}</div> @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
@endsection
