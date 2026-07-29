@extends('seller.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.coupons.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors border btn-sm inline-flex items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Edit Coupon: {{ $coupon->code }}</h4>
    </div>

    <div class="grid grid-cols-1">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <form method="POST" action="{{ route('seller.coupons.update', $coupon) }}">
                    @csrf
                    <div class="p-5">
                        @include('seller.coupons._form', ['coupon' => $coupon])
                    </div>
                    <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t text-right">
                        <a href="{{ route('seller.coupons.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors border me-2">Cancel</a>
                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors">Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
