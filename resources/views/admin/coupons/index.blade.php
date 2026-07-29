@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')
<div class="flex flex-wrap justify-between items-center mb-3">
    <h3 class="font-bold mb-0">Coupons</h3>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i data-feather="plus" style="width: 16px; height: 16px;"></i> Create Coupon
    </a>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm" style="border-radius: 12px;">
    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between bg-white border-b py-3">
        <form method="GET" class="grid grid-cols-1 gap-2 items-end">
            <div class="col-auto">
                <select name="type" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Types</option>
                    <option value="global" {{ request('type') === 'global' ? 'selected' : '' }}>Global</option>
                    <option value="seller" {{ request('type') === 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="status" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="text" name="search" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Search code/title..." value="{{ request('search') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Filter</button>
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">Reset</a>
            </div>
        </form>
    </div>
    <div class="p-5 p-0">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse table-hover align-middle mb-0">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="py-3 px-4">Code</th>
                        <th class="py-3">Discount</th>
                        <th class="py-3">Type</th>
                        <th class="py-3">Seller</th>
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
                                    <small class="text-ink-tertiary">-</small>
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
                            <td class="text-right">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-light btn-sm">
                                    <i data-feather="edit" class="icon-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline"
                                      onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i data-feather="trash-2" class="icon-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-ink-tertiary">
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
        <div class="px-5 py-3 border-t border-border bg-surface-muted bg-white border-t flex justify-end">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection
