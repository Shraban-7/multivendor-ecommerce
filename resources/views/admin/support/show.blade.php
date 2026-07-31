@php
    use App\Domain\Support\Enums\TicketStatus;
    use App\Domain\Support\Enums\TicketPriority;

    $pageTitle = 'Ticket '.$ticket->ticket_number;

    $statusMap = [
        TicketStatus::OPEN->value => ['label' => 'Open',         'pill' => 'bg-amber-500 text-white',  'icon' => 'inbox'],
        TicketStatus::IN_PROGRESS->value => ['label' => 'In Progress', 'pill' => 'bg-blue-500 text-white', 'icon' => 'loader'],
        TicketStatus::AWAITING_SELLER->value => ['label' => 'Awaiting Seller', 'pill' => 'bg-cyan-500 text-white', 'icon' => 'user-check'],
        TicketStatus::AWAITING_ADMIN->value => ['label' => 'Awaiting Admin', 'pill' => 'bg-cyan-500 text-white', 'icon' => 'shield'],
        TicketStatus::RESOLVED->value => ['label' => 'Resolved', 'pill' => 'bg-emerald-500 text-white', 'icon' => 'check-circle'],
        TicketStatus::CLOSED->value   => ['label' => 'Closed', 'pill' => 'bg-gray-500 text-white', 'icon' => 'archive'],
        TicketStatus::REJECTED->value => ['label' => 'Rejected', 'pill' => 'bg-rose-500 text-white', 'icon' => 'ban'],
    ];

    $priorityMap = [
        TicketPriority::LOW->value    => ['label' => 'Low',     'pill' => 'bg-surface-muted text-ink-emphasis'],
        TicketPriority::NORMAL->value => ['label' => 'Normal',  'pill' => 'bg-info-tint text-feedback-info'],
        TicketPriority::HIGH->value   => ['label' => 'High',    'pill' => 'bg-warning-tint text-feedback-warning'],
        TicketPriority::URGENT->value => ['label' => 'Urgent',  'pill' => 'bg-rose-500 text-white'],
    ];

    $currentStatus  = $statusMap[$ticket->status] ?? $statusMap[TicketStatus::OPEN->value];
    $currentPriority = $priorityMap[$ticket->priority] ?? $priorityMap[TicketPriority::NORMAL->value];
@endphp
@extends('admin.layouts.app')
@section('title', $pageTitle)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="life-buoy" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <a href="{{ route('admin.support.index') }}" class="hover:text-ink-soft transition-colors">Support Tickets</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $ticket->ticket_number }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $ticket->subject }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-white {{ $currentStatus['pill'] }}">
                        <i data-lucide="{{ $currentStatus['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $ticket->statusLabel() }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider {{ $currentPriority['pill'] }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                    @if ($ticket->isOverdue())
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500 text-white">
                            <i data-lucide="alert-triangle" style="width:11px;height:11px;" class="me-1"></i> SLA Overdue
                        </span>
                    @endif
                </div>
                <p class="text-sm text-ink-secondary mb-0">
                    Raised {{ $ticket->created_at->diffForHumans() }} ·
                    SLA due {{ optional($ticket->sla_due_at)->format('d M Y, H:i') ?? '—' }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.support.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back
                </a>
                @if (! $ticket->isClosed())
                    <form method="POST" action="{{ route('admin.support.resolve', $ticket) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i data-lucide="check-circle" class="icon-xs"></i> Mark Resolved
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('admin.support.reopen', $ticket) }}" class="inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i data-lucide="rotate-ccw" class="icon-xs"></i> Reopen
                        </button>
                    </form>
                @endif
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#selfAssignModal">
                    <i data-lucide="user-plus" class="icon-xs"></i> Self-assign
                </button>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <section class="bg-emerald-50 rounded-sm p-4 mb-4 flex items-start gap-3 text-feedback-success text-sm">
        <i data-lucide="check-circle" style="width:18px;height:18px;" class="shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </section>
