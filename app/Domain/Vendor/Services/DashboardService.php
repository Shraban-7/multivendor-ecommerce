<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerExpense;
use App\Domain\Vendor\Models\SellerPayout;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function dashboard(int $sellerId, string $startDate, string $endDate): array
    {
        $cacheKey = "dashboard:{$sellerId}:{$startDate}:{$endDate}";

        return Cache::remember($cacheKey, 300, function () use ($sellerId, $startDate, $endDate) {
            $start = $startDate . ' 00:00:00';
            $end = $endDate . ' 23:59:59';

            return [
                'overview' => $this->overview($sellerId, $start, $end),
                'chartData' => $this->chartData($sellerId, $start, $end),
                'topProducts' => $this->topProducts($sellerId, $start, $end),
                'latestOrders' => $this->latestOrders($sellerId, $start, $end),
                'lowStockProducts' => $this->lowStockProducts($sellerId),
                'pendingPayout' => $this->pendingPayout($sellerId),
                'orderStatusDistribution' => $this->orderStatusDistribution($sellerId, $start, $end),
            ];
        });
    }

    public function overview(int $sellerId, string $start, string $end): array
    {
        $aggregates = Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('
                COUNT(*) as total_orders,
                SUM(seller_earnings) as total_sales,
                SUM(total_commission) as total_commission,
                COUNT(DISTINCT user_id) as total_customers,
                SUM(CASE WHEN status = 0 THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 2 THEN 1 ELSE 0 END) as shipped_orders,
                SUM(CASE WHEN status = 3 THEN 1 ELSE 0 END) as delivered_orders,
                SUM(CASE WHEN status = 5 THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(CASE WHEN status IN (8, 9) THEN 1 ELSE 0 END) as refunded_orders,
                COALESCE(SUM(CASE WHEN status IN (8, 9) THEN seller_earnings ELSE 0 END), 0) as refund_amount
            ')
            ->first();

        $orderIds = Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->pluck('id');

        $costPriceSum = OrderItem::whereIn('order_id', $orderIds)->sum('cost_price');

        $totalExpense = SellerExpense::where('seller_id', $sellerId)
            ->whereBetween('expense_date', [$start, $end])
            ->sum('amount');

        $totalProducts = Product::where('seller_id', $sellerId)->count();

        $stockValue = DB::table('product_variants')
            ->join('products', 'product_variants.product_id', '=', 'products.id')
            ->where('products.seller_id', $sellerId)
            ->selectRaw('COALESCE(SUM(GREATEST(COALESCE(product_variants.stock_in, 0) - COALESCE(product_variants.stock_out, 0), 0) * product_variants.price), 0) as total')
            ->value('total');

        $profit = $aggregates->total_sales - $costPriceSum;
        $averageOrderValue = $aggregates->total_orders > 0
            ? round($aggregates->total_sales / $aggregates->total_orders, 2)
            : 0;

        return [
            'total_orders' => (int) $aggregates->total_orders,
            'total_sales' => (float) $aggregates->total_sales,
            'total_commission' => (float) $aggregates->total_commission,
            'total_customers' => (int) $aggregates->total_customers,
            'pending_orders' => (int) $aggregates->pending_orders,
            'shipped_orders' => (int) $aggregates->shipped_orders,
            'delivered_orders' => (int) $aggregates->delivered_orders,
            'cancelled_orders' => (int) $aggregates->cancelled_orders,
            'refunded_orders' => (int) $aggregates->refunded_orders,
            'refund_amount' => (float) $aggregates->refund_amount,
            'total_expense' => $totalExpense,
            'total_stock_value' => $stockValue,
            'total_products' => $totalProducts,
            'profit' => $profit,
            'average_order_value' => $averageOrderValue,
            'cost_price_sum' => $costPriceSum,
        ];
    }

    public function chartData(int $sellerId, string $start, string $end): array
    {
        $daily = Order::selectRaw('DATE(orders.created_at) as label')
            ->selectRaw('COUNT(orders.id) as order_count')
            ->selectRaw('COALESCE(SUM(orders.seller_earnings), 0) as sale')
            ->selectRaw('COALESCE(SUM(order_items.cost_price), 0) as cost_price')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.seller_id', $sellerId)
            ->whereBetween('orders.created_at', [$start, $end])
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        return [
            'labels' => $daily->pluck('label'),
            'orders' => $daily->pluck('order_count'),
            'sales' => $daily->pluck('sale'),
            'profits' => $daily->map(fn ($d) => (float) $d->sale - (float) $d->cost_price),
        ];
    }

    public function topProducts(int $sellerId, string $start, string $end, int $limit = 5): Collection
    {
        $orderIds = Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->pluck('id');

        if ($orderIds->isEmpty()) {
            return collect();
        }

        return Product::where('seller_id', $sellerId)
            ->whereIn('id', function ($query) use ($orderIds) {
                $query->select('product_id')
                    ->from('order_items')
                    ->whereIn('order_id', $orderIds);
            })
            ->withCount(['orderItems as sales_count' => function ($query) use ($orderIds) {
                $query->whereIn('order_id', $orderIds);
            }])
            ->orderByDesc('sales_count')
            ->limit($limit)
            ->get();
    }

    public function latestOrders(int $sellerId, string $start, string $end, int $limit = 20): Collection
    {
        return Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->with(['user', 'billing_address', 'items'])
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function lowStockProducts(int $sellerId, int $threshold = 10): Collection
    {
        return Product::where('seller_id', $sellerId)
            ->whereRaw('(COALESCE(stock_in, 0) - COALESCE(stock_out, 0)) <= low_stock_quantity')
            ->where('low_stock_quantity', '>', 0)
            ->select('id', 'name', 'thumbnail', 'stock_in', 'stock_out', 'low_stock_quantity')
            ->limit(10)
            ->get()
            ->map(function ($product) {
                $product->available_stock = (int) $product->stock_in - (int) $product->stock_out;
                return $product;
            });
    }

    public function pendingPayout(int $sellerId): float
    {
        return (float) SellerPayout::where('seller_id', $sellerId)
            ->whereIn('status', [SellerPayout::STATUS_PENDING, SellerPayout::STATUS_PROCESSING])
            ->sum('amount');
    }

    public function orderStatusDistribution(int $sellerId, string $start, string $end): array
    {
        $statuses = Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return $statuses;
    }

    public function clearCache(int $sellerId): void
    {
        $today = now()->toDateString();
        $monthStart = now()->startOfMonth()->toDateString();
        Cache::forget("dashboard:{$sellerId}:{$monthStart}:{$today}");
    }
}
