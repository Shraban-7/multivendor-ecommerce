@extends('seller.layouts.app')
@section('title', 'Coupons')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Coupons</h1>
            <p class="text-sm text-ink-secondary mt-1">Create discount codes to boost conversions</p>
        </div>
        <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary btn-sm">
            <i data-lucide="plus" class="icon-xs"></i> Create Coupon
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-4">
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Total Coupons</div>
            <div class="text-xl font-bold text-ink">{{ number_format($summary['total']) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Active</div>
            <div class="text-xl font-bold" style="color: #059669">{{ number_format($summary['active']) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Expired</div>
            <div class="text-xl font-bold" style="color: #dc2626">{{ number_format($summary['expired']) }}</div>
        </div>
        <div class="bg-white border border-border rounded-sm shadow-sm p-4">
            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Total Redemptions</div>
            <div class="text-xl font-bold" style="color: #2563eb">{{ number_format($summary['total_used']) }}</div>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
        <div class="px-4 py-3 border-b border-border bg-surface-muted">
            <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Search & Filter</h6>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('seller.coupons.index') }}" class="flex items-end gap-3 flex-wrap">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Search</label>
                    <input type="text" name="q" value="{{ request('q') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Coupon code or title…">
                </div>
                <div class="w-44">
                    <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                    <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                        <option value="">All</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expired</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">
                    <i data-lucide="search" class="icon-xs"></i> Filter
                </button>
                @if(request('q') || request('status'))
                    <a href="{{ route('seller.coupons.index') }}" class="btn btn-light btn-sm">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2.5">Code</th>
                        <th class="px-4 py-2.5">Discount</th>
                        <th class="px-4 py-2.5">Min Purchase</th>
                        <th class="px-4 py-2.5">Uses</th>
                        <th class="px-4 py-2.5">Validity</th>
                        <th class="px-4 py-2.5">Status</th>
                        <th class="px-4 py-2.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @forelse ($coupons as $coupon)
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-mono font-semibold rounded-full bg-surface-muted text-ink">{{ $coupon->code }}</span>
                                @if ($coupon->title)
                                    <div class="text-sm text-ink-tertiary mt-0.5">{{ $coupon->title }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                @if ($coupon->discount_type === 'percentage')
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-purple-500 text-white">{{ $coupon->discount_value }}%</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">{{ money($coupon->discount_value) }}</span>
                                @endif
                                @if ($coupon->max_discount)
                                    <div class="text-xs text-ink-tertiary mt-0.5">Cap: {{ money($coupon->max_discount) }}</div>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm">{{ $coupon->min_purchase > 0 ? money($coupon->min_purchase) : '—' }}</td>
                            <td class="px-4 py-3 text-sm">
                                @if ($coupon->usage_limit)
                                    <span class="font-semibold">{{ $coupon->used_count }}</span> / {{ $coupon->usage_limit }}
                                @else
                                    <span class="font-semibold">{{ $coupon->used_count }}</span> / <span class="text-ink-tertiary">∞</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-ink-secondary">
                                {{ $coupon->valid_from->format('d M Y') }} → {{ $coupon->valid_until->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3">
                                @if(!$coupon->status)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-500 text-white">Inactive</span>
                                @elseif($coupon->valid_until->isPast())
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-500 text-white">Expired</span>
                                @elseif(!$coupon->valid_from->isPast() && $coupon->valid_from->isFuture())
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-blue-500 text-white">Scheduled</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">Active</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="text-xs text-ink-tertiary">{{ $coupon->products->count() }} product(s)</span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('seller.coupons.analytics') }}" class="btn btn-light btn-sm" title="Analytics">
                                        <i data-lucide="bar-chart-3" style="width:14px;height:14px;"></i>
                                    </a>
                                    <a href="{{ route('seller.coupons.edit', $coupon) }}" class="btn btn-light btn-sm" title="Edit">
                                        <i data-lucide="edit" style="width:14px;height:14px;"></i>
                                    </a>
                                    <form method="POST" action="{{ route('seller.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('Delete this coupon? This cannot be undone.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i data-lucide="trash-2" style="width:14px;height:14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-10">
                                <i data-lucide="ticket-percent" style="width:48px;height:48px;" class="mx-auto text-ink-tertiary"></i>
                                <p class="text-ink-tertiary mt-3 mb-0">No coupons found.</p>
                                <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary btn-sm mt-3">
                                    <i data-lucide="plus" class="icon-xs"></i> Create Your First Coupon
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($coupons->hasPages())
            <div class="flex justify-end px-4 py-3 border-t border-border">
                {{ $coupons->links() }}
            </div>
        @endif
    </div>
@endsection