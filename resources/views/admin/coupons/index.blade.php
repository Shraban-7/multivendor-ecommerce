@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
    <h3 class="fw-bold mb-0">Coupons</h3>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-1">
        <i data-feather="plus" style="width: 16px; height: 16px;"></i> Create Coupon
    </a>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-header bg-white border-bottom py-3">
        <form method="GET" class="row g-2 align-items-end">
            <div class="col-auto">
                <select name="type" class="form-select form-select-sm">
                    <option value="">All Types</option>
                    <option value="global" {{ request('type') === 'global' ? 'selected' : '' }}>Global</option>
                    <option value="seller" {{ request('type') === 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search code/title..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-sm btn-primary">Filter</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-sm btn-light border">Reset</a>
            </div>
        </form>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4">Code</th>
                        <th class="py-3">Discount</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Seller</th>
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
                                    <span class="badge badge-soft-primary">{{ $coupon->discount_value }}%</span>
                                @else
                                    <span class="badge badge-soft-success">{{ money($coupon->discount_value) }}</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $coupon->isGlobal() ? 'badge-soft-info' : 'badge-soft-warning' }}">
                                    {{ $coupon->isGlobal() ? 'Global' : 'Seller' }}
                                </span>
                            </td>
                            <td>
                                @if ($coupon->seller)
                                    <small>{{ $coupon->seller->business_name ?? $coupon->seller->name }}</small>
                                @else
                                    <small class="text-muted">-</small>
                                @endif
                            </td>
                            <td>
                                @if ($coupon->usage_limit)
                                    {{ $coupon->used_count }}/{{ $coupon->usage_limit }}
                                @else
                                    {{ $coupon->used_count }}/∞
                                @endif
                            </td>
                            <td class="small">
                                {{ $coupon->valid_from->format('d M') }} - {{ $coupon->valid_until->format('d M Y') }}
                            </td>
                            <td>
                                <span class="badge {{ $coupon->status ? 'badge-soft-success' : 'badge-soft-secondary' }}">
                                    {{ $coupon->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-sm btn-light border">
                                    <i data-feather="edit" class="icon-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="d-inline"
                                      onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-light border text-danger">
                                        <i data-feather="trash-2" class="icon-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i data-feather="tag" style="width: 48px; height: 48px;" class="mb-3"></i>
                                <p class="mb-0">No coupons found.</p>
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
@endsection
