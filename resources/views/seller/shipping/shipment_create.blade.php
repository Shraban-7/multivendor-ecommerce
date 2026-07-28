@extends('seller.layouts.app')
@section('title', 'Create Shipment')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Create Shipment</h4>
        <span class="badge badge-soft-primary">#{{ $order->invoice_id }}</span>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold mb-0">Shipment Information</h5>
                </div>
                <form method="POST" action="{{ route('seller.shipping.shipments.store') }}">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Carrier <span class="text-danger">*</span></label>
                                <select name="shipping_carrier_id" class="form-select" required>
                                    <option value="">Select carrier...</option>
                                    @foreach ($carriers as $carrier)
                                        <option value="{{ $carrier->id }}">{{ $carrier->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Tracking Number <span class="text-danger">*</span></label>
                                <input type="text" name="tracking_number" class="form-control" placeholder="e.g., STF123456789" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Weight (kg)</label>
                                <input type="number" step="0.01" min="0" name="weight" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Shipping Cost (৳)</label>
                                <input type="number" step="0.01" min="0" name="shipping_cost" class="form-control">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">COD Amount (৳)</label>
                                <input type="number" step="0.01" min="0" name="cod_amount" class="form-control">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" class="form-control" rows="3" placeholder="Additional notes..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top text-end">
                        <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-1">
                            <i data-feather="package" style="width: 16px; height: 16px;"></i> Create Shipment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
