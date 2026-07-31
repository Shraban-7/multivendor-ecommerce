@php
    use App\Domain\Vendor\Enums\PerformancePeriod;
    use App\Domain\Vendor\Enums\PerformanceTier;

    $pageTitle = 'Seller Performance';

    $tierBg = [
        PerformanceTier::EXCELLENT->value => 'bg-emerald-500',
        PerformanceTier::GOOD->value      => 'bg-blue-500',
        PerformanceTier::AVERAGE->value   => 'bg-amber-500',
        PerformanceTier::POOR->value      => 'bg-rose-500',
        PerformanceTier::NEW->value       => 'bg-gray-500',
    ];

    $tierBadge = [
        PerformanceTier::EXCELLENT->value => 'bg-emerald-500 text-white',
        PerformanceTier::GOOD->value      => 'bg-blue-500 text-white',
        PerformanceTier::AVERAGE->value   => 'bg-amber-500 text-white',
        PerformanceTier::POOR->value      => 'bg-rose-500 text-white',
        PerformanceTier::NEW->value       => 'bg-gray-500 text-white',
    ];
@endphp
@extends('admin.layouts.app')
@section('title', $pageTitle)

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="gauge" class="text-feedback-warning" style="width:12px;height:12px;"></i>
                    <span>Sellers</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Performance</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $pageTitle }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-brand-tint text-brand-deep">
                        <i data-lucide="bar-chart-3" style="width:11px;height:11px;" class="me-1"></i> {{ $period->label() }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Tier breakdown, leaderboard and seller-by-seller performance metrics.</p>
            </div>
            <form method="GET" class="flex items-center gap-2">
                <label class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">Period:</label>
                <select name="period"
                        class="px-3 py-1.5 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors"
                        onchange="this.form.submit()">
                    @foreach (PerformancePeriod::cases() as $p)
                        <option value="{{ $p->value }}" @selected($period->value === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
            </form>
        </div>
    </div>
</section>

{{-- ═══ TIER BREAKDOWN TILES ═══ --}}
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
    @foreach (PerformanceTier::cases() as $tier)
        @php $bg = $tierBg[$tier->value] ?? 'bg-gray-500'; @endphp
        <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 {{ $bg }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $tier->label() }}</p>
                    <h3 class="mb-0 font-bold text-2xl text-ink-emphasis mt-1">{{ number_format((int) ($stats[$tier->value] ?? 0)) }}</h3>
                    <small class="text-ink-tertiary">{{ Str::plural('seller', (int) ($stats[$tier->value] ?? 0)) }} in {{ $period->label() }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center"
                      style="background-color: {{ $tier->color() }}1a; color: {{ $tier->color() }};">
                    <i data-lucide="{{
                        $tier === PerformanceTier::EXCELLENT ? 'trophy' :
                        ($tier === PerformanceTier::GOOD ? 'thumbs-up' :
                        ($tier === PerformanceTier::AVERAGE ? 'minus-circle' :
                        ($tier === PerformanceTier::POOR ? 'shield-alert' : 'sparkles'))) }}"
                       style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    {{-- ═══ TOP PERFORMERS LEADERBOARD ═══ --}}
    <section class="lg:col-span-2 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="trophy" class="text-feedback-warning" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Top Performers</h5>
            </div>
            <small class="text-ink-tertiary">top 10 by {{ $period->label() }} score</small>
        </div>
        <div class="p-4">
            @forelse ($leaderboard as $i => $row)
                <div class="flex items-center justify-between gap-3 py-2 @if (!$loop->last) border-b border-border @endif">
                    <div class="flex items-center gap-3 min-w-0">
                        @if ($i === 0)
                            <span class="shrink-0 w-7 h-7 rounded-full bg-amber-100 text-feedback-warning flex items-center justify-center">
                                <i data-lucide="crown" style="width:14px;height:14px;"></i>
                            </span>
                        @elseif ($i === 1)
                            <span class="shrink-0 w-7 h-7 rounded-full bg-blue-50 text-feedback-info flex items-center justify-center">
                                <i data-lucide="medal" style="width:14px;height:14px;"></i>
                            </span>
                        @elseif ($i === 2)
                            <span class="shrink-0 w-7 h-7 rounded-full bg-emerald-50 text-feedback-success flex items-center justify-center">
                                <i data-lucide="award" style="width:14px;height:14px;"></i>
                            </span>
                        @else
                            <span class="shrink-0 w-7 h-7 rounded-full bg-surface-muted text-ink-tertiary flex items-center justify-center font-bold text-xs">
                                {{ $i + 1 }}
                            </span>
                        @endif
                        <div class="min-w-0">
                            <p class="mb-0 font-semibold text-ink-emphasis truncate">{{ $row->seller?->business_name ?? 'Seller #'.$row->seller_id }}</p>
                            <small class="text-ink-tertiary">@{{ $row->seller?->username ?? '—' }} · {{ $row->total_orders }} orders</small>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <strong class="font-mono font-bold text-ink-emphasis">{{ number_format((float) $row->overall_score, 2) }}</strong>
                        <br>
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $tierBadge[$row->tier] ?? 'bg-surface-muted text-ink-tertiary' }}">
                            {{ $row->tierLabel() }}
                        </span>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-sm text-ink-tertiary">
                    <i data-lucide="trophy" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
                    <p class="mb-0 font-semibold text-ink-emphasis">No scored sellers yet for this period</p>
                    <small>Run <code>php artisan seller:performance:recompute</code> to populate the index.</small>
                </div>
            @endforelse
        </div>
    </section>

    {{-- ═══ FILTERS ═══ --}}
    <section class="lg:col-span-1 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2">
            <i data-lucide="sliders-horizontal" class="text-feedback-info" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Filters</h5>
        </div>
        <form method="GET" class="p-4 space-y-3">
            <input type="hidden" name="period" value="{{ $period->value }}">
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Seller name / username"
                       class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
            </div>
            <div>
                <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Tier</label>
                <select name="tier"
                        class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                    <option value="">All tiers</option>
                    @foreach (PerformanceTier::cases() as $tier)
                        <option value="{{ $tier->value }}" @selected(request('tier') === $tier->value)>{{ $tier->label() }}</option>
                    @endforeach
                </select>
            </div>
            @if (request()->hasAnyFilled(['search', 'tier']))
                <a href="{{ route('admin.seller-performance.index', ['period' => $period->value]) }}" class="btn btn-light btn-sm w-full">
                    <i data-lucide="rotate-ccw" style="width:14px;height:14px;"></i> Reset Filters
                </a>
            @endif
            <button class="btn btn-primary btn-sm w-full">
                <i data-lucide="search" style="width:14px;height:14px;"></i> Apply
            </button>
        </form>
    </section>
