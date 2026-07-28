@extends('seller.layouts.app')
@section('title', 'Tracking - '.$order->invoice_id)

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Shipping & Tracking</h4>
        <span class="badge badge-soft-primary">#{{ $order->invoice_id }}</span>
    </div>

    <div class="row g-3">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">Add Tracking Info</h5>
                </div>
                <form method="POST" action="{{ route('seller.orders.tracking.store', $order) }}">
                    @csrf
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Carrier <span class="text-danger">*</span></label>
                            <select name="carrier_id" class="form-select" required>
                                <option value="">Select carrier...</option>
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tracking Number <span class="text-danger">*</span></label>
                            <input type="text" name="tracking_number" class="form-control" placeholder="e.g., STF123456789" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Notes (Optional)</label>
                            <textarea name="notes" class="form-control" rows="3" placeholder="Additional shipping notes..."></textarea>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="truck" style="width: 16px; height: 16px;"></i> Add Tracking
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">Tracking History</h5>
                </div>
                <div class="card-body p-0">
                    @if ($trackings->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach ($trackings as $tracking)
                                <div class="list-group-item px-4 py-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="fw-semibold">{{ $tracking->carrier->name ?? 'Unknown Carrier' }}</span>
                                                <code class="small">{{ $tracking->tracking_number }}</code>
                                            </div>
                                            @if ($tracking->notes)
                                                <p class="text-muted small mb-0">{{ $tracking->notes }}</p>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $tracking->created_at->format('d M Y, h:i A') }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i data-feather="package" style="width: 48px; height: 48px;" class="mb-3"></i>
                            <p class="mb-0">No tracking information added yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
