@extends('admin.layouts.app')
@section('title', 'Create Coupon')

@section('content')
<div class="flex items-center gap-2 mb-3">
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
        <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
    </a>
    <h3 class="font-bold mb-0">Create Global Coupon</h3>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
    <form method="POST" action="{{ route('admin.coupons.store') }}">
        @csrf
        <div class="p-5">
            <div class="grid grid-cols-1 gap-3">
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
                    <input type="text" name="title" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('title') }}">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code <span class="text-feedback-danger">*</span></label>
                    <input type="text" name="code" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('code') }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type <span class="text-feedback-danger">*</span></label>
                    <select name="discount_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                        <option value="percentage">Percentage (%)</option>
                        <option value="flat">Flat (৳)</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value <span class="text-feedback-danger">*</span></label>
                    <input type="number" step="0.01" min="0" name="discount_value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount (৳)</label>
                    <input type="number" step="0.01" min="0" name="max_discount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase (৳)</label>
                    <input type="number" step="0.01" min="0" name="min_purchase" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit</label>
                    <input type="number" min="1" name="usage_limit" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From <span class="text-feedback-danger">*</span></label>
                    <input type="date" name="valid_from" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ now()->format('Y-m-d') }}" required>
                </div>
                <div class="md:col-span-1">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until <span class="text-feedback-danger">*</span></label>
                    <input type="date" name="valid_until" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ now()->addMonth()->format('Y-m-d') }}" required>
                </div>
                <div class="col-span-full">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                    <textarea name="description" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3"></textarea>
                </div>
            </div>
        </div>
        <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t text-right">
            <a href="{{ route('admin.coupons.index') }}" class="btn btn-light me-2">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Coupon</button>
        </div>
    </form>
</div>
@endsection
