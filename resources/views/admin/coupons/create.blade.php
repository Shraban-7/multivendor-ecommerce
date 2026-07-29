@extends('admin.layouts.app')
@section('title', 'Create Coupon')

@section('content')
<div class="flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-ink">Create Coupon</h1>
        <p class="text-sm text-ink-secondary mt-1">Create a new discount coupon</p>
    </div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
        <i data-lucide="arrow-left" class="icon-xs"></i> Back
    </a>
</div>

<div class="max-w-2xl">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
        <form method="POST" action="{{ route('admin.coupons.store') }}">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
                    <input type="text" name="title" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('title') }}">
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code <span class="text-feedback-danger">*</span></label>
                    <input type="text" name="code" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ old('code') }}" required>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type <span class="text-feedback-danger">*</span></label>
                        <select name="discount_type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                            <option value="percentage">Percentage (%)</option>
                            <option value="flat">Flat (৳)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value <span class="text-feedback-danger">*</span></label>
                        <input type="number" step="0.01" min="0" name="discount_value" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount (৳)</label>
                        <input type="number" step="0.01" min="0" name="max_discount" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase (৳)</label>
                        <input type="number" step="0.01" min="0" name="min_purchase" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit</label>
                        <input type="number" min="1" name="usage_limit" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                        <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From <span class="text-feedback-danger">*</span></label>
                        <input type="date" name="valid_from" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ now()->format('Y-m-d') }}" required>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until <span class="text-feedback-danger">*</span></label>
                        <input type="date" name="valid_until" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ now()->addMonth()->format('Y-m-d') }}" required>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                    <x-textarea-input name="description" value="" />
                </div>
            </div>
            <div class="mt-6 pt-4 border-t border-border flex items-center gap-2">
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
                <button type="submit" class="btn btn-primary">Create Coupon</button>
            </div>
        </form>
    </div>
</div>
@endsection