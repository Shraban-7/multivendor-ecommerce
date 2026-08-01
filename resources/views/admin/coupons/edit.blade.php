@extends('admin.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Edit Coupon</h1>
            <p class="text-sm text-ink-secondary mt-1 font-mono">{{ $coupon->code }}@if ($coupon->title)
                    · {{ $coupon->title }}
                @endif
            </p>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Coupon Details</h6>
                </div>
                <form method="POST" action="{{ route('admin.coupons.update', $coupon) }}">
                    @csrf
                    @method('PUT')
                    <div class="p-5 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Title</label>
                                <input type="text" name="title"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ old('title', $coupon->title) }}" placeholder="e.g., Summer Sale 2026">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Coupon Code </label>
                                <input type="text" name="code"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ old('code', $coupon->code) }}" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Type </label>
                                <select name="discount_type"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                    required>
                                    <option value="percentage"
                                        {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>
                                        Percentage (%)</option>
                                    <option value="flat"
                                        {{ old('discount_type', $coupon->discount_type) === 'flat' ? 'selected' : '' }}>Flat
                                        (৳)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Discount Value </label>
                                <input type="number" step="0.01" min="0" name="discount_value"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ old('discount_value', $coupon->discount_value) }}" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Max Discount</label>
                                <input type="number" step="0.01" min="0" name="max_discount"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ old('max_discount', $coupon->max_discount) }}" placeholder="No cap">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Min Purchase</label>
                                <input type="number" step="0.01" min="0" name="min_purchase"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ old('min_purchase', $coupon->min_purchase) }}" placeholder="No minimum">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Usage Limit <span
                                        class="text-ink-tertiary text-xs">(blank = unlimited)</span></label>
                                <input type="number" min="1" name="usage_limit"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                                    value="{{ old('usage_limit', $coupon->usage_limit) }}" placeholder="Unlimited">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="1"
                                        {{ old('status', (string) $coupon->status) == '1' ? 'selected' : '' }}>Active
                                    </option>
                                    <option value="0"
                                        {{ old('status', (string) $coupon->status) === '0' ? 'selected' : '' }}>Inactive
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Valid From </label>
                                <input type="date" name="valid_from"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                    value="{{ old('valid_from', $coupon->valid_from->format('Y-m-d')) }}" required>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Valid Until </label>
                                <input type="date" name="valid_until"
                                    class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors"
                                    value="{{ old('valid_until', $coupon->valid_until->format('Y-m-d')) }}" required>
                            </div>
                        </div>

                        @if ($coupon->seller)
                            <div class="p-3 bg-brand-tint border border-border rounded-xs flex items-center gap-3">
                                <i data-lucide="store" style="width:20px;height:20px;" class="text-brand shrink-0"></i>
                                <div>
                                    <div class="text-xs text-ink-tertiary uppercase tracking-wider font-semibold">Seller
                                        Coupon</div>
                                    <div class="font-semibold text-ink">
                                        {{ $coupon->seller->business_name ?? $coupon->seller->name }}</div>
                                </div>
                            </div>
                        @endif

                        <div>
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Description</label>
                            <x-textarea-input name="description" :value="old('description', $coupon->description)" />
                        </div>
                    </div>

                    <div class="flex justify-end px-4 py-3 border-t border-border bg-surface-muted gap-2">
                        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="icon-xs"></i> Update
                            Coupon</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider"><i data-lucide="activity"
                            class="icon-xs me-1"></i> Snapshot</h6>
                </div>
                <div class="p-5 space-y-3 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-ink-secondary">Redemptions</span>
                        <span class="font-semibold text-ink">{{ number_format($coupon->used_count) }}@if ($coupon->usage_limit)
                                / {{ number_format($coupon->usage_limit) }}
                            @endif
                        </span>
                    </div>
                    @if ($coupon->usage_limit)
                        @php $pct = round(min(100, ($coupon->used_count / max(1, $coupon->usage_limit)) * 100)); @endphp
                        <div class="w-full bg-surface-muted rounded-full h-1.5 overflow-hidden">
                            <div class="bg-brand h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    @endif
                    <div class="flex items-center justify-between"><span class="text-ink-secondary">Valid From</span><span
                            class="text-ink">{{ $coupon->valid_from->format('d M Y') }}</span></div>
                    <div class="flex items-center justify-between"><span class="text-ink-secondary">Valid
                            Until</span><span class="text-ink">{{ $coupon->valid_until->format('d M Y') }}</span></div>
                    @if ($coupon->discount_type === 'percentage')
                        <div class="flex items-center justify-between"><span
                                class="text-ink-secondary">Discount</span><span
                                class="text-ink font-semibold">{{ $coupon->discount_value }}% @if ($coupon->max_discount)
                                    (cap {{ money($coupon->max_discount) }})
                                @endif
                            </span></div>
                    @else
                        <div class="flex items-center justify-between"><span
                                class="text-ink-secondary">Discount</span><span
                                class="text-ink font-semibold">{{ money($coupon->discount_value) }}</span></div>
                    @endif
                    @if ($coupon->min_purchase)
                        <div class="flex items-center justify-between"><span class="text-ink-secondary">Min
                                Purchase</span><span class="text-ink">{{ money($coupon->min_purchase) }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
