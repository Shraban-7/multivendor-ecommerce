@extends('seller.layouts.app')
@section('title', 'Performance History')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="fw-bold mb-0">Performance History</h4>
            <small class="text-muted">Last {{ (int) request('days', 90) }} days</small>
        </div>
        <a href="{{ route('seller.performance.dashboard') }}" class="btn btn-sm btn-light border">← Back</a>
    </div>

    <form method="GET" class="mb-3 d-flex gap-2">
        <select name="days" class="form-select form-select-sm" onchange="this.form.submit()" style="max-width: 180px;">
            @foreach ([30, 60, 90, 180] as $d)
                <option value="{{ $d }}" @selected((int) request('days', 90) === $d)>Last {{ $d }} days</option>
            @endforeach
        </select>
    </form>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Date</th>
                            <th class="text-end">Orders</th>
                            <th class="text-end">Cancellations</th>
                            <th class="text-end">Late ships</th>
                            <th class="text-end">Reviews</th>
                            <th class="text-end">Avg rating</th>
                            <th class="text-end">Score</th>
                            <th class="text-end">Tier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trend as $snapshot)
                            <tr>
                                <td>{{ $snapshot->snapshot_date->format('d/m/Y') }}</td>
                                <td class="text-end">{{ $snapshot->total_orders }}</td>
                                <td class="text-end">{{ $snapshot->cancelled_orders }} <span class="text-muted small">({{ round($snapshot->cancellation_rate * 100, 1) }}%)</span></td>
                                <td class="text-end">{{ $snapshot->late_shipped_orders }} <span class="text-muted small">({{ round($snapshot->late_shipping_rate * 100, 1) }}%)</span></td>
                                <td class="text-end">{{ $snapshot->review_count }}</td>
                                <td class="text-end">{{ number_format((float) $snapshot->avg_review_rating, 2) }}</td>
                                <td class="text-end fw-semibold">{{ number_format((float) $snapshot->overall_score, 2) }}</td>
                                <td class="text-end">
                                    <span class="badge bg-{{\App\Domain\Vendor\Enums\PerformanceTier::tryFrom($snapshot->tier)?->color() ?? 'secondary'}}">
                                        {{ \App\Domain\Vendor\Enums\PerformanceTier::tryFrom($snapshot->tier)?->label() ?? ucfirst($snapshot->tier) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-muted">No history yet. Recompute performance from your dashboard to start tracking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
