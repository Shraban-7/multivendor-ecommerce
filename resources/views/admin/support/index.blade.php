@php
    use App\Domain\Support\Enums\TicketStatus;
    use App\Domain\Support\Enums\TicketPriority;

    $pageTitle = 'Support Tickets';

    $statusMap = [
        TicketStatus::OPEN->value => ['label' => 'Open',                'pill' => 'bg-amber-500 text-white',   'icon' => 'inbox',          'tone' => 'warning'],
        TicketStatus::IN_PROGRESS->value => ['label' => 'In Progress',         'pill' => 'bg-blue-500 text-white',    'icon' => 'loader',         'tone' => 'info'],
        TicketStatus::AWAITING_SELLER->value => ['label' => 'Awaiting Seller',   'pill' => 'bg-cyan-500 text-white',   'icon' => 'user-check',    'tone' => 'cyan'],
        TicketStatus::AWAITING_ADMIN->value  => ['label' => 'Awaiting Admin',    'pill' => 'bg-cyan-500 text-white',   'icon' => 'shield',        'tone' => 'cyan'],
        TicketStatus::RESOLVED->value => ['label' => 'Resolved',            'pill' => 'bg-emerald-500 text-white', 'icon' => 'check-circle',  'tone' => 'success'],
        TicketStatus::CLOSED->value   => ['label' => 'Closed',              'pill' => 'bg-gray-500 text-white',   'icon' => 'archive',        'tone' => 'muted'],
        TicketStatus::REJECTED->value => ['label' => 'Rejected',            'pill' => 'bg-rose-500 text-white',   'icon' => 'ban',            'tone' => 'danger'],
    ];

    $priorityMap = [
        TicketPriority::LOW->value    => ['label' => 'Low',     'pill' => 'bg-surface-muted text-ink-emphasis'],
        TicketPriority::NORMAL->value => ['label' => 'Normal',  'pill' => 'bg-info-tint text-feedback-info'],
        TicketPriority::HIGH->value   => ['label' => 'High',    'pill' => 'bg-warning-tint text-feedback-warning'],
        TicketPriority::URGENT->value => ['label' => 'Urgent',  'pill' => 'bg-rose-500 text-white'],
    ];

    $statusTones = [
        'warning' => ['bar' => 'bg-amber-500',  'icon' => 'bg-warning-tint text-feedback-warning'],
        'info'    => ['bar' => 'bg-blue-500',   'icon' => 'bg-info-tint text-feedback-info'],
        'cyan'    => ['bar' => 'bg-cyan-500',   'icon' => 'bg-cyan-50 text-cyan-700'],
        'success' => ['bar' => 'bg-emerald-500','icon' => 'bg-emerald-50 text-feedback-success'],
        'muted'   => ['bar' => 'bg-gray-500',   'icon' => 'bg-surface-muted text-ink-soft'],
        'danger'  => ['bar' => 'bg-rose-500',   'icon' => 'bg-rose-50 text-rose-500'],
    ];
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
                    <span>Operations</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Support Tickets</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    @if (($overdueCount ?? 0) > 0)
                        <a href="{{ route('admin.support.index', ['overdue' => 1]) }}"
                           class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-rose-500 text-white">
                            <i data-lucide="alert-circle" style="width:11px;height:11px;" class="me-1"></i> {{ $overdueCount }} SLA breached
                        </a>
                    @endif
                </div>
                <p class="text-sm text-ink-secondary mb-0">Triage seller and customer support tickets, manage SLAs, assign owners, and reply with internal notes.</p>
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

