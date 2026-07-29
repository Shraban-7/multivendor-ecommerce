@php
    $pageTitle = "Seller Dashboard | {$seller->business_name}";
    $hour = (int) now()->format('G');
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $periodLabel = \Carbon\Carbon::parse($start_date)->format('M j').' – '.\Carbon\Carbon::parse($end_date)->format('M j, Y');
@endphp
@extends('seller.layouts.app')
@section('title', $pageTitle)
@section('content')

<div class="seller-dash">

@if (!$seller->profile_completed)
    <div class="seller-dash__alert" role="alert">
        <div class="flex items-start gap-3">
            <span class="seller-dash__alert-icon">
                <i data-lucide="triangle-alert" style="width:18px;height:18px;"></i>
            </span>
            <div>
                <strong class="text-ink">Your profile is incomplete.</strong>
                <p class="mb-0 text-sm text-ink-tertiary">Complete your profile to unlock full platform access.</p>
            </div>
        </div>
        <a href="{{ route('seller.profile') }}" class="btn btn-sm" style="background:#B7791A;color:#fff;border:none;white-space:nowrap;">Complete Profile</a>
    </div>
@endif

{{-- Hero --}}
<section class="seller-dash__hero">
    <div class="seller-dash__hero-main">
        <p class="seller-dash__eyebrow">{{ $greeting }}</p>
        <h1 class="seller-dash__title">{{ $seller->business_name }}</h1>
        <p class="seller-dash__subtitle">Store pulse for <span>{{ $periodLabel }}</span></p>

        <div class="seller-dash__hero-metrics">
            <div>
                <span class="seller-dash__metric-label">Sales</span>
                <strong class="seller-dash__metric-value">{{ money($total_sales) }}</strong>
            </div>
            <div class="seller-dash__metric-divider" aria-hidden="true"></div>
            <div>
                <span class="seller-dash__metric-label">Earnings</span>
                <strong class="seller-dash__metric-value">{{ money($total_earnings) }}</strong>
            </div>
            <div class="seller-dash__metric-divider" aria-hidden="true"></div>
            <div>
                <span class="seller-dash__metric-label">Orders</span>
                <strong class="seller-dash__metric-value">{{ number_format($total_orders) }}</strong>
            </div>
        </div>
    </div>

    <form method="GET" action="{{ route('seller.dashboard') }}" class="seller-dash__filter">
        <label class="seller-dash__filter-label">Date range</label>
        <div class="seller-dash__filter-row">
            <input type="date" name="start_date" value="{{ $start_date }}" class="seller-dash__date">
            <span class="text-ink-tertiary text-xs">to</span>
            <input type="date" name="end_date" value="{{ $end_date }}" class="seller-dash__date">
            <button type="submit" class="btn btn-primary btn-sm">
                <i data-lucide="funnel" style="width:14px;height:14px;"></i> Apply
            </button>
        </div>
        <div class="seller-dash__presets">
            <a href="{{ route('seller.dashboard', ['start_date' => now()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="seller-dash__chip">Today</a>
            <a href="{{ route('seller.dashboard', ['start_date' => now()->copy()->startOfWeek()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="seller-dash__chip">This week</a>
            <a href="{{ route('seller.dashboard', ['start_date' => now()->copy()->startOfMonth()->toDateString(), 'end_date' => now()->toDateString()]) }}" class="seller-dash__chip">This month</a>
        </div>
    </form>
</section>

