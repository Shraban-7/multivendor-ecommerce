@extends('seller.layouts.app')
@section('title', 'Coupon Analytics')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Coupon Analytics</h4>
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-1">
            <i data-feather="arrow-left" style="width: 16px; height: 16px;"></i> Back to Coupons
        </a>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Coupons</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $overview->total_coupons }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Active</p>
                    <h3 class="fw-bold mb-0 text-success">{{ $overview->active_coupons }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Uses</p>
                    <h3 class="fw-bold mb-0 text-dark">{{ $overview->total_uses }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-body">
                    <p class="text-muted mb-1 small">Total Discount Given</p>
                    <h3 class="fw-bold mb-0 text-primary">{{ money($totalDiscountGiven) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold text-dark mb-0">Top Performing Coupons</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="py-3 px-4">Code</th>
                                    <th class="py-3">Discount</th>
                                    <th class="py-3">Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topCoupons as $coupon)
                                    <tr>
                                        <td class="px-4 fw-semibold">{{ $coupon->code }}</td>
                                        <td>
                                            @if ($coupon->discount_type === 'percentage')
                                                {{ $coupon->discount_value }}%
                                            @else
                                                {{ money($coupon->discount_value) }}
                                            @endif
                                        </td>
                                        <td>{{ $coupon->used_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center py-4 text-muted">No coupon usage data yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm" style="border-radius: 12px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="fw-semibold text-dark mb-0">Monthly Usage Trend</h5>
                </div>
                <div class="card-body">
                    @if ($monthlyUsage->isNotEmpty())
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="py-3">Month</th>
                                        <th class="py-3">Uses</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyUsage as $row)
                                        <tr>
                                            <td>{{ $row->month }}</td>
                                            <td>{{ $row->uses }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted mb-0">No usage data yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
