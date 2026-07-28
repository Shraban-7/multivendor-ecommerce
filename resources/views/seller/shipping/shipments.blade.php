@extends('seller.layouts.app')
@section('title', 'Shipments')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Shipments</h4>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2 align-items-center">
                <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    @foreach (\App\Domain\Shipping\Models\Shipment::statuses() as $value => $label)
                        <option value="{{ $value }}" {{ request('status') === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="text" name="tracking_number" class="form-control form-control-sm" style="width:160px;" placeholder="Tracking #" value="{{ request('tracking_number') }}">
                <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1"><i data-feather="search" style="width:14px;height:14px;"></i></button>
            </form>
        </div>
    </div>

    @if ($shipments->count() > 0)
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="small fw-semibold text-muted">#</th>
                            <th class="small fw-semibold text-muted">Order</th>
                            <th class="small fw-semibold text-muted">Carrier</th>
                            <th class="small fw-semibold text-muted">Tracking</th>
                            <th class="small fw-semibold text-muted">Status</th>
                            <th class="small fw-semibold text-muted">Date</th>
                            <th class="small fw-semibold text-muted">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>{{ $shipment->id }}</td>
                                <td>
                                    <a href="{{ route('seller.orders.details', $shipment->order?->invoice_id) }}" class="text-decoration-none">
                                        #{{ $shipment->order?->invoice_id ?? 'N/A' }}
                                    </a>
                                </td>
                                <td>{{ $shipment->carrier?->name ?? 'N/A' }}</td>
                                <td><code>{{ $shipment->tracking_number ?? 'N/A' }}</code></td>
                                <td>
                                    @php $label = $shipment->status; @endphp
                                    @if ($label === 'pending')
                                        <span class="badge badge-soft-warning">Pending</span>
                                    @elseif ($label === 'picked_up')
                                        <span class="badge badge-soft-info">Picked Up</span>
                                    @elseif ($label === 'in_transit')
                                        <span class="badge badge-soft-primary">In Transit</span>
                                    @elseif ($label === 'out_for_delivery')
                                        <span class="badge badge-soft-warning">Out for Delivery</span>
                                    @elseif ($label === 'delivered')
                                        <span class="badge badge-soft-success">Delivered</span>
                                    @elseif ($label === 'failed')
                                        <span class="badge badge-soft-danger">Failed</span>
                                    @elseif ($label === 'returned')
                                        <span class="badge badge-soft-secondary">Returned</span>
                                    @else
                                        <span class="badge badge-soft-secondary">{{ $label }}</span>
                                    @endif
                                </td>
                                <td>{{ $shipment->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('seller.shipping.shipments.show', $shipment->id) }}"
                                       class="btn btn-sm btn-light border d-inline-flex align-items-center gap-1">
                                        <i data-feather="eye" style="width:14px;height:14px;"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($shipments->hasPages())
            <div class="mt-3 d-flex justify-content-end">{{ $shipments->links() }}</div>
        @endif
    @else
        <div class="card border-0 shadow-sm" style="border-radius: 12px;">
            <div class="card-body text-center py-5">
                <i data-feather="package" style="width: 64px; height: 64px;" class="text-muted mb-3"></i>
                <h5 class="fw-semibold mb-2">No Shipments</h5>
                <p class="text-muted mb-0">Shipments will appear here after you create them from order details.</p>
            </div>
        </div>
    @endif
</div>
@endsection
