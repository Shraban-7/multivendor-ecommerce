<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = Cache::remember('admin_dashboard', 300, function () {
            $completed = OrderStatus::COMPLETED->value;
            $pending = OrderStatus::PENDING->value;
            $shipped = OrderStatus::SHIPPED->value;
            $cancelled = OrderStatus::CANCELLED->value;
            $delivered = OrderStatus::DELIVERED->value;

            $counts = Order::selectRaw("
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = {$pending} THEN 1 ELSE 0 END) as pending_orders_count,
                    SUM(CASE WHEN status = {$shipped} THEN 1 ELSE 0 END) as shipped_orders_count,
                    SUM(CASE WHEN status = {$cancelled} THEN 1 ELSE 0 END) as cancelled_orders_count,
                    SUM(CASE WHEN status = {$delivered} THEN 1 ELSE 0 END) as delivered_orders_count,
                    SUM(CASE WHEN status = {$completed} THEN payable ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN status = {$delivered} THEN payable ELSE 0 END) as total_sales,
                    SUM(total_commission) as total_commission
                ")->first();

            $stats = [
                'total_revenue' => $counts->total_revenue,
                'total_orders' => $counts->total_orders,
                'total_vendors' => Seller::where('status', Seller::ACTIVE)->count(),
                'total_customers' => User::count(),
                'total_products' => Product::count(),
                'pending_orders' => $counts->pending_orders_count,
            ];

            $data = [
                'total_products' => $stats['total_products'],
                'total_orders' => $counts->total_orders,
                'pending_orders' => $counts->pending_orders_count,
                'shipped_orders' => $counts->shipped_orders_count,
                'cancelled_orders' => $counts->cancelled_orders_count,
                'delivered_orders' => $counts->delivered_orders_count,
                'total_sales' => $counts->total_sales,
                'total_sellers' => Seller::count(),
                'total_customers' => Order::distinct('user_id')->count('user_id'),
                'total_commission' => $counts->total_commission,
            ];

            $recent_orders = Order::with(['user', 'seller', 'billing_address', 'items'])
                ->latest()
                ->take(5)
                ->get();

            $top_vendors = Seller::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->take(5)
                ->get();

            $monthly_revenue = Order::where('status', $completed)
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                    DB::raw('SUM(payable) as revenue')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $pending_sellers = Seller::where('status', Seller::PENDING)->get();
            $pending_sellers_count = $pending_sellers->count();

            return compact('stats', 'recent_orders', 'top_vendors', 'monthly_revenue', 'pending_sellers', 'pending_sellers_count', 'data');
        });

        return view('admin.dashboard', $data);
    }
}