{{-- Primary KPIs --}}
<section class="seller-dash__kpi-grid">
    <article class="seller-dash__kpi seller-dash__kpi--sales">
        <div class="seller-dash__kpi-top">
            <span>Total sales</span>
            <span class="seller-dash__kpi-icon"><i data-lucide="trending-up" style="width:16px;height:16px;"></i></span>
        </div>
        <strong>{{ money($total_sales) }}</strong>
        <small>Avg order {{ money($average_order_value) }}</small>
    </article>
    <article class="seller-dash__kpi seller-dash__kpi--profit">
        <div class="seller-dash__kpi-top">
            <span>Profit</span>
            <span class="seller-dash__kpi-icon"><i data-lucide="wallet" style="width:16px;height:16px;"></i></span>
        </div>
        <strong>{{ money($profit) }}</strong>
        <small>After product cost</small>
    </article>
    <article class="seller-dash__kpi seller-dash__kpi--orders">
        <div class="seller-dash__kpi-top">
            <span>Orders</span>
            <span class="seller-dash__kpi-icon"><i data-lucide="shopping-cart" style="width:16px;height:16px;"></i></span>
        </div>
        <strong>{{ number_format($total_orders) }}</strong>
        <small>{{ $delivery_rate }}% delivered / completed</small>
    </article>
    <article class="seller-dash__kpi seller-dash__kpi--customers">
        <div class="seller-dash__kpi-top">
            <span>Customers</span>
            <span class="seller-dash__kpi-icon"><i data-lucide="users" style="width:16px;height:16px;"></i></span>
        </div>
        <strong>{{ number_format($total_customers) }}</strong>
        <small><a href="{{ route('seller.customers') }}">View customers</a></small>
    </article>
</section>

{{-- Attention + pipeline --}}
<section class="seller-dash__split">
    <div class="seller-dash__panel">
        <div class="seller-dash__panel-head">
            <h2>Needs attention</h2>
            <span class="seller-dash__hint">Act on these first</span>
        </div>
        <div class="seller-dash__attention">
            <a href="{{ route('seller.orders.pending') }}" class="seller-dash__attn seller-dash__attn--warn">
                <i data-lucide="clock" style="width:18px;height:18px;"></i>
                <div>
                    <strong>{{ $pending_orders }}</strong>
                    <span>Pending orders</span>
                </div>
            </a>
            <a href="{{ route('seller.returns.index') }}" class="seller-dash__attn seller-dash__attn--danger">
                <i data-lucide="rotate-ccw" style="width:18px;height:18px;"></i>
                <div>
                    <strong>{{ $open_returns }}</strong>
                    <span>Open returns</span>
                </div>
            </a>
            <a href="{{ route('seller.reviews.index', ['status' => 'unreplied']) }}" class="seller-dash__attn seller-dash__attn--info">
                <i data-lucide="message-square" style="width:18px;height:18px;"></i>
                <div>
                    <strong>{{ $unreplied_reviews }}</strong>
                    <span>Unreplied reviews</span>
                </div>
            </a>
            <a href="{{ route('seller.products.index') }}" class="seller-dash__attn seller-dash__attn--stock">
                <i data-lucide="package-x" style="width:18px;height:18px;"></i>
                <div>
                    <strong>{{ $lowStockProducts->count() }}</strong>
                    <span>Low stock items</span>
                </div>
            </a>
        </div>
    </div>

    <div class="seller-dash__panel">
        <div class="seller-dash__panel-head">
            <h2>Order pipeline</h2>
            <a href="{{ route('seller.orders.index') }}" class="text-sm">All orders</a>
        </div>
        <div class="seller-dash__pipeline">
            @foreach ([
                ['label' => 'Pending', 'value' => $pending_orders, 'tone' => 'warn', 'href' => route('seller.orders.pending')],
                ['label' => 'Accepted', 'value' => $accepted_orders, 'tone' => 'info', 'href' => route('seller.orders.index')],
                ['label' => 'Shipped', 'value' => $shipped_orders, 'tone' => 'primary', 'href' => route('seller.orders.index')],
                ['label' => 'Delivered', 'value' => $delivered_orders, 'tone' => 'success', 'href' => route('seller.orders.delivered')],
                ['label' => 'Cancelled', 'value' => $cancelled_orders, 'tone' => 'danger', 'href' => route('seller.orders.index')],
                ['label' => 'Refunded', 'value' => $refunded_orders, 'tone' => 'muted', 'href' => route('seller.orders.index')],
            ] as $step)
                <a href="{{ $step['href'] }}" class="seller-dash__pipe seller-dash__pipe--{{ $step['tone'] }}">
                    <span>{{ $step['label'] }}</span>
                    <strong>{{ $step['value'] }}</strong>
                </a>
            @endforeach
        </div>
        <div class="seller-dash__rates">
            <div>
                <span>Delivery rate</span>
                <div class="seller-dash__bar"><i style="width:{{ min(100, $delivery_rate) }}%"></i></div>
                <em>{{ $delivery_rate }}%</em>
            </div>
            <div>
                <span>Cancel rate</span>
                <div class="seller-dash__bar seller-dash__bar--danger"><i style="width:{{ min(100, $cancel_rate) }}%"></i></div>
                <em>{{ $cancel_rate }}%</em>
            </div>
        </div>
    </div>
