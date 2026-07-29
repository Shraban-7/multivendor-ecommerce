@extends('seller.layouts.app')
@section('title', 'Performance Analytics')

@section('content')

@php
    $tierColor = $score->tierColor();
    $scoreNum = (float) $score->overall_score;
    $scoreInt = (int) round($scoreNum);
    $tierLabel = $score->tierLabel();
    // Determine trend by comparing current to previous-bigger period
    $score30 = $scores[\App\Domain\Vendor\Enums\PerformancePeriod::LAST_30_DAYS->value] ?? $score;
    $score7  = $scores[\App\Domain\Vendor\Enums\PerformancePeriod::LAST_7_DAYS->value] ?? null;
    $prevScore = $scores[\App\Domain\Vendor\Enums\PerformancePeriod::LAST_90_DAYS->value] ?? null;
    $momDelta = $prevScore ? round($scoreNum - (float) $prevScore->overall_score, 1) : null;
    // Sub-score breakdown with weights
    $subscores = [
        ['key' => 'cancellation_score',  'label' => 'Cancellation',     'icon' => 'x-circle',     'weight' => ($score->weights['cancellation'] ?? 0) * 100, 'value' => (float) $score->cancellation_score, 'rate' => round($score->cancellation_rate * 100, 1), 'sub' => $score->cancelled_orders.' of '.$score->total_orders.' orders cancelled'],
        ['key' => 'late_shipping_score',  'label' => 'Late shipping',    'icon' => 'truck',        'weight' => ($score->weights['late_shipping'] ?? 0) * 100, 'value' => (float) $score->late_shipping_score, 'rate' => round($score->late_shipping_rate * 100, 1), 'sub' => $score->late_shipped_orders.' of '.$score->shipped_orders.' shipped late'],
        ['key' => 'rating_score',         'label' => 'Customer rating',  'icon' => 'star',         'weight' => ($score->weights['rating'] ?? 0) * 100, 'value' => (float) $score->rating_score, 'rate' => number_format($score->avg_review_rating, 2), 'sub' => $score->review_count.' reviews'],
        ['key' => 'response_score',       'label' => 'Response rate',    'icon' => 'message-square','weight' => ($score->weights['response'] ?? 0) * 100, 'value' => (float) $score->response_score, 'rate' => round($score->response_rate * 100, 1), 'sub' => $score->chat_responded_count.' of '.$score->chat_count.' chats'],
        ['key' => 'dispute_score',        'label' => 'Disputes',         'icon' => 'gavel',        'weight' => ($score->weights['dispute'] ?? 0) * 100, 'value' => (float) $score->dispute_score, 'rate' => round($score->dispute_rate * 100, 1), 'sub' => $score->disputed_returns.' disputed of '.$score->returned_orders.' returns'],
    ];
@endphp

<div class="flex flex-wrap items-end justify-between gap-2 mb-4">
    <div>
        <h1 class="text-xl font-semibold text-ink mb-0">Performance Analytics</h1>
        <p class="text-sm text-ink-secondary mt-1">Score and trends across <strong>{{ $period->label() }}</strong></p>
    </div>
    <div class="flex flex-wrap gap-2 items-center">
        <form method="GET" action="{{ route('seller.performance.dashboard') }}" class="flex items-center gap-2">
            <label class="text-xs text-ink-tertiary">Range</label>
            <select name="period" onchange="this.form.submit()" class="px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                    <option value="{{ $p->value }}" @selected($period->value === $p->value)>{{ $p->label() }}</option>
                @endforeach
            </select>
        </form>
        <form method="POST" action="{{ route('seller.performance.recompute') }}">
            @csrf
            <button class="btn btn-light btn-sm" title="Recompute scores from latest data">
                <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Refresh
            </button>
        </form>
    </div>
</div>

@if (session('success'))
    <div class="p-4 rounded-sm bg-emerald-50 border border-emerald-200 text-feedback-success text-sm flex items-start gap-3 mb-4">
        <i data-lucide="check-circle" style="width:18px;height:18px;"></i><span>{{ session('success') }}</span>
    </div>
@endif

