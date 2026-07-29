@extends('admin.layouts.app')
@section('title', 'Ticket '.$ticket->ticket_number)
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">{{ $ticket->subject }}</h4>
            <small class="text-ink-tertiary">{{ $ticket->ticket_number }} ·
                raised {{ $ticket->created_at->diffForHumans() }} ·
                SLA due {{ optional($ticket->sla_due_at)->format('d/m/Y H:i') ?? '—' }}
                @if ($ticket->isOverdue())
                    <span class="badge bg-feedback-danger">Overdue</span>
                @endif
            </small>
        </div>
        <a href="{{ route('admin.support.index') }}" class="btn btn-light btn-sm">← Back</a>
    </div>

    @if (session('success'))
        <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-3 mb-4">
        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="p-5">
                    <h6 class="font-bold mb-2">Ticket info</h6>
                    <table class="w-full text-left text-sm text-ink border-collapse border-0 text-sm mb-0">
                        <tr><td class="text-ink-tertiary">Status</td><td><span class="badge bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></td></tr>
                        <tr><td class="text-ink-tertiary">Priority</td><td><span class="badge bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span></td></tr>
                        <tr><td class="text-ink-tertiary">Category</td><td>{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</td></tr>
                        <tr><td class="text-ink-tertiary">Reporter</td>
                            <td>
                                @if ($ticket->seller)
                                    {{ $ticket->seller->business_name }}<br><small class="text-ink-tertiary">@{{ $ticket->seller->username }}</small>
                                @elseif ($ticket->user)
                                    {{ $ticket->user->name }}
                                @else
                                    <em class="text-ink-tertiary">N/A</em>
                                @endif
                            </td>
                        </tr>
                        @if ($ticket->order)
                            <tr><td class="text-ink-tertiary">Related order</td><td>#{{ $ticket->order->invoice_id }}</td></tr>
                        @endif
                    </table>

                    <hr>

                    <form method="POST" action="{{ route('admin.support.priority', $ticket) }}" class="mb-2">
                        @csrf
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm text-ink-tertiary">Change priority</label>
                        <select name="priority" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" onchange="this.form.submit()">
                            @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                                <option value="{{ $p->value }}" @selected($ticket->priority === $p)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                    </form>

                    <form method="POST" action="{{ route('admin.support.status', $ticket) }}" class="mb-2">
                        @csrf
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm text-ink-tertiary">Change status</label>
                        <div class="flex gap-2">
                            <select name="status" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $s)
                                    <option value="{{ $s->value }}" @selected($ticket->status === $s)>{{ $s->label() }}</option>
                                @endforeach
                            </select>
                            <button class="btn btn-light btn-sm">Set</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.support.assign', $ticket) }}" class="mb-2">
                        @csrf
                        <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm text-ink-tertiary">Assign admin (id)</label>
                        <div class="flex gap-2">
                            <input name="assigned_admin_id" type="number" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="admin id" min="1" value="{{ $ticket->assigned_admin_id ?? '' }}">
                            <button class="btn btn-light btn-sm">Assign</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.support.selfAssign', $ticket) }}" class="mb-2">
                        @csrf
                        <button class="btn btn-primary btn-sm">Assign to me</button>
                    </form>

                    @if (! $ticket->isClosed())
                        <form method="POST" action="{{ route('admin.support.resolve', $ticket) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-success btn-sm">Mark as Resolved</button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.support.reopen', $ticket) }}" class="mb-2">
                            @csrf
                            <button class="btn btn-warning btn-sm">Reopen</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4">
                <div class="p-5">
                    <h6 class="font-bold mb-3">Conversation</h6>
                    <ul class="list-none mb-0">
                        @forelse ($ticket->messages as $message)
                            @if ($message->is_internal_note && ! $message->is_status_change)
                                <li class="mb-2 text-sm text-ink-tertiary fst-italic border-l border-2 ps-2">
                                    <i class="bi bi-lock"></i> Internal note (#{{ $message->id }}): {{ Str::limit($message->body, 100) }}
                                </li>
                                @continue
                            @endif
                            <li class="border-b pb-3 mb-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-semibold">
                                        @if ($message->isFromSeller())
                                            <i data-feather="user" class="icon-xs"></i> {{ $ticket->seller->business_name ?? 'Seller' }}
                                        @elseif ($message->isFromAdmin())
                                            <i data-feather="shield" class="icon-xs"></i> {{ $message->adminSender()?->name ?? 'Admin' }}
                                            @if ($message->sender_id === auth()->id())
                                                <span class="badge bg-brand-deep ms-1 text-sm">You</span>
                                            @endif
                                        @else
                                            <i data-feather="settings" class="icon-xs"></i> System
                                        @endif
                                        @if ($message->is_status_change)
                                            <span class="badge bg-surface-muted ms-1 text-sm">status change</span>
                                        @endif
                                        @if ($message->is_internal_note)
                                            <span class="badge bg-feedback-warning text-ink ms-1 text-sm">internal</span>
                                        @endif
                                    </span>
                                    <small class="text-ink-tertiary">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                <p class="mb-1 mt-1 text-sm" style="white-space: pre-line;">{{ $message->body }}</p>
                                @if ($message->attachments->isNotEmpty())
                                    <div class="mt-2">
                                        @foreach ($message->attachments as $att)
                                            <a href="{{ $att->url() }}" target="_blank" class="badge bg-surface-muted text-ink border me-1">
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

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="p-5">
                    <h6 class="font-bold mb-3">Reply</h6>
                    <form method="POST" action="{{ route('admin.support.reply', $ticket) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="flex items-center gap-2 form-switch mb-2">
                            <input type="checkbox" name="is_internal" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" id="isInternal">
                            <label class="text-sm text-ink" for="isInternal">Internal note (not visible to seller)</label>
                        </div>
                        <textarea name="body" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="5" required maxlength="10000" placeholder="Reply to {{ $ticket->seller?->business_name ?? 'customer' }}..."></textarea>
                        <div class="flex gap-2 mt-2 items-center">
                            <input type="file" name="attachments[]" multiple class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" style="max-width: 280px;">
                            <small class="text-ink-tertiary">Max 10MB per file.</small>
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
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
        <div class="p-5">
            <h6 class="font-bold mb-3">Activity Log</h6>
            <ul class="list-none mb-0 text-sm">
                @foreach ($ticket->events as $event)
                    <li class="border-b py-2">
                        <span class="badge bg-surface-muted">{{ ucfirst(str_replace('_', ' ', $event->type)) }}</span>
                        @if ($event->from_value || $event->to_value)
                            <span class="text-ink-tertiary ms-2">{{ $event->from_value ?? '—' }} → {{ $event->to_value ?? '—' }}</span>
                        @endif
                        <span class="text-ink-tertiary ms-2">· by {{ $event->actor_type }} #{{ $event->actor_id }} · {{ $event->created_at->diffForHumans() }}</span>
                        @if ($event->note) <div class="text-ink-tertiary">{{ $event->note }}</div> @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif
@endsection