</section>

{{-- Charts --}}
<section class="seller-dash__charts">
    <div class="seller-dash__panel seller-dash__panel--chart">
        <div class="seller-dash__panel-head">
            <h2>Sales & orders</h2>
        </div>
        <div class="seller-dash__chart-box">
            <canvas id="salesOrderChart"></canvas>
        </div>
    </div>
    <div class="seller-dash__panel seller-dash__panel--donut">
        <div class="seller-dash__panel-head">
            <h2>Status mix</h2>
        </div>
        <div class="seller-dash__donut-wrap">
            <canvas id="statusDonutChart"></canvas>
        </div>
    </div>
</section>

{{-- Finance + catalog --}}
<section class="seller-dash__stat-row">
    @foreach ([
        ['label' => 'Seller earnings', 'value' => money($total_earnings), 'icon' => 'banknote', 'tone' => 'success', 'sub' => 'After commission'],
        ['label' => 'Commission paid', 'value' => money($total_commission), 'icon' => 'percent', 'tone' => 'primary', 'sub' => 'Platform fee'],
        ['label' => 'Pending payout', 'value' => money($pendingPayout), 'icon' => 'credit-card', 'tone' => 'warn', 'sub' => 'Awaiting transfer', 'href' => route('seller.payouts.index')],
        ['label' => 'Expenses', 'value' => money($total_expense), 'icon' => 'receipt', 'tone' => 'muted', 'sub' => 'Period spend', 'href' => route('seller.expenses.index')],
        ['label' => 'Products', 'value' => number_format($total_products), 'icon' => 'package', 'tone' => 'info', 'sub' => $active_products.' active · stock '.money($total_stock_value), 'href' => route('seller.products.index')],
        ['label' => 'Shop rating', 'value' => number_format($avg_rating, 1).' / 5', 'icon' => 'star', 'tone' => 'rating', 'sub' => $review_count.' reviews', 'href' => route('seller.reviews.index')],
    ] as $stat)
        @if (isset($stat['href']))
            <a href="{{ $stat['href'] }}" class="seller-dash__stat seller-dash__stat--{{ $stat['tone'] }}">
                <span class="seller-dash__stat-icon"><i data-lucide="{{ $stat['icon'] }}" style="width:16px;height:16px;"></i></span>
                <div>
                    <span class="seller-dash__stat-label">{{ $stat['label'] }}</span>
                    <strong>{{ $stat['value'] }}</strong>
                    <small>{{ $stat['sub'] }}</small>
                </div>
            </a>
        @else
            <div class="seller-dash__stat seller-dash__stat--{{ $stat['tone'] }}">
                <span class="seller-dash__stat-icon"><i data-lucide="{{ $stat['icon'] }}" style="width:16px;height:16px;"></i></span>
                <div>
                    <span class="seller-dash__stat-label">{{ $stat['label'] }}</span>
                    <strong>{{ $stat['value'] }}</strong>
                    <small>{{ $stat['sub'] }}</small>
                </div>
            </div>
        @endif
    @endforeach
</section>

{{-- Quick actions --}}
<section class="seller-dash__panel seller-dash__actions-panel">
    <div class="seller-dash__panel-head">
        <h2>Quick actions</h2>
    </div>
    <div class="seller-dash__actions">
        @foreach ([
            ['label' => 'Add product', 'href' => route('seller.products.create'), 'icon' => 'plus-circle'],
            ['label' => 'Pending orders', 'href' => route('seller.orders.pending'), 'icon' => 'inbox'],
            ['label' => 'Inventory', 'href' => route('seller.products.index'), 'icon' => 'boxes'],
            ['label' => 'Create payout', 'href' => route('seller.payouts.create'), 'icon' => 'landmark'],
            ['label' => 'Coupons', 'href' => route('seller.coupons.index'), 'icon' => 'ticket'],
            ['label' => 'Performance', 'href' => route('seller.performance.dashboard'), 'icon' => 'gauge'],
            ['label' => 'Support', 'href' => route('seller.support.index'), 'icon' => 'life-buoy'],
            ['label' => 'Settings', 'href' => route('seller.settings.index'), 'icon' => 'settings'],
        ] as $action)
            <a href="{{ $action['href'] }}" class="seller-dash__action">
                <i data-lucide="{{ $action['icon'] }}" style="width:18px;height:18px;"></i>
                <span>{{ $action['label'] }}</span>
            </a>
        @endforeach
    </div>
