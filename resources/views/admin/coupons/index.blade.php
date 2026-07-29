@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')
<div class="flex justify-between items-start mb-4">
    <div>
        <h1 class="text-xl font-semibold text-ink">Coupons</h1>
        <p class="text-sm text-ink-secondary mt-1">Manage discount coupons across the marketplace</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
        <i data-lucide="plus" class="icon-xs"></i> Create Coupon
    </a>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border bg-surface-muted">
        <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
    </div>
    <div class="p-4">
        <form method="GET" class="flex items-center gap-3 flex-wrap">
            <div class="w-48">
                <select name="type" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Types</option>
                    <option value="global" {{ request('type') === 'global' ? 'selected' : '' }}>Global</option>
                    <option value="seller" {{ request('type') === 'seller' ? 'selected' : '' }}>Seller</option>
                </select>
            </div>
            <div class="w-44">
                <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="search" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Search code or title..." value="{{ request('search') }}">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="search" class="icon-xs"></i> Filter
            </button>
            @if(request('search') || request('type') || request('status'))
                <a href="{{ route('admin.coupons.index') }}" class="btn btn-light btn-sm">Clear</a>
            @endif
        </form>
    </div>
</div>

<div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead>
                <tr>
                    <th class="py-3 px-4">Code</th>
                    <th class="py-3">Discount</th>
                    <th class="py-3">Type</th>
                    <th class="py-3">Seller</th>
                    <th class="py-3">Uses</th>
                    <th class="py-3">Validity</th>
                    <th class="py-3">Status</th>
                    <th class="py-3 text-right pr-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    <tr>
                        <td class="px-4">
                            <span class="font-semibold text-ink">{{ $coupon->code }}</span>
                            @if ($coupon->title)
                                <small class="block text-ink-tertiary">{{ $coupon->title }}</small>
                            @endif
                        </td>
                        <td>
                            @if ($coupon->discount_type === 'percentage')
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-brand-deep rounded-full">{{ $coupon->discount_value }}%</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">{{ money($coupon->discount_value) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white {{ $coupon->isGlobal() ? 'bg-feedback-info' : 'bg-yellow-400' }} rounded-full">
                                {{ $coupon->isGlobal() ? 'Global' : 'Seller' }}
                            </span>
                        </td>
                        <td>
                            @if ($coupon->seller)
                                <small class="text-ink-secondary">{{ $coupon->seller->business_name ?? $coupon->seller->name }}</small>
                            @else
                                <small class="text-ink-tertiary">—</small>
                            @endif
                        </td>
                        <td class="text-ink-secondary text-xs">
                            @if ($coupon->usage_limit)
                                {{ $coupon->used_count }}/{{ $coupon->usage_limit }}
                            @else
                                {{ $coupon->used_count }}/∞
                            @endif
                        </td>
                        <td class="text-ink-secondary text-xs">
                            {{ $coupon->valid_from->format('d M') }} - {{ $coupon->valid_until->format('d M Y') }}
                        </td>
                        <td>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white {{ $coupon->status ? 'bg-green-500' : 'bg-ink-tertiary' }} rounded-full">
                                {{ $coupon->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="text-right pr-4">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-light btn-sm">
                                    <i data-lucide="edit" class="icon-xs"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}" class="inline"
                                      onsubmit="return confirm('Delete this coupon?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i data-lucide="trash-2" class="icon-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-8 text-ink-tertiary">
                            <p class="mb-0">No coupons found.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($coupons->hasPages())
        <div class="px-4 py-3 border-t border-border flex justify-end">
            {{ $coupons->links() }}
        </div>
    @endif
</div>
@endsection