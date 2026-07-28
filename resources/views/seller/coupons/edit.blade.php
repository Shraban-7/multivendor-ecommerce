@extends('seller.layouts.app')
@section('title', 'Edit Coupon')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex align-items-center gap-2 mb-3">
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back
        </a>
        <h4 class="fw-bold mb-0 text-dark">Edit Coupon: {{ $coupon->code }}</h4>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <form method="POST" action="{{ route('seller.coupons.update', $coupon) }}">
                    @csrf
                    <div class="card-body">
                        @include('seller.coupons._form', ['coupon' => $coupon])
                    </div>
                    <div class="card-footer bg-white border-top text-end">
                        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light border me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Coupon</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
