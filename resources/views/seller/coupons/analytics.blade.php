@extends('seller.layouts.app')
@section('title', 'Coupon Analytics')

@section('content')
<div class="container-fluid px-0">
    <div class="flex flex-wrap justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Coupon Analytics</h4>
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-outline-primary">
            <i data-lucide="arrow-left" style="width: 16px; height: 16px;"></i> Back to Coupons
        </a>
    </div>

    <div class="grid grid-cols-1 gap-3 mb-4">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="p-5">
                    <p class="text-ink-tertiary mb-1 text-sm">Total Coupons</p>
                    <h3 class="font-bold mb-0 text-ink">{{ $overview->total_coupons }}</h3>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="p-5">
                    <p class="text-ink-tertiary mb-1 text-sm">Active</p>
                    <h3 class="font-bold mb-0 text-feedback-success">{{ $overview->active_coupons }}</h3>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="p-5">
                    <p class="text-ink-tertiary mb-1 text-sm">Total Uses</p>
                    <h3 class="font-bold mb-0 text-ink">{{ $overview->total_uses }}</h3>
                </div>
            </div>
        </div>
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="p-5">
                    <p class="text-ink-tertiary mb-1 text-sm">Total Discount Given</p>
                    <h3 class="font-bold mb-0 text-brand">{{ money($totalDiscountGiven) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-3">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                    <h5 class="font-semibold text-ink mb-0">Top Performing Coupons</h5>
                </div>
                <div class="p-5 p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle mb-0">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th class="py-3 px-4">Code</th>
                                    <th class="py-3">Discount</th>
                                    <th class="py-3">Used</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topCoupons as $coupon)
                                    <tr>
                                        <td class="px-4 font-semibold">{{ $coupon->code }}</td>
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
                                        <td colspan="3" class="text-center py-4 text-ink-tertiary">No coupon usage data yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b">
                    <h5 class="font-semibold text-ink mb-0">Monthly Usage Trend</h5>
                </div>
                <div class="p-5">
                    @if ($monthlyUsage->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle mb-0">
                                <thead class="bg-surface-muted">
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
                        <p class="text-ink-tertiary mb-0">No usage data yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
