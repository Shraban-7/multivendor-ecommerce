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


        $orders = Order::where('seller_id', $sellerId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
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
            'total_orders' => Order::where('seller_id', $sellerId)->count(),
            'pending_orders' => Order::pending()->where('seller_id', $sellerId)->count(),
            'shipped_orders' => Order::shipped()->where('seller_id', $sellerId)->count(),
            'cancelled_orders' => Order::cancelled()->where('seller_id', $sellerId)->count(),
            'delivered_orders' => Order::delivered()->where('seller_id', $sellerId)->count(),
            'total_revenue' => Order::delivered()->where('seller_id', $sellerId)->sum('total'),
            'total_customers' => Order::where('seller_id', $sellerId)->distinct('user_id')->count('user_id'),
            'top_selling_products' => $top_selling_products,
            'latest_orders' => Order::where('seller_id', $sellerId)->latest()->limit(20)->get(),
            'chartData' => $chartData,
        ]);
    }
}
