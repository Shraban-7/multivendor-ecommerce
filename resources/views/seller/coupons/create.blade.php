@extends('seller.layouts.app')
@section('title', 'Create Coupon')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Create Coupon</h4>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <form method="POST" action="{{ route('seller.coupons.store') }}">
                    @csrf
                    <div class="card-body">
                        @include('seller.coupons._form')
                    </div>
                    <div class="card-footer bg-white border-top text-end">
                        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light border me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create Coupon</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h6 class="fw-semibold mb-0">Tips</h6>
                </div>
                <div class="card-body small">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Use a unique, memorable coupon code.</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Set a usage limit to control redemptions.</span>
                        </li>
                        <li class="mb-2 d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Limit to specific products for targeted promotions.</span>
                        </li>
                        <li class="d-flex gap-2">
                            <i data-feather="info" style="width: 16px; height: 16px;" class="text-primary flex-shrink-0 mt-1"></i>
                            <span>Set a minimum purchase amount to increase average order value.</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
