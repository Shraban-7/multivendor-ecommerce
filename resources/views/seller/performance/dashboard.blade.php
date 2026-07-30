@php
    use App\Domain\Vendor\Enums\PerformancePeriod;
    use App\Domain\Vendor\Enums\PerformanceTier;

    $pageTitle = "Performance Analytics | {$seller->business_name}";

    $scoreNum   = (float) $score->overall_score;
    $scoreInt   = (int) round($scoreNum);
    $tierEnum   = $score->tierEnum();
    $tierHex    = $tierEnum->color();
    $tierLabel  = $tierEnum->label();

    $score7     = $scores[PerformancePeriod::LAST_7_DAYS->value] ?? null;
    $score30    = $scores[PerformancePeriod::LAST_30_DAYS->value] ?? $score;
    $score90    = $scores[PerformancePeriod::LAST_90_DAYS->value] ?? null;
    $scoreAll   = $scores[PerformancePeriod::ALL_TIME->value] ?? null;

    $deltaVs7   = $score7  ? round($scoreNum - (float) $score7->overall_score, 1)  : null;
    $deltaVs30  = $score30 && $score30->id !== $score->id ? round($scoreNum - (float) $score30->overall_score, 1) : null;
    $deltaVs90  = $score90 && $score90->id !== $score->id ? round($scoreNum - (float) $score90->overall_score, 1) : null;

    $periods = PerformancePeriod::cases();

    $subscores = [
        ['key' => 'cancellation_score', 'label' => 'Cancellation',     'icon' => 'x-circle',      'weight' => (float) ($score->weights['cancellation'] ?? 0) * 100, 'value' => (float) $score->cancellation_score, 'rate' => round($score->cancellation_rate * 100, 1), 'sub' => $score->cancelled_orders.' of '.$score->total_orders.' cancelled', 'numerator' => (int) $score->cancelled_orders, 'denominator' => (int) $score->total_orders],
        ['key' => 'late_shipping_score', 'label' => 'Late shipping',    'icon' => 'truck',         'weight' => (float) ($score->weights['late_shipping'] ?? 0) * 100, 'value' => (float) $score->late_shipping_score, 'rate' => round($score->late_shipping_rate * 100, 1), 'sub' => $score->late_shipped_orders.' of '.$score->shipped_orders.' late',  'numerator' => (int) $score->late_shipped_orders, 'denominator' => (int) $score->shipped_orders],
        ['key' => 'rating_score',        'label' => 'Customer rating',  'icon' => 'star',          'weight' => (float) ($score->weights['rating'] ?? 0) * 100,       'value' => (float) $score->rating_score,         'rate' => number_format($score->avg_review_rating, 2).' / 5', 'sub' => $score->review_count.' reviews', 'numerator' => (int) $score->review_count, 'denominator' => null],
        ['key' => 'response_score',      'label' => 'Response rate',    'icon' => 'message-square','weight' => (float) ($score->weights['response'] ?? 0) * 100,    'value' => (float) $score->response_score,       'rate' => round($score->response_rate * 100, 1),  'sub' => $score->chat_responded_count.' of '.$score->chat_count.' chats', 'numerator' => (int) $score->chat_responded_count, 'denominator' => (int) $score->chat_count],
        ['key' => 'dispute_score',       'label' => 'Disputes',         'icon' => 'gavel',         'weight' => (float) ($score->weights['dispute'] ?? 0) * 100,     'value' => (float) $score->dispute_score,        'rate' => round($score->dispute_rate * 100, 1),   'sub' => $score->disputed_returns.' of '.$score->returned_orders.' returns', 'numerator' => (int) $score->disputed_returns, 'denominator' => (int) $score->returned_orders],
    ];

    $ops = [
        ['key' => 'total_orders',     'label' => 'Total orders',     'value' => (int) $score->total_orders,    'icon' => 'shopping-cart', 'tone' => 'warning'],
        ['key' => 'delivered_orders', 'label' => 'Delivered',        'value' => (int) $score->delivered_orders,'icon' => 'package-check', 'tone' => 'success'],
        ['key' => 'cancelled_orders', 'label' => 'Cancelled',        'value' => (int) $score->cancelled_orders,'icon' => 'x-circle',      'tone' => 'danger'],
        ['key' => 'late_shipped_orders','label' => 'Late shipped',   'value' => (int) $score->late_shipped_orders,'icon' => 'truck',        'tone' => 'warning'],
        ['key' => 'shipped_orders',   'label' => 'Shipped',          'value' => (int) $score->shipped_orders,  'icon' => 'truck',        'tone' => 'info'],
        ['key' => 'refunded_orders',  'label' => 'Refunded',         'value' => (int) $score->refunded_orders, 'icon' => 'undo-2',       'tone' => 'purple'],
        ['key' => 'returned_orders',  'label' => 'Returned',         'value' => (int) $score->returned_orders, 'icon' => 'rotate-ccw',   'tone' => 'warning'],
        ['key' => 'disputed_returns', 'label' => 'Disputed',         'value' => (int) $score->disputed_returns,'icon' => 'gavel',        'tone' => 'danger'],
        ['key' => 'review_count',     'label' => 'Reviews',          'value' => (int) $score->review_count,    'icon' => 'star',         'tone' => 'brand'],
        ['key' => 'chat_count',       'label' => 'Chat total',       'value' => (int) $score->chat_count,      'icon' => 'message-circle','tone' => 'info'],
    ];

    $speed = [
        ['label' => 'Avg shipping time', 'value' => $score->avg_shipping_hours,    'suffix' => 'h', 'icon' => 'truck',          'tone' => 'warning', 'sub' => 'Time to dispatch'],
        ['label' => 'Avg chat response', 'value' => $score->avg_response_hours,    'suffix' => 'h', 'icon' => 'message-circle', 'tone' => 'info',    'sub' => 'First reply latency'],
        ['label' => 'Avg review rating','value' => $score->avg_review_rating,     'suffix' => ' / 5', 'icon' => 'star',        'tone' => 'purple',  'sub' => $score->review_count.' reviews'],
        ['label' => 'Chat response rate','value' => round((float) $score->response_rate * 100, 1), 'suffix' => '%', 'icon' => 'message-square', 'tone' => 'success', 'sub' => $score->chat_responded_count.' of '.$score->chat_count.' answered'],
    ];

    $totalOrders = max(1, (int) $score->total_orders);
    $insufficientData = $score->total_orders < (int) config('marketplace.performance.min_orders_for_scoring', 5);

    $tierBadgeClass = match ($tierEnum) {
        PerformanceTier::EXCELLENT => 'bg-emerald-500 text-white',
        PerformanceTier::GOOD      => 'bg-blue-500 text-white',
        PerformanceTier::AVERAGE   => 'bg-amber-500 text-white',
        PerformanceTier::POOR      => 'bg-rose-500 text-white',
        PerformanceTier::NEW       => 'bg-gray-500 text-white',
    };

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
    .perf-dash__weight-bar { position: relative; height: 8px; background: rgba(0,0,0,.06); border-radius: 999px; overflow: hidden; }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-5 lg:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="gauge" style="width:12px;height:12px;"></i>
                    <span>Insights</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-semibold">Performance</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink mb-0">Performance Analytics</h1>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $tierBadgeClass }}">
                        <i data-lucide="{{ $tierEnum === PerformanceTier::EXCELLENT ? 'trophy' : ($tierEnum === PerformanceTier::POOR ? 'shield-alert' : 'gauge') }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $tierLabel }} — {{ number_format($scoreNum, 1) }} / 100
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Score & metrics for <strong class="text-ink">{{ $period->label() }}</strong> · last computed {{ optional($score->computed_at)->diffForHumans() ?? '—' }}</p>
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
            <span class="font-medium text-ink me-1">Period:</span>
            @foreach ($periods as $p)
                <a href="{{ route('seller.performance.dashboard', ['period' => $p->value]) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ $period->value === $p->value ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">{{ $p->label() }} · {{ number_format((float) ($scores[$p->value]->overall_score ?? 0), 1) }}</a>
            @endforeach
        </div>
    </div>
