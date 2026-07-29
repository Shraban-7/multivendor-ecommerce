@extends('seller.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back
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
                        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
