<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerExpense;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $sellerId = Auth::id();

        $data = Cache::remember("seller_dashboard_{$sellerId}", 300, function () use ($sellerId) {
            $pending = OrderStatus::PENDING->value;
            $shipped = OrderStatus::SHIPPED->value;
            $cancelled = OrderStatus::CANCELLED->value;
            $delivered = OrderStatus::DELIVERED->value;
            $completed = OrderStatus::COMPLETED->value;

            $counts = Order::where('seller_id', $sellerId)
                ->selectRaw("
                    COUNT(*) as total_orders,
                    SUM(CASE WHEN status = {$pending} THEN 1 ELSE 0 END) as pending_count,
                    SUM(CASE WHEN status = {$shipped} THEN 1 ELSE 0 END) as shipped_count,
                    SUM(CASE WHEN status = {$cancelled} THEN 1 ELSE 0 END) as cancelled_count,
                    SUM(CASE WHEN status = {$delivered} THEN 1 ELSE 0 END) as delivered_count,
                    SUM(CASE WHEN status IN ({$delivered},{$completed}) THEN payable ELSE 0 END) as total_revenue,
                    SUM(CASE WHEN status IN ({$delivered},{$completed}) THEN total_commission ELSE 0 END) as total_commission
                ")->first();

            $totalProducts = Product::where('seller_id', $sellerId)->count();
            $totalExpenses = SellerExpense::where('seller_id', $sellerId)->sum('amount');

            $seller = Seller::withCount(['orders', 'products'])->find($sellerId);

            $recentOrders = Order::where('seller_id', $sellerId)
                ->with(['user', 'billing_address', 'items.product'])
                ->latest()
                ->take(10)
                ->get();

            $chartData = Order::where('seller_id', $sellerId)
                ->where('status', $completed)
                ->where('created_at', '>=', now()->subDays(30))
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(payable) as revenue'),
                    DB::raw('COUNT(*) as orders_count')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            return [
                'stats' => [
                    'total_orders' => (int) ($counts->total_orders ?? 0),
                    'pending_orders' => (int) ($counts->pending_count ?? 0),
                    'shipped_orders' => (int) ($counts->shipped_count ?? 0),
                    'cancelled_orders' => (int) ($counts->cancelled_count ?? 0),
                    'delivered_orders' => (int) ($counts->delivered_count ?? 0),
                    'total_revenue' => (float) ($counts->total_revenue ?? 0),
                    'total_commission' => (float) ($counts->total_commission ?? 0),
                    'total_products' => $totalProducts,
                    'total_expenses' => (float) $totalExpenses,
                    'total_sold' => $seller->total_sold ?? 0,
                    'total_followers' => $seller->total_followers ?? 0,
                    'rating' => (float) ($seller->rating ?? 0),
                ],
                'recent_orders' => $recentOrders->map(fn ($o) => [
                    'id' => $o->id,
                    'invoice_id' => $o->invoice_id,
                    'customer_name' => $o->billing_address?->customer_name ?? $o->user?->name,
                    'total' => (float) $o->total,
                    'status' => $o->status,
                    'created_at' => $o->created_at,
                ]),
                'chart_data' => $chartData,
            ];
        });

        return apiResponse($data);
    }
}