{{-- ═══ HERO: BIG SCORE + TIER + DELTA ═══ --}}
<section class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="p-5 text-center">
            <small class="text-ink-tertiary uppercase tracking-wider font-semibold">Overall Performance Score</small>
            <div class="my-3 position-relative">
                <svg viewBox="0 0 120 120" style="width:160px;height:160px;margin:0 auto;display:block;">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#E5E7EB" stroke-width="10"></circle>
                    <circle cx="60" cy="60" r="50" fill="none" stroke="{{ $tierColor }}" stroke-width="10"
                            stroke-dasharray="{{ $scoreInt * 3.1416 }} 314.16" stroke-linecap="round"
                            transform="rotate(-90 60 60)"></circle>
                </svg>
                <div style="position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;">
                    <strong class="text-3xl font-bold" style="color: {{ $tierColor }}">{{ number_format($scoreNum, 1) }}</strong>
                    <small class="text-ink-tertiary">/ 100</small>
                </div>
            </div>
            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full" style="background: {{ $tierColor }}1A; color: {{ $tierColor }}">{{ $tierLabel }}</span>
            @if($momDelta !== null)
                <div class="mt-3 text-sm">
                    @if($momDelta > 0)
                        <span class="text-feedback-success font-semibold"><i data-lucide="trending-up" style="width:14px;height:14px;"></i> +{{ $momDelta }} vs {{ \App\Domain\Vendor\Enums\PerformancePeriod::LAST_90_DAYS->label() }}</span>
                    @elseif($momDelta < 0)
                        <span class="text-feedback-danger font-semibold"><i data-lucide="trending-down" style="width:14px;height:14px;"></i> {{ $momDelta }} vs {{ \App\Domain\Vendor\Enums\PerformancePeriod::LAST_90_DAYS->label() }}</span>
                    @else
                        <span class="text-ink-tertiary">No change vs {{ \App\Domain\Vendor\Enums\PerformancePeriod::LAST_90_DAYS->label() }}</span>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <div class="lg:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="layout-grid" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Sub-score matrix</h5>
            <span class="ms-auto text-xs text-ink-tertiary">Tap a card to inspect</span>
        </div>
        <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            @foreach ($subscores as $sub)
                @php
                    $scoreTone = $sub['value'] >= 85 ? '#059669' : ($sub['value'] >= 70 ? '#2563eb' : ($sub['value'] >= 50 ? '#d97706' : '#dc2626'));
                @endphp
                <div class="border rounded-sm p-3 h-full" style="border-color: {{ $scoreTone }}30; background: {{ $scoreTone }}08">
                    <div class="flex items-center justify-between mb-2">
                        <span style="color: {{ $scoreTone }}"><i data-lucide="{{ $sub['icon'] }}" style="width:18px;height:18px;"></i></span>
                        <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary weight-badge">wt {{ number_format((float) $sub['weight'], 0) }}%</span>
                    </div>
                    <p class="text-xs text-ink-tertiary mb-1">{{ $sub['label'] }}</p>
                    <strong class="block text-ink" style="font-size:1.4rem;line-height:1.2;font-weight:700">{{ number_format((float) $sub['value'], 1) }}<small class="text-ink-tertiary" style="font-size:0.7rem;font-weight:400"> / 100</small></strong>
                    <div class="w-full bg-surface-muted rounded-full h-1.5 overflow-hidden mt-2 mb-2">
                        <div class="h-1.5 rounded-full transition-all" style="width: {{ min(100, max(0, $sub['value'])) }}%; background: {{ $scoreTone }}"></div>
                    </div>
                    <small class="text-ink-tertiary d-block">{{ $sub['rate'] }}% · {{ $sub['sub'] }}</small>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ ROW: WEIGHTS PIE + KEY METRICS ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="pie-chart" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">How your score is weighted</h5>
        </div>
        <div class="p-4">
            <canvas id="weightsChart" height="180"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="bar-chart-3" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Operational volume</h5>
        </div>
        <div class="p-4 grid grid-cols-2 gap-3">
            @php
                $ops = [
                    ['label' => 'Total orders',     'value' => $score->total_orders,                       'icon' => 'shopping-cart','color' => '#d97706'],
                    ['label' => 'Delivered',       'value' => $score->delivered_orders,                   'icon' => 'package-check','color' => '#059669'],
                    ['label' => 'Cancelled',       'value' => $score->cancelled_orders,                   'icon' => 'x-circle',     'color' => '#dc2626'],
                    ['label' => 'Late shipped',    'value' => $score->late_shipped_orders,                'icon' => 'truck',        'color' => '#ea580c'],
                    ['label' => 'Refunded',        'value' => $score->refunded_orders,                    'icon' => 'undo-2',       'color' => '#7c3aed'],
                    ['label' => 'Returned',        'value' => $score->returned_orders,                    'icon' => 'rotate-ccw',   'color' => '#ea580c'],
                    ['label' => 'Disputed',        'value' => $score->disputed_returns,                   'icon' => 'gavel',        'color' => '#dc2626'],
                    ['label' => 'Reviews',         'value' => $score->review_count,                       'icon' => 'star',         'color' => '#2563eb'],
                ];
            @endphp
            @foreach ($ops as $op)
                <div class="border border-border rounded-sm p-2.5 flex items-center gap-2 bg-surface-muted/30">
                    <span class="shrink-0 w-8 h-8 rounded-full bg-white flex items-center justify-center" style="color: {{ $op['color'] }}">
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
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-blue-50 text-feedback-info flex items-center justify-center"><i data-lucide="message-square" style="width:16px;height:16px;"></i></span>
                    <div>
                        <p class="text-sm text-ink font-medium mb-0">Chat response rate</p>
                        <small class="text-ink-tertiary">{{ $score->chat_responded_count }} of {{ $score->chat_count }} chats</small>
                    </div>
                </div>
                <div class="text-right">
                    <strong class="text-xl text-ink">{{ round($score->response_rate * 100, 1) }}%</strong>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-amber-50 text-feedback-warning flex items-center justify-center"><i data-lucide="truck" style="width:16px;height:16px;"></i></span>
                    <div>
                        <p class="text-sm text-ink font-medium mb-0">Avg shipping time</p>
                        <small class="text-ink-tertiary">Time to dispatch</small>
                    </div>
                </div>
                <div class="text-right">
                    <strong class="text-xl text-ink">
                        @if($score->avg_shipping_hours)
                            {{ number_format($score->avg_shipping_hours, 1) }}h
                        @else — @endif
                    </strong>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-emerald-50 text-feedback-success flex items-center justify-center"><i data-lucide="message-circle" style="width:16px;height:16px;"></i></span>
                    <div>
                        <p class="text-sm text-ink font-medium mb-0">Avg chat response</p>
                        <small class="text-ink-tertiary">First reply latency</small>
                    </div>
                </div>
                <div class="text-right">
                    <strong class="text-xl text-ink">
                        @if($score->avg_response_hours)
                            {{ number_format($score->avg_response_hours, 1) }}h
                        @else — @endif
                    </strong>
                </div>
            </div>
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-9 h-9 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center"><i data-lucide="star" style="width:16px;height:16px;"></i></span>
                    <div>
                        <p class="text-sm text-ink font-medium mb-0">Avg review</p>
                        <small class="text-ink-tertiary">{{ $score->review_count }} reviews</small>
                    </div>
                </div>
                <div class="text-right">
                    <strong class="text-xl text-ink">{{ number_format($score->avg_review_rating, 2) }} / 5</strong>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ═══ INSIGHTS — TOP ISSUES ═══ --}}