</section>

{{-- Products / stock / reviews --}}
<section class="seller-dash__three">
    <div class="seller-dash__panel">
        <div class="seller-dash__panel-head">
            <h2>Top selling products</h2>
            <a href="{{ route('seller.products.index') }}" class="text-sm">Catalog</a>
        </div>
        @if ($top_selling_products->count() > 0)
            <ul class="seller-dash__list">
                @foreach ($top_selling_products as $index => $product)
                    <li>
                        <span class="seller-dash__rank">{{ $index + 1 }}</span>
                        <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}" width="40" height="40">
                        <div class="seller-dash__list-body">
                            <span>{{ $product->name }}</span>
                        </div>
                        <span class="seller-dash__pill">{{ $product->sales_count }} sold</span>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="seller-dash__empty">
                <i data-lucide="shopping-bag" style="width:28px;height:28px;"></i>
                <p>No sales in this period yet.</p>
            </div>
        @endif
    </div>

    <div class="seller-dash__panel">
        <div class="seller-dash__panel-head">
            <h2>Low stock alerts</h2>
            <a href="{{ route('seller.products.index') }}" class="text-sm">Inventory</a>
        </div>
        @if ($lowStockProducts->count() > 0)
            <ul class="seller-dash__list">
                @foreach ($lowStockProducts as $product)
                    <li>
                        <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}" width="40" height="40">
                        <div class="seller-dash__list-body">
                            <span>{{ Str::limit($product->name, 36) }}</span>
                        </div>
                        <span class="seller-dash__pill seller-dash__pill--{{ $product->available_stock <= $product->low_stock_quantity / 2 ? 'danger' : 'warn' }}">
                            {{ $product->available_stock }} left
                        </span>
                    </li>
                @endforeach
            </ul>
            <a href="{{ route('seller.products.index') }}" class="btn btn-outline-primary btn-sm w-full mt-3">Manage inventory</a>
        @else
            <div class="seller-dash__empty seller-dash__empty--ok">
                <i data-lucide="check-circle" style="width:28px;height:28px;"></i>
                <p>All products are well stocked.</p>
            </div>
        @endif
    </div>

    <div class="seller-dash__panel">
        <div class="seller-dash__panel-head">
            <h2>Latest reviews</h2>
            <a href="{{ route('seller.reviews.index') }}" class="text-sm">All reviews</a>
        </div>
        @if ($recentReviews->count() > 0)
            <ul class="seller-dash__reviews">
                @foreach ($recentReviews as $review)
                    <li>
                        <div class="seller-dash__review-top">
                            <strong>{{ $review->user->name ?? 'Customer' }}</strong>
                            <span class="seller-dash__stars" aria-label="{{ $review->rating }} stars">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i data-lucide="star" class="{{ $i <= (int) $review->rating ? 'is-on' : '' }}" style="width:12px;height:12px;"></i>
                                @endfor
                            </span>
                        </div>
                        <p>{{ Str::limit($review->description ?? 'No comment', 90) }}</p>
                        <small>{{ optional($review->created_at)->diffForHumans() }} · {{ Str::limit($review->product->name ?? 'Product', 28) }}</small>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="seller-dash__empty">
                <i data-lucide="star" style="width:28px;height:28px;"></i>
                <p>No reviews yet.</p>
            </div>
        @endif
    </div>
</section>

