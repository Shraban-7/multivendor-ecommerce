@extends('frontend.layouts.app')

@section('title', 'Affiliate Dashboard')

@section('content')
    <main class="affiliate-dashboard my-10">
        <div class="container mx-auto px-4">
            
            <!-- Page Heading -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Affiliate Dashboard</h1>

                <!-- Filter Dropdown -->
                <form method="GET" action="{{ route('affiliator.dashboard') }}">
                    <select name="filter" onchange="this.form.submit()" 
                        class="border-primary rounded-lg px-3 py-2 text-sm focus:ring focus:ring-primary">
                        <option value="week" {{ $filter=='week' ? 'selected' : '' }}>This Week</option>
                        <option value="month" {{ $filter=='month' ? 'selected' : '' }}>This Month</option>
                        <option value="3months" {{ $filter=='3months' ? 'selected' : '' }}>Last 3 Months</option>
                        <option value="6months" {{ $filter=='6months' ? 'selected' : '' }}>Last 6 Months</option>
                        <option value="year" {{ $filter=='year' ? 'selected' : '' }}>Last 1 Year</option>
                    </select>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4 mb-10">
                <div class="bg-white shadow rounded-2xl p-6 text-center">
                    <h2 class="text-gray-500 text-sm mb-2">Total Clicks</h2>
                    <p class="text-2xl font-bold text-purple-600">{{ $clicks }}</p>
                </div>
                <div class="bg-white shadow rounded-2xl p-6 text-center">
                    <h2 class="text-gray-500 text-sm mb-2">Total Earnings</h2>
                    <p class="text-2xl font-bold text-green-600">{{ money($earnings) }}</p>
                </div>
                <div class="bg-white shadow rounded-2xl p-6 text-center">
                    <h2 class="text-gray-500 text-sm mb-2">Pending Earnings</h2>
                    <p class="text-2xl font-bold text-yellow-500">{{ money($pending_earnings) }}</p>
                </div>
                <div class="bg-white shadow rounded-2xl p-6 text-center">
                    <h2 class="text-gray-500 text-sm mb-2">Total Orders</h2>
                    <p class="text-2xl font-bold text-blue-600">{{ $total_orders }}</p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Performance Overview</h2>
                <canvas id="earningsChart" class="w-full h-80"></canvas>
            </div>

        </div>
    </main>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('earningsChart').getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($months),
                    datasets: [
                        {
                            label: "Earnings ({{ currency() }})",
                            data: @json($earnings_data),
                            borderColor: '#16a34a',
                            backgroundColor: 'rgba(22,163,74,0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointBackgroundColor: '#16a34a',
                            pointRadius: 4
                        },
                        {
                            label: "Pending Earnings ({{ currency() }})",
                            data: @json($pending_data),
                            borderColor: '#eab308',
                            backgroundColor: 'rgba(234,179,8,0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointBackgroundColor: '#eab308',
                            pointRadius: 4
                        },
                        {
                            label: 'Clicks',
                            data: @json($clicks_data),
                            borderColor: '#9333ea',
                            backgroundColor: 'rgba(147,51,234,0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointBackgroundColor: '#9333ea',
                            pointRadius: 4
                        },
                        {
                            label: 'Orders',
                            data: @json($orders_data),
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59,130,246,0.1)',
                            tension: 0.4,
                            fill: true,
                            borderWidth: 2,
                            pointBackgroundColor: '#3b82f6',
                            pointRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'top' } },
                    scales: { y: { beginAtZero: true } }
                }
            });
        </script>
    @endpush
@endsection
