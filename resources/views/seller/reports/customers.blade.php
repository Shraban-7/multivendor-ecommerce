@extends('seller.layouts.app')
@section('title', 'Customers Reports')

@section('content')
    <div>
        <header>
            <div class="grid grid-cols-1 items-center mb-4">
                <div class="md:col-span-1 mb-3 mb-md-0">
                    <h2 class="font-bold mb-1 text-ink">Customer Report</h2>
                    <nav aria-label="flex items-center gap-2 text-sm">
                        <ol class="flex items-center gap-2 text-sm mb-0 text-sm">
                            <li class="text-ink-tertiary text-ink-tertiary">Reports</li>
                            <li class="text-ink-tertiary active font-semibold" aria-current="page">Customer Report</li>
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
                            </select>
                        </div>
                        <div class="md:col-span-1 sm:col-span-1" id="customDateRange" style="{{ request('range') == 'custom' ? '' : 'display:none;' }}">
                            <label class="block text-xs font-medium text-ink-secondary mb-1 text-sm">Custom Date Range</label>
                            <div class="flex flex">
                                <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                <input type="date" name="date_to" value="{{ request('date_to') }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                            </div>
                        </div>
                        <div class="md:col-span-1 sm:col-span-full flex items-end">
                            <button class="inline-flex items-center justify-center px-3 py-1.5 bg-brand-deep text-white text-sm font-medium rounded-xs hover:bg-brand focus:outline-none focus:ring-2 focus:ring-brand-tint disabled:opacity-50 transition-colors w-full inline-flex items-center justify-center gap-1">Filter</button>
                        </div>
                    </form>
                </div>
            </div>
        </header>

        <div class="grid grid-cols-1 mb-5 g-4">
            @php
                $kpis = [
                    ['label' => 'Total Customers', 'value' => $allTimeTotalCustomers, 'change' => 0, 'icon' => 'fa-users', 'color' => 'primary', 'note' => 'all time'],
                    ['label' => 'New Customers', 'value' => $newCustomersCurrent, 'change' => $newCustomersChange, 'icon' => 'fa-user-plus', 'color' => 'info', 'note' => 'this period'],
                    ['label' => 'Returning %', 'value' => $returningPercentage . '%', 'change' => null, 'icon' => 'fa-redo-alt', 'color' => 'success', 'note' => 'vs previous'],
                    ['label' => 'Avg CLV', 'value' => money($avgClvCurrent), 'change' => $avgClvChange, 'icon' => 'fa-hand-holding-usd', 'color' => 'warning', 'note' => 'last period'],
                    ['label' => 'Avg Orders/Cust', 'value' => $avgOrdersPerCustomerCurrent, 'change' => $avgOrdersPerCustomerChange, 'icon' => 'fa-cart-shopping', 'color' => 'secondary', 'note' => 'vs last period'],
                ];
            @endphp
            @foreach ($kpis as $index => $kpi)
                <div class="xl:col-span-1-{{ in_array($index, [2, 3, 4]) ? 2 : 3 }} lg:col-span-1 md:col-span-1">
                    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 5px solid var(--bs-{{ $kpi['color'] }});">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-ink-tertiary uppercase text-sm">{{ $kpi['label'] }}</span>
                                <h5 class="font-bold text-{{ $kpi['color'] }} mb-0">{{ $kpi['value'] }}</h5>
                            </div>
                            <i class="fas {{ $kpi['icon'] }} opacity-50 text-xl"></i>
                        </div>
                        @if (!is_null($kpi['change']))
                            <small class="{{ $kpi['change'] >= 0 ? 'text-feedback-success' : 'text-feedback-danger' }} font-semibold mt-2">
                                <i class="fas fa-arrow-{{ $kpi['change'] >= 0 ? 'up' : 'down' }} me-1"></i>{{ abs($kpi['change']) }}%
                            </small>
                        @endif
                        <small class="text-ink-tertiary text-sm">{{ $kpi['note'] }}</small>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="lg:col-span-7">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3">Customer Growth Trend</h5>
                    <div class="flex justify-between items-center mb-3">
                        <ul class="nav nav-pills nav-pills-sm" id="customerTabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" id="total-tab" data-bs-toggle="pill" href="#total" role="tab">Total Customers</a></li>
                            <li class="nav-item"><a class="nav-link" id="new-returning-tab" data-bs-toggle="pill" href="#new-returning" role="tab">New vs Returning</a></li>
                        </ul>
                        <span class="text-ink-tertiary text-sm">Last 12 Months</span>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="total" role="tabpanel">
                            <canvas id="totalCustomersChart" height="200"></canvas>
                        </div>
                        <div class="tab-pane fade" id="new-returning" role="tabpanel">
                            <canvas id="newReturningChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="lg:col-span-5">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3 text-feedback-success">Top High-Value Customers</h5>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse text-sm border-0 mb-0">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Customer Name</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Orders</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Total Spent</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($topCustomers as $index => $cust)
                                    <tr class="{{ $index === 0 ? 'font-semibold' : '' }}">
                                        <td>@if ($index === 0)<i class="fas fa-trophy me-1 text-feedback-warning"></i>@endif{{ $cust['name'] }}</td>
                                        <td class="text-right">{{ $cust['orders'] }}</td>
                                        <td class="text-right text-feedback-success font-semibold">{{ money($cust['spent']) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-ink-tertiary py-3">No customers found for this period.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-5">
            <div class="lg:col-span-full">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-4 h-full" style="border-radius: 12px;">
                    <h5 class="font-bold mb-3 text-feedback-info">Loyalty & RFM Analysis</h5>
                    <div class="grid grid-cols-1 mb-3 g-3">
                        <div class="md:col-span-1">
                            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-3 bg-surface-muted border-0 shadow-sm" style="border-radius: 12px;">
                                <p class="mb-0 text-sm text-ink-tertiary">Repeat Rate:</p>
                                <h4 class="font-bold text-feedback-info mb-0">42%</h4>
                            </div>
                        </div>
                        <div class="md:col-span-1">
                            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-3 bg-surface-muted border-0 shadow-sm" style="border-radius: 12px;">
                                <p class="mb-0 text-sm text-ink-tertiary">Avg. Time Between Purchases:</p>
                                <h4 class="font-bold text-ink mb-0">18 days</h4>
                            </div>
                        </div>
                        <div class="md:col-span-1">
                            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-3 bg-surface-muted border-0 shadow-sm" style="border-radius: 12px;">
                                <p class="mb-0 text-sm text-ink-tertiary">Next Purchase Likelihood:</p>
                                <h4 class="font-bold text-feedback-success mb-0">High</h4>
                            </div>
                        </div>
                    </div>
                    <h6 class="font-semibold mt-3 text-ink-secondary">RFM (Recency, Frequency, Monetary) Summary</h6>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-ink border-collapse table-hover text-sm mb-0">
                            <thead class="bg-surface-muted">
                                <tr>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Segment</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Count</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary text-right">Revenue Share</th>
                                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="font-semibold bg-emerald-50">
                                    <td>Loyal <i class="fas fa-star text-feedback-warning"></i></td>
                                    <td class="text-right">220</td>
                                    <td class="text-right text-feedback-success">45%</td>
                                    <td>High CLV, frequent buyers</td>
                                </tr>
                                <tr>
                                    <td>At-Risk</td>
                                    <td class="text-right">60</td>
                                    <td class="text-right text-feedback-danger">12%</td>
                                    <td>Few recent orders, need engagement</td>
                                </tr>
                                <tr>
                                    <td>New</td>
                                    <td class="text-right">180</td>
                                    <td class="text-right text-feedback-info">20%</td>
                                    <td>Growing base, encourage repeat</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 mb-5">
            <div class="col-span-full">
                <h5 class="font-bold mb-3">Actionable Insights</h5>
                <div class="grid grid-cols-1 gap-3">
                    <div class="md:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #1D8A45;">
                            <div class="flex items-center">
                                <i class="fas fa-smile me-3 text-feedback-success fa-2x"></i>
                                <p class="mb-0 font-semibold">Loyal customers up <span class="text-feedback-success">9%</span> this month. Maintain personalized offers.</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #F85606;">
                            <div class="flex items-center">
                                <i class="fas fa-arrow-up-right-dots me-3 text-brand fa-2x"></i>
                                <p class="mb-0 font-semibold">Returning customers spend <span class="text-brand">30%</span> more per transaction.</p>
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-1">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm p-3 h-full" style="border-radius: 12px; border-left: 4px solid #D93025;">
                            <div class="flex items-center">
                                <i class="fas fa-chart-simple me-3 text-feedback-danger fa-2x"></i>
                                <p class="mb-0 font-semibold">At-Risk segment grew 5%. Launch a re-engagement campaign.</p>
                            </div>
                        </div>
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

        const totalCtx = document.getElementById('totalCustomersChart').getContext('2d');
        new Chart(totalCtx, {
            type: 'line', data: {
                labels: {!! json_encode($chartData['total']['labels']) !!},
                datasets: [{
                    label: 'Total Customers', data: {!! json_encode($chartData['total']['data']) !!},
                    backgroundColor: 'rgba(248, 86, 6, 0.1)', borderColor: '#F85606', borderWidth: 2, fill: true, tension: 0.3
                }]
            }, options: { responsive: true }
        });

        const newReturningCtx = document.getElementById('newReturningChart').getContext('2d');
        new Chart(newReturningCtx, {
            type: 'line', data: {
                labels: {!! json_encode($chartData['new_vs_returning']['labels']) !!},
                datasets: [
                    { label: 'New Customers', data: {!! json_encode($chartData['new_vs_returning']['new']) !!}, borderColor: '#1D8A45', backgroundColor: 'rgba(29, 138, 69, 0.1)', fill: true, tension: 0.3 },
                    { label: 'Returning Customers', data: {!! json_encode($chartData['new_vs_returning']['returning']) !!}, borderColor: '#F85606', backgroundColor: 'rgba(248, 86, 6, 0.1)', fill: true, tension: 0.3 }
                ]
            }, options: { responsive: true }
        });
    </script>
@endpush