@if (! empty($alerts))
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
        <i data-lucide="megaphone" class="text-feedback-warning" style="width:16px;height:16px;"></i>
        <h5 class="mb-0 font-bold text-ink">Insights & Action items</h5>
    </div>
    <div class="p-4 space-y-3">
        @foreach ($alerts as $alert)
            @php
                $alertBg = $alert['level'] === 'success' ? 'bg-emerald-50 border-emerald-200' : ($alert['level'] === 'warning' ? 'bg-amber-50 border-amber-200' : ($alert['level'] === 'danger' ? 'bg-rose-50 border-rose-200' : 'bg-blue-50 border-blue-200'));
                $alertText = $alert['level'] === 'success' ? 'text-feedback-success' : ($alert['level'] === 'warning' ? 'text-feedback-warning' : ($alert['level'] === 'danger' ? 'text-rose-700' : 'text-feedback-info'));
            @endphp
            <div class="p-3 rounded-sm border {{ $alertBg }} {{ $alertText }} d-flex align-items-start gap-3">
                <i data-lucide="{{ $alert['level'] === 'success' ? 'check-check' : ($alert['level'] === 'warning' ? 'alert-triangle' : ($alert['level'] === 'danger' ? 'shield-alert' : 'info')) }}" style="width:20px;height:20px;" class="shrink-0 mt-0.5"></i>
                <div class="min-w-0 flex-1">
                    <h6 class="font-semibold mb-1">{{ $alert['title'] }}</h6>
                    <p class="mb-0 text-sm">{{ $alert['body'] }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endif

{{-- ═══ ROW: TREND CHART + PERIOD TABLE ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Score Trend</h5>
            </div>
            <span class="text-xs text-ink-tertiary">last 30 days</span>
        </div>
        <div class="p-4">
            <canvas id="trendChart" height="160"></canvas>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="calendar-range" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Period Comparison</h5>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead class="bg-surface-muted">
                    <tr>
                        <th class="px-4 py-2.5">Window</th>
                        <th class="px-4 py-2.5 text-right">Score</th>
                        <th class="px-4 py-2.5 text-right">Tier</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border">
                    @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                        @php $row = $scores[$p->value]; @endphp
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <span class="font-medium">{{ $p->label() }}</span>
                                @if($p->value === $period->value)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-brand-tint text-brand text-xs ms-2">Current</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-semibold">{{ number_format((float) $row->overall_score, 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full text-white" style="background: {{ $row->tierColor() }}">{{ $row->tierLabel() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- ═══ WEIGHTED SCORE BREAKDOWN (DETAILED) ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="calculator" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Weighted score — detail</h5>
        </div>
        <span class="text-xs text-ink-tertiary">Sum of (sub-score × weight)</span>
    </div>
    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
        @foreach ($subscores as $sub)
            @php
                $weighted = (float) $sub['value'] * ((float) $sub['weight'] / 100);
                $scoreTone = $sub['value'] >= 85 ? '#059669' : ($sub['value'] >= 70 ? '#2563eb' : ($sub['value'] >= 50 ? '#d97706' : '#dc2626'));
            @endphp
            <div class="border border-border rounded-sm p-3 bg-surface-muted/40">
                <div class="flex items-start justify-between mb-2">
                    <span style="color: {{ $scoreTone }}"><i data-lucide="{{ $sub['icon'] }}" style="width:18px;height:18px;"></i></span>
                    <span class="text-xs px-1.5 py-0.5 rounded bg-surface-muted text-ink-tertiary">wt {{ number_format((float) $sub['weight'], 0) }}%</span>
                </div>
                <p class="text-xs text-ink-tertiary mb-0">{{ $sub['label'] }}</p>
                <div class="flex items-baseline gap-2">
                    <strong class="text-xl text-ink font-bold">{{ number_format((float) $sub['value'], 1) }}</strong>
                    <span class="text-xs text-ink-tertiary">× {{ number_format((float) $sub['weight'], 0) }}%</span>
                </div>
                <div class="flex items-baseline gap-2 mt-1">
                    <span class="text-sm" style="color: {{ $scoreTone }}">=</span>
                    <strong class="text-base" style="color: {{ $scoreTone }}">{{ number_format($weighted, 1) }}</strong>
                </div>
                <div class="w-full bg-white rounded-full h-1.5 overflow-hidden mt-2">
                    <div class="h-1.5 rounded-full" style="width: {{ min(100, max(0, $weighted)) }}%; background: {{ $scoreTone }}"></div>
                </div>
            </div>
        @endforeach
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    (function () {
        // ─── Weights chart ───
        const wctx = document.getElementById('weightsChart');
        if (wctx) {
            const weights = @json($score->weights ?? []);
            const labels = { cancellation: 'Cancellation', late_shipping: 'Late ship.', rating: 'Rating', response: 'Response', dispute: 'Dispute' };
            const colors = { cancellation: '#dc2626', late_shipping: '#ea580c', rating: '#d97706', response: '#2563eb', dispute: '#7c3aed' };
            const entries = Object.entries(weights).filter(([_, v]) => parseFloat(v) > 0);
            new Chart(wctx, {
                type: 'doughnut',
                data: {
                    labels: entries.map(([k]) => labels[k] || k),
                    datasets: [{
                        data: entries.map(([_, v]) => parseFloat(v).toFixed(3)),
                        backgroundColor: entries.map(([k]) => colors[k] || '#6B7280'),
                        borderWidth: 2,
                        borderColor: '#FFFFFF'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, padding: 8, font: { size: 11 } },
                        tooltip: { callbacks: { label: (c) => c.label + ': ' + (parseFloat(c.raw) * 100).toFixed(0) + '%' } }
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
                        borderColor: '#0ea5e9',
                        backgroundColor: 'rgba(14,165,233,.10)',
                        tension: 0.35,
                        fill: true,
                        borderWidth: 2.5,
                        pointRadius: 4,
                        pointHoverRadius: 7,
                        pointBackgroundColor: pointColors,
                        pointBorderColor: '#FFFFFF',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#191919', bodyColor: '#fff', padding: 10,
                            callbacks: {
                                afterLabel: (c) => {
                                    const s = c.raw;
                                    return s >= 85 ? 'Excellent' : s >= 70 ? 'Good' : s >= 50 ? 'Average' : 'Poor';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { min: 0, max: 100, grid: { color: 'rgba(0,0,0,.04)' }, ticks: { font: { size: 10 } } },
                        x: { grid: { display: false }, ticks: { font: { size: 10 }, maxRotation: 0 } }
                    }
                }
            });
        } else if (tctx) {
            tctx.parentElement.innerHTML += '<p class="text-ink-tertiary text-sm mb-0">No trend data yet — keep selling!</p>';
        }
    })();
</script>
@endpush

@endsection