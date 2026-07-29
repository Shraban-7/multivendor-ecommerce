@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-ink">Edit Coupon</h1>
        <p class="text-sm text-ink-secondary mt-1">{{ $coupon->code }}</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
        <i data-lucide="arrow-left" class="icon-xs"></i> Back
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
        <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
                    <input type="text" name="title" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('title', $coupon->title) }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code <span class="text-feedback-danger">*</span></label>
                    <input type="text" name="code" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('code', $coupon->code) }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type <span class="text-feedback-danger">*</span></label>
                        <select name="discount_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="percentage" {{ $coupon->discount_type === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="flat" {{ $coupon->discount_type === 'flat' ? 'selected' : '' }}>Flat (৳)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value <span class="text-feedback-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="discount_value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('discount_value', $coupon->discount_value) }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount (৳)</label>
                        <input type="number" step="0.01" min="0" name="max_discount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('max_discount', $coupon->max_discount) }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase (৳)</label>
                        <input type="number" step="0.01" min="0" name="min_purchase" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('min_purchase', $coupon->min_purchase) }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit</label>
                        <input type="number" min="1" name="usage_limit" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('usage_limit', $coupon->usage_limit) }}">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="1" {{ $coupon->status ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$coupon->status ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From <span class="text-feedback-danger">*</span></label>
                        <input type="date" name="valid_from" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until <span class="text-feedback-danger">*</span></label>
                        <input type="date" name="valid_until" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d')) }}" required>
                    </div>
                </div>
                @if ($coupon->seller)
                    <div class="p-3 bg-brand-tint border border-border rounded-xs">
                        <small class="text-ink-tertiary">Seller Coupon — created by</small>
                        <span class="font-semibold text-ink">{{ $coupon->seller->business_name ?? $coupon->seller->name }}</span>
                    </div>
                @endif
                <div>
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                    <x-textarea-input name="description" :value="old('description', $coupon->description)" />
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-border flex items-center gap-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Update Coupon</button>
            </div>
        </form>
    </div>
</div>
@endsection