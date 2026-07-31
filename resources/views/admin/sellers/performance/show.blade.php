@php
    use App\Domain\Vendor\Enums\PerformancePeriod;
    use App\Domain\Vendor\Enums\PerformanceTier;

    $pageTitle = $seller->business_name.' — Performance';

    $tierEnum  = $score->tierEnum();
    $tierHex   = $tierEnum->color();
    $tierLabel = $tierEnum->label();

    $subCards = [
        ['label' => 'Cancellation',     'value' => round($score->cancellation_rate * 100, 1).'%',      'score' => (float) $score->cancellation_score,  'sub' => $score->cancelled_orders.' of '.$score->total_orders.' cancelled',   'icon' => 'x-circle',      'tone' => 'danger'],
        ['label' => 'Late shipping',     'value' => round($score->late_shipping_rate * 100, 1).'%',  'score' => (float) $score->late_shipping_score, 'sub' => $score->late_shipped_orders.' of '.$score->shipped_orders.' late',     'icon' => 'truck',         'tone' => 'warning'],
        ['label' => 'Customer rating',  'value' => number_format($score->avg_review_rating, 2).'/5', 'score' => (float) $score->rating_score,         'sub' => $score->review_count.' reviews',                                       'icon' => 'star',          'tone' => 'purple'],
        ['label' => 'Response rate',     'value' => round($score->response_rate * 100, 1).'%',      'score' => (float) $score->response_score,       'sub' => $score->chat_responded_count.' of '.$score->chat_count.' chats',     'icon' => 'message-square','tone' => 'success'],
        ['label' => 'Dispute rate',      'value' => round($score->dispute_rate * 100, 1).'%',       'score' => (float) $score->dispute_score,        'sub' => $score->disputed_returns.' of '.$score->returned_orders.' returns', 'icon' => 'gavel',         'tone' => 'danger'],
    ];

    $toneBar = [
        'success' => 'bg-emerald-500',
        'warning' => 'bg-amber-500',
        'danger'  => 'bg-rose-500',
        'purple'  => 'bg-purple-500',
        'info'    => 'bg-blue-500',
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
                    <a href="{{ route('admin.seller-performance.index') }}" class="hover:text-ink-soft transition-colors">Seller Performance</a>
                    <i data-lucide="chevron-right" style="width:12px;height:16px;"></i>
                    <span class="text-ink-soft font-semibold">{{ $seller->business_name }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">{{ $seller->business_name }}</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider text-white"
                          style="background-color: {{ $tierHex }}">
                        <i data-lucide="{{ $tierEnum === PerformanceTier::EXCELLENT ? 'trophy' : ($tierEnum === PerformanceTier::POOR ? 'shield-alert' : 'gauge') }}" style="width:11px;height:11px;" class="me-1"></i>
                        {{ $tierLabel }}
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">@{{ $seller->username }} · scoring window: <strong class="text-ink-emphasis">{{ $period->label() }}</strong></p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.seller-performance.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs"></i> Back
                </a>
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
                <form method="POST" action="{{ route('admin.seller-performance.recompute', $seller) }}">
                    @csrf
                    <button class="btn btn-primary btn-sm">
                        <i data-lucide="refresh-cw" class="icon-xs"></i> Recompute
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@if (session('success'))
    <section class="bg-emerald-50 rounded-sm p-4 mb-4 flex items-start gap-3 text-feedback-success text-sm">
        <i data-lucide="check-circle" style="width:18px;height:18px;" class="shrink-0 mt-0.5"></i>
        <span>{{ session('success') }}</span>
    </section>
@endif

{{-- ═══ OVERALL SCORE BANNER ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
        <div class="text-center md:text-left">
            <p class="text-xs text-ink-tertiary mb-1 uppercase tracking-wider font-semibold">Overall performance</p>
            <div class="flex items-center gap-2 justify-center md:justify-start flex-wrap">
                <h2 class="font-bold text-ink-emphasis mb-0" style="font-size:2.6rem;line-height:1;color:{{ $tierHex }}">{{ number_format((float) $score->overall_score, 2) }}</h2>
                <small class="text-ink-tertiary">/ 100</small>
            </div>
            <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full text-white mt-2" style="background-color: {{ $tierHex }}">{{ $tierLabel }}</span>
        </div>
        <div class="md:col-span-2 grid grid-cols-2 sm:grid-cols-4 gap-3">
            <div class="p-3 rounded-xs bg-info-tint">
                <p class="text-xs text-ink-tertiary mb-0 uppercase font-semibold tracking-wider">Total orders</p>
                <p class="font-bold text-xl text-ink-emphasis mt-1">{{ number_format($score->total_orders) }}</p>
            </div>
            <div class="p-3 rounded-xs bg-emerald-50">
                <p class="text-xs text-ink-tertiary mb-0 uppercase font-semibold tracking-wider">Delivered</p>
                <p class="font-bold text-xl text-feedback-success mt-1">{{ number_format($score->delivered_orders) }}</p>
            </div>
            <div class="p-3 rounded-xs bg-warning-tint">
                <p class="text-xs text-ink-tertiary mb-0 uppercase font-semibold tracking-wider">Late ships</p>
                <p class="font-bold text-xl text-feedback-warning mt-1">{{ number_format($score->late_shipped_orders) }}</p>
            </div>
            <div class="p-3 rounded-xs bg-rose-50">
                <p class="text-xs text-ink-tertiary mb-0 uppercase font-semibold tracking-wider">Cancels</p>
                <p class="font-bold text-xl text-rose-600 mt-1">{{ number_format($score->cancelled_orders) }}</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══ SUB-SCORE GRID ═══ --}}
<section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 mb-4">
    @foreach ($subCards as $card)
        <article class="bg-white rounded-sm shadow-sm p-4 relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 {{ $toneBar[$card['tone']] ?? 'bg-gray-500' }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $card['label'] }}</p>
                    <h3 class="mb-0 font-bold text-xl text-ink-emphasis mt-1">{{ $card['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $card['sub'] }}</small>
                </div>
                <span class="shrink-0 w-9 h-9 rounded-sm flex items-center justify-center
                    {{ $card['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' :
                       ($card['tone'] === 'warning' ? 'bg-warning-tint text-feedback-warning' :
                       ($card['tone'] === 'purple'  ? 'bg-purple-50 text-purple-600' :
                       ($card['tone'] === 'info' ? 'bg-info-tint text-feedback-info' : 'bg-rose-50 text-rose-500'))) }}">
                    <i data-lucide="{{ $card['icon'] }}" style="width:16px;height:16px;"></i>
                </span>
            </div>
            <div class="w-full bg-surface-muted rounded-full overflow-hidden mt-3" style="height:6px;">
                <div class="h-full rounded-full transition-all {{ $toneBar[$card['tone']] ?? 'bg-gray-500' }}"
                     style="width: {{ min(100, max(0, $card['score'])) }}%"></div>
            </div>
            <small class="text-ink-tertiary mt-1 block">Score: <span class="font-mono font-semibold text-ink-emphasis">{{ number_format($card['score'], 1) }}</span> / 100</small>
        </article>
    @endforeach
</section>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-3 mb-4">
    {{-- ═══ TREND CHART ═══ --}}
    <section class="lg:col-span-2 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Score Trend</h5>
            </div>
            <small class="text-ink-tertiary">last 60 days</small>
        </div>
        <div class="p-4 relative" style="height: 260px;">
            <canvas id="trendChart"></canvas>
        </div>
    </section>

    {{-- ═══ BY PERIOD ═══ --}}
    <section class="lg:col-span-1 bg-white rounded-sm shadow-sm overflow-hidden">
        <div class="px-5 py-3 flex items-center gap-2">
            <i data-lucide="calendar-range" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink-emphasis text-sm">By period</h5>
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
                    @foreach (PerformancePeriod::cases() as $p)
                        @php $row = $scores[$p->value] ?? null; @endphp
                        <tr class="hover:bg-surface-muted/50 transition-colors">
                            <td class="px-4 py-3">
                                <strong class="text-ink-emphasis">{{ $p->label() }}</strong>
                                @if ($p->value === $period->value)
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded-full bg-brand-tint text-brand-deep text-[11px] ms-1 font-semibold">Current</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-mono font-bold text-ink-emphasis">{{ number_format((float) ($row->overall_score ?? 0), 2) }}</td>
                            <td class="px-4 py-3 text-right">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-full text-white {{ $tierBadge[$row->tier] ?? 'bg-surface-muted text-ink-tertiary' }}">{{ $row->tierLabel() }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>

{{-- ═══ RAW BREAKDOWN ═══ --}}
<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 flex items-center gap-2">
        <i data-lucide="braces" class="text-brand" style="width:16px;height:16px;"></i>
        <h5 class="mb-0 font-bold text-ink-emphasis text-sm">Score breakdown ({{ $period->label() }})</h5>
    </div>
    <div class="p-4">
        <pre class="bg-surface-muted p-4 rounded-xs text-sm text-ink-soft overflow-x-auto mb-0 font-mono">{{ json_encode($score->breakdown, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function () {
    const trend = @json($trend);
    const labels = trend.map(t => t.snapshot_date);
    const scores = trend.map(t => Number(t.overall_score));
    const ctx = document.getElementById('trendChart');
    if (!ctx || trend.length === 0) return;
    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Score',
                data: scores,
                borderColor: '{{ $tierHex }}',
                backgroundColor: '{{ $tierHex }}20',
                tension: 0.35,
                fill: true,
                borderWidth: 2.5,
                pointRadius: trend.length > 30 ? 1 : 4,
                pointHoverRadius: 6,
                pointBackgroundColor: '{{ $tierHex }}',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 8, right: 12, left: 4, bottom: 4 } },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)', bodyColor: '#fff', padding: 10, cornerRadius: 6, displayColors: false,
                }
            },
            scales: {
                y: { min: 0, max: 100, grid: { color: 'rgba(0,0,0,.05)' }, ticks: { color: '#767676', font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { color: '#767676', font: { size: 10 } } },
            }
        },
    });
})();
</script>
@endpush

@endsection