{{-- ═══ KPI TILES — STATUS BREAKDOWN ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-3 mb-4">
    @foreach (TicketStatus::cases() as $status)
        @php
            $meta = $statusMap[$status->value] ?? [];
            $tones = $statusTones[$meta['tone']] ?? $statusTones['muted'];
        @endphp
        <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 {{ $tones['bar'] }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $meta['label'] }}</p>
                    <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format((int) ($stats[$status->value] ?? 0)) }}</h3>
                    <a href="{{ route('admin.support.index', ['status' => $status->value]) }}" class="text-[11px] text-ink-tertiary hover:text-brand transition-colors">View →</a>
                </div>
                <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center {{ $tones['icon'] }}">
                    <i data-lucide="{{ $meta['icon'] }}" style="width:18px;height:18px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTERS + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="list-filter" class="text-feedback-info" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Ticket Queue</h5>
            <span class="text-ink-tertiary text-xs">· {{ $tickets->total() }} {{ Str::plural('ticket', $tickets->total()) }}</span>
        </div>
    </div>

    <form method="GET" class="px-5 py-4 bg-surface-muted">
        <div class="grid grid-cols-1 md:grid-cols-6 gap-3">
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Ticket / Subject / Seller"
                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Status</label>
                <select name="status" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All statuses</option>
                    @foreach (TicketStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $statusMap[$s->value]['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Priority</label>
                <select name="priority" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All priorities</option>
                    @foreach (TicketPriority::cases() as $p)
                        <option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Category</label>
                <select name="category" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All categories</option>
                    @foreach (\App\Domain\Support\Enums\TicketCategory::labels() as $value => $label)
                        <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">SLA</label>
                <label class="flex items-center gap-2 px-3 py-2 text-sm bg-surface-muted rounded-xs cursor-pointer">
                    <input type="checkbox" name="overdue" value="1"
                           class="h-4 w-4 rounded border-border text-brand focus:ring-brand focus:ring-2"
                           @checked(request()->boolean('overdue'))>
                    <span class="text-ink-secondary">Only overdue</span>
                </label>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="btn btn-primary btn-sm flex-1">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Search
                </button>
                <a href="{{ route('admin.support.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="rotate-ccw" style="width:14px;height:14px;"></i> Reset
                </a>
            </div>
        </div>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-soft">
            <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-36">Ticket</th>
                    <th class="px-4 py-2.5">Subject</th>
                    <th class="px-4 py-2.5">From</th>
                    <th class="px-4 py-2.5">Category</th>
                    <th class="px-4 py-2.5">Priority</th>
                    <th class="px-4 py-2.5">Status</th>
                    <th class="px-4 py-2.5">Assigned</th>
                    <th class="px-4 py-2.5">SLA</th>
                    <th class="px-4 py-2.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($tickets as $ticket)
                    @php
                        $statusMeta = $statusMap[$ticket->status] ?? [];
                        $priorityMeta = $priorityMap[$ticket->priority] ?? null;
                    @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <span class="font-mono font-semibold text-ink-emphasis">{{ $ticket->ticket_number }}</span>
                                @if ($ticket->isOverdue())
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-500 text-white">
                                        <i data-lucide="alert-circle" style="width:10px;height:10px;" class="me-0.5"></i> Overdue
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-ink-emphasis font-medium truncate max-w-xs">{{ Str::limit($ticket->subject, 60) }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if ($ticket->seller)
                                <p class="text-ink-emphasis font-medium mb-0">{{ $ticket->seller->business_name }}</p>
                                <small class="text-ink-tertiary">@{{ $ticket->seller->username }}</small>
                            @elseif ($ticket->user)
                                <p class="text-ink-emphasis font-medium mb-0">{{ $ticket->user->name }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-ink-secondary">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            @if ($priorityMeta)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $priorityMeta['pill'] }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>
                            @else
                                <span class="text-ink-tertiary">{{ ucfirst($ticket->priority) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium text-white {{ $statusMeta['pill'] ?? 'bg-surface-muted text-ink-soft' }}">
                                <i data-lucide="{{ $statusMeta['icon'] ?? 'circle' }}" style="width:11px;height:11px;" class="me-1"></i>
                                {{ $ticket->statusLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink-soft">{{ $ticket->admin?->name ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if ($ticket->isOverdue())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-semibold">
                                    <i data-lucide="alert-triangle" style="width:11px;height:11px;" class="me-1"></i>
                                    {{ optional($ticket->sla_due_at)->diffForHumans() ?? '—' }}
                                </span>
                            @else
                                <span class="text-ink-secondary">{{ optional($ticket->sla_due_at)->diffForHumans() ?? '—' }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-light btn-sm" title="View ticket">
                                <i data-lucide="eye" class="icon-xs"></i> Open
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="inbox" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink-emphasis">No tickets match your filters</p>
                            <small>Reset filters or wait for new tickets to arrive.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($tickets->hasPages())
        <div class="px-5 py-3 bg-surface-muted flex items-center justify-between">
            <small class="text-ink-tertiary">Showing {{ $tickets->firstItem() }}–{{ $tickets->lastItem() }} of {{ $tickets->total() }}</small>
            {{ $tickets->links() }}
        </div>
    @endif
</section>

@endsection
