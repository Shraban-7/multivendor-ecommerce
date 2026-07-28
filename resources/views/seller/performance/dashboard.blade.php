@extends('seller.layouts.app')
@section('title', 'Performance Dashboard')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Performance Dashboard</h4>
            <small class="text-muted">Score across {{ $period->label() }}</small>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                        <option value="{{ $p->value }}" @selected($period->value === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
            </form>
            <form method="POST" action="{{ route('seller.performance.recompute') }}">
                @csrf
                <button class="btn btn-sm btn-light border">
                    <i data-feather="refresh-cw" class="icon-xs"></i> Refresh
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm p-4">
                <span class="text-muted small">Overall Score</span>
                <div class="d-flex align-items-end gap-2">
                    <h2 class="fw-bold mb-0">{{ number_format((float) $score->overall_score, 2) }}</h2>
                    <span class="text-muted small mb-1">/ 100</span>
                </div>
                <span class="badge bg-{{ $score->tierColor() }} mt-2">{{ $score->tierLabel() }}</span>
            </div>
        </div>
        @foreach ([
            ['label' => 'Cancellation rate', 'value' => round($score->cancellation_rate * 100, 1).'%', 'score' => $score->cancellation_score, 'sub' => $score->cancelled_orders.' of '.$score->total_orders],
            ['label' => 'Late shipping rate', 'value' => round($score->late_shipping_rate * 100, 1).'%', 'score' => $score->late_shipping_score, 'sub' => $score->late_shipped_orders.' of '.$score->shipped_orders],
            ['label' => 'Customer rating', 'value' => number_format($score->avg_review_rating, 2).' / 5', 'score' => $score->rating_score, 'sub' => $score->review_count.' reviews'],
            ['label' => 'Response rate', 'value' => round($score->response_rate * 100, 1).'%', 'score' => $score->response_score, 'sub' => $score->chat_responded_count.' of '.$score->chat_count],
        ] as $card)
            <div class="col-xl-2 col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <span class="text-muted small">{{ $card['label'] }}</span>
                    <h4 class="fw-bold mb-1">{{ $card['value'] }}</h4>
                    <div class="small text-muted">{{ $card['sub'] }}</div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $card['score'] >= 75 ? 'success' : ($card['score'] >= 50 ? 'warning' : 'danger') }}" style="width: {{ $card['score'] }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if (! empty($alerts))
        <div class="row g-3 mb-4">
            @foreach ($alerts as $alert)
                <div class="col-lg-6">
                    <div class="alert alert-{{ $alert['level'] }} border-0 shadow-sm mb-0">
                        <h6 class="fw-bold mb-1">{{ $alert['title'] }}</h6>
                        <div class="small">{{ $alert['body'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Score Trend (last 30 days)</h5>
                    <canvas id="trendChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Period comparison</h5>
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Window</th>
                                <th class="text-end">Score</th>
                                <th class="text-end">Tier</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                                @php $row = $scores[$p->value]; @endphp
                                <tr>
                                    <td>{{ $p->label() }}</td>
                                    <td class="text-end fw-semibold">{{ number_format((float) $row->overall_score, 2) }}</td>
                                    <td class="text-end"><span class="badge bg-{{ $row->tierColor() }}">{{ $row->tierLabel() }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h5 class="fw-bold mb-3">How your score is calculated</h5>
            <div class="row g-3">
                @foreach (['cancellation_score' => 'Cancellation', 'late_shipping_score' => 'Late shipping', 'rating_score' => 'Customer rating', 'response_score' => 'Response rate', 'dispute_score' => 'Return disputes'] as $key => $label)
                    @php
                        $subScore = (float) ($score->{$key} ?? 0);
                        $weight = (float) ($score->weights[str_replace('_score', '', $key)] ?? 0) * 100;
                    @endphp
                    <div class="col-lg-4 col-md-6">
                        <div class="border rounded p-3">
                            <div class="d-flex justify-content-between">
                                <span class="fw-semibold">{{ $label }}</span>
                                <span class="text-muted small">weight {{ number_format($weight, 0) }}%</span>
                            </div>
                            <div class="d-flex align-items-end gap-2 mt-2">
                                <h4 class="fw-bold mb-0">{{ number_format($subScore, 1) }}</h4>
                                <span class="text-muted small mb-1">/ 100</span>
                            </div>
                            <div class="progress mt-2" style="height: 4px;">
                                <div class="progress-bar bg-{{ $subScore >= 75 ? 'success' : ($subScore >= 50 ? 'warning' : 'danger') }}" style="width: {{ $subScore }}%"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function () {
            const trend = @json($trend);
            const labels = trend.map(t => t.snapshot_date);
            const scores = trend.map(t => Number(t.overall_score));

            const ctx = document.getElementById('trendChart');
            if (! ctx || trend.length === 0) {
                ctx.parentElement.innerHTML += '<p class="text-muted small mb-0">No trend data yet — keep selling!</p>';
                return;
            }

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'Overall score',
                        data: scores,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13,110,253,.1)',
                        tension: 0.3,
                        fill: true,
                    }],
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { min: 0, max: 100 } },
                },
            });
        })();
    </script>
@endpush