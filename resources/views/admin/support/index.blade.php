@extends('admin.layouts.app')
@section('title', 'Support Tickets')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Support Tickets</h4>
        @if (($overdueCount ?? 0) > 0)
            <a href="{{ route('admin.support.index', ['overdue' => 1]) }}" class="btn btn-sm btn-danger">
                {{ $overdueCount }} overdue
            </a>
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $status)
            <div class="col-xl col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm p-3">
                    <span class="text-muted small">{{ $status->label() }}</span>
                    <h5 class="fw-bold mb-0 text-{{ $status->color() }}">{{ (int) ($stats[$status->value] ?? 0) }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <form method="GET" class="row g-2 mb-3 align-items-end">
                <div class="col-md-3">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Ticket / Subject / Seller" value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <select name="status" class="form-select form-select-sm">
                        <option value="">All statuses</option>
                        @foreach (\App\Domain\Support\Enums\TicketStatus::cases() as $s)
                            <option value="{{ $s->value }}" @selected(request('status') === $s->value)>{{ $s->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="priority" class="form-select form-select-sm">
                        <option value="">All priorities</option>
                        @foreach (\App\Domain\Support\Enums\TicketPriority::cases() as $p)
                            <option value="{{ $p->value }}" @selected(request('priority') === $p->value)>{{ $p->label() }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="category" class="form-select form-select-sm">
                        <option value="">All categories</option>
                        @foreach (\App\Domain\Support\Enums\TicketCategory::labels() as $value => $label)
                            <option value="{{ $value }}" @selected(request('category') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-check small">
                        <input type="checkbox" name="overdue" value="1" class="form-check-input" @checked(request('overdue'))>
                        Only SLA-overdue
                    </label>
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary btn-sm w-100">Search</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ticket</th>
                            <th>Subject</th>
                            <th>From</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Assigned</th>
                            <th>SLA</th>
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
                                <td>{{ Str::limit($ticket->subject, 60) }}</td>
                                <td>
                                    @if ($ticket->seller)
                                        <div class="fw-semibold">{{ $ticket->seller->business_name }}</div>
                                        <small class="text-muted">@{{ $ticket->seller->username }}</small>
                                    @elseif ($ticket->user)
                                        {{ $ticket->user->name }}
                                    @endif
                                </td>
                                <td><small class="text-muted">{{ ucwords(str_replace('_', ' ', $ticket->category)) }}</small></td>
                                <td><span class="badge bg-{{ $ticket->priorityColor() }}">{{ ucfirst($ticket->priority) }}</span></td>
                                <td><span class="badge bg-{{ $ticket->statusColor() }}">{{ $ticket->statusLabel() }}</span></td>
                                <td>{{ $ticket->admin?->name ?? '—' }}</td>
                                <td class="small {{ $ticket->isOverdue() ? 'text-danger fw-semibold' : '' }}">
                                    {{ optional($ticket->sla_due_at)->diffForHumans() ?? '—' }}
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.support.show', $ticket) }}" class="btn btn-sm btn-light border">
                                        <i data-feather="eye" class="icon-xs"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="9" class="text-center py-4 text-muted">No tickets.</td></tr>
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
