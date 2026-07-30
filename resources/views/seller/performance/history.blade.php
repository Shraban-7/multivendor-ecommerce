@php
    use App\Domain\Vendor\Enums\PerformanceTier;

    $pageTitle = "Performance History | {$seller->business_name}";
    $days       = (int) request()->integer('days', 90);
    $snapshots  = $trend;

    $totalSnapshots = $snapshots->count();
    $latest          = $snapshots->last();
    $earliest        = $snapshots->first();
    $avgScore        = $totalSnapshots > 0 ? round($snapshots->avg('overall_score'), 1) : 0;
    $maxScore        = $totalSnapshots > 0 ? $snapshots->max('overall_score') : 0;
    $minScore        = $totalSnapshots > 0 ? $snapshots->min('overall_score') : 0;
    $bestIdx         = $totalSnapshots > 0 ? $snapshots->search(fn ($s) => (float) $s->overall_score === (float) $maxScore) : null;
    $bestSnapshot    = ($bestIdx !== false && $bestIdx !== null) ? $snapshots->get($bestIdx) : null;

    $totalOrders  = $snapshots->sum('total_orders');
    $totalCancels = $snapshots->sum('cancelled_orders');
    $totalLate    = $snapshots->sum('late_shipped_orders');
    $totalReviews = $snapshots->sum('review_count');
    $bestDay      = $snapshots->sortByDesc('delivered_orders')->first();
    $peakCancel   = $snapshots->sortByDesc('cancellation_rate')->first();
    $peakLate     = $snapshots->sortByDesc('late_shipping_rate')->first();

    $tierDistribution = $snapshots->groupBy('tier')->map->count()->sortDesc();
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

@push('style')
<style>
    .perf-history__spark-track { position: relative; height: 6px; background: rgba(0,0,0,.06); border-radius: 999px; overflow: hidden; }
    .perf-history__spark-bar  { position: absolute; top: 0; bottom: 0; left: 0; border-radius: 999px; background: linear-gradient(90deg, #F85606, #fb923c); }
</style>
@endpush

{{-- ═══ HERO ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
    <div class="p-5 lg:p-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="gauge" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.performance.dashboard') }}" class="hover:text-ink transition-colors">Performance</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink font-semibold">History</span>
                </nav>
                <h1 class="text-xl font-bold text-ink mb-1">Performance History</h1>
                <p class="text-sm text-ink-secondary mb-0">
                    Daily snapshots from the last <strong class="text-ink">{{ $days }}</strong> days ·
                    <strong class="text-ink">{{ $totalSnapshots }}</strong> {{ Str::plural('record', $totalSnapshots) }}
                </p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('seller.performance.dashboard') }}" class="btn btn-primary btn-sm">
                    <i data-lucide="gauge" style="width:14px;height:14px;"></i> Dashboard
                </a>
                <a href="{{ route('seller.performance.recompute') }}" class="btn btn-light btn-sm" onclick="event.preventDefault(); document.getElementById('refresh-form').submit();">
                    <i data-lucide="refresh-cw" style="width:14px;height:14px;"></i> Refresh
                </a>
            </div>
        </div>

        <form id="refresh-form" method="POST" action="{{ route('seller.performance.recompute') }}" class="hidden">@csrf</form>

        <div class="mt-4 flex flex-wrap gap-2 text-sm items-center text-ink-secondary">
            <i data-lucide="calendar" style="width:14px;height:14px;"></i>
            <span class="font-medium text-ink me-1">Range:</span>
            @foreach ([30, 60, 90, 180, 365] as $d)
                <a href="{{ route('seller.performance.history', ['days' => $d]) }}" class="px-2 py-0.5 rounded-xs transition-colors {{ $days === $d ? 'bg-brand-tint text-brand font-semibold' : 'hover:bg-surface-muted text-ink-secondary' }}">Last {{ $d }}d</a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ SUMMARY KPI TILES — 6 DYNAMIC ═══ --}}
