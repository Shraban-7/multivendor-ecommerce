@extends('admin.layouts.app')
@section('title', 'Support Tickets')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0">Support Tickets</h4>
        @if (($overdueCount ?? 0) > 0)
            <a href="{{ route('admin.support.index', ['overdue' => 1]) }}" class="btn btn-danger btn-sm">
                {{ $overdueCount }} overdue
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="p-4 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm flex items-start gap-3 alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-1 gap-3 mb-4">
        @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $status)
            <div class="xl:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3">
                    <span class="text-ink-tertiary text-sm">{{ $status->label() }}</span>
                    <h5 class="font-bold mb-0 text-{{ $status->color() }}">{{ (int) ($stats[$status->value] ?? 0) }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
        <div class="p-5">
            <form method="GET" class="grid grid-cols-1 gap-2 mb-3 items-end">
                <div class="md:col-span-1">
                    <input type="text" name="search" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Ticket / Subject / Seller" value="{{ request('search') }}">
                </div>
                <div class="md:col-span-1">
                    <select name="status" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All statuses</option>
                        @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <select name="priority" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All priorities</option>
                        @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                            <option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <select name="category" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All categories</option>
                        @foreach (\App\Domain\Support\Enums\TicketCategory::labels() as $value => $label)
                            <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="flex items-center gap-2 text-sm">
                        <input type="checkbox" name="overdue" value="1" class="h-4 w-4 rounded border-border text-brand focus:ring-brand" @checked(request('overdue'))>
                        Only SLA-overdue
                    </label>
                </div>
                <div class="md:col-span-1">
                    <button class="btn btn-primary btn-sm">Search</button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered align-middle mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th>Ticket</th>
                            <th>Subject</th>
                            <th>From</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>SLA</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="font-semibold">
                                    {{ $ticket->ticket_number }}
                                    @if ($ticket->isOverdue())
                                        <span class="badge bg-feedback-danger ms-1">Overdue</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($ticket->subject, 60) }}</td>
                                <td>
                                    @if ($ticket->seller)
                                        <div class="font-semibold">{{ $ticket->seller->business_name }}</div>
                                        <small class="text-ink-tertiary">@{{ $ticket->seller->username }}</small>
                                    @elseif ($ticket->user)
                                        {{ $ticket->user->name }}
                                    @endif
                                </td>
                                <td><small class="text-ink-tertiary">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</small></td>
                                <td><span class="badge bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span></td>
                                <td><span class="badge bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></td>
                                <td>{{ $ticket->admin?->name ?? '—' }}</td>
                                <td class="text-sm {{ $ticket->isOverdue() ? 'text-feedback-danger font-semibold' : '' }}">
                                    {{ optional($ticket->sla_due_at)->diffForHumans() ?? '—' }}
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-light btn-sm">
                                        <i data-feather="eye" class="icon-xs"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-ink-tertiary">No tickets.</td></tr>
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
