<?php

namespace App\Http\Controllers\Admin;

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

            $top_vendors = Seller::select('sellers.*')
                ->selectSub(function ($query) {
                    $query->from('orders')
                        ->whereColumn('orders.seller_id', 'sellers.id')
                        ->whereIn('orders.status', [
                            OrderStatus::COMPLETED->value,
                            OrderStatus::DELIVERED->value,
                        ])
                        ->select(DB::raw('COALESCE(SUM(orders.payable), 0)'));
                }, 'total_sales')
                ->withCount(['orders' => function ($q) {
                    $q->whereIn('status', [
                        OrderStatus::COMPLETED->value,
                        OrderStatus::DELIVERED->value,
                    ]);
                }])
                ->orderBy('total_sales', 'desc')
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

            // Previous month revenue for growth comparison
            $last_month_revenue = Order::where('status', $completed)
                ->where('created_at', '>=', now()->subMonths(2)->startOfMonth())
                ->where('created_at', '<', now()->subMonth()->startOfMonth())
                ->sum('payable');
            $this_month_revenue = Order::where('status', $completed)
                ->where('created_at', '>=', now()->startOfMonth())
                ->sum('payable');
            $revenue_growth = $last_month_revenue > 0
                ? round(($this_month_revenue - $last_month_revenue) / $last_month_revenue * 100, 1)
                : ($this_month_revenue > 0 ? 100 : 0);

            $pending_sellers = Seller::where('status', Seller::PENDING)->get();
            $pending_sellers_count = $pending_sellers->count();

            // Order statuses with display metadata
            $order_status_distribution = [
                'pending' => ['count' => $counts->pending_orders_count, 'label' => 'Pending', 'icon' => 'clock', 'color' => 'text-feedback-warning', 'bg' => 'bg-amber-50'],
                'shipped' => ['count' => $counts->shipped_orders_count, 'label' => 'Shipped', 'icon' => 'truck', 'color' => 'text-feedback-info', 'bg' => 'bg-blue-50'],
                'delivered' => ['count' => $counts->delivered_orders_count, 'label' => 'Delivered', 'icon' => 'check-circle', 'color' => 'text-feedback-success', 'bg' => 'bg-emerald-50'],
                'cancelled' => ['count' => $counts->cancelled_orders_count, 'label' => 'Cancelled', 'icon' => 'x-circle', 'color' => 'text-rose-500', 'bg' => 'bg-rose-50'],
            ];

            // Complete status style map for badge rendering (all possible statuses)
            $status_styles = collect(OrderStatus::cases())->mapWithKeys(function ($status) {
                $label = $status->label();
                $style = match ($label) {
                    'completed' => ['bg' => 'bg-emerald-50', 'text' => 'text-feedback-success'],
                    'delivered' => ['bg' => 'bg-emerald-50', 'text' => 'text-feedback-success'],
                    'shipped' => ['bg' => 'bg-blue-50', 'text' => 'text-feedback-info'],
                    'accepted' => ['bg' => 'bg-blue-50', 'text' => 'text-feedback-info'],
                    'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-feedback-warning'],
                    'cancelled' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-500'],
                    'returned' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                    'return_requested' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                    'return_approved' => ['bg' => 'bg-orange-50', 'text' => 'text-orange-600'],
                    'refunded' => ['bg' => 'bg-purple-50', 'text' => 'text-purple-600'],
                    default => ['bg' => 'bg-surface-muted', 'text' => 'text-ink-tertiary'],
                };
                return [$label => $style];
            });

            return compact('stats', 'recent_orders', 'top_vendors', 'monthly_revenue', 'pending_sellers', 'pending_sellers_count', 'data', 'revenue_growth', 'order_status_distribution', 'status_styles');
        });

        return view('admin.dashboard', $data);
    }
}