@php
    $historyKpis = [
        ['label' => 'Avg score',     'value' => $avgScore.' / 100',                 'sub' => $totalSnapshots.' snapshots',         'icon' => 'gauge',         'tone' => 'brand'],
        ['label' => 'Peak score',    'value' => number_format((float) $maxScore, 2), 'sub' => $bestSnapshot ? $bestSnapshot->snapshot_date->format('d M') : '—', 'icon' => 'trending-up', 'tone' => 'success'],
        ['label' => 'Lowest score',  'value' => number_format((float) $minScore, 2), 'sub' => 'across range',                       'icon' => 'trending-down','tone' => 'danger'],
        ['label' => 'Total orders',  'value' => number_format($totalOrders),        'sub' => 'in window',                          'icon' => 'shopping-cart','tone' => 'info'],
        ['label' => 'Cancellations', 'value' => number_format($totalCancels),       'sub' => $totalOrders > 0 ? round(($totalCancels / max(1,$totalOrders)) * 100, 1).'% of orders' : '—', 'icon' => 'x-circle', 'tone' => 'warning'],
        ['label' => 'Late ships',    'value' => number_format($totalLate),          'sub' => $bestDay ? 'Best day: '.$bestDay->delivered_orders.' delivered' : '—', 'icon' => 'truck', 'tone' => 'muted'],
    ];
@endphp
<section class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-4">
    @foreach ($historyKpis as $kpi)
        <article class="bg-white border border-border rounded-sm shadow-sm p-4 transition-shadow hover:shadow-md relative overflow-hidden">
            <div class="h-1 absolute top-0 left-0 right-0
                {{ $kpi['tone'] === 'brand' ? 'bg-brand' : (
                   $kpi['tone'] === 'success' ? 'bg-emerald-500' : (
                   $kpi['tone'] === 'info' ? 'bg-blue-500' : (
                   $kpi['tone'] === 'warning' ? 'bg-amber-500' : (
                   $kpi['tone'] === 'danger' ? 'bg-rose-500' : 'bg-gray-500')))) }}"></div>
            <div class="flex items-start justify-between gap-3 mt-1">
                <div class="min-w-0 flex-1">
                    <p class="text-xs text-ink-tertiary mb-0 uppercase tracking-wider font-semibold">{{ $kpi['label'] }}</p>
                    <h3 class="mb-0 font-bold text-xl text-ink mt-1 truncate">{{ $kpi['value'] }}</h3>
                    <small class="text-ink-tertiary">{{ $kpi['sub'] }}</small>
                </div>
                <span class="shrink-0 w-10 h-10 rounded-sm flex items-center justify-center
                    {{ $kpi['tone'] === 'brand' ? 'bg-brand-tint text-brand' : (
                       $kpi['tone'] === 'success' ? 'bg-emerald-50 text-feedback-success' : (
                       $kpi['tone'] === 'info' ? 'bg-blue-50 text-feedback-info' : (
                       $kpi['tone'] === 'warning' ? 'bg-amber-50 text-feedback-warning' : (
                       $kpi['tone'] === 'danger' ? 'bg-rose-50 text-rose-500' : 'bg-surface-muted text-ink-tertiary')))) }}">
                    <i data-lucide="{{ $kpi['icon'] }}" style="width:20px;height:20px;"></i>
                </span>
            </div>
        </article>
    @endforeach
</section>

