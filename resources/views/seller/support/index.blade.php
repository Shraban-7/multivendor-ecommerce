@extends('seller.layouts.app')
@section('title', 'Support')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Support</h4>
        <a href="{{ route('seller.support.create') }}" class="btn btn-primary btn-sm">
            <i data-feather="plus" class="icon-xs"></i> New Ticket
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        @foreach ([
            ['key' => 'total', 'label' => 'Total', 'class' => 'dark'],
            ['key' => 'open', 'label' => 'Open', 'class' => 'warning'],
            ['key' => 'awaiting_admin', 'label' => 'Awaiting Admin', 'class' => 'info'],
            ['key' => 'resolved', 'label' => 'Resolved', 'class' => 'success'],
            ['key' => 'overdue', 'label' => 'SLA Overdue', 'class' => 'danger'],
        ] as $card)
            <div class="col-xl col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm p-3">
                    <span class="text-muted small">{{ $card['label'] }}</span>
                    <h5 class="fw-bold mb-0 text-{{ $card['class'] }}">{{ $counts[$card['key']] ?? 0 }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="d-flex gap-2 mb-3 align-items-center">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search RMA / Subject" value="{{ request('search') }}" style="max-width: 280px;">
                <select name="status" class="form-select form-select-sm" style="max-width: 180px;">
                    <option value="">All statuses</option>
                    @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $s)
                        <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                    @endforeach
                </select>
                <select name="priority" class="form-select form-select-sm" style="max-width: 160px;">
                    <option value="">All priorities</option>
                    @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                        <option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm">Filter</button>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket</th>
                            <th>Subject</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>Last Reply</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr>
                                <td class="fw-semibold">
                                    {{ $ticket->ticket_number }}
                                    @if ($ticket->isOverdue())
                                        <span class="badge bg-danger ms-1">Overdue</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($ticket->subject, 50) }}</td>
                                <td><small class="text-muted">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</small></td>
                                <td>
                                    <span class="badge bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span>
                                </td>
                                <td><span class="badge bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></td>
                                <td>{{ $ticket->admin?->name ?? '—' }}</td>
                                <td class="small">{{ optional($ticket->last_message_at)->diffForHumans() ?? '—' }}</td>
                                <td class="text-end">
                                    <a href="{{ route('seller.support.show', $ticket) }}" class="btn btn-sm btn-light border">
                                        <i data-feather="eye" class="icon-xs"></i> Open
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No tickets yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
@endsection
