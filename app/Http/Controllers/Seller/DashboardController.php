<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\OrderItem;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $sellerId = seller()->id;

        $dateRange = $request->input('date_range', 'daily');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $ordersQuery = Order::where('seller_id', $sellerId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $orders = (clone $ordersQuery)
            ->selectRaw('DATE(created_at) as label, COUNT(*) as order_count, SUM(total) as revenue')
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $chartData = [
            'labels' => $orders->pluck('label'),
            'orders' => $orders->pluck('order_count'),
            'sales' => $orders->pluck('revenue'),
        ];

        $orderIds = Order::where('seller_id', $sellerId)->pluck('id');

        $orderItemProductIds = OrderItem::whereIn('order_id', $orderIds)->pluck('product_id');

        $top_selling_products = Product::where('seller_id', $sellerId)
            ->whereIn('id', $orderItemProductIds)
            ->withCount(['orderItems as sales_count' => function ($query) use ($orderIds) {
                $query->whereIn('order_id', $orderIds);
            }])
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        return view('seller.dashboard', [
            'total_products' => Product::where('seller_id', $sellerId)->count(),
            'total_orders' => (clone $ordersQuery)->count(),
            'pending_orders' => (clone $ordersQuery)->pending()->count(),
            'shipped_orders' => (clone $ordersQuery)->shipped()->count(),
            'cancelled_orders' => (clone $ordersQuery)->cancelled()->count(),
            'delivered_orders' => (clone $ordersQuery)->delivered()->count(),
            'total_revenue' => (clone $ordersQuery)->delivered()->sum('total'),
            'total_customers' => (clone $ordersQuery)->distinct('user_id')->count('user_id'),
            'top_selling_products' => $top_selling_products,
            'latest_orders' => (clone $ordersQuery)->latest()->limit(20)->get(),
            'chartData' => $chartData,
        ]);
    }
}