</section>

@if (session('success'))
    <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 mb-4">
        <i data-lucide="check-circle" style="width:18px;height:18px;"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

{{-- ═══ KPI TILES — 5 DYNAMIC TILES ═══ --}}
@php
    $kpis = [
        ['label' => 'Overall score', 'value' => number_format($scoreNum, 1), 'sub' => 'Out of 100',           'icon' => 'gauge',         'tone' => 'brand',  'pill' => $tierBadgeClass, 'pillText' => $tierLabel],
        ['label' => 'Total orders',  'value' => number_format($score->total_orders), 'sub' => 'In this window', 'icon' => 'shopping-cart','tone' => 'info',   'growth' => 'order_breakdown'],
        ['label' => 'Avg rating',    'value' => number_format($score->avg_review_rating, 2).' / 5','sub' => $score->review_count.' reviews','icon' => 'star','tone' => 'rating', 'growth' => 'rating'],
        ['label' => 'Cancellation',  'value' => round($score->cancellation_rate * 100, 1).'%', 'sub' => $score->cancelled_orders.' cancels', 'icon' => 'x-circle','tone' => 'danger', 'inverse' => true],
        ['label' => 'Response',      'value' => round($score->response_rate * 100, 1).'%', 'sub' => $score->chat_responded_count.'/'.$score->chat_count, 'icon' => 'message-square','tone' => 'success'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">
    @foreach ($kpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="h-1 absolute top-0 left-0 right-0
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'rating' ? 'bg-purple-500' : (
                   $kpi['tone'] === 'danger' ? 'bg-rose-500' : 'bg-emerald-500'))) }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-xl text-ink mt-1 truncate">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                    @if (! empty($kpi['pill']))
                        <div class="mt-1"><span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $kpi['pill'] }}">{{ $kpi['pillText'] }}</span></div>
                    @endif
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'rating' ? 'bg-purple-50 text-purple-600' : (
                       $kpi['tone'] === 'danger' ? 'bg-rose-50 text-rose-500' : 'bg-emerald-50 text-feedback-success'))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ ALERTS PANEL — DYNAMIC FROM SCORE CONDITIONS ═══ --}}
