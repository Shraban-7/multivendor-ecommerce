<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\District;
use App\Models\Division;
use Carbon\CarbonPeriod;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\SellerExpense;
use Illuminate\Support\Facades\DB;
use App\Models\OrderBillingAddress;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    public function financial(Request $request)
    {
        $seller = Seller::find(get_seller_id());
        $filter = $request->get('filter', 'monthly');

        // Define current, last, next periods
        switch ($filter) {
            case 'weekly':
                $currentStart = now()->startOfWeek();
                $currentEnd = now()->endOfWeek();
                $lastStart = now()->subWeek()->startOfWeek();
                $lastEnd = now()->subWeek()->endOfWeek();
                $nextStart = now()->addWeek()->startOfWeek();
                $nextEnd = now()->addWeek()->endOfWeek();
                break;

            case 'monthly':
                $currentStart = now()->startOfMonth();
                $currentEnd = now()->endOfMonth();
                $lastStart = now()->subMonth()->startOfMonth();
                $lastEnd = now()->subMonth()->endOfMonth();
                $nextStart = now()->addMonth()->startOfMonth();
                $nextEnd = now()->addMonth()->endOfMonth();
                break;

            case 'yearly':
                $currentStart = now()->startOfYear();
                $currentEnd = now()->endOfYear();
                $lastStart = now()->subYear()->startOfYear();
                $lastEnd = now()->subYear()->endOfYear();
                $nextStart = now()->addYear()->startOfYear();
                $nextEnd = now()->addYear()->endOfYear();
                break;

            default:
                $currentStart = now()->startOfMonth();
                $currentEnd = now()->endOfMonth();
                $lastStart = now()->subMonth()->startOfMonth();
                $lastEnd = now()->subMonth()->endOfMonth();
                $nextStart = now()->addMonth()->startOfMonth();
                $nextEnd = now()->addMonth()->endOfMonth();
        }

        // Helper to calculate metrics
        $calculateMetrics = function ($start, $end) use ($seller) {
            $orders = Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$start, $end])
                ->get();

            $total_revenue = $orders->sum('seller_earnings');

            $total_product_cost = OrderItem::whereHas('order', function ($q) use ($seller, $start, $end) {
                $q->where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$start, $end]);
            })->sum(DB::raw('buying_price * quantity'));

            $total_selling_price = OrderItem::whereHas('order', function ($q) use ($seller, $start, $end) {
                $q->where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$start, $end]);
            })->sum(DB::raw('unit_price * quantity'));

            $gross_profit = $total_selling_price - $total_product_cost;

            $total_expense = SellerExpense::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount');

            $net_profit = $gross_profit - $total_expense;

            $profit_margin = $total_revenue > 0 ? ($net_profit / $total_revenue) * 100 : 0;

            return [
                'total_revenue' => $total_revenue,
                'total_product_cost' => $total_product_cost,
                'gross_profit' => $gross_profit,
                'total_expense' => $total_expense,
                'net_profit' => $net_profit,
                'profit_margin' => $profit_margin,
            ];
        };

        // Current, last, next metrics
        $currentMetrics = $calculateMetrics($currentStart, $currentEnd);
        $lastMetrics = $calculateMetrics($lastStart, $lastEnd);
        $nextMetrics = $calculateMetrics($nextStart, $nextEnd);

        // Change percentages
        $calculateChange = fn($current, $last) => $last > 0 ? (($current - $last) / $last) * 100 : 100;

        $changes = [
            'revenue' => $calculateChange($currentMetrics['total_revenue'], $lastMetrics['total_revenue']),
            'gross_profit' => $calculateChange($currentMetrics['gross_profit'], $lastMetrics['gross_profit']),
            'net_profit' => $calculateChange($currentMetrics['net_profit'], $lastMetrics['net_profit']),
            'profit_margin' => $currentMetrics['profit_margin'] - $lastMetrics['profit_margin'],
            'expense' => $calculateChange($currentMetrics['total_expense'], $lastMetrics['total_expense']),
        ];

        // Inventory value
        $inventory_value = Product::where('seller_id', $seller->id)
            ->sum(DB::raw('buying_price * (stock_in - stock_out)'));

        // Low turnover warning (example: products not sold in last 90 days)
        $lowTurnoverDays = 90;
        $cutoffDate = now()->subDays($lowTurnoverDays);

        // Get product IDs sold in the last X days
        $soldProductIds = OrderItem::whereHas('order', function ($q) use ($seller, $cutoffDate) {
            $q->where('seller_id', $seller->id)
                ->where('created_at', '>=', $cutoffDate);
        })->pluck('product_id')->unique();

        // Count products not sold in last X days
        $lowTurnoverCount = Product::where('seller_id', $seller->id)
            ->whereNotIn('id', $soldProductIds)
            ->count();

        // Inventory grouped by category
        $inventoryByCategory = Product::where('seller_id', $seller->id)
            ->select('category_id', DB::raw('COUNT(*) as sku_count'), DB::raw('SUM(buying_price * (stock_in - stock_out)) as stock_value'))
            ->groupBy('category_id')
            ->with('category') // assuming Product has relation: category()
            ->get();

        // Total stock value for percentage calculation
        $totalStockValue = $inventoryByCategory->sum('stock_value');

        // Prepare 12-month trend for chart
        $monthlyTrend = collect();
        for ($i = 11; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();
            $metrics = $calculateMetrics($start, $end);
            $monthlyTrend->push([
                'month' => $start->format('M Y'),
                'net_profit' => $metrics['net_profit'],
                'gross_profit' => $metrics['gross_profit'],
                'total_revenue' => $metrics['total_revenue'],
            ]);
        }

        // --- Income Sources Data (dynamic) ---
        $incomeSources = [
            'Product Sales' => Order::where('seller_id', $seller->id)->sum('seller_earnings'),
            'POS Sales' => Order::where('seller_id', $seller->id)->where('user_id', null)->sum('total'),
        ];

        $totalIncome = array_sum($incomeSources);

        $incomeData = collect($incomeSources)->map(function ($amount, $source) use ($totalIncome) {
            $percentage = $totalIncome > 0 ? ($amount / $totalIncome) * 100 : 0;
            $status = match ($source) {
                'Product Sales' => 'Primary Source',
                'Service Fees' => 'Stable Stream',
                'POS Sales' => 'New Stream',
                default => 'Other',
            };
            $badgeClass = match ($source) {
                'Product Sales' => 'bg-primary',
                'Service Fees' => 'bg-secondary',
                'POS Sales' => 'bg-info',
                default => 'bg-dark',
            };
            return [
                'source' => $source,
                'amount' => $amount,
                'percentage' => $percentage,
                'status' => $status,
                'badgeClass' => $badgeClass,
            ];
        });

        // Total Expense for current period
        $totalExpense = SellerExpense::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('amount');

        // Expense categories with total
        $expenseCategories = SellerExpense::where('seller_id', $seller->id)
            ->select('seller_expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->groupBy('seller_expense_category_id')
            ->with('category') // make sure relation exists in model
            ->get();

        // Highest expense category
        $highestExpense = $expenseCategories->sortByDesc('total')->first();

        // Last period range (already calculated in controller)
        $lastStart = $lastStart ?? now()->subMonth()->startOfMonth();
        $lastEnd = $lastEnd ?? now()->subMonth()->endOfMonth();

        // Expense growth %
        $lastTotalExpense = SellerExpense::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$lastStart, $lastEnd])
            ->sum('amount');

        $expenseGrowth = $lastTotalExpense > 0
            ? (($totalExpense - $lastTotalExpense) / $lastTotalExpense) * 100
            : 100;

        // Expense trend for chart
        $expenseTrend = collect();
        for ($i = 11; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();

            $monthlyAmount = SellerExpense::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('amount');

            $expenseTrend->push([
                'month' => $monthStart->format('M Y'),
                'amount' => $monthlyAmount,
            ]);
        }


        return view('seller.reports.financial', compact(
            'currentMetrics',
            'lastMetrics',
            'nextMetrics',
            'changes',
            'inventory_value',
            'monthlyTrend',
            'incomeData',
            'filter',
            'totalExpense',
            'expenseCategories',
            'highestExpense',
            'expenseGrowth',
            'expenseTrend',
            'lastStart',
            'lastEnd',
            'lowTurnoverDays',
            'lowTurnoverCount',
            'inventoryByCategory',
            'totalStockValue'
        ));
    }

    public function sales()
    {
        $seller = Seller::find(get_seller_id());
        $range = request('range', 'monthly');

        $ordersQuery = Order::where('seller_id', $seller->id);
        $refundOrdersQuery = Order::where('seller_id', $seller->id)->where('status', 'refunded');

        switch ($range) {
            case 'daily':
                $from = now()->startOfDay();
                $to = now()->endOfDay();
                $prevFrom = now()->subDay()->startOfDay();
                $prevTo = now()->subDay()->endOfDay();
                break;
            case 'weekly':
                $from = now()->startOfWeek()->startOfDay();
                $to = now()->endOfWeek()->endOfDay();
                $prevFrom = now()->subWeek()->startOfWeek()->startOfDay();
                $prevTo = now()->subWeek()->endOfWeek()->endOfDay();
                break;
            case 'monthly':
                $from = now()->startOfMonth()->startOfDay();
                $to = now()->endOfMonth()->endOfDay();
                $prevFrom = now()->subMonth()->startOfMonth()->startOfDay();
                $prevTo = now()->subMonth()->endOfMonth()->endOfDay();
                break;
            case 'yearly':
                $from = now()->startOfYear()->startOfDay();
                $to = now()->endOfYear()->endOfDay();
                $prevFrom = now()->subYear()->startOfYear()->startOfDay();
                $prevTo = now()->subYear()->endOfYear()->endOfDay();
                break;
            case 'custom':
                $from = request('date_from') ? Carbon::parse(request('date_from'))->startOfDay() : now()->startOfYear()->startOfDay();
                $to = request('date_to') ? Carbon::parse(request('date_to'))->endOfDay() : now()->endOfDay();
                $days = $from->diffInDays($to) + 1;
                $prevFrom = $from->copy()->subDays($days);
                $prevTo = $from->copy()->subDay();
                break;
            default:
                $from = now()->startOfMonth()->startOfDay();
                $to = now()->endOfMonth()->endOfDay();
                $prevFrom = now()->subMonth()->startOfMonth()->startOfDay();
                $prevTo = now()->subMonth()->endOfMonth()->endOfDay();
        }

        $orders = $ordersQuery->whereBetween('created_at', [$from, $to])->get();
        $refundOrders = $refundOrdersQuery->whereBetween('created_at', [$from, $to])->count();

        $total_revenue = $orders->sum('seller_earnings');
        $total_order = $orders->count();
        $avg_order = $orders->avg('total');
        $refund_rate = $total_order > 0 ? round(($refundOrders / $total_order) * 100, 2) : 0;

        $previousOrders = Order::where('seller_id', $seller->id)->whereBetween('created_at', [$prevFrom, $prevTo])->get();
        $prev_revenue = $previousOrders->sum('seller_earnings');
        $prev_order = $previousOrders->count();
        $prev_avg_order = $previousOrders->avg('total');

        $calcGrowth = fn($current, $previous) => (!$previous || $previous == 0) ? 0 : round((($current - $previous) / $previous) * 100, 2);

        $revenue_growth = $calcGrowth($total_revenue, $prev_revenue);
        $order_growth = $calcGrowth($total_order, $prev_order);
        $avg_order_growth = $calcGrowth($avg_order, $prev_avg_order);

        $prevTotalOrders = $previousOrders->count();
        $prevRefundOrders = $previousOrders->where('status', 'refunded')->count();
        $prev_refund_rate = $prevTotalOrders > 0 ? round(($prevRefundOrders / $prevTotalOrders) * 100, 2) : 0;

        $refundRateChange = round($refund_rate - $prev_refund_rate, 2);

        $bestSelling = OrderItem::whereHas('order', fn($q) => $q->where('seller_id', $seller->id)->whereBetween('created_at', [$from, $to]))
            ->select('product_id', DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product:id,name')
            ->first();

        $labels = [];
        $revenues = [];
        switch ($range) {
            case 'daily':
            case 'custom':
                foreach (CarbonPeriod::create($from, $to) as $date) {
                    $labels[] = $date->format('d M');
                    $revenues[] = Order::where('seller_id', $seller->id)->whereDate('created_at', $date)->sum('seller_earnings');
                }
                break;
            case 'weekly':
                $start = $from->copy();
                while ($start <= $to) {
                    $weekStart = $start->copy()->startOfWeek();
                    $weekEnd = $start->copy()->endOfWeek();
                    $labels[] = $weekStart->format('d M') . ' - ' . $weekEnd->format('d M');
                    $revenues[] = Order::where('seller_id', $seller->id)->whereBetween('created_at', [$weekStart, $weekEnd])->sum('seller_earnings');
                    $start->addWeek();
                }
                break;
            case 'monthly':
                $start = $from->copy();
                while ($start <= $to) {
                    $monthStart = $start->copy()->startOfMonth();
                    $monthEnd = $start->copy()->endOfMonth();
                    $labels[] = $start->format('M Y');
                    $revenues[] = Order::where('seller_id', $seller->id)->whereBetween('created_at', [$monthStart, $monthEnd])->sum('seller_earnings');
                    $start->addMonth();
                }
                break;
            case 'yearly':
                $start = $from->copy();
                while ($start <= $to) {
                    $yearStart = $start->copy()->startOfYear();
                    $yearEnd = $start->copy()->endOfYear();
                    $labels[] = $start->format('Y');
                    $revenues[] = Order::where('seller_id', $seller->id)->whereBetween('created_at', [$yearStart, $yearEnd])->sum('seller_earnings');
                    $start->addYear();
                }
                break;
        }

        $orderItems = OrderItem::whereHas('order', fn($q) => $q->where('seller_id', $seller->id)->whereBetween('created_at', [$from, $to]))->with('product.category')->get();

        $prevOrderItems = OrderItem::whereHas(
            'order',
            fn($q) =>
            $q->where('seller_id', $seller->id)
                ->whereBetween('created_at', [$prevFrom, $prevTo])
        )->with('product.category')->get();

        $categoryData = $orderItems->groupBy(fn($item) => $item->product->category->name ?? 'Uncategorized')
            ->map(function ($items, $category) use ($prevOrderItems) {
                $sales = $items->sum(fn($i) => $i->unit_price * $i->quantity);
                $orders = $items->groupBy('order_id')->count();

                $prevSales = $prevOrderItems->filter(fn($i) => ($i->product->category->name ?? 'Uncategorized') === $category)
                    ->sum(fn($i) => $i->unit_price * $i->quantity);

                $growth = $prevSales > 0 ? round((($sales - $prevSales) / $prevSales) * 100, 2) : 0;

                return [
                    'category' => $category,
                    'sales' => $sales,
                    'orders' => $orders,
                    'growth' => $growth
                ];
            })->values();


        $webOrders = $orders->whereNotNull('user_id');
        $posOrders = $orders->whereNull('user_id');
        $totalRevenue = $orders->sum('seller_earnings');

        $channelData = [
            ['channel' => 'Web / E-comm', 'revenue' => $webOrders->sum('seller_earnings'), 'orders' => $webOrders->count()],
            ['channel' => 'POS (Retail)', 'revenue' => $posOrders->sum('seller_earnings'), 'orders' => $posOrders->count()],
        ];

        $maxRevenue = max(array_column($channelData, 'revenue'));
        foreach ($channelData as &$data) {
            $data['contribution'] = $totalRevenue > 0 ? round(($data['revenue'] / $totalRevenue) * 100, 2) : 0;
            $data['isTop'] = $data['revenue'] === $maxRevenue;
        }
        unset($data);

        $items = OrderItem::whereHas('order', fn($q) => $q->where('seller_id', $seller->id)->whereBetween('created_at', [$from, $to]))->get();

        $productStats = $items->groupBy('product_id')->map(function ($group) {
            $product = $group->first()->product;
            $unitsSold = $group->sum('quantity');
            $totalSale = $group->sum(fn($i) => $i->unit_price * $i->quantity);
            $totalCost = $group->sum(fn($i) => $i->buying_price * $i->quantity);
            $profitMargin = $totalSale > 0 ? (($totalSale - $totalCost) / $totalSale) * 100 : 0;
            $price = $group->avg('unit_price');
            return [
                'product_name' => $product->name ?? 'Unknown',
                'price' => $price,
                'units_sold' => $unitsSold,
                'total_sales' => $totalSale,
                'profit_margin' => round($profitMargin, 2),
                'relative_sales' => 0
            ];
        })->sortByDesc('total_sales')->values();

        $maxSales = $productStats->max('total_sales');
        $productStats = $productStats->map(fn($prod) => array_merge($prod, ['relative_sales' => $maxSales > 0 ? round(($prod['total_sales'] / $maxSales) * 100) : 0]));

        $regionData = OrderBillingAddress::whereHas('order', fn($q) => $q->where('seller_id', $seller->id))
            ->select('division_id', 'district_id')
            ->get();

        $ordersByDivision = $regionData->groupBy('division_id')->map(function ($group, $divisionId) {
            return [
                'division' => Division::find($divisionId)->name ?? 'Unknown',
                'orders_count' => $group->count(),
                'districts' => $group->groupBy('district_id')->map(fn($dgroup, $districtId) => [
                    'district' => District::find($districtId)->name ?? 'Unknown',
                    'orders_count' => $dgroup->count()
                ])->values()
            ];
        })->values();

        $divisionLabels = $ordersByDivision->pluck('division')->toArray();
        $divisionOrders = $ordersByDivision->pluck('orders_count')->toArray();

        return view('seller.reports.sales', compact(
            'total_revenue',
            'total_order',
            'avg_order',
            'bestSelling',
            'refund_rate',
            'refundRateChange',
            'range',
            'revenue_growth',
            'order_growth',
            'avg_order_growth',
            'labels',
            'revenues',
            'categoryData',
            'channelData',
            'productStats',
            'divisionLabels',
            'divisionOrders'
        ));
    }

    public function customers()
    {
        return view('seller.reports.customers');
    }
}
