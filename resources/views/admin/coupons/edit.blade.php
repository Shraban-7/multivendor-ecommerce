@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="flex items-center gap-2 mb-3">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
    </a>
    <h3 class="font-bold mb-0">Edit Coupon: {{ $coupon->code }}</h3>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
    <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
        @csrf
        <div class="p-5">
            <div class="grid grid-cols-1 gap-3">
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
                    <input type="text" name="title" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('title', $coupon->title) }}">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code <span class="text-feedback-danger">*</span></label>
                    <input type="text" name="code" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('code', $coupon->code) }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type <span class="text-feedback-danger">*</span></label>
                    <select name="discount_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                        <option value="percentage" {{ $coupon->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="flat" {{ $coupon->discount_type === 'flat' ? 'selected' : '' }}>Flat (৳)</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value <span class="text-feedback-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="discount_value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount (৳)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('max_discount', $coupon->max_discount) }}">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase (৳)</label>
                    <input type="number" step="0.01" min="0" name="min_purchase" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('min_purchase', $coupon->min_purchase) }}">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="1" {{ $coupon->status ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ !$coupon->status ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From <span class="text-feedback-danger">*</span></label>
                    <input type="date" name="valid_from" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until <span class="text-feedback-danger">*</span></label>
                    <input type="date" name="valid_until" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d')) }}" required>
                </div>
                @if ($coupon->seller)
                    <div class="col-span-full">
                        <div class="p-3 rounded" style="background: var(--bs-light-primary);">
                            <small class="text-ink-tertiary">Seller Coupon — created by</small>
                            <span class="font-semibold">{{ $coupon->seller->business_name ?? $coupon->seller->name }}</span>
                        </div>
                    </div>
                @endif
                <div class="col-span-full">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                    <textarea name="description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3">{{ old('description', $coupon->description) }}</textarea>
                </div>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t text-right">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Coupon</button>
        </div>
    </form>
</div>
@endsection
