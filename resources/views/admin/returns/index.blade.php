@extends('admin.layouts.app')
@section('title', 'Return Management')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Return Management</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        @php
            $summaryCards = [
                ['key' => 'total',             'label' => 'Total',            'class' => 'dark'],
                ['key' => 'pending',           'label' => 'Pending',          'class' => 'warning'],
                ['key' => 'awaiting_shipment', 'label' => 'Awaiting',         'class' => 'info'],
                ['key' => 'approved',          'label' => 'Approved',         'class' => 'primary'],
                ['key' => 'rejected',          'label' => 'Rejected',         'class' => 'danger'],
                ['key' => 'refunded',          'label' => 'Refunded',         'class' => 'success'],
                ['key' => 'disputed',          'label' => 'Disputed',         'class' => 'danger'],
            ];
        @endphp
        @foreach ($summaryCards as $card)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm p-3">
                    <span class="text-muted small">{{ $card['label'] }}</span>
                    <h5 class="fw-bold mb-0 text-{{ $card['class'] }}">{{ $counts[$card['key']] ?? 0 }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3">
                <a href="{{ route('admin.returns.index') }}" class="btn btn-sm {{ !request('status') && !request('disputed') ? 'btn-dark' : 'btn-light border' }}">All</a>
                <a href="{{ route('admin.returns.index', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-light border' }}">Pending</a>
                <a href="{{ route('admin.returns.index', ['status' => 'approved']) }}" class="btn btn-sm {{ request('status') === 'approved' ? 'btn-success' : 'btn-light border' }}">Approved</a>
                <a href="{{ route('admin.returns.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ request('status') === 'rejected' ? 'btn-danger' : 'btn-light border' }}">Rejected</a>
                <a href="{{ route('admin.returns.index', ['status' => 'refunded']) }}" class="btn btn-sm {{ request('status') === 'refunded' ? 'btn-info' : 'btn-light border' }}">Refunded</a>
                <a href="{{ route('admin.returns.index', ['disputed' => 1]) }}" class="btn btn-sm {{ request('disputed') ? 'btn-danger' : 'btn-light border' }}">Disputed</a>

                <form method="GET" class="ms-auto d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search RMA, order, customer..." value="{{ request('search') }}">
                    <button class="btn btn-primary btn-sm">Search</button>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>RMA</th>
                            <th>Order</th>
                            <th>Customer</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Disputed</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            <tr>
                                <td class="fw-semibold">{{ $return->rma_number }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.index') }}?search={{ $return->order->invoice_id }}" class="text-primary">
                                        #{{ $return->order->invoice_id }}
                                    </a>
                                </td>
                                <td>{{ $return->user?->name ?? 'N/A' }}</td>
                                <td><span class="badge bg-secondary">{{ $return->typeLabel() }}</span></td>
                                <td>
                                    <span class="badge bg-{{ $return->statusColor() }}">
                                        {{ $return->label() }}
                                    </span>
                                </td>
                                <td>
                                    @if ($return->is_disputed)
                                        <span class="badge bg-danger">Disputed</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="small">{{ $return->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-sm btn-light border">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No return requests found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-end mt-3">
                {{ $returns->links() }}
            </div>
        </div>
    </div>
@endsection
