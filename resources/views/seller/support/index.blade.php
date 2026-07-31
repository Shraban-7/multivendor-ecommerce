@php
    use App\Domain\Support\Enums\TicketStatus;

    $statusCases = \App\Domain\Support\Enums\TicketStatus::cases();
    $priorityCases = \App\Domain\Support\Enums\TicketPriority::cases();

    $countCards = [
        ['key' => 'total',          'label' => 'Total Tickets',   'top' => '#F85606', 'text' => 'text-brand-deep',  'icon' => 'inbox'],
        ['key' => 'open',           'label' => 'Open',            'top' => '#fb923c', 'text' => 'text-feedback-warning', 'icon' => 'message-square-warning'],
        ['key' => 'awaiting_admin', 'label' => 'Awaiting Admin',  'top' => '#0ea5e9', 'text' => 'text-feedback-info',  'icon' => 'hourglass'],
        ['key' => 'resolved',       'label' => 'Resolved',        'top' => '#10b981', 'text' => 'text-feedback-success','icon' => 'check-circle-2'],
        ['key' => 'overdue',        'label' => 'SLA Overdue',     'top' => '#ef4444', 'text' => 'text-feedback-danger', 'icon' => 'alarm-clock'],
    ];
@endphp
@extends('seller.layouts.app')
@section('title', 'Support Tickets')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="message-circle" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Helpdesk</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Support Tickets</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Support Tickets</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="headphones" style="width:11px;height:11px;" class="me-1"></i> {{ count($tickets) }} Active
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Track, manage, and respond to your support requests with our admin team.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('seller.support.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:16px;height:16px;"></i> New Ticket
                </a>
            </div>
        </div>
    </div>
</section>

{{-- Flash messages --}}
@if (session('success'))
    <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm mb-3 alert-dismissible fade show">{{ session('success') }}</div>
@endif
@if (session('error'))
    <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 border border-feedback-danger/20 text-feedback-danger text-sm mb-3 alert-dismissible fade show">{{ session('error') }}</div>
@endif

{{-- ═══ KPI TILES ═══ --}}
<section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-3">
    @foreach ($countCards as $card)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $card['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $card['label'] }}</span>
                    <i data-lucide="{{ $card['icon'] }}" class="text-ink-tertiary" style="width:13px;height:13px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $card['text'] }} mb-0">{{ number_format($counts[$card['key']] ?? 0) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER + TABLE ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="p-5">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-2 mb-4">
            <div class="md:col-span-4 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" placeholder="Search by RMA number or subject…"
                       value="{{ request('search') }}"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All statuses</option>
                    @foreach ($statusCases as $s)
                        <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <select name="priority"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All priorities</option>
                    @foreach ($priorityCases as $p)
                        <option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <i data-lucide="sliders-horizontal" style="width:14px;height:14px;"></i> Filter
                </button>
                @if (request('search') || request('status') || request('priority'))
                    <a href="{{ route('seller.support.index') }}" class="btn btn-light">
                        <i data-lucide="x" style="width:14px;height:14px;"></i>
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Ticket</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Subject</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Category</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Priority</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Assigned</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Last Reply</th>
                        <th class="px-4 py-2 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tickets as $ticket)
                        <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                            <td class="px-4 py-3 font-semibold text-ink-emphasis">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="{{ $ticket->status === TicketStatus::Open->value ? 'mail' : 'mail-open' }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                                    {{ $ticket->ticket_number }}
                                </div>
                                <small class="text-ink-tertiary font-normal block mt-0.5">
                                    Opened {{ $ticket->created_at->diffForHumans() }}
                                </small>
                                @if ($ticket->isOverdue())
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-feedback-danger/15 text-feedback-danger mt-1">
                                        <i data-lucide="alarm-clock" style="width:10px;height:10px;" class="me-1"></i> Overdue
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-ink-soft">{{ Str::limit($ticket->subject, 60) }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium text-ink-secondary">
                                    <i data-lucide="tag" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                                    {{ ucwords(str_replace('_', ' ', $ticket->category)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-{{ $ticket->priorityColor() }}">
                                    <i data-lucide="flag" style="width:10px;height:10px;" class="me-1"></i>{{ ucfirst($ticket->priority) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-{{ $ticket->statusColor() }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                    {{ $ticket->statusLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-ink-soft">
                                @if ($ticket->admin)
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-brand-tint flex items-center justify-center text-brand-deep text-[11px] font-bold">
                                            {{ mb_strtoupper(mb_substr($ticket->admin->name ?? 'A', 0, 1)) }}
                                        </div>
                                        <span class="text-sm">{{ $ticket->admin->name }}</span>
                                    </div>
                                @else
                                    <span class="text-ink-tertiary text-sm">— unassigned —</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-ink-secondary">
                                @if ($ticket->last_message_at)
                                    <i data-lucide="clock" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                                    {{ $ticket->last_message_at->diffForHumans() }}
                                @else
                                    <span class="text-ink-tertiary">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('seller.support.show', $ticket) }}" class="btn btn-light btn-sm">
                                    <i data-lucide="eye" style="width:14px;height:14px;"></i> Open
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
                                <div class="py-10 text-center">
                                    <i data-lucide="inbox" class="text-ink-tertiary mx-auto mb-2" style="width:32px;height:32px;"></i>
                                    <p class="text-ink-soft font-semibold mb-1">No tickets yet</p>
                                    <p class="text-ink-tertiary text-xs mb-3">Start a conversation with our admin team.</p>
                                    <a href="{{ route('seller.support.create') }}" class="btn btn-primary btn-sm">
                                        <i data-lucide="plus" style="width:14px;height:14px;"></i> Create your first ticket
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-3">
            {{ $tickets->links() }}
        </div>
    </div>
</section>

@endsection