@if (! empty($alerts) || $insufficientData)
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <span class="shrink-0 w-7 h-7 rounded-sm bg-feedback-warning text-white flex items-center justify-center"><i data-lucide="megaphone" style="width:14px;height:14px;"></i></span>
            <h5 class="mb-0 font-bold text-ink">Insights & Action items</h5>
        </div>
        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">{{ count($alerts) + ($insufficientData ? 1 : 0) }} total</span>
    </div>
    <div class="p-4 grid grid-cols-1 md:grid-cols-2 gap-3">
        @if ($insufficientData)
            <div class="p-3 border border-blue-200 bg-blue-50 rounded-sm flex items-start gap-3 text-info">
                <i data-lucide="info" style="width:20px;height:20px;" class="shrink-0 mt-0.5"></i>
                <div class="min-w-0 flex-1">
                    <h6 class="font-semibold mb-1">Not enough data yet</h6>
                    <p class="mb-0 text-sm">You need at least {{ (int) config('marketplace.performance.min_orders_for_scoring', 5) }} orders in this period before scoring kicks in. Currently {{ $score->total_orders }}.</p>
                </div>
            </div>
        @endif
        @foreach ($alerts as $alert)
            @php
                $alertClasses = match ($alert['level']) {
                    'success' => ['border-emerald-200 bg-emerald-50 text-feedback-success', 'check-check'],
                    'warning' => ['border-amber-200 bg-amber-50 text-feedback-warning', 'alert-triangle'],
                    'danger'  => ['border-rose-200 bg-rose-50 text-rose-700', 'shield-alert'],
                    default   => ['border-blue-200 bg-blue-50 text-feedback-info', 'info'],
                };
            @endphp
            <div class="p-3 border rounded-sm flex items-start gap-3 {{ $alertClasses[0] }}">
                <i data-lucide="{{ $alertClasses[1] }}" style="width:20px;height:20px;" class="shrink-0 mt-0.5"></i>
                <div class="min-w-0 flex-1">
                    <h6 class="font-semibold mb-1">{{ $alert['title'] }}</h6>
                    <p class="mb-0 text-sm">{{ $alert['body'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ ROW: SCORE RING + SUB-SCORE MATRIX ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    {{-- Big score ring --}}
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="circle-gauge" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Overall Score</h5>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ $tierBadgeClass }}">{{ $tierLabel }}</span>
        </div>
        <div class="p-5 text-center">
            <div class="position-relative inline-block" style="width:170px;height:170px;">
                <svg viewBox="0 0 120 120" width="170" height="170">
                    <circle cx="60" cy="60" r="50" class="perf-dash__score-ring-bg"></circle>
                    <circle cx="60" cy="60" r="50" class="perf-dash__score-ring-fg"
                            stroke="{{ $tierHex }}"
                            stroke-dasharray="{{ $scoreInt * 3.1416 * (50 / 50) }} 314.16"
                            transform="rotate(-90 60 60)"></circle>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <strong class="font-bold" style="font-size:2.2rem;line-height:1;color:{{ $tierHex }}">{{ number_format($scoreNum, 1) }}</strong>
                    <small class="text-ink-tertiary">of 100</small>
                </div>
            </div>
            @if($deltaVs30 !== null)
                <div class="mt-3 text-sm">
                    @if($deltaVs30 > 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-emerald-50 text-feedback-success font-semibold"><i data-lucide="trending-up" style="width:12px;height:12px;" class="me-1"></i> +{{ $deltaVs30 }} vs 30d</span>
                    @elseif($deltaVs30 < 0)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-rose-50 text-rose-600 font-semibold"><i data-lucide="trending-down" style="width:12px;height:12px;" class="me-1"></i> {{ $deltaVs30 }} vs 30d</span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-surface-muted text-ink-tertiary font-semibold">No change vs 30d</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Sub-score matrix --}}
    <div class="lg:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="layout-grid" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Sub-score matrix</h5>
            </div>
            <small class="text-ink-tertiary">5 dimensions · weighted</small>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach ($subscores as $sub)
                @php $tone = $subTone((float) $sub['value']); @endphp
                <div class="border border-border rounded-sm p-3 h-full bg-surface-muted/40 relative">
                    <div class="flex items-center justify-between mb-2">
                        <span class="shrink-0 w-7 h-7 rounded-sm flex items-center justify-center
                            {{ $tone === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                               $tone === 'info'    ? 'bg-blue-50 text-feedback-info' : (
                               $tone === 'warning' ? 'bg-amber-50 text-feedback-warning' : 'bg-rose-50 text-rose-500')) }}">
                            <i data-lucide="{{ $sub['icon'] }}" style="width:14px;height:14px;"></i>
                        </span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">wt {{ number_format((float) $sub['weight'], 0) }}%</span>
                    </div>
                    <p class="text-xs text-ink-tertiary mb-0">{{ $sub['label'] }}</p>
                    <strong class="block font-bold text-ink" style="font-size:1.4rem;line-height:1.2">{{ number_format((float) $sub['value'], 1) }}<small class="text-ink-tertiary ms-1" style="font-size:0.7rem;font-weight:400"> / 100</small></strong>
                    <div class="w-full bg-surface-muted rounded-full h-1.5 overflow-hidden mt-2 mb-2">
                        <div class="h-1.5 rounded-full transition-all
                            {{ $tone === 'success' ? 'bg-emerald-500' : (
                               $tone === 'info'    ? 'bg-blue-500' : (
                               $tone === 'warning' ? 'bg-amber-500' : 'bg-rose-500')) }}"
                            style="width: {{ min(100, max(0, $sub['value'])) }}%"></div>
                    </div>
                    <small class="text-ink-tertiary d-block">{{ $sub['rate'] }} · {{ $sub['sub'] }}</small>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ ROW: WEIGHTS DONUT × OPS VOLUME × RESPONSE SPEED ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Score weighting</h5>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="weightsChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="bar-chart-3" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Operational volume</h5>
            </div>
            <small class="text-ink-tertiary">{{ $ops[0]['value'] }} orders</small>
        </div>
        <div class="p-4 grid grid-cols-2 gap-2">
            @foreach ($ops as $op)
                @php
                    $toneBg = match ($op['tone']) {
                        'brand'   => 'bg-brand-tint text-brand',
                        'success' => 'bg-emerald-50 text-feedback-success',
                        'info'    => 'bg-blue-50 text-feedback-info',
                        'warning' => 'bg-amber-50 text-feedback-warning',
                        'danger'  => 'bg-rose-50 text-rose-500',
                        'purple'  => 'bg-purple-50 text-purple-600',
                        default   => 'bg-surface-muted text-ink-tertiary',
                    };
                @endphp
                <div class="flex items-center gap-2 p-2 rounded-sm bg-surface-muted/40 border border-border">
                    <span class="shrink-0 w-8 h-8 rounded-sm flex items-center justify-center {{ $toneBg }}">
                        <i data-lucide="{{ $op['icon'] }}" style="width:14px;height:14px;"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-xs text-ink-tertiary mb-0">{{ $op['label'] }}</p>
                        <strong class="text-ink">{{ number_format($op['value']) }}</strong>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="activity" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Response speed</h5>
        </div>
        <div class="p-4 space-y-3">
            @foreach ($speed as $sp)
                @php
                    $toneBg = match ($sp['tone']) {
                        'brand'   => 'bg-brand-tint text-brand',
                        'success' => 'bg-emerald-50 text-feedback-success',
                        'info'    => 'bg-blue-50 text-feedback-info',
                        'warning' => 'bg-amber-50 text-feedback-warning',
                        'purple'  => 'bg-purple-50 text-purple-600',
                            default   => 'bg-surface-muted text-ink-tertiary',
                    };
                @endphp
                <div class="flex items-center justify-between gap-2">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center {{ $toneBg }}">
                            <i data-lucide="{{ $sp['icon'] }}" style="width:16px;height:16px;"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-sm text-ink font-medium mb-0 truncate">{{ $sp['label'] }}</p>
                            <small class="text-ink-tertiary">{{ $sp['sub'] }}</small>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <strong class="text-xl text-ink font-bold">
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
    <div class="xl:col-span-2 bg-white border border-border shadow-sm rounded-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Score Trend</h5>
            </div>
            <span class="text-xs text-ink-tertiary">last 30 days · <strong class="text-ink">{{ $trend->count() }} snapshots</strong></span>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="trendChart"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="calendar-range" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Period Comparison</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
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
                            $rowTierHex = $rowTier->color();
                            $rowBadge = match ($rowTier) {
                                PerformanceTier::EXCELLENT => 'bg-emerald-500 text-white',
                                PerformanceTier::GOOD      => 'bg-blue-500 text-white',
                                PerformanceTier::AVERAGE   => 'bg-amber-500 text-white',
                                PerformanceTier::POOR      => 'bg-rose-500 text-white',
                                PerformanceTier::NEW       => 'bg-gray-500 text-white',
                            };
                        @endphp
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-medium">{{ $p->label() }}</span>
                                @if($p->value === $period->value)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-brand-tint text-brand text-xs ms-2 font-semibold">Current</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold">{{ number_format((float) ($row->overall_score ?? 0), 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $rowBadge }}">{{ $rowTier->label() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ═══ WEIGHTED SCORE DETAIL ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="calculator" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Weighted score — detail</h5>
        </div>
        <span class="text-xs text-ink-tertiary">overall&nbsp;=&nbsp;Σ(sub_score&nbsp;×&nbsp;weight)</span>
    </div>
    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        @foreach ($subscores as $sub)
            @php
                $weighted  = round((float) $sub['value'] * ((float) $sub['weight'] / 100), 1);
                $tone      = $subTone((float) $sub['value']);
                $toneBg = match ($tone) {
                    'success' => 'bg-emerald-500',
                    'info'    => 'bg-blue-500',
                    'warning' => 'bg-amber-500',
                    default   => 'bg-rose-500',
                };
            @endphp
            <div class="border border-border rounded-sm p-3 bg-surface-muted/40">
                <div class="flex items-start justify-between mb-2">
                    <span class="shrink-0 w-7 h-7 rounded-sm flex items-center justify-center
                        {{ $tone === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                           $tone === 'info'    ? 'bg-blue-50 text-feedback-info' : (
                           $tone === 'warning' ? 'bg-amber-50 text-feedback-warning' : 'bg-rose-50 text-rose-500')) }}">
                        <i data-lucide="{{ $sub['icon'] }}" style="width:14px;height:14px;"></i>
                    </span>
                    <span class="text-xs px-1.5 py-0.5 rounded font-semibold bg-surface-muted text-ink-tertiary">wt {{ number_format((float) $sub['weight'], 0) }}%</span>
                </div>
                <p class="text-xs text-ink-tertiary mb-0">{{ $sub['label'] }}</p>
                <div class="flex items-baseline gap-2 mt-1">
                    <strong class="text-xl text-ink font-bold">{{ number_format((float) $sub['value'], 1) }}</strong>
                    <span class="text-xs text-ink-tertiary">× {{ number_format((float) $sub['weight'], 0) }}%</span>
                </div>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-xs {{ $tone === 'success' ? 'text-feedback-success' : ($tone === 'info' ? 'text-feedback-info' : ($tone === 'warning' ? 'text-feedback-warning' : 'text-rose-500')) }}">=</span>
                    <strong class="text-base {{ $tone === 'success' ? 'text-feedback-success' : ($tone === 'info' ? 'text-feedback-info' : ($tone === 'warning' ? 'text-feedback-warning' : 'text-rose-500')) }}">{{ number_format($weighted, 1) }}</strong>
                </div>
                <div class="w-full bg-white rounded-full h-1.5 overflow-hidden mt-2">
                    <div class="h-1.5 rounded-full {{ $toneBg }}" style="width: {{ min(100, max(0, $weighted)) }}%"></div>
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

        // ─── Weights doughnut ───
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

        // ─── Trend chart ───
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