@endif
@if (session('error'))
    <section class="bg-rose-50 rounded-sm p-4 mb-4 flex items-start gap-3 text-rose-600 text-sm">
        <i data-lucide="alert-triangle" style="width:18px;height:18px;" class="shrink-0 mt-0.5"></i>
        <span>{{ session('error') }}</span>
    </section>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">

    {{-- ═══ TICKET INFO SIDEBAR ═══ --}}
    <div class="lg:col-span-1 space-y-3">
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="file-text" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Ticket info</h5>
            </div>
            <div class="p-5 space-y-3">
                <div>
                    <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Status</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white {{ $currentStatus['pill'] }}">
                        <i data-lucide="{{ $currentStatus['icon'] }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $ticket->statusLabel() }}
                    </span>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Priority</p>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $currentPriority['pill'] }}">
                        {{ ucfirst($ticket->priority) }}
                    </span>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Category</p>
                    <p class="text-ink-soft mb-0">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Reporter</p>
                    @if ($ticket->seller)
                        <p class="text-ink-emphasis font-semibold mb-0">{{ $ticket->seller->business_name }}</p>
                        <small class="text-ink-tertiary">@{{ $ticket->seller->username }}</small>
                    @elseif ($ticket->user)
                        <p class="text-ink-emphasis font-semibold mb-0">{{ $ticket->user->name }}</p>
                    @else
                        <p class="text-ink-tertiary mb-0">N/A</p>
                    @endif
                </div>
                @if ($ticket->order)
                    <div>
                        <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Related order</p>
                        <p class="text-ink-soft font-mono mb-0">#{{ $ticket->order->invoice_id }}</p>
                    </div>
                @endif
                <div>
                    <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Assigned admin</p>
                    @if ($ticket->admin)
                        <div class="flex items-center gap-2">
                            <span class="shrink-0 w-7 h-7 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center font-bold text-xs">
                                {{ mb_substr($ticket->admin->name, 0, 1) }}
                            </span>
                            <span class="text-ink-emphasis font-medium">{{ $ticket->admin->name }}</span>
                        </div>
                    @else
                        <p class="text-ink-tertiary italic mb-0">Unassigned</p>
                    @endif
                </div>
                <div>
                    <p class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">SLA</p>
                    @if ($ticket->sla_due_at)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $ticket->isOverdue() ? 'bg-rose-500 text-white' : 'bg-info-tint text-feedback-info' }}">
                            <i data-lucide="{{ $ticket->isOverdue() ? 'alert-triangle' : 'clock' }}" style="width:11px;height:11px;" class="me-1"></i>
                            {{ $ticket->sla_due_at->format('d M Y, H:i') }}
                        </span>
                    @else
                        <span class="text-ink-tertiary">—</span>
                    @endif
                </div>
            </div>
        </section>

        {{-- Quick actions --}}
        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="sliders-horizontal" class="text-feedback-info" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Quick Actions</h5>
            </div>
            <div class="p-5 space-y-3">
                <form method="POST" action="{{ route('admin.support.priority', $ticket) }}">
                    @csrf
                    <label class="block text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Change priority</label>
                    <select name="priority" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" onchange="this.form.submit()">
                        @foreach (TicketPriority::cases() as $p)
                            <option value="{{ $p->value }}" @selected($ticket->priority === $p)>{{ $p->label() }}</option>
                        @endforeach
                    </select>
                </form>
                <form method="POST" action="{{ route('admin.support.assign', $ticket) }}">
                    @csrf
                    <label class="block text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider mb-1">Assign admin</label>
                    <div class="flex gap-2">
                        <input name="assigned_admin_id" type="number" min="1"
                               value="{{ $ticket->assigned_admin_id ?? '' }}"
                               placeholder="admin id"
                               class="flex-1 px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                        <button class="btn btn-light btn-sm">Set</button>
                    </div>
                </form>
                <button class="btn btn-info btn-sm w-full" data-bs-toggle="modal" data-bs-target="#statusModal">
                    <i data-lucide="refresh-cw" class="icon-xs"></i> Change Status
                </button>
            </div>
        </section>
    </div>

    {{-- ═══ CONVERSATION + REPLY ═══ --}}
    <div class="lg:col-span-2 space-y-3">

        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="messages-square" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Conversation</h5>
                <span class="text-ink-tertiary text-xs ms-auto">{{ $ticket->messages->count() }} {{ Str::plural('message', $ticket->messages->count()) }}</span>
            </div>
            <div class="p-5">
                @forelse ($ticket->messages as $message)
                    @if ($message->is_internal_note && ! $message->is_status_change)
                        <div class="mb-2 px-3 py-2 text-xs text-ink-tertiary italic bg-amber-50 rounded-xs flex items-start gap-2">
                            <i data-lucide="lock" style="width:14px;height:14px;" class="shrink-0 mt-0.5"></i>
                            <span>Internal note: {{ Str::limit($message->body, 160) }}</span>
                        </div>
                        @continue
                    @endif
                    <div class="flex gap-3 {{ $loop->last ? '' : 'mb-4 pb-4 border-b border-border' }}">
                        <span class="shrink-0 w-9 h-9 rounded-full
                            {{ $message->isFromAdmin() ? 'bg-info-tint text-feedback-info' :
                               ($message->isFromSeller() ? 'bg-brand-tint text-brand-deep' : 'bg-surface-muted text-ink-soft') }}
                            flex items-center justify-center font-bold text-xs">
                            @if ($message->isFromAdmin())
                                <i data-lucide="shield" style="width:14px;height:14px;"></i>
                            @elseif ($message->isFromSeller())
                                <i data-lucide="user" style="width:14px;height:14px;"></i>
                            @else
                                <i data-lucide="settings" style="width:14px;height:14px;"></i>
                            @endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2 mb-1 flex-wrap">
                                <strong class="text-sm text-ink-emphasis">
                                    @if ($message->isFromSeller())
                                        {{ $ticket->seller->business_name ?? 'Seller' }}
                                    @elseif ($message->isFromAdmin())
                                        {{ $message->adminSender()?->name ?? 'Admin' }}
                                    @else
                                        System
                                    @endif
                                </strong>
                                @if ($message->sender_id === auth()->id())
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">You</span>
                                @endif
                                @if ($message->is_status_change)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-surface-muted text-ink-soft">Status change</span>
                                @endif
                                @if ($message->is_internal_note)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold bg-warning-tint text-feedback-warning">Internal</span>
                                @endif
                                <small class="text-ink-tertiary ms-auto">{{ $message->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            <p class="text-ink-soft text-sm mb-0" style="white-space: pre-line;">{!! nl2br(e($message->body)) !!}</p>
                            @if ($message->attachments->isNotEmpty())
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach ($message->attachments as $att)
                                        <a href="{{ $att->url() }}" target="_blank"
                                           class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-surface-muted text-ink-emphasis hover:bg-brand-tint hover:text-brand-deep transition-colors">
                                            <i data-lucide="paperclip" style="width:11px;height:11px;" class="me-1"></i>
                                            {{ $att->original_name }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-sm text-ink-tertiary">
                        <i data-lucide="message-circle" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
                        <p class="mb-0 font-semibold text-ink-emphasis">No messages yet</p>
                        <small>Start the conversation by replying below.</small>
                    </div>
                @endforelse
            </div>
        </section>

        <section class="bg-white rounded-sm shadow-sm overflow-hidden">
            <div class="px-5 py-3 flex items-center gap-2">
                <i data-lucide="reply" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Reply</h5>
            </div>
            <form method="POST" action="{{ route('admin.support.reply', $ticket) }}" enctype="multipart/form-data" class="p-5">
                @csrf
                <label class="flex items-center gap-2 mb-3 px-3 py-2 rounded-xs bg-amber-50 cursor-pointer">
                    <input type="checkbox" name="is_internal" value="1"
                           class="h-4 w-4 rounded border-border text-feedback-warning focus:ring-feedback-warning focus:ring-2"
                           id="isInternal">
                    <label class="text-sm font-medium text-ink-emphasis" for="isInternal">
                        <i data-lucide="lock" style="width:13px;height:13px;" class="me-1 text-feedback-warning"></i>
                        Internal note (not visible to seller)
                    </label>
                </label>
                <x-textarea-input name="body" value="" required maxlength="10000" placeholder="Reply to {{ $ticket->seller?->business_name ?? 'customer' }}..." />
                <div class="flex flex-wrap items-center gap-3 mt-3">
                    <label class="flex items-center gap-2 text-sm text-ink-soft cursor-pointer px-3 py-2 bg-surface-muted rounded-xs hover:bg-brand-tint transition-colors">
                        <i data-lucide="paperclip" style="width:14px;height:14px;"></i>
                        Attach files
                        <input type="file" name="attachments[]" multiple class="hidden">
                    </label>
                    <small class="text-ink-tertiary">Max 10MB per file.</small>
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button class="btn btn-primary">
                        <i data-lucide="send" class="icon-xs me-1"></i> Send Reply
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>

{{-- ═══ ACTIVITY LOG ═══ --}}
@if ($ticket->events->isNotEmpty())
    <section class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2">
            <i data-lucide="history" class="text-feedback-warning" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Activity Log</h5>
            <span class="text-ink-tertiary text-xs ms-auto">{{ $ticket->events->count() }} {{ Str::plural('event', $ticket->events->count()) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink-soft">
                <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Event</th>
                        <th class="px-4 py-2.5">Change</th>
                        <th class="px-4 py-2.5">Actor</th>
                        <th class="px-4 py-2.5">Note</th>
                        <th class="px-4 py-2.5 text-right">When</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($ticket->events as $event)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-emphasis">
                                    {{ ucfirst(str_replace('_', ' ', $event->type)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono text-ink-secondary">
                                @if ($event->from_value || $event->to_value)
                                    {{ $event->from_value ?? '—' }} <span class="text-ink-tertiary">→</span> {{ $event->to_value ?? '—' }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-4 py-3 text-ink-soft">{{ $event->actor_type }} #{{ $event->actor_id }}</td>
                            <td class="px-4 py-3 text-ink-soft">{{ $event->note ?? '—' }}</td>
                            <td class="px-4 py-3 text-right text-ink-tertiary">{{ $event->created_at->diffForHumans() }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
@endif

@push('modals')
{{-- Self-assign modal --}}
<div class="modal fade" id="selfAssignModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.support.selfAssign', $ticket) }}">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                            <i data-lucide="user-plus" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink-emphasis mb-0">Assign to me</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="text-sm text-ink-secondary mb-0">
                        Take ownership of <strong class="text-ink-emphasis">{{ $ticket->ticket_number }}</strong>?
                        The ticket will be assigned to <strong class="text-ink-emphasis">{{ auth()->user()?->name ?? 'you' }}</strong>.
                    </p>
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="check" class="icon-xs me-1"></i> Confirm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Status change modal --}}
<div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <form method="POST" action="{{ route('admin.support.status', $ticket) }}">
                @csrf
                <div class="modal-header border-b border-border bg-surface-muted">
                    <div class="flex items-center gap-2">
                        <span class="shrink-0 w-9 h-9 rounded-sm bg-info-tint text-feedback-info flex items-center justify-center">
                            <i data-lucide="refresh-cw" style="width:18px;height:18px;"></i>
                        </span>
                        <h5 class="modal-title font-bold text-ink-emphasis mb-0">Change status</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">New status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                        @foreach (TicketStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected($ticket->status === $s)>{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer border-t border-border bg-surface-muted">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i data-lucide="check" class="icon-xs me-1"></i> Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@endsection
