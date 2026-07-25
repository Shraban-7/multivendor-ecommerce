<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\SellerExpense;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function overview()
    {
        $sellerId = Auth::id();

        $data = Cache::remember("seller_report_overview_{$sellerId}", 900, function () use ($sellerId) {
            $completed = OrderStatus::COMPLETED->value;

            $metrics = Order::where('seller_id', $sellerId)
                ->selectRaw("
                    COUNT(*) as total_orders,
                    COALESCE(AVG(CASE WHEN status = {$completed} THEN payable END), 0) as avg_order_value,
                    COALESCE(SUM(CASE WHEN status = {$completed} THEN payable ELSE 0 END), 0) as total_revenue,
                    COALESCE(SUM(CASE WHEN status = {$completed} THEN total_commission ELSE 0 END), 0) as total_commission
                ")->first();

            $totalProducts = Product::where('seller_id', $sellerId)->count();
            $totalExpenses = SellerExpense::where('seller_id', $sellerId)->sum('amount');

            return [
                'avg_order_value' => (float) ($metrics->avg_order_value ?? 0),
                'total_orders' => (int) ($metrics->total_orders ?? 0),
                'total_revenue' => (float) ($metrics->total_revenue ?? 0),
                'total_commission' => (float) ($metrics->total_commission ?? 0),
                'net_profit' => (float) (($metrics->total_revenue ?? 0) - ($metrics->total_commission ?? 0) - $totalExpenses),
                'total_products' => $totalProducts,
                'total_expenses' => (float) $totalExpenses,
            ];
        });

        return apiResponse($data);
    }

    public function financial(Request $request)
    {
        $sellerId = Auth::id();
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->endOfMonth()->toDateString());

        $completed = OrderStatus::COMPLETED->value;

        $revenue = Order::where('seller_id', $sellerId)
            ->where('status', $completed)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                COALESCE(SUM(payable), 0) as total_revenue,
                COALESCE(SUM(total_commission), 0) as total_commission,
                COALESCE(SUM(shipping_fee), 0) as total_shipping
            ")->first();

        $expenses = SellerExpense::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('COALESCE(SUM(amount), 0) as total')
            ->first();

        $grossProfit = ($revenue->total_revenue ?? 0) - ($revenue->total_commission ?? 0);
        $netProfit = $grossProfit - ($expenses->total ?? 0);

        return apiResponse([
            'period' => ['start' => $startDate, 'end' => $endDate],
            'total_revenue' => (float) ($revenue->total_revenue ?? 0),
            'total_commission' => (float) ($revenue->total_commission ?? 0),
            'total_shipping' => (float) ($revenue->total_shipping ?? 0),
            'total_expenses' => (float) ($expenses->total ?? 0),
            'gross_profit' => (float) $grossProfit,
            'net_profit' => (float) $netProfit,
        ]);
    }

    public function sales(Request $request)
    {
        $sellerId = Auth::id();
        $range = $request->input('range', 'monthly');
        $completed = OrderStatus::COMPLETED->value;

        $groupBy = match ($range) {
            'daily' => DB::raw('DATE(created_at) as period'),
            'weekly' => DB::raw('YEARWEEK(created_at) as period'),
            'yearly' => DB::raw('DATE_FORMAT(created_at, "%Y") as period'),
            default => DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
        };

        $sales = Order::where('seller_id', $sellerId)
            ->where('status', $completed)
            ->select(
                $groupBy,
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('COALESCE(SUM(payable), 0) as revenue'),
                DB::raw('COALESCE(SUM(total_commission), 0) as commission')
            )
            ->groupBy('period')
            ->orderBy('period', 'desc')
            ->take(12)
            ->get();

        $topProducts = OrderItem::whereHas('order', fn ($q) => $q->where('seller_id', $sellerId)->where('status', $completed))
            ->select(
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('COALESCE(SUM(sub_total), 0) as total_revenue')
            )
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get()
            ->load('product:id,name,thumbnail');

        return apiResponse([
            'range' => $range,
            'sales' => $sales,
            'top_products' => $topProducts->map(fn ($i) => [
                'product_id' => $i->product_id,
                'name' => $i->product?->name,
                'thumbnail' => $i->product?->thumbnail,
                'quantity_sold' => (int) $i->total_qty,
                'revenue' => (float) $i->total_revenue,
            ]),
        ]);
    }

    public function customers(Request $request)
    {
        $sellerId = Auth::id();

        $customerData = Order::where('seller_id', $sellerId)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('COALESCE(SUM(payable), 0) as total_spent'),
                DB::raw('MIN(created_at) as first_order'),
                DB::raw('MAX(created_at) as last_order')
            )
            ->groupBy('user_id')
            ->orderByDesc('total_spent')
            ->paginate(25);

        $customerData->getCollection()->transform(function ($item) {
            $user = \App\Domain\Auth\Models\User::find($item->user_id);
            return [
                'user_id' => $item->user_id,
                'name' => $user?->name,
                'phone' => $user?->phone,
                'email' => $user?->email,
                'order_count' => (int) $item->order_count,
                'total_spent' => (float) $item->total_spent,
                'first_order' => $item->first_order,
                'last_order' => $item->last_order,
            ];
        });

        return apiResourceResponse($customerData);
    }
}
