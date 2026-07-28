@extends('admin.layouts.app')
@section('title', 'Create Coupon')

@section('content')
<div class="d-flex align-items-center gap-2 mb-3">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
        <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
    </a>
    <h3 class="fw-bold mb-0">Create Global Coupon</h3>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Coupon Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Coupon Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="{{ old('code') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount Type <span class="text-danger">*</span></label>
                    <select name="discount_type" class="form-select" required>
                        <option value="percentage">Percentage (%)</option>
                        <option value="flat">Flat (৳)</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Discount Value <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="discount_value" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Max Discount (৳)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Min Purchase (৳)</label>
                    <input type="number" step="0.01" min="0" name="min_purchase" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" class="form-control">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valid From <span class="text-danger">*</span></label>
                    <input type="date" name="valid_from" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Valid Until <span class="text-danger">*</span></label>
                    <input type="date" name="valid_until" class="form-control" value="{{ now()->addMonth()->format('Y-m-d') }}" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="card-footer bg-white border-top text-end">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light border me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Coupon</button>
        </div>
    </form>
</div>
@endsection
