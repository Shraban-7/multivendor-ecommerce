@extends('seller.layouts.app')
@section('title', 'Report Overview')

@section('content')
    <div>
        <header>
            <div class="grid grid-cols-1 items-center mb-4">
                <div class="md:col-span-1 mb-3 mb-md-0">
                    <h2 class="font-bold mb-1 text-ink">Business Overview</h2>
                    <nav aria-label="flex items-center gap-2 text-sm">
                        <ol class="flex items-center gap-2 text-sm mb-0 text-sm">
                            <li class="text-ink-tertiary text-ink-tertiary">Reports</li>
                            <li class="text-ink-tertiary active font-semibold" aria-current="page">Business Overview</li>
                        </ol>
                    </nav>
                </div>

                <div class="md:col-span-1">
                    <form method="GET" class="grid grid-cols-1 gap-2 justify-end">
                        <div class="md:col-span-1 sm:col-span-1">
                            <select name="range" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors-sm"
                                onchange="toggleCustomDates(this.value)">
                                <option disabled selected>--select--</option>
                                <option value="daily" {{ request('range') == 'daily' ? 'selected' : '' }}>Daily</option>
                                <option value="weekly" {{ request('range') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                <option value="monthly" {{ request('range') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="yearly" {{ request('range') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                <option value="custom" {{ request('range') == 'custom' ? 'selected' : '' }}>Custom</option>
                            </select>
                        </div>

                        <div class="md:col-span-1 sm:col-span-1" id="customDateRange"
                            style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
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

        <div class="grid grid-cols-1 mb-5 g-3">
            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-ink-tertiary uppercase mb-1 text-sm font-semibold">Total Sales</p>
                            <span class="font-bold text-xl text-brand">{{ money($calculateMetrics['total_sales']) }}</span>
                        </div>
                        <i data-lucide="shopping-bag" class="opacity-25 text-xl"></i>
                    </div>
                    <div class="mt-2">
                        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden mb-1" style="height: 3px;">
                            <div class="h-full bg-brand-deep rounded-full transition-all bg-feedback-success" style="width: {{ $calculateMetrics['sales_growth'] }}%"></div>
                        </div>
                        <small class="text-feedback-success font-semibold"><i data-lucide="arrow-up" class="me-1"></i>{{ $calculateMetrics['sales_growth'] }}%</small>
                        <small class="text-ink-tertiary text-sm">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-ink-tertiary uppercase mb-1 text-sm font-semibold">Orders</p>
                            <span class="font-bold text-xl text-feedback-info">{{ $calculateMetrics['total_orders'] }}</span>
                        </div>
                        <i data-lucide="clipboard-list" class="opacity-25 text-xl"></i>
                    </div>
                    <div class="mt-2">
                        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden mb-1" style="height: 3px;">
                            <div class="h-full bg-brand-deep rounded-full transition-all bg-feedback-success" style="width: {{ $calculateMetrics['orders_growth'] }}%"></div>
                        </div>
                        <small class="text-feedback-success font-semibold"><i data-lucide="arrow-up" class="me-1"></i>{{ $calculateMetrics['orders_growth'] }}%</small>
                        <small class="text-ink-tertiary text-sm">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-ink-tertiary uppercase mb-1 text-sm font-semibold">Net Profit</p>
                            <span class="font-bold text-xl text-feedback-success">{{ money($calculateMetrics['net_profit']) }}</span>
                        </div>
                        <i data-lucide="dollar-sign" class="opacity-25 text-xl"></i>
                    </div>
                    <div class="mt-2">
                        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden mb-1" style="height: 3px;">
                            <div class="h-full bg-brand-deep rounded-full transition-all bg-feedback-success" style="width: {{ $calculateMetrics['profit_growth'] }}%"></div>
                        </div>
                        <small class="text-feedback-success font-semibold"><i data-lucide="arrow-up" class="me-1"></i>{{ $calculateMetrics['profit_growth'] }}%</small>
                        <small class="text-ink-tertiary text-sm">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-ink-tertiary uppercase mb-1 text-sm font-semibold">Ret. Customers</p>
                            <span class="font-bold text-xl text-feedback-warning">{{ number_format($quickFacts['returning_customers_percent'], 2)}}%</span>
                        </div>
                        <i data-lucide="users" class="opacity-25 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-ink-tertiary uppercase mb-1 text-sm font-semibold">AOV</p>
                            <span class="font-bold text-xl text-ink-secondary">{{ money($calculateMetrics['aov']) }}</span>
                        </div>
                        <i data-lucide="shopping-basket" class="opacity-25 text-xl"></i>
                    </div>
                    <div class="mt-2">
                        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden mb-1" style="height: 3px;">
                            <div class="h-full bg-brand-deep rounded-full transition-all bg-feedback-success" style="width: {{ $calculateMetrics['aov_growth'] }}%"></div>
                        </div>
                        <small class="text-feedback-success font-semibold"><i data-lucide="arrow-up" class="me-1"></i>{{ $calculateMetrics['aov_growth'] }}%</small>
                        <small class="text-ink-tertiary text-sm">vs last {{ request('range') }}</small>
                    </div>
                </div>
            </div>

            <div class="xl:col-span-1 lg:col-span-1 md:col-span-1 sm:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px;">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-ink-tertiary uppercase mb-1 text-sm font-semibold">Total Stock</p>
                            <span class="font-bold text-xl text-ink">{{ $calculateMetrics['total_stock'] }}</span>
                        </div>
                        <i data-lucide="boxes" class="opacity-25 text-xl"></i>
                    </div>
                    <div class="mt-2">
                        <div class="w-full h-2 bg-surface-muted rounded-full overflow-hidden mb-1" style="height: 3px;">
                            <div class="h-full bg-brand-deep rounded-full transition-all bg-feedback-info" style="width: {{ $calculateMetrics['stock_growth'] }}%"></div>
                        </div>
                        <small class="text-feedback-info font-semibold">Stable</small>
                        <small class="text-ink-tertiary text-sm">inventory level</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 mb-5 g-4">
            <div class="lg:col-span-2">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Revenue & Order Trends</h5>
                    <div class="grid grid-cols-1 gap-4">
                        <div class="md:col-span-1">
                            <p class="font-semibold text-ink-tertiary mb-1">{{ request('range') }} Revenue Trend</p>
                            <div class="bg-surface-muted rounded-md border flex items-center justify-center" style="min-height: 250px;">
                                <canvas id="revenueTrend"></canvas>
                            </div>
                        </div>
                        <div class="md:col-span-1">
                            <p class="font-semibold text-ink-tertiary mb-1">Orders vs. Returns</p>
                            <div class="bg-surface-muted rounded-md border flex items-center justify-center" style="min-height: 250px;">
                                <canvas id="ordersReturns"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Quick Facts</h5>
                    <ul class="flex flex-col ">
                        <li class="flex items-center px-0 py-2 border-b border-border flex justify-between items-center px-0">
                            Total Orders:
                            <span class="font-bold text-brand">{{ $quickFacts['total_orders'] }}</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border flex justify-between items-center px-0">
                            Refund Rate:
                            <span class="font-bold text-feedback-danger">{{ $quickFacts['refund_rate'] }}%</span>
                        </li>
                        <li class="flex items-center px-0 py-2 border-b border-border flex justify-between items-center px-0">
                            Best Sales Day:
                            <span class="font-bold text-feedback-success">{{ $quickFacts['best_sales_day'] ?? '-' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4">
            <div class="lg:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Smart Highlights</h5>
                    <div class="flex flex-col ">
                        <a href="#"
                            class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0 py-2 flex items-center">
                            <span class="badge bg-emerald-50 text-feedback-success me-3 p-2"><i data-lucide="chart-line"></i></span>
                            <span class="font-semibold">Revenue grew <span class="text-feedback-success">8%</span> last week.</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0 py-2 flex items-center">
                            <span class="badge bg-brand-tint text-brand me-3 p-2"><i data-lucide="shirt"></i></span>
                            <span class="font-semibold">Apparel category contributed <span class="text-brand">32%</span> of total sales.</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0 py-2 flex items-center">
                            <span class="badge bg-amber-50 text-feedback-warning me-3 p-2"><i data-lucide="repeat"></i></span>
                            <span class="font-semibold">Returning customers spent <span class="text-feedback-warning">18%</span> more.</span>
                        </a>
                        <a href="#"
                            class="flex items-center px-0 py-2 border-b border-border flex flex-col-item-action border-0 py-2 flex items-center">
                            <span class="badge bg-red-50 text-feedback-danger me-3 p-2"><i data-lucide="package"></i></span>
                            <span class="font-semibold">High stock alert in Electronics. Review turnover.</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Top Product Snapshot</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse table-hover mb-0">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Product</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Units Sold</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Sales</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($topProducts as $product)
                                    <tr>
                                        <td class="font-semibold">{{ $product['name'] }}</td>
                                        <td class="text-right">{{ $product['units_sold'] }}</td>
                                        <td class="text-right text-feedback-success font-semibold">{{ money($product['sales']) }}</td>
                                        <td class="text-right">{{ $product['stock'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 mt-4 g-4">
            <div class="col-span-full">
                <h5 class="font-bold mb-3">Recent Reports & Exports</h5>
                <div class="flex flex-wrap gap-3">
                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3" style="border-radius: 12px; width: 18rem;">
                        <div class="flex justify-between items-center">
                            <p class="mb-0 font-semibold">Weekly Performance Summary</p>
                            <a href="#" class="text-brand" title="Download"><i data-lucide="download"></i></a>
                        </div>
                        <small class="text-ink-tertiary">Generated Nov 15, 2025</small>
                    </div>

                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3" style="border-radius: 12px; width: 18rem;">
                        <div class="flex justify-between items-center">
                            <p class="mb-0 font-semibold">Sales vs Target - October</p>
                            <a href="#" class="text-brand" title="Export"><i data-lucide="file-output"></i></a>
                        </div>
                        <small class="text-ink-tertiary">Generated Nov 1, 2025</small>
                    </div>

                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3" style="border-radius: 12px; width: 18rem;">
                        <div class="flex justify-between items-center">
                            <p class="mb-0 font-semibold">Customer Segment Analysis</p>
                            <a href="#" class="text-brand" title="Export"><i data-lucide="file-output"></i></a>
                        </div>
                        <small class="text-ink-tertiary">Generated Nov 18, 2025</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function toggleCustomDates(value) {
            const custom = document.getElementById('customDateRange');
            custom.style.display = (value === 'custom') ? 'block' : 'none';
        }

        const revenueCtx = document.getElementById('revenueTrend');
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: @json($chartData['revenueTrend']['labels']),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartData['revenueTrend']['values']),
                    borderWidth: 2
                }]
            }
        });

        const ordersCtx = document.getElementById('ordersReturns');
        new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: ['Orders', 'Returns'],
                datasets: [{
                    label: 'Orders vs Returns',
                    data: @json([$chartData['ordersReturns']['orders'] ?? 0, $chartData['ordersReturns']['returns'] ?? 0]),
                    backgroundColor: ['#4e73df', '#e74a3b'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endpush
