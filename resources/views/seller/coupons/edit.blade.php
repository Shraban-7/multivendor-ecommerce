@extends('seller.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
    <div class="flex items-center gap-2 mb-4">
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-ink">Edit Coupon</h1>
            <p class="text-sm text-ink-secondary mt-0.5 font-mono">{{ $coupon->code }}@if($coupon->title) · {{ $coupon->title }}@endif</p>
        </div>
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
                <form method="POST" action="{{ route('seller.coupons.update', $coupon) }}">
                    @csrf
                    @method('PUT')
                    <div class="p-5">
                        @include('seller.coupons._form', ['coupon' => $coupon])
                    </div>
                    <div class="flex justify-end px-4 py-3 border-t border-border bg-surface-muted gap-2">
                        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light">Cancel</a>
                        <button type="submit" class="btn btn-primary"><i data-lucide="save" class="icon-xs"></i> Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider"><i data-lucide="activity" class="icon-xs me-1"></i> Usage</h6>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-ink-secondary">Redemptions</span>
                        <span class="font-semibold text-ink">{{ number_format($coupon->used_count) }}@if($coupon->usage_limit) / {{ number_format($coupon->usage_limit) }}@endif</span>
                    </div>
                    @if($coupon->usage_limit)
                        @php $pct = round(min(100, ($coupon->used_count / max(1, $coupon->usage_limit)) * 100)); @endphp
                        <div class="w-full bg-surface-muted rounded-full h-1.5 mb-3 overflow-hidden">
                            <div class="bg-brand h-1.5 rounded-full" style="width: {{ $pct }}%"></div>
                        </div>
                    @endif
                    <div class="flex items-center justify-between mb-2 text-sm text-ink-secondary">
                        <span>Valid From</span>
                        <span class="text-ink">{{ $coupon->valid_from->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between mb-2 text-sm text-ink-secondary">
                        <span>Valid Until</span>
                        <span class="text-ink">{{ $coupon->valid_until->format('d M Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-ink-secondary">
                        <span>Products</span>
                        <span class="text-ink">{{ number_format($coupon->products->count()) }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider text-feedback-danger"><i data-lucide="trash-2" class="icon-xs me-1"></i> Danger Zone</h6>
                </div>
                <div class="p-5">
                    <form method="POST" action="{{ route('seller.coupons.destroy', $coupon) }}" onsubmit="return confirm('Delete this coupon? This cannot be undone.')">
                        @csrf
                        @method('DELETE')
                        <p class="text-sm text-ink-secondary mb-3">Permanently delete this coupon and unlink it from any products.</p>
                        <button type="submit" class="btn btn-danger btn-sm w-full">
                            <i data-lucide="trash-2" class="icon-xs"></i> Delete Coupon
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection