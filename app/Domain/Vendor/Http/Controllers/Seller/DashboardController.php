<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerExpense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $dateRange = $request->input('date_range', 'daily');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $cacheKey = "seller_dashboard:{$seller->id}:{$startDate}:{$endDate}";

        $data = Cache::remember($cacheKey, 300, function () use ($seller, $startDate, $endDate) {
            $start = $startDate.' 00:00:00';
            $end = $endDate.' 23:59:59';

            $ordersQuery = Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$start, $end]);

            $deliveredOrdersQuery = (clone $ordersQuery)->delivered();

            $orders = Order::selectRaw('DATE(orders.created_at) as label, COUNT(orders.id) as order_count, SUM(orders.payable) as sale, SUM(order_items.buying_price) as buying_price')
                ->join('order_items', 'orders.id', '=', 'order_items.order_id')
                ->where('orders.seller_id', $seller->id)
                ->whereBetween('orders.created_at', [$start, $end])
                ->groupBy('label')
                ->orderBy('label')
                ->get();

            $orderIds = Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$start, $end])
                ->pluck('id');

            $orderItemProductIds = OrderItem::whereIn('order_id', $orderIds)->pluck('product_id');
            $TotalBuyingPrice = OrderItem::whereIn('order_id', $orderIds)->sum('buying_price');

            $profit = (clone $ordersQuery)->sum('seller_earnings') - $TotalBuyingPrice;

            $chartData = [
                'labels' => $orders->pluck('label'),
                'orders' => $orders->pluck('order_count'),
                'sales' => $orders->pluck('sale'),
                'profits' => $orders->map(fn ($order) => $order->sale - $order->buying_price),
            ];

            $top_selling_products = Product::where('seller_id', $seller->id)
                ->whereIn('id', $orderItemProductIds)
                ->withCount(['orderItems as sales_count' => function ($query) use ($orderIds) {
                    $query->whereIn('order_id', $orderIds);
                }])
                ->orderByDesc('sales_count')
                ->limit(5)
                ->get();

            $total_commission = Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$start, $end])
                ->sum('total_commission');

            $total_expense = SellerExpense::where('seller_id', $seller->id)
                ->whereBetween('expense_date', [$start, $end])
                ->sum('amount');

            $total_stock_product_amount = DB::table('product_variants')
                ->join('products', 'product_variants.product_id', '=', 'products.id')
                ->where('products.seller_id', $seller->id)
                ->selectRaw('SUM(GREATEST(COALESCE(product_variants.stock_in, 0) - COALESCE(product_variants.stock_out, 0), 0) * product_variants.selling_price) as total')
                ->value('total');

            $statusCounts = (clone $ordersQuery)
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as shipped, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as cancelled, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as delivered', [
                    OrderStatus::PENDING->value,
                    OrderStatus::SHIPPED->value,
                    OrderStatus::CANCELLED->value,
                    OrderStatus::COMPLETED->value,
                ])
                ->first();

            $total_sales = (clone $ordersQuery)->sum('seller_earnings');
            $total_customers = (clone $ordersQuery)->distinct('user_id')->count('user_id');
            $total_products = Product::where('seller_id', $seller->id)->count();

            $latest_orders = (clone $ordersQuery)
                ->with(['user', 'billing_address', 'items'])
                ->latest()
                ->limit(20)
                ->get();

            return compact(
                'total_products', 'statusCounts', 'total_sales', 'profit',
                'total_customers', 'top_selling_products', 'latest_orders',
                'chartData', 'total_commission', 'total_stock_product_amount',
                'total_expense',
            );
        });

        return view('seller.dashboard', array_merge($data, [
            'total_orders' => $data['statusCounts']->total,
            'pending_orders' => $data['statusCounts']->pending,
            'shipped_orders' => $data['statusCounts']->shipped,
            'cancelled_orders' => $data['statusCounts']->cancelled,
            'delivered_orders' => $data['statusCounts']->delivered,
            'seller' => $seller,
        ]));
    }
}
