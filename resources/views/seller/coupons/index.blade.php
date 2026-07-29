@extends('seller.layouts.app')
@section('title', 'Coupons')

@section('content')
<div class="container-fluid px-0">
    <div class="flex flex-wrap justify-between items-center mb-3">
        <h4 class="font-bold mb-0 text-ink">Coupons</h4>
        <a href="{{ route('seller.coupons.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors inline-flex items-center gap-1">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Create Coupon
        </a>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
        <div class="p-5 p-0">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3">Discount</th>
                            <th class="py-3">Min Purchase</th>
                            <th class="py-3">Uses</th>
                            <th class="py-3">Validity</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td class="px-4">
                                    <span class="font-semibold">{{ $coupon->code }}</span>
                                    @if ($coupon->title)
                                        <small class="block text-ink-tertiary">{{ $coupon->title }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($coupon->discount_type === 'percentage')
                                        <span class="badge badge-soft-primary">{{ $coupon->discount_value }}% Off</span>
                                    @else
                                        <span class="badge badge-soft-success">{{ money($coupon->discount_value) }} Off</span>
                                    @endif
                                    @if ($coupon->max_discount)
                                        <small class="block text-ink-tertiary">Max: {{ money($coupon->max_discount) }}</small>
                                    @endif
                                </td>
                                <td>{{ $coupon->min_purchase > 0 ? money($coupon->min_purchase) : 'N/A' }}</td>
                                <td>
                                    @if ($coupon->usage_limit)
                                        {{ $coupon->used_count }}/{{ $coupon->usage_limit }}
                                    @else
                                        {{ $coupon->used_count }}/∞
                                    @endif
                                </td>
                                <td class="small">
                                    {{ $coupon->valid_from->format('d M Y') }} - {{ $coupon->valid_until->format('d M Y') }}
                                </td>
                                <td>
                                    @if ($coupon->status)
                                        <span class="badge badge-soft-success">Active</span>
                                    @else
                                        <span class="badge badge-soft-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-right">
                                    <a href="{{ route('seller.coupons.edit', $coupon) }}" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors border">
                                        <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                    </a>
                                    <form method="POST" action="{{ route('seller.coupons.destroy', $coupon) }}" class="inline"
                                          onsubmit="return confirm('Delete this coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-3 py-1.5 bg-surface-muted text-ink text-sm font-medium border border-border rounded-xs hover:bg-border/30 focus:outline-none transition-colors border text-feedback-danger">
                                            <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-ink-tertiary">
                                    <i data-feather="tag" style="width: 48px; height: 48px;" class="mb-3"></i>
                                    <p class="mb-0">No coupons yet.</p>
                                    <a href="{{ route('seller.coupons.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors mt-2">Create Your First Coupon</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($coupons->hasPages())
            <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t flex justify-end">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
