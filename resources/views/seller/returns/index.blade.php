@extends('seller.layouts.app')
@section('title', 'Returns')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Return Management</h4>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show py-2">{{ session('error') }}</div>
    @endif

    <div class="row g-3 mb-4">
        @php
            $tickets = [
                ['key' => 'total',              'label' => 'Total',              'class' => 'dark'],
                ['key' => 'pending',            'label' => 'Pending',            'class' => 'warning'],
                ['key' => 'awaiting_shipment',  'label' => 'Awaiting Shipment',  'class' => 'info'],
                ['key' => 'approved',           'label' => 'Approved',           'class' => 'primary'],
                ['key' => 'received',           'label' => 'Item Received',      'class' => 'success'],
                ['key' => 'refunded',           'label' => 'Refunded',           'class' => 'secondary'],
                ['key' => 'disputed',           'label' => 'Disputed',           'class' => 'danger'],
            ];
        @endphp
        @foreach ($tickets as $ticket)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm p-3">
                    <span class="text-muted small">{{ $ticket['label'] }}</span>
                    <h5 class="fw-bold mb-0 text-{{ $ticket['class'] }}">{{ $counts[$ticket['key']] ?? 0 }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap gap-2 mb-3 align-items-center">
                <a href="{{ route('seller.returns.index') }}" class="btn btn-sm {{ !request('status') && !request('disputed') ? 'btn-dark' : 'btn-light border' }}">All</a>
                @foreach (['pending' => 'Pending', 'awaiting_shipment' => 'Awaiting', 'approved' => 'Approved', 'item_received' => 'Received', 'refunded' => 'Refunded', 'rejected' => 'Rejected'] as $key => $label)
                    <a href="{{ route('seller.returns.index', ['status' => $key]) }}"
                       class="btn btn-sm {{ request('status') === $key ? 'btn-primary' : 'btn-light border' }}">{{ $label }}</a>
                @endforeach
                <a href="{{ route('seller.returns.index', ['disputed' => 1]) }}"
                   class="btn btn-sm {{ request('disputed') ? 'btn-danger' : 'btn-light border' }}">Disputed</a>

                <form method="GET" class="ms-auto d-flex gap-2">
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Search RMA / Order / Customer" value="{{ request('search') }}">
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
                            <th>Items</th>
                            <th>Refund</th>
                            <th>Status</th>
                            <th>Dispute</th>
                            <th>Date</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($returns as $return)
                            <tr>
                                <td class="fw-semibold">{{ $return->rma_number }}</td>
                                <td>#{{ $return->order->invoice_id }}</td>
                                <td>{{ $return->user?->name ?? 'N/A' }}<br><small class="text-muted">{{ $return->user?->phone ?? '' }}</small></td>
                                <td><span class="badge bg-secondary">{{ $return->typeLabel() }}</span></td>
                                <td class="small">
                                    @foreach ($return->items as $item)
                                        <div>{{ $item->orderItem?->product?->name ?? 'Item' }} × {{ $item->quantity }}</div>
                                    @endforeach
                                </td>
                                <td>{{ number_format($return->totalRefundAmount(), 2) }}</td>
                                <td><span class="badge bg-{{ $return->statusColor() }}">{{ $return->label() }}</span></td>
                                <td>
                                    @if ($return->is_disputed)
                                        <span class="badge bg-danger" title="{{ $return->dispute?->status?->label() ?? 'Open' }}">Disputed</span>
                                    @else<span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="small">{{ $return->created_at->format('d/m/Y') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('seller.returns.show', $return) }}" class="btn btn-sm btn-light border">
                                        <i data-feather="eye" class="icon-xs"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-4 text-muted">No return requests found.</td></tr>
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