{{-- Latest orders --}}
<section class="seller-dash__panel seller-dash__orders">
    <div class="seller-dash__panel-head">
        <h2>Latest orders</h2>
        <a href="{{ route('seller.orders.index') }}" class="btn btn-outline-primary btn-sm">View all orders</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-surface-muted border-b border-border text-xs font-semibold text-ink-tertiary uppercase tracking-wider">
                <tr>
                    <th scope="col">Order ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Total</th>
                    <th scope="col">Status</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($latest_orders as $order)
                    <tr>
                        <td class="font-medium">{{ $order->invoice_id }}</td>
                        <td>{{ $order->user->name ?? 'N/A' }}</td>
                        <td class="font-medium">{{ money($order->total) }}</td>
                        <td>
                            @php $label = $order->status->label(); @endphp
                            @if ($label === 'pending')
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-warning">Pending</span>
                            @elseif ($label === 'shipped')
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-primary">Shipped</span>
                            @elseif ($label === 'cancelled')
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-danger">Cancelled</span>
                            @elseif ($label === 'delivered' || $label === 'completed')
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-success">{{ ucfirst($label) }}</span>
                            @elseif ($label === 'refunded')
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-info">Refunded</span>
                            @else
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-semibold badge-soft-secondary">{{ ucfirst($label) }}</span>
                            @endif
                        </td>
                        <td class="text-sm text-ink-tertiary">{{ optional($order->created_at)->format('Y-m-d H:i') ?? 'N/A' }}</td>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" class="btn btn-outline-primary btn-sm">
                                <i data-lucide="eye" style="width:14px;height:14px;"></i> View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4 text-ink-tertiary">No orders in this period.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>

</div>

@endsection

@push('scripts')
<script>
    const chartData = @json($chartData);
    const statusData = @json($orderStatusDistribution);
    const brand = getComputedStyle(document.documentElement).getPropertyValue('--bs-primary').trim() || '#F85606';
    const success = getComputedStyle(document.documentElement).getPropertyValue('--bs-success').trim() || '#1D8A45';

    const statusLabels = {
        0: 'Pending', 1: 'Accepted', 2: 'Shipped', 3: 'Delivered',
        4: 'Completed', 5: 'Cancelled', 6: 'Return Requested',
        7: 'Return Approved', 8: 'Returned', 9: 'Refunded'
    };

    const statusColors = {
        0: '#F59E0B', 1: '#6366F1', 2: '#0ea5e9', 3: '#22C55E',
        4: '#16A34A', 5: '#EF4444', 6: '#F97316', 7: '#A855F7',
        8: '#EC4899', 9: '#94A3B8'
    };

    const ctx = document.getElementById('salesOrderChart').getContext('2d');
    const salesGradient = ctx.createLinearGradient(0, 0, 0, 140);
    salesGradient.addColorStop(0, 'rgba(248, 86, 6, 0.28)');
    salesGradient.addColorStop(1, 'rgba(248, 86, 6, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Orders',
                data: chartData.orders,
                borderColor: brand,
                backgroundColor: salesGradient,
                tension: 0.4,
                fill: true,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3
            }, {
                label: 'Sales',
                data: chartData.sales,
                borderColor: '#0ea5e9',
                backgroundColor: 'transparent',
                tension: 0.4,
                fill: false,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3
            }, {
                label: 'Profit',
                data: chartData.profits,
                borderColor: success,
                backgroundColor: 'transparent',
                tension: 0.4,
                fill: false,
                borderWidth: 2,
                pointRadius: 0,
                pointHoverRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: { usePointStyle: true, boxWidth: 5, padding: 10, font: { size: 10, family: 'Noto Sans' } }
                }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: { callback: function(value) { return '৳' + value; }, font: { size: 9 }, maxTicksLimit: 4 }
                },
                x: { grid: { display: false }, ticks: { font: { size: 9 }, maxTicksLimit: 6 } }
            }
        }
    });

    const statusCtx = document.getElementById('statusDonutChart').getContext('2d');
    const filteredStatuses = Object.entries(statusData).filter(([_, count]) => parseInt(count) > 0);
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: filteredStatuses.map(([key]) => statusLabels[key] || 'Unknown'),
            datasets: [{
                data: filteredStatuses.map(([_, count]) => count),
                backgroundColor: filteredStatuses.map(([key]) => statusColors[key] || '#6B7280'),
                borderWidth: 0,
                hoverOffset: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '72%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { boxWidth: 8, padding: 6, font: { size: 9, family: 'Noto Sans' } }
                }
            }
        }
    });
</script>
@endpush
