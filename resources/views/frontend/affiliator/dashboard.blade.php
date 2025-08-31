@extends('frontend.layouts.app')

@section('title', 'Affiliate Dashboard')

@section('content')
    <main class="affiliate-dashboard py-10 bg-gray-50">
        <div class="container mx-auto px-4">
            
            <!-- Page Heading -->
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Affiliate Dashboard</h1>

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

            <!-- Earnings Chart -->
            <div class="bg-white shadow rounded-2xl p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Earnings Overview</h2>
                <canvas id="earningsChart" class="w-full h-64"></canvas>
            </div>

        </div>
    </main>

    @push('scripts')
        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const ctx = document.getElementById('earningsChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                    datasets: [{
                        label: 'Earnings ($)',
                        data: [200, 400, 300, 600, 500, 800, 750],
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22,163,74,0.1)',
                        tension: 0.4,
                        fill: true,
                        borderWidth: 2,
                        pointBackgroundColor: '#16a34a',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: true, position: 'top' },
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 200 }
                        }
                    }
                }
            });
        </script>
    @endpush
@endsection
