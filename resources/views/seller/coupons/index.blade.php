@extends('seller.layouts.app')
@section('title', 'Coupons')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0 text-dark">Coupons</h4>
        <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
            <i data-feather="plus" style="width: 16px; height: 16px;"></i> Create Coupon
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="py-3 px-4">Code</th>
                            <th class="py-3">Discount</th>
                            <th class="py-3">Min Purchase</th>
                            <th class="py-3">Uses</th>
                            <th class="py-3">Validity</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($coupons as $coupon)
                            <tr>
                                <td class="px-4">
                                    <span class="fw-semibold">{{ $coupon->code }}</span>
                                    @if ($coupon->title)
                                        <small class="d-block text-muted">{{ $coupon->title }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if ($coupon->discount_type === 'percentage')
                                        <span class="badge badge-soft-primary">{{ $coupon->discount_value }}% Off</span>
                                    @else
                                        <span class="badge badge-soft-success">{{ money($coupon->discount_value) }} Off</span>
                                    @endif
                                    @if ($coupon->max_discount)
                                        <small class="d-block text-muted">Max: {{ money($coupon->max_discount) }}</small>
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
                                <td class="text-end">
                                    <a href="{{ route('seller.coupons.edit', $coupon) }}" class="btn btn-sm btn-light border">
                                        <i data-feather="edit" style="width: 14px; height: 14px;"></i>
                                    </a>
                                    <form method="POST" action="{{ route('seller.coupons.destroy', $coupon) }}" class="d-inline"
                                          onsubmit="return confirm('Delete this coupon?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger">
                                            <i data-feather="trash-2" style="width: 14px; height: 14px;"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i data-feather="tag" style="width: 48px; height: 48px;" class="mb-3"></i>
                                    <p class="mb-0">No coupons yet.</p>
                                    <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary mt-2">Create Your First Coupon</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if ($coupons->hasPages())
            <div class="card-footer bg-white border-top d-flex justify-content-end">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
