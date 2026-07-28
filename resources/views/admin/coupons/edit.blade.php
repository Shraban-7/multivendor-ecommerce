@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
    </a>
    <h3 class="fw-bold mb-0">Edit Coupon: {{ $coupon->code }}</h3>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Coupon Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $coupon->title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code', $coupon->code) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="percentage" {{ $coupon->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="flat" {{ $coupon->discount_type === 'flat' ? 'selected' : '' }}>Flat (৳)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="discount_value" class="form-control" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Max Discount (৳)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" class="form-control" value="{{ old('max_discount', $coupon->max_discount) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Purchase (৳)</label>
                    <input type="number" step="0.01" min="0" name="min_purchase" class="form-control" value="{{ old('min_purchase', $coupon->min_purchase) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" class="form-control" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1" {{ $coupon->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$coupon->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valid From <span class="text-danger">*</span></label>
                    <input type="date" name="valid_from" class="form-control" value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                    <input type="date" name="valid_until" class="form-control" value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d')) }}" required>
                </div>
                @if ($coupon->seller)
                    <div class="col-12">
                        <div class="p-3 rounded" style="background: var(--bs-light-primary);">
                            <small class="text-muted">Seller Coupon — created by</small>
                            <span class="fw-semibold">{{ $coupon->seller->business_name ?? $coupon->seller->name }}</span>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3">{{ old('description', $coupon->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white border-top text-end">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light border me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Coupon</button>
        </div>
    </form>
</div>
@endsection
