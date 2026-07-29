@extends('seller.layouts.app')
@section('title', 'Coupon Analytics')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Coupon Analytics</h1>
            <p class="text-sm text-ink-secondary mt-1">Track redemption, savings, and active offers</p>
        </div>
        <a href="{{ route('seller.coupons.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" class="icon-xs"></i> Back to Coupons
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold">Total Coupons</div>
                <i data-lucide="ticket-percent" style="width:18px;height:18px;" class="text-ink-tertiary"></i>
            </div>
            <div class="text-2xl font-bold text-ink">{{ number_format($overview->total_coupons ?? 0) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold">Active</div>
                <i data-lucide="check-circle-2" style="width:18px;height:18px;" class="text-ink-tertiary"></i>
            </div>
            <div class="text-2xl font-bold" style="color: #059669">{{ number_format($overview->active_coupons ?? 0) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold">Total Uses</div>
                <i data-lucide="repeat" style="width:18px;height:18px;" class="text-ink-tertiary"></i>
            </div>
            <div class="text-2xl font-bold" style="color: #2563eb">{{ number_format($overview->total_uses ?? 0) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="flex items-center justify-between mb-2">
                <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold">Discount Given</div>
                <i data-lucide="hand-coins" style="width:18px;height:18px;" class="text-ink-tertiary"></i>
            </div>
            <div class="text-2xl font-bold text-brand">{{ money($totalDiscountGiven) }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider"><i data-lucide="trophy" class="icon-xs me-1"></i> Top Performing Coupons</h6>
                    <span class="text-xs text-ink-tertiary">By redemption count</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <thead>
                            <tr>
                                <th class="px-4 py-2.5">Code</th>
                                <th class="px-4 py-2.5">Discount</th>
                                <th class="px-4 py-2.5 text-right">Redemptions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border">
                            @forelse ($topCoupons as $coupon)
                                <tr class="hover:bg-surface-muted/50 transition-colors">
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2 py-0.5 text-xs font-mono font-semibold rounded-full bg-surface-muted text-ink">{{ $coupon->code }}</span>
                                        @if($coupon->title)
                                            <div class="text-xs text-ink-tertiary mt-0.5">{{ $coupon->title }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($coupon->discount_type === 'percentage')
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-purple-500 text-white">{{ $coupon->discount_value }}%</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">{{ money($coupon->discount_value) }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="font-bold text-ink">{{ number_format($coupon->used_count) }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center py-6 text-ink-tertiary">No coupon redemptions yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="lg:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider"><i data-lucide="bar-chart-3" class="icon-xs me-1"></i> Monthly Usage Trend</h6>
                </div>
                <div class="p-5">
                    @if ($monthlyUsage->isNotEmpty())
                        @php $maxUses = $monthlyUsage->max('uses') ?: 1; @endphp
                        <div class="space-y-3">
                            @foreach ($monthlyUsage as $row)
                                <div>
                                    <div class="flex items-center justify-between mb-1 text-xs">
                                        <span class="text-ink-secondary">{{ $row->month }}</span>
                                        <span class="font-semibold text-ink">{{ number_format($row->uses) }}</span>
                                    </div>
                                    <div class="w-full bg-surface-muted rounded-full h-2 overflow-hidden">
                                        <div class="bg-brand h-2 rounded-full transition-all" style="width: {{ round(($row->uses / $maxUses) * 100) }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i data-lucide="calendar-clock" style="width:36px;height:36px;" class="mx-auto text-ink-tertiary"></i>
                            <p class="text-ink-tertiary mb-0 mt-2 text-sm">No usage data yet.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection