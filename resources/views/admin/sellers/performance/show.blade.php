@extends('admin.layouts.app')
@section('title', $seller->business_name.' — Performance')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">{{ $seller->business_name }}</h4>
            <small class="text-muted">{{ $period->label() }} · @{{ $seller->username }}</small>
        </div>
        <div class="d-flex gap-2">
            <form method="GET" class="d-flex gap-2">
                <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                        <option value="{{ $p->value }}" @selected($period->value === $p->value)>{{ $p->label() }}</option>
                    @endforeach
                </select>
            </form>
            <form method="POST" action="{{ route('admin.seller-performance.recompute', $seller) }}">
                @csrf
                <button class="btn btn-sm btn-light border">
                    <i data-feather="refresh-cw" class="icon-xs"></i> Recompute
                </button>
            </form>
            <a href="{{ route('admin.seller-performance.index') }}" class="btn btn-sm btn-light border">← Back</a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show py-2">{{ session('success') }}</div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-lg-6">
            <div class="card border-0 shadow-sm p-4">
                <span class="text-muted small">Overall Score</span>
                <h2 class="fw-bold mb-1">{{ number_format((float) $score->overall_score, 2) }} <small class="text-muted">/ 100</small></h2>
                <span class="badge bg-{{ $score->tierColor() }}">{{ $score->tierLabel() }}</span>
            </div>
        </div>
        @foreach ([
            ['Cancellation', round($score->cancellation_rate * 100, 1).'%', $score->cancellation_score, $score->cancelled_orders.' of '.$score->total_orders],
            ['Late shipping', round($score->late_shipping_rate * 100, 1).'%', $score->late_shipping_score, $score->late_shipped_orders.' of '.$score->shipped_orders],
            ['Customer rating', number_format($score->avg_review_rating, 2).'/5', $score->rating_score, $score->review_count.' reviews'],
            ['Response rate', round($score->response_rate * 100, 1).'%', $score->response_score, $score->chat_responded_count.' of '.$score->chat_count],
            ['Dispute rate', round($score->dispute_rate * 100, 1).'%', $score->dispute_score, $score->disputed_returns.' disputed'],
        ] as $card)
            <div class="col-xl-2 col-lg-3 col-md-6">
                <div class="card border-0 shadow-sm p-3 h-100">
                    <span class="text-muted small">{{ $card[0] }}</span>
                    <h4 class="fw-bold mb-1">{{ $card[1] }}</h4>
                    <div class="small text-muted">{{ $card[3] }}</div>
                    <div class="progress mt-2" style="height: 6px;">
                        <div class="progress-bar bg-{{ $card[2] >= 75 ? 'success' : ($card[2] >= 50 ? 'warning' : 'danger') }}" style="width: {{ $card[2] }}%"></div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Score Trend (last 60 days)</h5>
                    <canvas id="trendChart" height="120"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">By period</h5>
                    <table class="table table-sm mb-0">
                        <thead class="table-light">
                            <tr><th>Window</th><th class="text-end">Score</th><th class="text-end">Tier</th></tr>
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
            <h5 class="fw-bold mb-3">Score breakdown ({{ $period->label() }})</h5>
            <pre class="bg-light p-3 rounded small mb-0">{{ json_encode($score->breakdown, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
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
            if (! ctx || trend.length === 0) return;
            new Chart(ctx, {
                type: 'line',
                data: { labels, datasets: [{ label: 'Score', data: scores, borderColor: '#0d6efd', backgroundColor: 'rgba(13,110,253,.1)', tension: 0.3, fill: true }] },
                options: { plugins: { legend: { display: false } }, scales: { y: { min: 0, max: 100 } } },
            });
        })();
    </script>
@endpush