{{-- ═══ TIER DISTRIBUTION + TREND CHART ═══ --}}
<section class="grid grid-cols-1 xl:grid-cols-3 gap-3 mb-4">
    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center gap-2 bg-surface-muted">
            <i data-lucide="layers" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Tier Distribution</h5>
        </div>
        <div class="p-4 space-y-3">
            @if ($tierDistribution->isEmpty())
                <p class="text-ink-tertiary text-sm mb-0">No tier history yet.</p>
            @else
                @foreach ($tierDistribution as $tierKey => $count)
                    @php
                        $tierEnum = PerformanceTier::tryFrom($tierKey);
                        $tierLabel = $tierEnum?->label() ?? ucfirst($tierKey);
                        $tierHex = $tierEnum?->color() ?? '#6B7280';
                        $pct = max(0, min(100, $totalSnapshots > 0 ? round(($count / $totalSnapshots) * 100) : 0));
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-xs mb-1">
                            <span class="flex items-center gap-2">
                                <span class="shrink-0 w-3 h-3 rounded-full" style="background: {{ $tierHex }}"></span>
                                <strong class="text-ink font-semibold">{{ $tierLabel }}</strong>
                            </span>
                            <span class="text-ink-tertiary font-mono">{{ $count }} · {{ $pct }}%</span>
                        </div>
                        <div class="perf-history__spark-track">
                            <div class="perf-history__spark-bar" style="width: {{ $pct }}%; background: {{ $tierHex }}"></div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="xl:col-span-2 bg-white border border-border rounded-sm shadow-sm overflow-hidden">
        <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
            <div class="flex items-center gap-2">
                <i data-lucide="line-chart" class="text-brand" style="width:16px;height:16px;"></i>
                <h5 class="mb-0 font-bold text-ink">Score over Time</h5>
            </div>
            <small class="text-ink-tertiary">range: {{ number_format((float) $minScore, 1) }} – {{ number_format((float) $maxScore, 1) }}</small>
        </div>
        <div class="p-4 relative" style="height: 280px;">
            <canvas id="historyTrendChart"></canvas>
        </div>
    </div>
</section>

