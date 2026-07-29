@extends('admin.layouts.app')
@section('title', 'Seller Performance')
@section('content')

    <div class="flex justify-between items-center mb-3">
        <h4 class="font-bold mb-0">Seller Performance — {{ $period->label() }}</h4>
        <form method="GET" class="flex gap-2">
            <select name="period" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" onchange="this.form.submit()">
                @foreach (\App\Domain\Vendor\Enums\PerformancePeriod::cases() as $p)
                    <option value="{{ $p->value }}" @selected($period->value === $p->value)>{{ $p->label() }}</option>
                @endforeach
            </select>
        </form>
    </div>

    <div class="grid grid-cols-1 gap-3 mb-4">
        @foreach (\App\Domain\Vendor\Enums\PerformanceTier::cases() as $tier)
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3">
                    <span class="text-ink-tertiary text-sm">{{ $tier->label() }}</span>
                    <h5 class="font-bold mb-0 text-{{ $tier->color() }}">{{ (int) ($stats[$tier->value] ?? 0) }}</h5>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 gap-3 mb-4">
        <div class="lg:col-span-7">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Top Performers</h5>
                    @forelse ($leaderboard as $i => $row)
                        <div class="flex justify-between items-center border-b py-2">
                            <div class="flex items-center gap-2">
                                <span class="badge bg-{{ $i < 3 ? 'success' : 'secondary' }}">#{{ $i + 1 }}</span>
                                <span class="font-semibold">{{ $row->seller?->business_name ?? 'Seller #'.$row->seller_id }}</span>
                            </div>
                            <div class="text-right">
                                <span class="font-bold">{{ number_format((float) $row->overall_score, 2) }}</span>
                                <span class="badge bg-{{ $row->tierColor() }} ms-1">{{ $row->tierLabel() }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-ink-tertiary text-sm mb-0">No scored sellers yet for this period.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="lg:col-span-5">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
                <div class="p-5">
                    <h5 class="font-bold mb-3">Filters</h5>
                    <form method="GET" class="vstack gap-2">
                        <input type="hidden" name="period" value="{{ $period->value }}">
                        <input name="search" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" placeholder="Seller name / username" value="{{ request('search') }}">
                        <select name="tier" class="w-full px-2 py-1 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
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

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm">
        <div class="p-5">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-ink border-collapse table-bordered align-middle mb-0">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th>Ranking</th>
                            <th>Seller</th>
                            <th class="text-right">Orders</th>
                            <th class="text-right">Cancel %</th>
                            <th class="text-right">Late %</th>
                            <th class="text-right">Rating</th>
                            <th class="text-right">Response %</th>
                            <th class="text-right">Score</th>
                            <th>Status</th>
                            <th class="text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($scores as $i => $row)
                            <tr>
                                <td class="font-semibold">{{ $scores->firstItem() + $i }}</td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <img src="{{ $row->seller?->businessAvatar }}" alt="" width="28" height="28" class="rounded-full">
                                        <div>
                                            <div class="font-semibold">{{ $row->seller?->business_name ?? 'N/A' }}</div>
                                            <small class="text-ink-tertiary">@{{ $row->seller?->username ?? '—' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-right">{{ $row->total_orders }}</td>
                                <td class="text-right">{{ round($row->cancellation_rate * 100, 1) }}%</td>
                                <td class="text-right">{{ round($row->late_shipping_rate * 100, 1) }}%</td>
                                <td class="text-right">{{ number_format((float) $row->avg_review_rating, 2) }}</td>
                                <td class="text-right">{{ round($row->response_rate * 100, 1) }}%</td>
                                <td class="text-right font-bold">{{ number_format((float) $row->overall_score, 2) }}</td>
                                <td><span class="badge bg-{{ $row->tierColor() }}">{{ $row->tierLabel() }}</span></td>
                                <td class="text-right">
                                    <a href="{{ route('admin.seller-performance.show', $row->seller_id) }}" class="btn btn-light btn-sm">
                                        <i data-feather="eye" class="icon-xs"></i> View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="text-center py-4 text-ink-tertiary">No scores computed for this period. Run <code>php artisan seller:performance:recompute</code>.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="flex justify-end mt-3">
                {{ $scores->links() }}
            </div>
        </div>
    </div>
@endsection
