@extends('seller.layouts.app')
@section('title', 'Create Coupon')

@section('content')
<div class="container-fluid px-0">
    <div class="flex items-center gap-2 mb-3">
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light btn-sm">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="font-bold mb-0 text-ink">Create Coupon</h4>
    </div>

    <div class="grid grid-cols-1">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <form method="POST" action="{{ route('seller.coupons.store') }}">
                    @csrf
                    <div class="p-5">
                        @include('seller.coupons._form')
                    </div>
                    <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t text-right">
                        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                    <h6 class="font-semibold mb-0">Tips</h6>
                </div>
                <div class="p-5 text-sm">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Use a unique, memorable coupon code.</span>
                        </li>
                        <li class="mb-2 flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Set a usage limit to control redemptions.</span>
                        </li>
                        <li class="mb-2 flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Limit to specific products for targeted promotions.</span>
                        </li>
                        <li class="flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-brand shrink-0 mt-1"></i>
                            <span>Set a minimum purchase amount to increase average order value.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
