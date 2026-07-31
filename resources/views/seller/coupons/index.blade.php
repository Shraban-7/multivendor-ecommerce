@php
    $summary = $summary ?? ['total' => 0, 'active' => 0, 'expired' => 0, 'total_used' => 0];
@endphp
@extends('seller.layouts.app')
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
                    <span>Workspace</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Coupons</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Coupons</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-[#a855f7]/15 text-[#a855f7]">
                        <i data-lucide="percent-circle" style="width:11px;height:11px;" class="me-1"></i> Discount Engine
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Create and monitor discount codes that boost your conversions.</p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary">
                    <i data-lucide="plus" style="width:15px;height:15px;"></i> Create Coupon
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ KPI TILES ═══ --}}
@php
    $tiles = [
        ['key' => 'total',      'label' => 'Total Coupons',    'top' => '#a855f7', 'text' => 'text-[#a855f7]',         'icon' => 'ticket-percent'],
        ['key' => 'active',     'label' => 'Active',           'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'check-circle-2'],
        ['key' => 'expired',    'label' => 'Expired',          'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'x-circle'],
        ['key' => 'total_used', 'label' => 'Redemptions',      'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'shopping-bag'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
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

{{-- ═══ FILTER + TABLE CARD ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Search & Filter</h3>
        <div class="grow"></div>
        @if(request('q') || request('status'))
            <a href="{{ route('seller.coupons.index') }}" class="text-[11px] font-semibold text-ink-tertiary hover:text-ink-emphasis inline-flex items-center gap-1">
                <i data-lucide="x" style="width:11px;height:11px;"></i> Clear
            </a>
        @endif
    </div>
    <div class="p-4 border-t border-border">
        <form method="GET" action="{{ route('seller.coupons.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-2">
            <div class="md:col-span-7 relative">
                <i data-lucide="search" class="absolute top-1/2 -translate-y-1/2 text-ink-tertiary" style="width:14px;height:14px; left: 10px;"></i>
                <input type="text" name="q" value="{{ request('q') }}"
                       placeholder="Search by coupon code or title…"
                       class="w-full pl-8 pr-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div class="md:col-span-3">
                <select name="status"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All statuses</option>
                    <option value="active"   @selected(request('status') === 'active')>Active</option>
                    <option value="inactive" @selected(request('status') === 'inactive')>Inactive</option>
                    <option value="expired"  @selected(request('status') === 'expired')>Expired</option>
                </select>
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
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Code</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Discount</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Min Purchase</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-center">Uses</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Validity</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary">Status</th>
                    <th class="px-4 py-2.5 text-[11px] font-semibold uppercase tracking-wider text-ink-tertiary text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($coupons as $coupon)
                    @php
                        $isExpired   = $coupon->valid_until->isPast();
                        $isScheduled = $coupon->valid_from->isFuture();
                        $pillBg = match (true) {
                            !$coupon->status   => 'bg-surface-muted text-ink-tertiary',
                            $isExpired         => 'bg-feedback-danger/15 text-feedback-danger',
                            $isScheduled       => 'bg-feedback-info/15 text-feedback-info',
                            default            => 'bg-feedback-success/15 text-feedback-success',
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
                            @if ($coupon->discount_type === 'percentage')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xs text-[11px] font-bold bg-[#a855f7]/15 text-[#a855f7]">
                                    <i data-lucide="percent" style="width:11px;height:11px;" class="me-1"></i> {{ $coupon->discount_value }}%
                                </span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-xs text-[11px] font-bold bg-feedback-success/15 text-feedback-success">
                                    {{ money($coupon->discount_value) }}
                                </span>
                            @endif
                            @if ($coupon->max_discount)
                                <small class="text-ink-tertiary block mt-1">Cap: {{ money($coupon->max_discount) }}</small>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-sm text-ink-secondary">
                            {{ $coupon->min_purchase > 0 ? money($coupon->min_purchase) : '—' }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm whitespace-nowrap">
                            <span class="font-semibold text-ink-emphasis">{{ $coupon->used_count }}</span>
                            <span class="text-ink-tertiary">/</span>
                            <span class="text-ink-secondary">
                                {{ $coupon->usage_limit ? $coupon->usage_limit : '∞' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-xs text-ink-secondary whitespace-nowrap">
                            <i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>
                            {{ $coupon->valid_from->format('d M Y') }} → {{ $coupon->valid_until->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                                {{ $pillLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <div class="dropdown inline-block">
                                <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i data-lucide="more-horizontal" style="width:14px;height:14px;"></i>
                                    <span class="ms-1">Manage</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end py-1" style="min-width:170px;">
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('seller.coupons.analytics', $coupon) }}">
                                            <i data-lucide="bar-chart-3" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> Analytics
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item py-1.5" href="{{ route('seller.coupons.edit', $coupon) }}">
                                            <i data-lucide="pencil" style="width:13px;height:13px;" class="me-2 text-ink-tertiary"></i> Edit
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li>
                                        <form method="POST" action="{{ route('seller.coupons.destroy', $coupon) }}"
                                              onsubmit="return confirm('Delete this coupon? This cannot be undone.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="dropdown-item py-1.5 text-feedback-danger">
                                                <i data-lucide="trash-2" style="width:13px;height:13px;" class="me-2"></i> Delete
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="py-10 text-center">
                                <i data-lucide="ticket-percent" class="text-ink-tertiary mx-auto mb-2" style="width:36px;height:36px;"></i>
                                <p class="text-ink-soft font-semibold mb-1">No coupons yet</p>
                                <p class="text-ink-tertiary text-xs mb-3">Create your first coupon to start discounting.</p>
                                <a href="{{ route('seller.coupons.create') }}" class="btn btn-primary btn-sm">
                                    <i data-lucide="plus" style="width:14px;height:14px;"></i> Create Your First Coupon
                                </a>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($coupons->hasPages())
        <div class="flex justify-end p-4 border-t border-border">
            {{ $coupons->links() }}
        </div>
    @endif
</section>

@endsection
