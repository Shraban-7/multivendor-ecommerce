@php
    use App\Domain\Vendor\Enums\PerformancePeriod;
    use App\Domain\Vendor\Enums\PerformanceTier;

    $pageTitle = "Performance Analytics | {$seller->business_name}";

    $scoreNum  = (float) $score->overall_score;
    $scoreInt  = (int) round($scoreNum);
    $tierEnum  = $score->tierEnum();
    $tierHex   = $tierEnum->color();
    $tierLabel = $tierEnum->label();

    $score7  = $scores[PerformancePeriod::LAST_7_DAYS->value] ?? null;
    $score30 = $scores[PerformancePeriod::LAST_30_DAYS->value] ?? $score;
    $score90 = $scores[PerformancePeriod::LAST_90_DAYS->value] ?? null;

    $deltaVs30 = $score30 && $score30->id !== $score->id ? round($scoreNum - (float) $score30->overall_score, 1) : null;

    $periods = PerformancePeriod::cases();

    $subscores = [
        ['key' => 'cancellation_score',  'label' => 'Cancellation',     'icon' => 'x-circle',      'weight' => (float) ($score->weights['cancellation'] ?? 0) * 100, 'value' => (float) $score->cancellation_score,  'rate' => round($score->cancellation_rate * 100, 1),   'sub' => $score->cancelled_orders.' of '.$score->total_orders.' cancelled', 'numerator' => (int) $score->cancelled_orders, 'denominator' => (int) $score->total_orders],
        ['key' => 'late_shipping_score', 'label' => 'Late shipping',    'icon' => 'truck',         'weight' => (float) ($score->weights['late_shipping'] ?? 0) * 100,'value' => (float) $score->late_shipping_score, 'rate' => round($score->late_shipping_rate * 100, 1), 'sub' => $score->late_shipped_orders.' of '.$score->shipped_orders.' late', 'numerator' => (int) $score->late_shipped_orders, 'denominator' => (int) $score->shipped_orders],
        ['key' => 'rating_score',        'label' => 'Customer rating',  'icon' => 'star',          'weight' => (float) ($score->weights['rating'] ?? 0) * 100,      'value' => (float) $score->rating_score,         'rate' => number_format($score->avg_review_rating, 2).' / 5', 'sub' => $score->review_count.' reviews', 'numerator' => (int) $score->review_count, 'denominator' => null],
        ['key' => 'response_score',      'label' => 'Response rate',    'icon' => 'message-square','weight' => (float) ($score->weights['response'] ?? 0) * 100,   'value' => (float) $score->response_score,       'rate' => round($score->response_rate * 100, 1),  'sub' => $score->chat_responded_count.' of '.$score->chat_count.' chats', 'numerator' => (int) $score->chat_responded_count, 'denominator' => (int) $score->chat_count],
        ['key' => 'dispute_score',       'label' => 'Disputes',         'icon' => 'gavel',         'weight' => (float) ($score->weights['dispute'] ?? 0) * 100,    'value' => (float) $score->dispute_score,        'rate' => round($score->dispute_rate * 100, 1),   'sub' => $score->disputed_returns.' of '.$score->returned_orders.' returns', 'numerator' => (int) $score->disputed_returns, 'denominator' => (int) $score->returned_orders],
    ];

    $ops = [
        ['label' => 'Total orders',     'value' => (int) $score->total_orders,    'icon' => 'shopping-cart', 'tone' => 'warning'],
        ['label' => 'Delivered',        'value' => (int) $score->delivered_orders,'icon' => 'package-check', 'tone' => 'success'],
        ['label' => 'Cancelled',        'value' => (int) $score->cancelled_orders,'icon' => 'x-circle',      'tone' => 'danger'],
        ['label' => 'Late shipped',     'value' => (int) $score->late_shipped_orders,'icon' => 'truck',        'tone' => 'warning'],
        ['label' => 'Shipped',          'value' => (int) $score->shipped_orders,  'icon' => 'truck',        'tone' => 'info'],
        ['label' => 'Refunded',         'value' => (int) $score->refunded_orders, 'icon' => 'undo-2',       'tone' => 'purple'],
        ['label' => 'Returned',         'value' => (int) $score->returned_orders, 'icon' => 'rotate-ccw',   'tone' => 'warning'],
        ['label' => 'Disputed',         'value' => (int) $score->disputed_returns,'icon' => 'gavel',        'tone' => 'danger'],
        ['label' => 'Reviews',          'value' => (int) $score->review_count,    'icon' => 'star',         'tone' => 'brand'],
        ['label' => 'Chat total',       'value' => (int) $score->chat_count,      'icon' => 'message-circle','tone' => 'info'],
    ];

    $speed = [
        ['label' => 'Avg shipping time', 'value' => $score->avg_shipping_hours,    'suffix' => 'h', 'icon' => 'truck',          'tone' => 'warning', 'sub' => 'Time to dispatch'],
        ['label' => 'Avg chat response', 'value' => $score->avg_response_hours,    'suffix' => 'h', 'icon' => 'message-circle', 'tone' => 'info',    'sub' => 'First reply latency'],
        ['label' => 'Avg review rating', 'value' => $score->avg_review_rating,     'suffix' => ' / 5', 'icon' => 'star',        'tone' => 'purple',  'sub' => $score->review_count.' reviews'],
        ['label' => 'Chat response rate','value' => round((float) $score->response_rate * 100, 1), 'suffix' => '%', 'icon' => 'message-square', 'tone' => 'success', 'sub' => $score->chat_responded_count.' of '.$score->chat_count.' answered'],
    ];

    $insufficientData = $score->total_orders < (int) config('marketplace.performance.min_orders_for_scoring', 5);

    $tierBadgeClass = match ($tierEnum) {
        PerformanceTier::EXCELLENT => 'bg-emerald-500 text-white',
        PerformanceTier::GOOD      => 'bg-blue-500 text-white',
        PerformanceTier::AVERAGE   => 'bg-amber-500 text-white',
        PerformanceTier::POOR      => 'bg-rose-500 text-white',
        PerformanceTier::NEW       => 'bg-gray-500 text-white',
    };

    $toneBg = [
        'success' => 'bg-emerald-500',
        'info'    => 'bg-blue-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-rose-500',
        'brand'   => 'bg-brand',
        'purple'  => 'bg-purple-500',
        'muted'   => 'bg-gray-500',
    ];

    $toneIcon = [
        'success' => 'bg-emerald-50 text-feedback-success',
        'info'    => 'bg-info-tint text-feedback-info',
        'warning' => 'bg-warning-tint text-feedback-warning',
        'danger'  => 'bg-rose-50 text-rose-500',
        'brand'   => 'bg-brand-tint text-brand-deep',
        'purple'  => 'bg-purple-50 text-purple-600',
        'muted'   => 'bg-surface-muted text-ink-soft',
    ];

    $subTone = function ($v) {
        return $v >= 85 ? 'success' : ($v >= 70 ? 'info' : ($v >= 50 ? 'warning' : 'danger'));
    };
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .perf-dash__score-ring-bg { stroke: #E5E7EB; stroke-width: 12; fill: none; }
    .perf-dash__score-ring-fg { stroke-width: 12; fill: none; stroke-linecap: round; transform: rotate(-90deg); transform-origin: center; }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="gauge" style="width:12px;height:12px;"></i>
                    <span>Insights</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Performance</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Performance Analytics</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-white {{ $tierBadgeClass }}">
                        <i data-lucide="{{ $tierEnum === PerformanceTier::EXCELLENT ? 'trophy' : ($tierEnum === PerformanceTier::POOR ? 'shield-alert' : 'gauge') }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $tierLabel }} · {{ number_format($scoreNum, 1) }} / 100
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Score & metrics for <strong class="text-ink-emphasis">{{ $period->label() }}</strong> · last computed {{ optional($score->computed_at)->diffForHumans() ?? '—' }}</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.performance.history') }}" class="btn btn-light btn-sm">
                    <i data-lucide="history" style="width:14px;height:14px;"></i> History
                </a>
                <form method="POST" action="{{ route('seller.performance.recompute') }}">
                    @csrf
                    <button class="btn btn-primary btn-sm" title="Force-recompute scores from latest orders/chats">
                        <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Refresh
                    </button>
                </form>
            </div>
        </div>
        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;"></i>
            <span class="font-medium text-ink-emphasis me-1">Period:</span>
            @foreach ($periods as $p)
                <a href="{{ route('seller.performance.dashboard', ['period' => $p->value]) }}"
                   class="px-2 py-0.5 rounded-xs transition-colors {{ $period->value === $p->value ? 'bg-brand-tint text-brand-deep font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">
                    {{ $p->label() }} · {{ number_format((float) ($scores[$p->value]->overall_score ?? 0), 1) }}
                </a>
            @endforeach
        </div>
    </div>
</section>

@if (session('success'))
    <section class="bg-emerald-50 rounded-sm p-4 mb-4 flex items-start gap-3 text-feedback-success text-sm">
        <i data-lucide="check-circle" style="width:18px;height:18px;" class="shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </section>
@endif

{{-- ═══ KPI TILES — 5 ═══ --}}
@php
    $kpis = [
        ['label' => 'Overall score', 'value' => number_format($scoreNum, 1),                                       'sub' => 'Out of 100',         'icon' => 'gauge',         'tone' => 'brand'],
        ['label' => 'Total orders',  'value' => number_format($score->total_orders),                          'sub' => 'In this window',     'icon' => 'shopping-cart','tone' => 'info'],
        ['label' => 'Avg rating',    'value' => number_format($score->avg_review_rating, 2).' / 5',           'sub' => $score->review_count.' reviews','icon' => 'star','tone' => 'purple'],
        ['label' => 'Cancellation',  'value' => round($score->cancellation_rate * 100, 1).'%',                'sub' => $score->cancelled_orders.' cancels', 'icon' => 'x-circle','tone' => 'danger'],
        ['label' => 'Response',      'value' => round($score->response_rate * 100, 1).'%',                    'sub' => $score->chat_responded_count.'/'.$score->chat_count, 'icon' => 'message-square','tone' => 'success'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
    @foreach ($kpis as $kpi)
        <article class="bg-white rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 {{ $toneBg[$kpi['tone']] ?? 'bg-gray-500' }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-xl text-ink-emphasis mt-1 truncate">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center {{ $toneIcon[$kpi['tone']] ?? 'bg-surface-muted text-ink-soft' }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ ALERTS PANEL ═══ --}}
@if (! empty($alerts) || $insufficientData)
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="shrink-0 w-7 h-7 rounded-sm bg-warning-tint text-feedback-warning flex items-center justify-center">
                <i data-lucide="megaphone" style="width:14px;height:14px;"></i>
            </span>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Insights &amp; Action items</h5>
        </div>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">{{ count($alerts) + ($insufficientData ? 1 : 0) }} total</span>
    </div>
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        @if ($insufficientData)
            <div class="p-3 rounded-xs bg-info-tint flex items-start gap-3 text-feedback-info">
                <i data-lucide="info" style="width:20px;height:20px;" class="shrink-0 mt-0.5"></i>
                <div class="min-w-0 flex-1">
                    <h6 class="font-semibold text-ink-emphasis mb-1">Not enough data yet</h6>
                    <p class="mb-0 text-sm text-ink-secondary">You need at least {{ (int) config('marketplace.performance.min_orders_for_scoring', 5) }} orders in this period before scoring kicks in. Currently {{ $score->total_orders }}.</p>
                </div>
            </div>
        @endif
        @foreach ($alerts as $alert)
            @php
                $alertClasses = match ($alert['level']) {
                    'success' => ['bg-emerald-50 text-feedback-success', 'check-check'],
                    'warning' => ['bg-amber-50 text-feedback-warning', 'alert-triangle'],
                    'danger'  => ['bg-rose-50 text-rose-600', 'shield-alert'],
                    default   => ['bg-info-tint text-feedback-info', 'info'],
                };
            @endphp
            <div class="p-3 rounded-xs flex items-start gap-3 {{ $alertClasses[0] }}">
                <i data-lucide="{{ $alertClasses[1] }}" style="width:20px;height:20px;" class="shrink-0 mt-0.5"></i>
                <div class="min-w-0 flex-1">
                    <h6 class="font-semibold text-ink-emphasis mb-1">{{ $alert['title'] }}</h6>
                    <p class="mb-0 text-sm text-ink-soft">{{ $alert['body'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ ROW: SCORE RING + SUB-SCORES ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    <div class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="circle-gauge" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Overall Score</h5>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold text-white {{ $tierBadgeClass }}">{{ $tierLabel }}</span>
        </div>
        <div class="p-5 text-center">
            <div class="relative inline-block" style="width:170px;height:170px;">
                <svg viewBox="0 0 120 120" width="170" height="170">
                    <circle cx="60" cy="60" r="50" class="perf-dash__score-ring-bg"></circle>
                    <circle cx="60" cy="60" r="50" class="perf-dash__score-ring-fg"
                            stroke="{{ $tierHex }}"
                            stroke-dasharray="{{ $scoreInt * 3.1416 }} 314.16"
                            transform="rotate(-90 60 60)"></circle>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <strong class="font-bold" style="font-size:2.4rem;line-height:1;color:{{ $tierHex }}">{{ number_format($scoreNum, 1) }}</strong>
                    <small class="text-ink-tertiary mt-1">of 100</small>
                </div>
            </div>
            @if ($deltaVs30 !== null)
                <div class="mt-3 text-sm">
                    @if ($deltaVs30 > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-feedback-success font-semibold">
                            <i data-lucide="trending-up" style="width:12px;height:12px;" class="me-1"></i> +{{ $deltaVs30 }} vs 30d
                        </span>
                    @elseif ($deltaVs30 < 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-semibold">
                            <i data-lucide="trending-down" style="width:12px;height:12px;" class="me-1"></i> {{ $deltaVs30 }} vs 30d
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-muted text-ink-tertiary font-semibold">No change vs 30d</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="layout-grid" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Sub-score matrix</h5>
            </div>
            <small class="text-ink-tertiary">5 dimensions · weighted</small>
        </div>
        <div class="p-4 grid grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach ($subscores as $sub)
                @php $tone = $subTone((float) $sub['value']); @endphp
                <div class="rounded-xs bg-surface-muted p-3 h-full">
                    <div class="flex items-center justify-between mb-2">
                        <span class="shrink-0 w-7 h-7 rounded-sm flex items-center justify-center {{ $toneIcon[$tone] }}">
                            <i data-lucide="{{ $sub['icon'] }}" style="width:14px;height:14px;"></i>
                        </span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-white text-ink-tertiary">wt {{ number_format((float) $sub['weight'], 0) }}%</span>
                    </div>
                    <p class="text-xs text-ink-tertiary mb-1">{{ $sub['label'] }}</p>
                    <div class="flex items-baseline gap-2">
                        <strong class="font-bold text-ink-emphasis" style="font-size:1.4rem;line-height:1.2">{{ number_format((float) $sub['value'], 1) }}</strong>
                        <small class="text-ink-tertiary" style="font-size:0.7rem;font-weight:400">/ 100</small>
                    </div>
                    <div class="w-full bg-white rounded-full overflow-hidden mt-2 mb-2" style="height:6px;">
                        <div class="h-full rounded-full transition-all {{ $toneBg[$tone] }}"
                             style="width: {{ min(100, max(0, $sub['value'])) }}%"></div>
                    </div>
                    <small class="text-ink-tertiary block">{{ $sub['rate'] }} · {{ $sub['sub'] }}</small>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ ROW: WEIGHTS × OPS × RESPONSE SPEED ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Score weighting</h5>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="weightsChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Operational volume</h5>
            </div>
            <small class="text-ink-tertiary">{{ $ops[0]['value'] }} orders</small>
        </div>
        <div class="p-4 grid grid-cols-2 gap-2">
            @foreach ($ops as $op)
                <div class="flex items-center gap-2 p-2 rounded-xs bg-surface-muted">
                    <span class="shrink-0 w-8 h-8 rounded-sm flex items-center justify-center {{ $toneIcon[$op['tone']] }}">
                        <i data-lucide="{{ $op['icon'] }}" style="width:14px;height:14px;"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-ink-tertiary mb-0 truncate">{{ $op['label'] }}</p>
                        <strong class="text-ink-emphasis">{{ number_format($op['value']) }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2">
            <i data-lucide="activity" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Response speed</h5>
        </div>
        <div class="p-4 space-y-3">
            @foreach ($speed as $sp)
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center {{ $toneIcon[$sp['tone']] }}">
                            <i data-lucide="{{ $sp['icon'] }}" style="width:16px;height:16px;"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-ink-emphasis mb-0 truncate">{{ $sp['label'] }}</p>
                            <small class="text-ink-tertiary">{{ $sp['sub'] }}</small>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <strong class="text-xl text-ink-emphasis font-bold">
                            @if (is_null($sp['value']) || $sp['value'] === 0)
                                <span class="text-ink-tertiary">—</span>
                            @else
                                {{ rtrim(rtrim(number_format((float) $sp['value'], 1), '0'), '.') }}<small class="text-ink-tertiary ms-0.5">{{ $sp['suffix'] }}</small>
                            @endif
                        </strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ ROW: TREND CHART × PERIOD COMPARISON ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Score Trend</h5>
            </div>
            <span class="text-xs text-ink-tertiary">last 30 days · <strong class="text-ink-emphasis">{{ $trend->count() }} snapshots</strong></span>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2">
            <i data-lucide="calendar-range" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Period Comparison</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink-soft">
                <thead class="bg-surface-muted text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-2.5">Window</th>
                        <th class="px-4 py-2.5 text-right">Score</th>
                        <th class="px-4 py-2.5 text-right">Tier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach ($periods as $p)
                        @php
                            $row = $scores[$p->value] ?? null;
                            $rowTier = $row ? $row->tierEnum() : PerformanceTier::NEW;
                            $rowBadge = match ($rowTier) {
                                PerformanceTier::EXCELLENT => 'bg-emerald-500 text-white',
                                PerformanceTier::GOOD      => 'bg-blue-500 text-white',
                                PerformanceTier::AVERAGE   => 'bg-amber-500 text-white',
                                PerformanceTier::POOR      => 'bg-rose-500 text-white',
                                PerformanceTier::NEW       => 'bg-gray-500 text-white',
                            };
                        @endphp
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3 font-medium text-ink-emphasis">
                                {{ $p->label() }}
                                @if ($p->value === $period->value)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-brand-tint text-brand-deep text-xs ms-2 font-semibold">Current</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-ink-emphasis">{{ number_format((float) ($row->overall_score ?? 0), 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white {{ $rowBadge }}">{{ $rowTier->label() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ═══ WEIGHTED SCORE DETAIL ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-5 py-3 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <i data-lucide="calculator" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Weighted score — detail</h5>
        </div>
        <span class="text-xs text-ink-tertiary">overall&nbsp;=&nbsp;Σ(sub_score&nbsp;×&nbsp;weight)</span>
    </div>
    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        @foreach ($subscores as $sub)
            @php
                $weighted = round((float) $sub['value'] * ((float) $sub['weight'] / 100), 1);
                $tone = $subTone((float) $sub['value']);
            @endphp
            <div class="rounded-xs bg-surface-muted p-3">
                <div class="flex items-start justify-between mb-2">
                    <span class="shrink-0 w-7 h-7 rounded-sm flex items-center justify-center {{ $toneIcon[$tone] }}">
                        <i data-lucide="{{ $sub['icon'] }}" style="width:14px;height:14px;"></i>
                    </span>
                    <span class="text-xs px-1.5 py-0.5 rounded font-semibold bg-white text-ink-tertiary">wt {{ number_format((float) $sub['weight'], 0) }}%</span>
                </div>
                <p class="text-xs text-ink-tertiary mb-1">{{ $sub['label'] }}</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <strong class="text-xl text-ink-emphasis font-bold">{{ number_format((float) $sub['value'], 1) }}</strong>
                    <span class="text-xs text-ink-tertiary">× {{ number_format((float) $sub['weight'], 0) }}%</span>
                </div>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xs {{ $tone === 'success' ? 'text-feedback-success' : ($tone === 'info' ? 'text-feedback-info' : ($tone === 'warning' ? 'text-feedback-warning' : 'text-rose-500')) }}">=</span>
                    <strong class="text-base {{ $tone === 'success' ? 'text-feedback-success' : ($tone === 'info' ? 'text-feedback-info' : ($tone === 'warning' ? 'text-feedback-warning' : 'text-rose-500')) }}">{{ number_format($weighted, 1) }}</strong>
                </div>
                <div class="w-full bg-white rounded-full overflow-hidden mt-2" style="height:6px;">
                    <div class="h-full rounded-full {{ $toneBg[$tone] }}" style="width: {{ min(100, max(0, $weighted)) }}%"></div>
                </div>
            </div>
        @endforeach
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function () {
    const tierHex = @json($tierHex);

    // ── Weights doughnut ──
    const wctx = document.getElementById('weightsChart');
    if (wctx) {
        const weights = @json($score->weights ?? []);
        const labels = { cancellation: 'Cancellation', late_shipping: 'Late shipping', rating: 'Rating', response: 'Response', dispute: 'Disputes' };
        const colors = { cancellation: '#dc2626', late_shipping: '#ea580c', rating: '#d97706', response: '#2563eb', dispute: '#7c3aed' };
        const entries = Object.entries(weights).filter(([_, v]) => parseFloat(v) > 0);

        new Chart(wctx, {
            type: 'doughnut',
            data: {
                labels: entries.map(([k]) => labels[k] || k),
                datasets: [{
                    data: entries.map(([_, v]) => (parseFloat(v) * 100).toFixed(1)),
                    backgroundColor: entries.map(([k]) => colors[k] || '#6B7280'),
                    borderWidth: 2,
                    borderColor: '#FFFFFF',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 8, bottom: 8 } },
                cutout: '60%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, padding: 8, font: { size: 11 } } },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)', bodyColor: '#fff', padding: 10, cornerRadius: 6,
                        callbacks: { label: (c) => c.label + ': ' + c.raw + '%' }
                    }
                }
            }
        });
    }

    // ── Trend chart ──
    const tctx = document.getElementById('trendChart');
    const trend = @json($trend);
    if (tctx && trend.length > 0) {
        const labels = trend.map(t => {
            const dt = new Date(t.snapshot_date);
            return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const scores = trend.map(t => Number(t.overall_score));
        const tierColor = (s) => s >= 85 ? '#059669' : s >= 70 ? '#2563eb' : s >= 50 ? '#d97706' : '#dc2626';
        const pointColors = scores.map(tierColor);

        new Chart(tctx, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Overall score',
                    data: scores,
                    borderColor: tierHex,
                    backgroundColor: tierHex === '#059669' ? 'rgba(5,150,105,.10)'
                                  : tierHex === '#2563eb' ? 'rgba(37,99,235,.10)'
                                  : tierHex === '#d97706' ? 'rgba(217,119,6,.10)'
                                  : tierHex === '#dc2626' ? 'rgba(220,38,38,.10)'
                                  : 'rgba(107,114,128,.10)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2.5,
                    pointRadius: trend.length > 30 ? 1 : 4,
                    pointHoverRadius: 7,
                    pointBackgroundColor: pointColors,
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                layout: { padding: { top: 12, right: 16, left: 4, bottom: 4 } },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.95)', bodyColor: '#fff', padding: 10, cornerRadius: 6, displayColors: false,
                        callbacks: {
                            afterLabel: (c) => {
                                const s = c.raw;
                                return s >= 85 ? 'Excellent' : s >= 70 ? 'Good' : s >= 50 ? 'Average' : 'Poor';
                            }
                        }
                    }
                },
                scales: {
                    y: { min: 0, max: 100, grid: { color: 'rgba(0,0,0,.05)' }, ticks: { color: '#767676', font: { size: 10 } } },
                    x: { grid: { display: false }, ticks: { color: '#767676', font: { size: 10 }, maxRotation: trend.length > 14 ? 45 : 0, autoSkip: true, autoSkipPadding: 14 } }
                }
            }
        });
    } else if (tctx) {
        tctx.parentElement.innerHTML += '<p class="absolute inset-0 flex items-center justify-center text-ink-tertiary text-sm">No trend snapshots yet — keep selling!</p>';
    }
})();
</script>
@endpush
@endsection
