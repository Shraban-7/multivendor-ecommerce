@extends('seller.layouts.app')
@section('title', 'Performance History')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <div>
            <h4 class="font-bold mb-0">Performance History</h4>
            <small class="text-ink-tertiary">Last {{ (int) request('days', 90) }} days</small>
        </div>
        <a href="{{ route('seller.performance.dashboard') }}" class="btn btn-light btn-sm">← Back</a>
    </div>

    <form method="GET" class="mb-3 flex gap-2">
        <select name="days" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors-sm" onchange="this.form.submit()" style="max-width: 180px;">
            @foreach ([30, 60, 90, 180] as $d)
                <option value="{{ $d }}" @selected((int) request('days', 90) === $d)>Last {{ $d }} days</option>
            @endforeach
        </select>
    </form>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered align-middle mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th>Date</th>
                            <th class="text-right">Orders</th>
                            <th class="text-right">Cancellations</th>
                            <th class="text-right">Late ships</th>
                            <th class="text-right">Reviews</th>
                            <th class="text-right">Avg rating</th>
                            <th class="text-right">Score</th>
                            <th class="text-right">Tier</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($trend as $snapshot)
                            <tr>
                                <td>{{ $snapshot->snapshot_date->format('d/m/Y') }}</td>
                                <td class="text-right">{{ $snapshot->total_orders }}</td>
                                <td class="text-right">{{ $snapshot->cancelled_orders }} <span class="text-ink-tertiary text-sm">({{ round($snapshot->cancellation_rate * 100, 1) }}%)</span></td>
                                <td class="text-right">{{ $snapshot->late_shipped_orders }} <span class="text-ink-tertiary text-sm">({{ round($snapshot->late_shipping_rate * 100, 1) }}%)</span></td>
                                <td class="text-right">{{ $snapshot->review_count }}</td>
                                <td class="text-right">{{ number_format((float) $snapshot->avg_review_rating, 2) }}</td>
                                <td class="text-right font-semibold">{{ number_format((float) $snapshot->overall_score, 2) }}</td>
                                <td class="text-right">
                                    <span class="badge bg-{{\App\Domain\Vendor\Enums\PerformanceTier::tryFrom($snapshot->tier)?->color() ?? 'secondary'}}">
                                        {{ \App\Domain\Vendor\Enums\PerformanceTier::tryFrom($snapshot->tier)?->label() ?? ucfirst($snapshot->tier) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4 text-ink-tertiary">No history yet. Recompute performance from your dashboard to start tracking.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
