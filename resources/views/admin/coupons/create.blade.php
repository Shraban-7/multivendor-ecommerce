@extends('admin.layouts.app')
@section('title', 'Create Coupon')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Create Coupon</h1>
            <p class="text-sm text-ink-secondary mt-1">Create a new global discount coupon</p>
        </div>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" class="icon-xs"></i> Back
        </a>
    </div>

    @if ($errors->any())
        <div class="p-3 rounded-sm bg-red-50 border border-red-200 text-feedback-danger text-sm mb-4">
            <strong class="font-semibold">Please fix the following:</strong>
            <ul class="list-disc list-inside mt-1 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="max-w-3xl">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Coupon Details</h6>
            </div>
            <form method="POST" action="{{ route('admin.coupons.store') }}">
                @csrf
                <div class="p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
                            <input type="text" name="title"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                value="{{ old('title') }}" placeholder="e.g., Summer Sale 2026">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code </label>
                            <input type="text" name="code"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('code') is-invalid @enderror"
                                value="{{ old('code') }}" placeholder="e.g., SUMMER20" required>
                            @error('code')
                                <div class="text-feedback-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type </label>
                            <select name="discount_type"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                required>
                                <option value="percentage" {{ old('discount_type') === 'percentage' ? 'selected' : '' }}>
                                    Percentage (%)</option>
                                <option value="flat" {{ old('discount_type') === 'flat' ? 'selected' : '' }}>Flat (৳)
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value </label>
                            <input type="number" step="0.01" min="0" name="discount_value"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors @error('discount_value') is-invalid @enderror"
                                value="{{ old('discount_value') }}" placeholder="e.g., 20" required>
                            @error('discount_value')
                                <div class="text-feedback-danger text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount (<small
                                    class="text-ink-tertiary">cap % only</small>)</label>
                            <input type="number" step="0.01" min="0" name="max_discount"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                value="{{ old('max_discount') }}" placeholder="No cap">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase</label>
                            <input type="number" step="0.01" min="0" name="min_purchase"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                value="{{ old('min_purchase') }}" placeholder="No minimum">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit <span
                                    class="text-ink-tertiary text-xs">(blank = unlimited)</span></label>
                            <input type="number" min="1" name="usage_limit"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                value="{{ old('usage_limit') }}" placeholder="Unlimited">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                            <select name="status"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From </label>
                            <input type="date" name="valid_from"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                value="{{ old('valid_from', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until </label>
                            <input type="date" name="valid_until"
                                class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                value="{{ old('valid_until', now()->addMonth()->format('Y-m-d')) }}" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                        <x-textarea-input name="description" value="" />
                    </div>
                </div>

                <div class="flex justify-end px-4 py-3 border-t border-border bg-surface-muted gap-2">
                    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary"><i data-lucide="save" class="icon-xs"></i> Create
                        Coupon</button>
                </div>
            </form>
        </div>
    </div>
@endsection