</div>

{{-- ═══ FULL TABLE ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="list" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">All Sellers — {{ $period->label() }}</h5>
        </div>
        <small class="text-ink-tertiary">{{ $scores->total() }} {{ Str::plural('seller', $scores->total()) }}</small>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink-soft">
            <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5 w-12">#</th>
                    <th class="px-4 py-2.5">Seller</th>
                    <th class="px-4 py-2.5 text-right">Orders</th>
                    <th class="px-4 py-2.5 text-right">Cancel %</th>
                    <th class="px-4 py-2.5 text-right">Late %</th>
                    <th class="px-4 py-2.5 text-right">Rating</th>
                    <th class="px-4 py-2.5 text-right">Response %</th>
                    <th class="px-4 py-2.5 text-right">Score</th>
                    <th class="px-4 py-2.5">Tier</th>
                    <th class="px-4 py-2.5 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($scores as $i => $row)
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3">
                            <span class="font-semibold text-ink-emphasis font-mono">{{ $scores->firstItem() + $i }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2">
                                <img src="{{ $row->seller?->businessAvatar }}" alt="" width="32" height="32"
                                     class="rounded-sm object-cover border border-border shrink-0" style="width:32px;height:32px;">
                                <div class="min-w-0">
                                    <p class="mb-0 font-medium text-ink-emphasis truncate">{{ $row->seller?->business_name ?? 'N/A' }}</p>
                                    <small class="text-ink-tertiary">@{{ $row->seller?->username ?? '—' }}</small>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-info-tint text-feedback-info">{{ number_format($row->total_orders) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-ink-emphasis">{{ round($row->cancellation_rate * 100, 1) }}%</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-ink-emphasis">{{ round($row->late_shipping_rate * 100, 1) }}%</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center gap-0.5 text-ink-emphasis font-semibold">
                                <i data-lucide="star" style="width:11px;height:11px;" class="text-feedback-warning"></i>
                                {{ number_format((float) $row->avg_review_rating, 2) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="text-ink-emphasis">{{ round($row->response_rate * 100, 1) }}%</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-mono font-bold text-ink-emphasis">{{ number_format((float) $row->overall_score, 2) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold text-white {{ $tierBadge[$row->tier] ?? 'bg-surface-muted text-ink-tertiary' }}">
                                {{ $row->tierLabel() }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('admin.seller-performance.show', $row->seller_id) }}" class="btn btn-light btn-sm" title="View seller performance">
                                <i data-lucide="eye" class="icon-xs"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-10 text-center text-sm text-ink-tertiary">
                            <i data-lucide="inbox" class="mx-auto mb-3 opacity-50" style="width:40px;height:40px;"></i>
                            <p class="mb-1 font-semibold text-ink-emphasis">No scores computed for this period</p>
                            <small>Run <code>php artisan seller:performance:recompute</code> to populate the index.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($scores->hasPages())
        <div class="px-5 py-3 bg-surface-muted flex items-center justify-between">
            <small class="text-ink-tertiary">Showing {{ $scores->firstItem() }}–{{ $scores->lastItem() }} of {{ $scores->total() }}</small>
            {{ $scores->links() }}
        </div>
    @endif
</section>

@endsection
