@extends('admin.layouts.app')
@section('title', 'Seller Performance')
@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Seller Performance — {{ $period->label() }}</h4>
        <form method="GET" class="d-flex gap-2">
            <select name="period" class="form-select form-select-sm" onchange="this.form.submit()">
                @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                    <option value="{{ $p->value }}" @selected($period->value === $p->value)>{{ $p->label() }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="row g-3 mb-4">
        @foreach (\App\Domain\Vendor\Enums\PerformanceTier::cases() as $tier)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card border-0 shadow-sm p-3">
                    <span class="text-muted small">{{ $tier->label() }}</span>
                    <h5 class="fw-bold mb-0 text-{{ $tier->color() }}">{{ (int) ($stats[$tier->value] ?? 0) }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Top Performers</h5>
                    @forelse ($leaderboard as $i => $row)
                        <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-{{ $i < 3 ? 'success' : 'secondary' }}">#{{ $i + 1 }}</span>
                                <span class="fw-semibold">{{ $row->seller?->business_name ?? 'Seller #'.$row->seller_id }}</span>
                            </div>
                            <div class="text-end">
                                <span class="fw-bold">{{ number_format((float) $row->overall_score, 2) }}</span>
                                <span class="badge bg-{{ $row->tierColor() }} ms-1">{{ $row->tierLabel() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">No scored sellers yet for this period.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Filters</h5>
                    <form method="GET" class="vstack gap-2">
                        <input type="hidden" name="period" value="{{ $period->value }}">
                        <input name="search" class="form-control form-control-sm" placeholder="Seller name / username" value="{{ request('search') }}">
                        <select name="tier" class="form-select form-select-sm">
                            <option value="">All tiers</option>
                            @foreach (\App\Domain\Vendor\Enums\PerformanceTier::cases() as $tier)
                                <option value="{{ $tier->value }}" @selected(request('tier') === $tier->value)>{{ $tier->label() }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-primary btn-sm">Apply</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Ranking</th>
                            <th>Seller</th>
                            <th class="text-end">Orders</th>
                            <th class="text-end">Cancel %</th>
                            <th class="text-end">Late %</th>
                            <th class="text-end">Rating</th>
                            <th class="text-end">Response %</th>
                            <th class="text-end">Score</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($scores as $i => $row)
                            <tr>
                                <td class="fw-semibold">{{ $scores->firstItem() + $i }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $row->seller?->businessAvatar }}" alt="" width="28" height="28" class="rounded-circle">
                                        <div>
                                            <div class="fw-semibold">{{ $row->seller?->business_name ?? 'N/A' }}</div>
                                            <small class="text-muted">@{{ $row->seller?->username ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-end">{{ $row->total_orders }}</td>
                                <td class="text-end">{{ round($row->cancellation_rate * 100, 1) }}%</td>
                                <td class="text-end">{{ round($row->late_shipping_rate * 100, 1) }}%</td>
                                <td class="text-end">{{ number_format((float) $row->avg_review_rating, 2) }}</td>
                                <td class="text-end">{{ round($row->response_rate * 100, 1) }}%</td>
                                <td class="text-end fw-bold">{{ number_format((float) $row->overall_score, 2) }}</td>
                                <td><span class="badge bg-{{ $row->tierColor() }}">{{ $row->tierLabel() }}</span></td>
                                <td class="text-end">
                                    <a href="{{ route('admin.seller-performance.show', $row->seller_id) }}" class="btn btn-sm btn-light border">
                                        <i data-feather="eye" class="icon-xs"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-4 text-muted">No scores computed for this period. Run <code>php artisan seller:performance:recompute</code>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $scores->links() }}
            </div>
        </div>
    </div>
@endsection
