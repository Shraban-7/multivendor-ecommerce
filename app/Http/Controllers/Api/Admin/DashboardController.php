<?php

namespace App\Http\Controllers\Api\Admin;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = Cache::remember('admin_api_dashboard', 300, function () {
            $completed = OrderStatus::COMPLETED->value;
            $pending = OrderStatus::PENDING->value;
            $shipped = OrderStatus::SHIPPED->value;
            $cancelled = OrderStatus::CANCELLED->value;
            $delivered = OrderStatus::DELIVERED->value;

            $counts = Order::selectRaw("
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = {$pending} THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = {$shipped} THEN 1 ELSE 0 END) as shipped_count,
                    SUM(CASE WHEN status = {$cancelled} THEN 1 ELSE 0 END) as cancelled_count,
                    SUM(CASE WHEN status = {$delivered} THEN 1 ELSE 0 END) as delivered_count,
                    SUM(CASE WHEN status = {$completed} THEN payable ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN status = {$delivered} THEN payable ELSE 0 END) as total_sales,
                    SUM(total_commission) as total_commission
                ")->first();

            $stats = [
                'total_revenue' => (float) ($counts->total_revenue ?? 0),
                'total_orders' => (int) ($counts->total_orders ?? 0),
                'pending_orders' => (int) ($counts->pending_count ?? 0),
                'shipped_orders' => (int) ($counts->shipped_count ?? 0),
                'cancelled_orders' => (int) ($counts->cancelled_count ?? 0),
                'delivered_orders' => (int) ($counts->delivered_count ?? 0),
                'total_sales' => (float) ($counts->total_sales ?? 0),
                'total_commission' => (float) ($counts->total_commission ?? 0),
                'total_vendors' => Seller::where('status', Seller::ACTIVE)->count(),
                'total_customers' => User::count(),
                'total_products' => Product::count(),
                'pending_sellers' => Seller::where('status', Seller::PENDING)->count(),
            ];

            $recentOrders = Order::with(['user', 'billing_address', 'items.product'])
                ->latest()
                ->take(10)
                ->get()
                ->map(fn ($o) => [
                    'id' => $o->id,
                    'invoice_id' => $o->invoice_id,
                    'customer' => $o->user?->name,
                    'total' => (float) $o->total,
                    'status' => $o->status_label,
                    'created_at' => $o->created_at,
                ]);

            $monthlyRevenue = Order::where('status', $completed)
                ->where('created_at', '>=', now()->subMonths(6))
                ->select(
                    DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
                    DB::raw('COALESCE(SUM(payable), 0) as revenue')
                )
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            $topVendors = Seller::withCount(['orders' => fn ($q) => $q->where('status', $completed)])
                ->orderBy('orders_count', 'desc')
                ->take(5)
                ->get(['id', 'name', 'business_name', 'total_sold', 'total_followers', 'rating']);

            return compact('stats', 'recentOrders', 'monthlyRevenue', 'topVendors');
        });

        return apiResponse($data);
    }
}
