@extends('seller.layouts.app')
@section('title', 'Sales Reports')

@section('content')
    <div>
        <header>
            <div class="grid grid-cols-1 items-center mb-4">
                <div class="md:col-span-1 mb-3 mb-md-0">
                    <h2 class="font-bold mb-1 text-ink">Sales Report</h2>
                    <nav aria-label="flex items-center gap-2 text-sm">
                        <ol class="flex items-center gap-2 text-sm mb-0 text-sm">
                            <li class="text-ink-tertiary text-ink-tertiary">Reports</li>
                            <li class="text-ink-tertiary active font-semibold" aria-current="page">Sales Report</li>
                        </ol>
                    </nav>
                </div>

                <div class="md:col-span-1">
                    <form method="GET" class="grid grid-cols-1 gap-2 justify-end">
                        <div class="md:col-span-1 sm:col-span-1">
                            <select name="range" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors-sm" onchange="toggleCustomDates(this.value)">
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>
                        <div class="md:col-span-1 sm:col-span-1" id="customDateRange" style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                            <div class="flex flex">
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                        </div>
                        <div class="md:col-span-1 sm:col-span-full flex items-end">
                            <button class="btn btn-primary btn-sm w-full">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 mb-4 g-3">
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #F85606;">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-ink-tertiary uppercase text-sm">Total Revenue</span>
                            <h5 class="font-bold mb-0 text-brand">{{ money($total_revenue) }}</h5>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-25"></i>
                    </div>
                    <small class="{{ $revenue_growth >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                        <i class="fas {{ $revenue_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ $revenue_growth }}%
                    </small>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #0ea5e9;">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-ink-tertiary uppercase text-sm">Orders</span>
                            <h5 class="font-bold mb-0 text-feedback-info">{{ $total_order }}</h5>
                        </div>
                        <i class="fas fa-box fa-2x opacity-25"></i>
                    </div>
                    <small class="{{ $order_growth >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                        <i class="fas {{ $order_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ $order_growth }}%
                    </small>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #B7791A;">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-ink-tertiary uppercase text-sm">Avg Order Value</span>
                            <h5 class="font-bold mb-0 text-feedback-warning">{{ money($avg_order) }}</h5>
                        </div>
                        <i class="fas fa-receipt fa-2x opacity-25"></i>
                    </div>
                    <small class="{{ $avg_order_growth >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                        <i class="fas {{ $avg_order_growth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i> {{ $avg_order_growth }}%
                    </small>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #1D8A45;">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-ink-tertiary uppercase text-sm">Best Seller</span>
                            @php
                                if ($bestSelling) {
                                    $productName = $bestSelling->product->name;
                                    $unitsSold = $bestSelling->total_qty;
                                } else {
                                    $productName = null;
                                    $unitsSold = 0;
                                }
                            @endphp
                            <h6 class="font-bold mb-0 text-feedback-success">{{ $productName }}</h6>
                            <p class="mb-0 text-sm text-ink-tertiary">{{ $unitsSold }} units</p>
                        </div>
                        <i class="fas fa-award fa-2x opacity-25"></i>
                    </div>
                    <small class="text-ink-tertiary mt-2">Highest revenue driver</small>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #637381;">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-ink-tertiary uppercase text-sm">Growth %</span>
                            <h5 class="font-bold mb-0 text-ink-secondary">{{ $avg_order_growth > 0 ? '+' : '' }}{{ $avg_order_growth }}%</h5>
                        </div>
                        <i class="fas fa-chart-line fa-2x opacity-25"></i>
                    </div>
                    <small class="text-feedback-success font-semibold mt-2">vs previous {{ request('range') }}</small>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #D93025;">
                    <div class="flex justify-between items-center">
                        <div>
                            <span class="text-ink-tertiary uppercase text-sm">Refund Rate</span>
                            <h5 class="font-bold mb-0 text-feedback-danger">{{ $refund_rate }}%</h5>
                        </div>
                        <i class="fas fa-undo fa-2x opacity-25"></i>
                    </div>
                    <small class="font-semibold mt-2 {{ $refundRateChange >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                        <i class="fas fa-arrow-{{ $refundRateChange >= 0 ? 'up' : 'down' }} me-1"></i> {{ $refundRateChange }} pts
                    </small>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="lg:col-span-full">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Revenue Trend Over Time</h5>

                    <div class="flex justify-start mb-3">
                        <ul class="nav nav-pills nav-pills-sm">
                            @php $range = request('range', 'daily'); @endphp
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'daily' ? 'active' : '' }}">Day</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'weekly' ? 'active' : '' }}">Week</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'monthly' ? 'active' : '' }}">Month</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'yearly' ? 'active' : '' }}">Yearly</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $range == 'custom' ? 'active' : '' }}">Custom</a>
                            </li>
                        </ul>
                    </div>

                    <canvas id="revenueTrendChart" height="100"></canvas>

                    <p class="p-4 rounded-sm bg-surface-muted border border-border text-ink text-sm flex items-start gap-3 p-2 mt-3 mb-0 text-center font-semibold">
                        <i class="fas fa-check-circle me-1"></i>
                        Sales are {{ $revenue_growth >= 0 ? 'up' : 'down' }}
                        <span class="{{ $revenue_growth >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ abs($revenue_growth) }}%</span>
                        vs. previous period.
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="lg:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Product Category Performance</h5>
                    <div class="grid grid-cols-1">
                        <div class="md:col-span-5 flex justify-center items-center">
                            <div style="width:100%; min-height: 200px;">
                                <canvas id="categoryPieChart"></canvas>
                            </div>
                        </div>
                        <div class="md:col-span-7">
                            <p class="font-semibold text-ink-tertiary text-sm mt-3 mt-md-0">Revenue & Order Breakdown:</p>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm text-ink border-collapse text-sm border-0 mb-0">
                                    <thead class="bg-surface-muted">
                                        <tr>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary">Category</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Sales</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Orders</th>
                                            <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Growth</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categoryData as $data)
                                            <tr class="{{ $data['growth'] >= 0 ? 'font-semibold' : '' }}">
                                                <td>{{ $data['category'] }}</td>
                                                <td class="text-right">{{ money($data['sales']) }}</td>
                                                <td class="text-right">{{ $data['orders'] }}</td>
                                                <td class="text-right {{ $data['growth'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                                                    {{ $data['growth'] >= 0 ? '+' : '' }}{{ $data['growth'] }}%
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Sales Channel Contribution</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse table-hover mb-0">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Channel</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Revenue</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Orders</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Contribution %</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($channelData as $data)
                                    <tr class="{{ $data['isTop'] ? 'font-semibold' : '' }}">
                                        <td>
                                            {{ $data['channel'] }}
                                            @if ($data['isTop'])
                                                <span class="badge badge-soft-primary ms-2">Top Source</span>
                                            @endif
                                        </td>
                                        <td class="text-right">{{ money($data['revenue']) }}</td>
                                        <td class="text-right">{{ $data['orders'] }}</td>
                                        <td class="text-right">{{ $data['contribution'] }}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">
                        <p class="font-semibold text-ink-tertiary text-sm mb-1">Total Orders Distribution:</p>
                        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden" style="height: 15px;">
                            @foreach ($channelData as $data)
                                <div class="h-full bg-brand-deep rounded-full transition-all {{ $data['isTop'] ? 'bg-brand-deep' : 'bg-feedback-info' }}"
                                    role="w-full h-2 bg-surface-muted rounded-full overflow-hiddenbar" style="width: {{ $data['contribution'] }}%"
                                    aria-valuenow="{{ $data['contribution'] }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $data['channel'] }} ({{ $data['contribution'] }}%)
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="lg:col-span-7">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Top-Selling Products by Revenue</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse table-hover mb-0">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Product</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Price</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Units Sold</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Total Sales</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Profit Margin</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productStats as $prod)
                                    <tr>
                                        <td class="font-semibold">{{ $prod['product_name'] }}</td>
                                        <td class="text-right">{{ money($prod['price']) }}</td>
                                        <td class="text-right">{{ $prod['units_sold'] }}</td>
                                        <td class="text-right text-feedback-success font-semibold">{{ money($prod['total_sales']) }}</td>
                                        <td class="text-right text-brand font-semibold">{{ $prod['profit_margin'] }}%</td>
                                    </tr>
                                    <tr>
                                        <td colspan="5" class="p-1">
                                            <div class="text-sm text-ink-tertiary">Relative Sales: {{ $prod['relative_sales'] }}%</div>
                                            <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden" style="height: 5px;">
                                                <div class="h-full bg-brand-deep rounded-full transition-all bg-feedback-success" style="width: {{ $prod['relative_sales'] }}%"></div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Sales by Region (Orders)</h5>
                    <canvas id="regionChart" style="min-height: 200px;"></canvas>
                    <p class="mt-3 mb-0 text-sm text-ink-tertiary text-center">
                        Focus on regions with high order volume for targeted marketing campaigns.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        function toggleCustomDates(value) {
            const custom = document.getElementById('customDateRange');
            custom.style.display = (value === 'custom') ? 'block' : 'none';
        }

        const revenueCtx = document.getElementById('revenueTrendChart').getContext('2d');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($revenues),
                    backgroundColor: 'rgba(248, 86, 6, 0.1)',
                    borderColor: '#F85606',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        const categoryPieCtx = document.getElementById('categoryPieChart').getContext('2d');
        const categoryLabels = {!! json_encode($categoryData->pluck('category')) !!};
        const categoryRevenue = {!! json_encode($categoryData->pluck('sales')) !!};
        new Chart(categoryPieCtx, {
            type: 'pie',
            data: {
                labels: categoryLabels,
                datasets: [{
                    data: categoryRevenue,
                    backgroundColor: ['#F85606', '#1D8A45', '#0ea5e9', '#B7791A', '#D93025', '#637381', '#fd7e14', '#20c997', '#6610f2', '#6f42c1'],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: 'right', labels: { boxWidth: 20, padding: 15 } }
                }
            }
        });

        const ctxRegion = document.getElementById('regionChart').getContext('2d');
        new Chart(ctxRegion, {
            type: 'bar',
            data: {
                labels: @json($divisionLabels),
                datasets: [{
                    label: 'Orders',
                    data: @json($divisionOrders),
                    backgroundColor: 'rgba(248, 86, 6, 0.6)',
                    borderColor: '#F85606',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush
