<?php
namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $sellerId = seller()->id;

        $dateRange = $request->input('date_range', 'daily');
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', now()->toDateString());

        $ordersQuery = Order::where('seller_id', $sellerId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $deliveredOrdersQuery = (clone $ordersQuery)->delivered();

        $orders = Order::selectRaw('DATE(orders.created_at) as label, COUNT(orders.id) as order_count, SUM(orders.payable) as sale, SUM(order_items.buying_price) as buying_price')
            ->join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.seller_id', $sellerId)
            ->where('orders.status', OrderStatus::DELIVERED->value)
            ->whereDate('orders.created_at', '>=', $startDate)
            ->whereDate('orders.created_at', '<=', $endDate)
            ->groupBy('label')
            ->orderBy('label')
            ->get();

        $orderIds = Order::where('seller_id', $sellerId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->delivered()
            ->pluck('id');

        $orderItemProductIds = OrderItem::whereIn('order_id', $orderIds)->pluck('product_id');
        $TotalBuyingPrice = OrderItem::whereIn('order_id', $orderIds)->sum('buying_price');

        $profit = (clone $ordersQuery)->delivered()->sum('payable') - $TotalBuyingPrice;

        $chartData = [
            'labels'  => $orders->pluck('label'),
            'orders'  => $orders->pluck('order_count'),
            'sales'   => $orders->pluck('sale'),
            'profits' => $orders->map(fn($order) => $order->sale - $order->buying_price),
        ];

        $top_selling_products = Product::where('seller_id', $sellerId)
            ->whereIn('id', $orderItemProductIds)
            ->withCount(['orderItems as sales_count' => function ($query) use ($orderIds) {
                $query->whereIn('order_id', $orderIds);
            }])
            ->orderByDesc('sales_count')
            ->limit(5)
            ->get();

        $total_commission = Order::where('seller_id', $sellerId)
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->sum('total_commission');

        return view('seller.dashboard', [
            'total_products'       => Product::where('seller_id', $sellerId)->count(),
            'total_orders'         => (clone $ordersQuery)->count(),
            'pending_orders'       => (clone $ordersQuery)->pending()->count(),
            'shipped_orders'       => (clone $ordersQuery)->shipped()->count(),
            'cancelled_orders'     => (clone $ordersQuery)->cancelled()->count(),
            'delivered_orders'     => (clone $ordersQuery)->delivered()->count(),
            'total_sales'          => (clone $ordersQuery)->delivered()->sum('payable'),
            'profit'               => $profit,
            'total_customers'      => (clone $ordersQuery)->distinct('user_id')->count('user_id'),
            'top_selling_products' => $top_selling_products,
            'latest_orders'        => (clone $ordersQuery)->latest()->limit(20)->get(),
            'chartData'            => $chartData,
            'total_commission'     => $total_commission,
        ]);
    }
}
