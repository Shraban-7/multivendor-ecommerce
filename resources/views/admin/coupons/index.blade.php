@php
    $summary = $summary ?? ['total' => 0, 'global' => 0, 'seller' => 0, 'active' => 0, 'expired' => 0];
@endphp
@extends('admin.layouts.app')
@section('title', 'Coupons')

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #a855f7, #c084fc, #e879f9);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="ticket-percent" class="text-[#a855f7]" style="width:12px;height:12px;"></i>
                    <span>Reach</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Coupons</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Coupons</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#a855f7]/15 text-[#a855f7]">
                        <i data-lucide="percent" style="width:11px;height:11px;" class="me-1"></i> Discount Engine
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Manage discount codes across the entire marketplace.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Coupon
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',   'label' => 'Total',    'top' => '#a855f7', 'text' => 'text-[#a855f7]',         'icon' => 'ticket-percent'],
        ['key' => 'global',  'label' => 'Global',   'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'globe-2'],
        ['key' => 'seller',  'label' => 'Seller',   'top' => '#fb923c', 'text' => 'text-feedback-warning',  'icon' => 'store'],
        ['key' => 'active',  'label' => 'Active',   'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'expired', 'label' => 'Expired',  'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'x-circle'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ number_format($summary[$tile['key']]) }}</h3>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ FILTER + TABLE ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('search') || request('type') || request('status'))
            <a href="{{ route('admin.coupons.index') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form method="GET" action="{{ route('admin.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-2">
                <select name="type"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Types</option>
                    <option value="global" @selected(request('type') === 'global')>Global</option>
                    <option value="seller" @selected(request('type') === 'seller')>Seller</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All Status</option>
                    <option value="active"   @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="expired"  @selected(request('status') === 'expired')>Expired</option>
                </select>
            </div>
            <div class="md:col-span-6 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search by code or title…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-2">
                <button type="submit" class="btn btn-primary w-full">
                    <i data-lucide="search" style="width:14px;height:14px;"></i> Filter
                </button>
            </div>
        </form>
    </div>

    <div class="px-4 pt-4 pb-1 text-xs text-ink-tertiary">
        Showing <span class="text-ink-emphasis font-semibold">{{ $coupons->firstItem() ?? 0 }}</span>
        – <span class="text-ink-emphasis font-semibold">{{ $coupons->lastItem() ?? 0 }}</span>
        of <span class="text-ink-emphasis font-semibold">{{ $coupons->total() }}</span> coupons
    </div>

    <div class="overflow-x-auto px-4 pb-4">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-surface-muted">
                <tr>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Code</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Type</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Discount</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Min</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Uses</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Validity</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-3 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    @php
                        $isExpired   = $coupon->valid_until->isPast();
                        $isScheduled = $coupon->valid_from->isFuture();
                        $pillBg = match (true) {
                            !$coupon->status => 'bg-surface-muted text-ink-tertiary',
                            $isExpired       => 'bg-feedback-danger/15 text-feedback-danger',
                            $isScheduled     => 'bg-feedback-info/15 text-feedback-info',
                            default          => 'bg-feedback-success/15 text-feedback-success',
                        };
                        $pillLabel = match (true) {
                            !$coupon->status => 'Inactive',
                            $isExpired       => 'Expired',
                            $isScheduled     => 'Scheduled',
                            default          => 'Active',
                        };
                    @endphp
                    <tr class="border-t border-border hover:bg-surface-muted/40 transition-colors">
                        <td class="px-4 py-3">
                            <code class="px-1.5 py-0.5 rounded-xs bg-surface-muted text-ink-emphasis font-mono text-xs font-semibold">{{ $coupon->code }}</code>
                            @if ($coupon->title)
                                <div class="text-xs text-ink-tertiary mt-1">{{ $coupon->title }}</div>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $coupon->type === 'global' ? 'bg-feedback-info/15 text-feedback-info' : 'bg-feedback-warning/15 text-feedback-warning' }}">
                                {{ ucfirst($coupon->type) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-ink-secondary">
                            @if ($coupon->discount_type === 'percentage')
                                <span class="font-semibold text-[#a855f7]">{{ $coupon->discount_value }}%</span>
                                @if ($coupon->max_discount)
                                    <small class="text-ink-tertiary block">Cap {{ money($coupon->max_discount) }}</small>
                                @endif
                            @else
                                <span class="font-semibold text-feedback-success">{{ money($coupon->discount_value) }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary">{{ $coupon->min_purchase > 0 ? money($coupon->min_purchase) : '—' }}</td>
                        <td class="px-4 py-3 text-center text-sm whitespace-nowrap">
                            <span class="font-semibold text-ink-emphasis">{{ $coupon->used_count }}</span>
                            <span class="text-ink-tertiary">/</span>
                            <span class="text-ink-secondary">{{ $coupon->usage_limit ?: '∞' }}</span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary whitespace-nowrap">
                            {{ $coupon->valid_from->format('d M Y') }} <span class="text-ink-tertiary">→</span> {{ $coupon->valid_until->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $pillLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn btn-light btn-sm">
                                <i data-lucide="pencil" style="width:13px;height:13px;"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <div class="py-10 text-center">
                                <i data-lucide="ticket-percent" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No coupons yet</p>
                                <p class="text-ink-tertiary text-xs">Create the first marketplace coupon.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex justify-end p-4 border-t border-border">
        {{ $coupons->links() }}
    </div>
</section>

@endsection