{{-- ═══ SNAPSHOTS TABLE — DYNAMIC, ALL ROWS LIVE ═══ --}}
<section class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b border-border flex items-center justify-between bg-surface-muted">
        <div class="flex items-center gap-2">
            <i data-lucide="table" class="text-brand" style="width:16px;height:16px;"></i>
            <h5 class="mb-0 font-bold text-ink">Daily Snapshots</h5>
        </div>
        <small class="text-ink-tertiary">{{ $totalSnapshots }} records</small>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-2.5">Date</th>
                    <th class="px-4 py-2.5 text-right">Orders</th>
                    <th class="px-4 py-2.5 text-right">Delivered</th>
                    <th class="px-4 py-2.5 text-right">Late ships</th>
                    <th class="px-4 py-2.5 text-right">Cancels</th>
                    <th class="px-4 py-2.5 text-right">Reviews</th>
                    <th class="px-4 py-2.5 text-right">Avg rating</th>
                    <th class="px-4 py-2.5 text-right">Score</th>
                    <th class="px-4 py-2.5">Tier</th>
                    <th class="px-4 py-2.5">Trend</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border">
                @forelse ($snapshots->sortByDesc('snapshot_date')->values() as $snapshot)
                    @php
                        $sEnum = PerformanceTier::tryFrom($snapshot->tier);
                        $sLabel = $sEnum?->label() ?? ucfirst($snapshot->tier ?? '—');
                        $sHex = $sEnum?->color() ?? '#6B7280';
                        $sBadge = match ($sEnum) {
                            PerformanceTier::EXCELLENT => 'bg-emerald-500 text-white',
                            PerformanceTier::GOOD      => 'bg-blue-500 text-white',
                            PerformanceTier::AVERAGE   => 'bg-amber-500 text-white',
                            PerformanceTier::POOR      => 'bg-rose-500 text-white',
                            PerformanceTier::NEW       => 'bg-gray-500 text-white',
                            default                    => 'bg-surface-muted text-ink-tertiary',
                        };
                        $scoreVal = (float) $snapshot->overall_score;
                    @endphp
                    <tr class="hover:bg-surface-muted/50 transition-colors">
                        <td class="px-4 py-3 font-mono text-ink-secondary">{{ $snapshot->snapshot_date->format('d M Y') }}<br><small class="text-ink-tertiary">{{ $snapshot->snapshot_date->format('D') }}</small></td>
                        <td class="px-4 py-3 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-feedback-info">{{ number_format($snapshot->total_orders) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($snapshot->delivered_orders) }}</td>
                        <td class="px-4 py-3 text-right">
                            @if ((float) $snapshot->late_shipping_rate >= 0.30)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500 text-white">{{ number_format($snapshot->late_shipped_orders) }}</span>
                            @elseif ((float) $snapshot->late_shipping_rate >= 0.10)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-500 text-white">{{ number_format($snapshot->late_shipped_orders) }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-500 text-white">{{ number_format($snapshot->late_shipped_orders) }}</span>
                            @endif
                            <br><small class="text-ink-tertiary">{{ round($snapshot->late_shipping_rate * 100, 1) }}%</small>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ((float) $snapshot->cancellation_rate >= 0.10)
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-rose-500 text-white">{{ number_format($snapshot->cancelled_orders) }}</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-tertiary">{{ number_format($snapshot->cancelled_orders) }}</span>
                            @endif
                            <br><small class="text-ink-tertiary">{{ round($snapshot->cancellation_rate * 100, 1) }}%</small>
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-semibold">{{ number_format($snapshot->review_count) }}</span>
                        </td>
                        <td class="px-4 py-3 text-right">
                            @if ((float) $snapshot->avg_review_rating >= 4.0)
                                <span class="inline-flex items-center gap-0.5 text-feedback-success font-semibold">
                                    <i data-lucide="star" style="width:11px;height:11px;"></i>
                                    {{ number_format((float) $snapshot->avg_review_rating, 2) }}
                                </span>
                            @elseif ((float) $snapshot->avg_review_rating > 0)
                                <span class="text-feedback-warning font-semibold">
                                    {{ number_format((float) $snapshot->avg_review_rating, 2) }}
                                </span>
                            @else
                                <span class="text-ink-tertiary">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <span class="font-mono font-bold" style="color: {{ $sHex }}">{{ number_format($scoreVal, 2) }}</span>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $sBadge }}">
                                {{ $sLabel }}
                            </span>
                        </td>
                        <td class="px-4 py-3" style="min-width: 80px;">
                            <div class="flex items-center gap-1">
                                <div class="perf-history__spark-track flex-1" style="width: 60px; height: 6px;">
                                    <div class="perf-history__spark-bar" style="width: {{ $scoreVal }}%; background: {{ $sHex }}"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-8 text-sm text-ink-tertiary">
                            <i data-lucide="history" class="mx-auto mb-2 opacity-50" style="width:36px;height:36px;"></i>
                            <p class="mb-0">No history snapshots yet.</p>
                            <small>Snapshots are filed daily once your performance has data. Visit your dashboard to refresh now.</small>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
    const snapshots = @json($snapshots->map(fn ($s) => [
        'snapshot_date' => $s->snapshot_date->toDateString(),
        'overall_score' => (float) $s->overall_score,
        'tier' => $s->tier,
    ]));

    if (document.getElementById('historyTrendChart') && snapshots.length > 0) {
        const sorted = snapshots.slice().sort((a, b) => new Date(a.snapshot_date) - new Date(b.snapshot_date));
        const labels = sorted.map(s => {
            const dt = new Date(s.snapshot_date);
            return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        });
        const scores = sorted.map(s => Number(s.overall_score));
        const tierColor = (s) => s >= 85 ? '#059669' : s >= 70 ? '#2563eb' : s >= 50 ? '#d97706' : '#dc2626';
        const pointColors = scores.map(tierColor);

        new Chart(document.getElementById('historyTrendChart'), {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Score',
                    data: scores,
                    borderColor: '#0ea5e9',
                    backgroundColor: 'rgba(14,165,233,.10)',
                    tension: 0.35,
                    fill: true,
                    borderWidth: 2.5,
                    pointRadius: sorted.length > 30 ? 1 : 4,
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
                    x: { grid: { display: false }, ticks: { color: '#767676', font: { size: 10 }, maxRotation: labels.length > 14 ? 45 : 0, autoSkip: true, autoSkipPadding: 14 } }
                }
            }
        });
    }
</script>
@endpush
@endsection
