@extends('seller.layouts.app')
@section('title', 'Shipment #'.$shipment->id)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.shipping.shipments') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Shipment #{{ $shipment->id }}</h4>
        <span class="badge badge-soft-primary">#{{ $shipment->order?->invoice_id ?? 'N/A' }}</span>
    </div>

    <div class="row g-3">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">Shipment Details</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Status</span>
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
                            @endif
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Carrier</span>
                            <span class="fw-medium">{{ $shipment->carrier?->name ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Tracking #</span>
                            <code>{{ $shipment->tracking_number ?? 'N/A' }}</code>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Weight</span>
                            <span>{{ $shipment->weight ? $shipment->weight.' kg' : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Shipping Cost</span>
                            <span>{{ $shipment->shipping_cost ? money($shipment->shipping_cost) : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">COD Amount</span>
                            <span>{{ $shipment->cod_amount ? money($shipment->cod_amount) : 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span class="text-muted">Created</span>
                            <span>{{ $shipment->created_at->format('d/m/Y h:i A') }}</span>
                        </li>
                        @if ($shipment->shipped_at)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Shipped</span>
                                <span>{{ $shipment->shipped_at->format('d/m/Y h:i A') }}</span>
                            </li>
                        @endif
                        @if ($shipment->delivered_at)
                            <li class="list-group-item d-flex justify-content-between px-0">
                                <span class="text-muted">Delivered</span>
                                <span>{{ $shipment->delivered_at->format('d/m/Y h:i A') }}</span>
                            </li>
                        @endif
                    </ul>

                    @if ($shipment->notes)
                        <hr>
                        <p class="small mb-0">{{ $shipment->notes }}</p>
                    @endif
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-3" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">Update Status</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('seller.shipping.shipments.update-status', $shipment->id) }}">
                        @csrf
                        <div class="mb-2">
                            <select name="status" class="form-select form-select-sm" required>
                                @foreach (\App\Domain\Shipping\Models\Shipment::statuses() as $value => $label)
                                    <option value="{{ $value }}" {{ $shipment->status === $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-2">
                            <input type="text" name="location" class="form-control form-control-sm" placeholder="Location (optional)">
                        </div>
                        <div class="mb-2">
                            <textarea name="description" class="form-control form-control-sm" rows="2" placeholder="Description (optional)"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100">Update</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">Tracking Timeline</h5>
                </div>
                <div class="card-body">
                    @if ($shipment->trackingLogs->count() > 0)
                        <div class="timeline">
                            @foreach ($shipment->trackingLogs->sortByDesc('logged_at') as $log)
                                <div class="d-flex gap-3 mb-3">
                                    <div class="d-flex flex-column align-items-center" style="width: 24px;">
                                        <div class="rounded-circle bg-primary" style="width: 12px; height: 12px;"></div>
                                        @if (!$loop->last)
                                            <div class="flex-grow-1" style="width: 2px; background: #e0e0e0;"></div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1 pb-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-semibold small">{{ \App\Domain\Shipping\Models\Shipment::statuses()[$log->status] ?? $log->status }}</span>
                                            <small class="text-muted">{{ $log->logged_at->format('d M Y, h:i A') }}</small>
                                        </div>
                                        @if ($log->location)
                                            <small class="text-muted d-block">{{ $log->location }}</small>
                                        @endif
                                        @if ($log->description)
                                            <p class="small mb-0 mt-1">{{ $log->description }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">No tracking logs yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
