@extends('seller.layouts.app')
@section('title', 'Support')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0">Support</h4>
        <a href="{{ route('seller.support.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="icon-xs"></i> New Ticket
        </a>
    </div>

    @if (session('success'))
        <div class="px-4 py-2 rounded-sm bg-feedback-success/10 border border-feedback-success/20 text-feedback-success text-sm alert-dismissible fade show">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="px-4 py-2 rounded-sm bg-feedback-danger/10 border border-feedback-danger/20 text-feedback-danger text-sm alert-dismissible fade show">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 mb-4">
        @foreach ([
            ['key' => 'total', 'label' => 'Total', 'class' => 'ink'],
            ['key' => 'open', 'label' => 'Open', 'class' => 'feedback-warning'],
            ['key' => 'awaiting_admin', 'label' => 'Awaiting Admin', 'class' => 'feedback-info'],
            ['key' => 'resolved', 'label' => 'Resolved', 'class' => 'feedback-success'],
            ['key' => 'overdue', 'label' => 'SLA Overdue', 'class' => 'feedback-danger'],
        ] as $card)
            <div>
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 p-3">
                    <span class="text-ink-tertiary text-sm">{{ $card['label'] }}</span>
                    <h5 class="font-bold mb-0 text-{{ $card['class'] }}">{{ $counts[$card['key']] ?? 0 }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0">
        <div class="p-5">
            <form method="GET" class="flex gap-2 mb-3 items-center">
                <input type="text" name="search" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search RMA / Subject" value="{{ request('search') }}" style="max-width: 280px;">
                <select name="status" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" style="max-width: 180px;">
                    <option value="">All statuses</option>
                    @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <select name="priority" class="w-full px-2.5 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" style="max-width: 160px;">
                    <option value="">All priorities</option>
                    @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                        <option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm">Filter</button>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered align-middle mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th>Ticket</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>Last Reply</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="font-semibold">
                                    {{ $ticket->ticket_number }}
                                    @if ($ticket->isOverdue())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-feedback-danger text-white ms-1">Overdue</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($ticket->subject, 50) }}</td>
                                <td><small class="text-ink-tertiary">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</small></td>
                                <td>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span>
                                </td>
                                <td><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></td>
                                <td>{{ $ticket->admin?->name ?? '—' }}</td>
                                <td class="text-sm">{{ optional($ticket->last_message_at)->diffForHumans() ?? '—' }}</td>
                                <td class="text-right">
                                    <a href="{{ route('seller.support.show', $ticket) }}" class="btn btn-light btn-sm">
                                        <i data-lucide="eye" class="icon-xs"></i> Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-ink-tertiary">No tickets yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end mt-3">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection
