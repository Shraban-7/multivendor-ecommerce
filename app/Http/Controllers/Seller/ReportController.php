<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Customer;
use App\Models\District;
use App\Models\Division;
use Carbon\CarbonPeriod;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
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
        $filter = $request->get('range');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if ($filter && $filter !== 'custom') {
            $dates = $this->getDateRange($filter);
            $currentStart = $dates['currentStart'];
            $currentEnd = $dates['currentEnd'];
            $lastStart = $dates['lastStart'];
            $lastEnd = $dates['lastEnd'];
        } elseif ($filter === 'custom') {
            $currentStart = Carbon::parse($dateFrom)->startOfDay();
            $currentEnd = Carbon::parse($dateTo)->endOfDay();

            $days = Carbon::parse($dateFrom)->diffInDays($dateTo) + 1;
            $lastEnd = Carbon::parse($dateFrom)->subDay()->endOfDay();
            $lastStart = $lastEnd->copy()->subDays($days - 1)->startOfDay();
        } else {
            $currentStart = Order::min('created_at') ?? now();
            $currentEnd = now();
            $lastStart = null;
            $lastEnd = null;
        }

        $nextStart = match ($filter) {
            'daily' => now()->addDay()->startOfDay(),
            'weekly' => now()->addWeek()->startOfWeek(),
            'monthly' => now()->addMonth()->startOfMonth(),
            'yearly' => now()->addYear()->startOfYear(),
            'custom' => $currentEnd->copy()->addDay()->startOfDay(),
            default => now()->addMonth()->startOfMonth(),
        };

        $nextEnd = match ($filter) {
            'daily' => now()->addDay()->endOfDay(),
            'weekly' => now()->addWeek()->endOfWeek(),
            'monthly' => now()->addMonth()->endOfMonth(),
            'yearly' => now()->addYear()->endOfYear(),
            'custom' => $currentEnd->copy()->addDay()->endOfDay(),
            default => now()->addMonth()->endOfMonth(),
        };

        $calculateMetrics = function ($sellerId, $start, $end) {
            $orders = Order::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$start, $end])
                ->get();

            $total_revenue = $orders->sum('seller_earnings');

            $total_product_cost = OrderItem::whereHas('order', function ($q) use ($sellerId, $start, $end) {
                $q->where('seller_id', $sellerId)
                    ->whereBetween('created_at', [$start, $end]);
            })->sum(DB::raw('buying_price * quantity'));

            $total_selling_price = OrderItem::whereHas('order', function ($q) use ($sellerId, $start, $end) {
                $q->where('seller_id', $sellerId)
                    ->whereBetween('created_at', [$start, $end]);
            })->sum(DB::raw('unit_price * quantity'));

            $gross_profit = $total_selling_price - $total_product_cost;

            $total_expense = SellerExpense::where('seller_id', $sellerId)
                ->whereBetween('created_at', [$start, $end])
                ->sum('amount');

            $net_profit = $gross_profit - $total_expense;
            $profit_margin = $total_revenue > 0 ? ($net_profit / $total_revenue) * 100 : 0;

            return compact('total_revenue', 'total_product_cost', 'gross_profit', 'total_expense', 'net_profit', 'profit_margin');
        };

        $currentMetrics = $calculateMetrics($seller->id, $currentStart, $currentEnd);
        $lastMetrics = $calculateMetrics($seller->id, $lastStart, $lastEnd);
        $nextMetrics = $calculateMetrics($seller->id, $nextStart, $nextEnd);

        $calculateChange = fn($current, $last) => $last > 0 ? (($current - $last) / $last) * 100 : 100;

        $changes = [
            'revenue' => $calculateChange($currentMetrics['total_revenue'], $lastMetrics['total_revenue']),
            'gross_profit' => $calculateChange($currentMetrics['gross_profit'], $lastMetrics['gross_profit']),
            'net_profit' => $calculateChange($currentMetrics['net_profit'], $lastMetrics['net_profit']),
            'profit_margin' => $currentMetrics['profit_margin'] - $lastMetrics['profit_margin'],
            'expense' => $calculateChange($currentMetrics['total_expense'], $lastMetrics['total_expense']),
        ];

        $inventory_value = Product::where('seller_id', $seller->id)
            ->sum(DB::raw('buying_price * (stock_in - stock_out)'));

        $lowTurnoverDays = 90;
        $cutoffDate = now()->subDays($lowTurnoverDays);
        $soldProductIds = OrderItem::whereHas('order', function ($q) use ($seller, $cutoffDate) {
            $q->where('seller_id', $seller->id)
                ->where('created_at', '>=', $cutoffDate);
        })->pluck('product_id')->unique();

        $lowTurnoverCount = Product::where('seller_id', $seller->id)
            ->whereNotIn('id', $soldProductIds)
            ->count();

        if ($filter && $filter !== 'custom') {
            $dates = $this->getDateRange($filter);
            $startDate = $dates['currentStart'];
            $endDate = $dates['currentEnd'];
        } elseif ($filter === 'custom') {
            $startDate = Carbon::parse($dateFrom)->startOfDay();
            $endDate = Carbon::parse($dateTo)->endOfDay();
        } else {
            $startDate = Product::min('created_at') ?? now();
            $endDate = now();
        }

        $inventoryByCategory = Product::where('seller_id', $seller->id)
            ->whereHas('orderItems.order', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->select('category_id', DB::raw('COUNT(*) as sku_count'), DB::raw('SUM(buying_price * (stock_in - stock_out)) as stock_value'))
            ->groupBy('category_id')
            ->with('category')
            ->get();

        $totalStockValue = $inventoryByCategory->sum('stock_value');

        $trendData = $this->getTrendData($filter, $dateFrom, $dateTo);
        $expenseTrend = $this->getExpenseTrend($seller->id, $filter, $dateFrom, $dateTo);

        $incomeSources = [
            'Product Sales' => Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('seller_earnings'),
            'POS Sales' => Order::where('seller_id', $seller->id)
                ->where('user_id', null)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->sum('total'),
        ];

        $totalIncome = array_sum($incomeSources);

        $incomeData = collect($incomeSources)->map(function ($amount, $source) use ($totalIncome) {
            $percentage = $totalIncome > 0 ? ($amount / $totalIncome) * 100 : 0;
            $status = match ($source) {
                'Product Sales' => 'Primary Source',
                'POS Sales' => 'New Stream',
                default => 'Other',
            };
            $badgeClass = match ($source) {
                'Product Sales' => 'bg-primary',
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

        $totalExpense = SellerExpense::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('amount');

        $expenseCategories = SellerExpense::where('seller_id', $seller->id)
            ->select('seller_expense_category_id', DB::raw('SUM(amount) as total'))
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->groupBy('seller_expense_category_id')
            ->with('category')
            ->get();

        $highestExpense = $expenseCategories->sortByDesc('total')->first();

        $lastTotalExpense = SellerExpense::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$lastStart, $lastEnd])
            ->sum('amount');

        $expenseGrowth = $lastTotalExpense > 0
            ? (($totalExpense - $lastTotalExpense) / $lastTotalExpense) * 100
            : 100;

        return view('seller.reports.financial', compact(
            'currentMetrics',
            'lastMetrics',
            'nextMetrics',
            'changes',
            'inventory_value',
            'trendData',
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

    public function customers(Request $request)
    {
        $filter = $request->get('filter', null);

        if ($filter) {
            $dates = $this->getDateRange($filter);
            $currentStart = $dates['currentStart'];
            $currentEnd = $dates['currentEnd'];
            $lastStart = $dates['lastStart'];
            $lastEnd = $dates['lastEnd'];
        } else {
            $currentStart = Order::min('created_at');
            $currentEnd = now();
            $lastStart = null;
            $lastEnd = null;
        }

        $allTimeTotalCustomers = Order::get(['user_id', 'customer_id'])
            ->unique(fn($item) => $item->user_id . '-' . $item->customer_id)
            ->count();

        $newCustomersCurrent = Order::whereBetween('created_at', [$currentStart, $currentEnd->toDateString()])
            ->get(['user_id', 'customer_id'])
            ->unique(fn($item) => $item->user_id . '-' . $item->customer_id)
            ->count();

        $newCustomersLast = $lastStart && $lastEnd
            ? Order::whereBetween('created_at', [$lastStart, $lastEnd->toDateString()])
                ->get(['user_id', 'customer_id'])
                ->unique(fn($item) => $item->user_id . '-' . $item->customer_id)
                ->count()
            : 0;

        $returningCustomersCurrent = max($allTimeTotalCustomers - $newCustomersCurrent, 0);

        $returningPercentage = $allTimeTotalCustomers > 0
            ? round(($returningCustomersCurrent / $allTimeTotalCustomers) * 100, 1)
            : 0;

        $avgClvCurrent = Order::whereBetween('created_at', [$currentStart, $currentEnd->toDateString()])->avg('total') ?? 0;
        $avgClvLast = $lastStart && $lastEnd
            ? Order::whereBetween('created_at', [$lastStart, $lastEnd])->avg('total') ?? 0
            : 0;


        $totalOrdersCurrent = Order::whereBetween('created_at', [$currentStart, $currentEnd->toDateString()])->count();
        $totalOrdersLast = $lastStart && $lastEnd
            ? Order::whereBetween('created_at', [$lastStart, $lastEnd])->count()
            : 0;

        $avgOrdersPerCustomerCurrent = $newCustomersCurrent > 0
            ? round($totalOrdersCurrent / $newCustomersCurrent, 2)
            : 0;

        $avgOrdersPerCustomerLast = $newCustomersLast > 0
            ? round($totalOrdersLast / $newCustomersLast, 2)
            : 0;

        $newCustomersChange = $newCustomersLast > 0
            ? round((($newCustomersCurrent - $newCustomersLast) / $newCustomersLast) * 100, 1)
            : 0;

        $avgClvChange = $avgClvLast > 0
            ? round((($avgClvCurrent - $avgClvLast) / $avgClvLast) * 100, 1)
            : 0;

        $avgOrdersPerCustomerChange = $avgOrdersPerCustomerLast > 0
            ? round((($avgOrdersPerCustomerCurrent - $avgOrdersPerCustomerLast) / $avgOrdersPerCustomerLast) * 100, 1)
            : 0;


        $chartData = [
            'total' => ['labels' => [], 'data' => []],
            'new_vs_returning' => ['labels' => [], 'new' => [], 'returning' => []],
        ];

        $months = collect();
        for ($i = 11; $i >= 0; $i--) {
            $months->push(now()->copy()->subMonths($i));
        }

        foreach ($months as $month) {

            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();

            $monthlyTotal = Order::whereBetween('created_at', [$monthStart, $monthEnd])
                ->get(['user_id', 'customer_id'])
                ->unique(fn($item) => $item->user_id . '-' . $item->customer_id)
                ->count();

            $previousTotal = Order::where('created_at', '<', $monthStart)
                ->get(['user_id', 'customer_id'])
                ->unique(fn($item) => $item->user_id . '-' . $item->customer_id)
                ->count();

            $newCustomers = $monthlyTotal > 0 ? max($monthlyTotal - $previousTotal, 0) : 0;

            $returningCustomers = max($monthlyTotal - $newCustomers, 0);

            $label = $month->format('M Y');

            $chartData['total']['labels'][] = $label;
            $chartData['total']['data'][] = $monthlyTotal;

            $chartData['new_vs_returning']['labels'][] = $label;
            $chartData['new_vs_returning']['new'][] = $newCustomers;
            $chartData['new_vs_returning']['returning'][] = $returningCustomers;
        }


        $topCustomers = Order::with(['user:id,name', 'customer:id,name'])
            ->whereBetween('created_at', [$currentStart, $currentEnd->toDateString()])   // ← your filter applied
            ->selectRaw("
                    user_id,
                    customer_id,
                    COUNT(id) as total_orders,
                    SUM(total) as total_spent
                ")
            ->groupBy('user_id', 'customer_id')
            ->orderByDesc('total_spent')
            ->take(5)
            ->get()
            ->map(function ($row) {

                // Determine customer name
                if ($row->user_id && $row->user) {
                    $name = $row->user->name;
                } elseif ($row->customer_id && $row->customer) {
                    $name = $row->customer->name;
                } else {
                    $name = 'Guest Customer';
                }

                return [
                    'name' => $name,
                    'orders' => $row->total_orders,
                    'spent' => $row->total_spent,
                ];
            });


        return view('seller.reports.customers', compact(
            'filter',
            'allTimeTotalCustomers',
            'newCustomersCurrent',
            'newCustomersChange',
            'returningPercentage',
            'avgClvCurrent',
            'avgClvChange',
            'avgOrdersPerCustomerCurrent',
            'avgOrdersPerCustomerChange',
            'chartData',
            'topCustomers'
        ));
    }

    protected function calculateMetrics($sellerId, $start, $end)
    {
        $orders = Order::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $total_revenue = $orders->sum('seller_earnings');

        $total_product_cost = OrderItem::whereHas('order', function ($q) use ($sellerId, $start, $end) {
            $q->where('seller_id', $sellerId)
                ->whereBetween('created_at', [$start, $end]);
        })->sum(DB::raw('buying_price * quantity'));

        $total_selling_price = OrderItem::whereHas('order', function ($q) use ($sellerId, $start, $end) {
            $q->where('seller_id', $sellerId)
                ->whereBetween('created_at', [$start, $end]);
        })->sum(DB::raw('unit_price * quantity'));

        $gross_profit = $total_selling_price - $total_product_cost;

        $total_expense = SellerExpense::where('seller_id', $sellerId)
            ->whereBetween('created_at', [$start, $end])
            ->sum('amount');

        $net_profit = $gross_profit - $total_expense;

        return compact('total_revenue', 'total_product_cost', 'gross_profit', 'total_expense', 'net_profit');
    }


    protected function getDateRange($filter, $dateFrom = null, $dateTo = null)
    {
        switch ($filter) {

            case 'daily':
                $currentStart = now()->startOfDay();
                $currentEnd = now()->endOfDay();
                $lastStart = now()->subDay()->startOfDay();
                $lastEnd = now()->subDay()->endOfDay();
                break;

            case 'weekly':
                $currentStart = now()->startOfWeek();
                $currentEnd = now()->endOfWeek();
                $lastStart = now()->subWeek()->startOfWeek();
                $lastEnd = now()->subWeek()->endOfWeek();
                break;

            case 'yearly':
                $currentStart = now()->startOfYear();
                $currentEnd = now()->endOfYear();
                $lastStart = now()->subYear()->startOfYear();
                $lastEnd = now()->subYear()->endOfYear();
                break;

            case 'custom':
                $currentStart = Carbon::parse($dateFrom)->startOfDay();
                $currentEnd = Carbon::parse($dateTo)->endOfDay();

                $days = Carbon::parse($dateFrom)->diffInDays($dateTo) + 1;

                $lastEnd = Carbon::parse($dateFrom)->subDay()->endOfDay();
                $lastStart = $lastEnd->copy()->subDays($days - 1)->startOfDay();
                break;

            case 'monthly':
            default:
                $currentStart = now()->startOfMonth();
                $currentEnd = now()->endOfMonth();
                $lastStart = now()->subMonth()->startOfMonth();
                $lastEnd = now()->subMonth()->endOfMonth();
        }

        return [
            'currentStart' => $currentStart,
            'currentEnd' => $currentEnd,
            'lastStart' => $lastStart,
            'lastEnd' => $lastEnd,
        ];
    }

    protected function periodUnit($filter, $dateFrom = null, $dateTo = null)
    {
        return match ($filter) {
            'daily' => 'day',
            'weekly' => 'week',
            'yearly' => 'year',
            'monthly' => 'month',
            'custom' => 'day',
            default => 'month',
        };
    }

    protected function getTrendData($filter, $dateFrom = null, $dateTo = null)
    {
        $calculateMetrics = function ($start, $end) {
            $seller = Seller::find(get_seller_id());
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

            return [
                'net_profit' => $net_profit,
                'gross_profit' => $gross_profit,
                'total_revenue' => $total_revenue,
            ];
        };

        $trendData = collect();

        switch ($filter) {
            case 'daily':
                for ($i = 29; $i >= 0; $i--) {
                    $start = now()->subDays($i)->startOfDay();
                    $end = now()->subDays($i)->endOfDay();
                    $metrics = $calculateMetrics($start, $end);
                    $trendData->push([
                        'label' => $start->format('d M'),
                        'net_profit' => $metrics['net_profit'],
                        'gross_profit' => $metrics['gross_profit'],
                        'total_revenue' => $metrics['total_revenue'],
                    ]);
                }
                break;

            case 'weekly':
                for ($i = 11; $i >= 0; $i--) {
                    $start = now()->subWeeks($i)->startOfWeek();
                    $end = now()->subWeeks($i)->endOfWeek();
                    $metrics = $calculateMetrics($start, $end);
                    $trendData->push([
                        'label' => 'Week ' . now()->subWeeks($i)->weekOfYear,
                        'net_profit' => $metrics['net_profit'],
                        'gross_profit' => $metrics['gross_profit'],
                        'total_revenue' => $metrics['total_revenue'],
                    ]);
                }
                break;

            case 'yearly':
                for ($i = 4; $i >= 0; $i--) {
                    $start = now()->subYears($i)->startOfYear();
                    $end = now()->subYears($i)->endOfYear();
                    $metrics = $calculateMetrics($start, $end);
                    $trendData->push([
                        'label' => $start->format('Y'),
                        'net_profit' => $metrics['net_profit'],
                        'gross_profit' => $metrics['gross_profit'],
                        'total_revenue' => $metrics['total_revenue'],
                    ]);
                }
                break;

            case 'custom':
                if ($dateFrom && $dateTo) {
                    $startDate = Carbon::parse($dateFrom);
                    $endDate = Carbon::parse($dateTo);
                    $days = $startDate->diffInDays($endDate);

                    for ($i = 0; $i <= $days; $i++) {
                        $start = $startDate->copy()->addDays($i)->startOfDay();
                        $end = $startDate->copy()->addDays($i)->endOfDay();
                        $metrics = $calculateMetrics($start, $end);
                        $trendData->push([
                            'label' => $start->format('d M'),
                            'net_profit' => $metrics['net_profit'],
                            'gross_profit' => $metrics['gross_profit'],
                            'total_revenue' => $metrics['total_revenue'],
                        ]);
                    }
                }
                break;

            case 'monthly':
            default:
                for ($i = 11; $i >= 0; $i--) {
                    $start = now()->subMonths($i)->startOfMonth();
                    $end = now()->subMonths($i)->endOfMonth();
                    $metrics = $calculateMetrics($start, $end);
                    $trendData->push([
                        'label' => $start->format('M Y'),
                        'net_profit' => $metrics['net_profit'],
                        'gross_profit' => $metrics['gross_profit'],
                        'total_revenue' => $metrics['total_revenue'],
                    ]);
                }
        }

        return $trendData;
    }

    protected function getExpenseTrend($sellerId, $filter, $dateFrom = null, $dateTo = null)
    {
        $expenseTrend = collect();

        switch ($filter) {
            case 'daily':
                $days = 11; // last 12 days
                for ($i = $days; $i >= 0; $i--) {
                    $start = now()->subDays($i)->startOfDay();
                    $end = now()->subDays($i)->endOfDay();

                    $amount = SellerExpense::where('seller_id', $sellerId)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('amount');

                    $expenseTrend->push([
                        'label' => $start->format('d M'),
                        'amount' => $amount,
                    ]);
                }
                break;

            case 'weekly':
                for ($i = 11; $i >= 0; $i--) { // last 12 weeks
                    $start = now()->subWeeks($i)->startOfWeek();
                    $end = now()->subWeeks($i)->endOfWeek();

                    $amount = SellerExpense::where('seller_id', $sellerId)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('amount');

                    $expenseTrend->push([
                        'label' => $start->format('d M'),
                        'amount' => $amount,
                    ]);
                }
                break;

            case 'yearly':
                for ($i = 4; $i >= 0; $i--) { // last 5 years
                    $start = now()->subYears($i)->startOfYear();
                    $end = now()->subYears($i)->endOfYear();

                    $amount = SellerExpense::where('seller_id', $sellerId)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('amount');

                    $expenseTrend->push([
                        'label' => $start->format('Y'),
                        'amount' => $amount,
                    ]);
                }
                break;

            case 'custom':
                $from = $dateFrom ? Carbon::parse($dateFrom)->startOfDay() : now()->subMonth()->startOfMonth();
                $to = $dateTo ? Carbon::parse($dateTo)->endOfDay() : now()->endOfMonth();

                $period = CarbonPeriod::create($from, $to);

                foreach ($period as $date) {
                    $start = $date->copy()->startOfDay();
                    $end = $date->copy()->endOfDay();

                    $amount = SellerExpense::where('seller_id', $sellerId)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('amount');

                    $expenseTrend->push([
                        'label' => $start->format('d M Y'),
                        'amount' => $amount,
                    ]);
                }
                break;

            case 'monthly':
            default:
                for ($i = 11; $i >= 0; $i--) {
                    $start = now()->subMonths($i)->startOfMonth();
                    $end = now()->subMonths($i)->endOfMonth();

                    $amount = SellerExpense::where('seller_id', $sellerId)
                        ->whereBetween('created_at', [$start, $end])
                        ->sum('amount');

                    $expenseTrend->push([
                        'label' => $start->format('M Y'),
                        'amount' => $amount,
                    ]);
                }
                break;
        }

        return $expenseTrend;
    }

    public function overview(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $filter = $request->get('range');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        if ($filter && $filter !== 'custom') {

            $dates = $this->getDateRange($filter);

            $currentStart = $dates['currentStart'];
            $currentEnd = $dates['currentEnd'];

            $lastStart = $dates['lastStart'];
            $lastEnd = $dates['lastEnd'];

        } elseif ($filter === 'custom') {

            $currentStart = Carbon::parse($dateFrom)->startOfDay();
            $currentEnd = Carbon::parse($dateTo)->endOfDay();

            $days = Carbon::parse($dateFrom)->diffInDays($dateTo) + 1;

            $lastEnd = Carbon::parse($dateFrom)->subDay()->endOfDay();
            $lastStart = $lastEnd->copy()->subDays($days - 1)->startOfDay();

        } else {
            $currentStart = Order::min('created_at') ?? now();
            $currentEnd = now();
            $lastStart = null;
            $lastEnd = null;
        }

        $currentSales = Order::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->sum('total');

        $currentOrders = Order::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->count();

        $currentAOV = $currentOrders > 0 ? $currentSales / $currentOrders : 0;

        $currentCost = OrderItem::whereHas('order', function ($q) use ($seller, $currentStart, $currentEnd) {
            $q->where('seller_id', $seller->id)
                ->whereBetween('created_at', [$currentStart, $currentEnd]);
        })->sum(DB::raw('buying_price * quantity'));

        $currentProfit = $currentSales - $currentCost;

        $currentStock = Product::where('seller_id', $seller->id)->sum(DB::raw('stock_in - stock_out'));

        if ($lastStart && $lastEnd) {
            $lastSales = Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$lastStart, $lastEnd])
                ->sum('total');

            $lastOrders = Order::where('seller_id', $seller->id)
                ->whereBetween('created_at', [$lastStart, $lastEnd])
                ->count();

            $lastAOV = $lastOrders > 0 ? $lastSales / $lastOrders : 0;

            $lastCost = OrderItem::whereHas('order', function ($q) use ($seller, $lastStart, $lastEnd) {
                $q->where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$lastStart, $lastEnd]);
            })->sum(DB::raw('buying_price * quantity'));

            $lastProfit = $lastSales - $lastCost;

            $lastStock = Product::where('seller_id', $seller->id)->sum(DB::raw('stock_in-stock_out'));
        } else {
            $lastSales = $lastOrders = $lastAOV = $lastProfit = $lastStock = 0;
        }

        $growth = function ($current, $last) {
            if ($last == 0)
                return 0;
            return round((($current - $last) / $last) * 100, 2);
        };

        $calculateMetrics = [
            'total_sales' => $currentSales,
            'total_orders' => $currentOrders,
            'aov' => $currentAOV,
            'net_profit' => $currentProfit,
            'total_stock' => $currentStock,

            'sales_growth' => $growth($currentSales, $lastSales),
            'orders_growth' => $growth($currentOrders, $lastOrders),
            'aov_growth' => $growth($currentAOV, $lastAOV),
            'profit_growth' => $growth($currentProfit, $lastProfit),
            'stock_growth' => $growth($currentStock, $lastStock),
        ];

        switch ($filter) {
            case 'daily':
                $trend = Order::where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$currentStart, $currentEnd])
                    ->selectRaw('HOUR(created_at) as label, SUM(seller_earnings) as revenue')
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;

            case 'weekly':
                $trend = Order::where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$currentStart, $currentEnd])
                    ->selectRaw('DAYNAME(created_at) as label, SUM(seller_earnings) as revenue')
                    ->groupBy('label')
                    ->orderByRaw('MIN(created_at)')
                    ->get();
                break;

            case 'monthly':
                $trend = Order::where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$currentStart, $currentEnd])
                    ->selectRaw('DAY(created_at) as label, SUM(seller_earnings) as revenue')
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;

            case 'yearly':
                $trend = Order::where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$currentStart, $currentEnd])
                    ->selectRaw('MONTHNAME(created_at) as label, SUM(seller_earnings) as revenue')
                    ->groupBy('label')
                    ->orderByRaw('MIN(created_at)')
                    ->get();
                break;

            case 'custom':
            default:
                $trend = Order::where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$currentStart, $currentEnd])
                    ->selectRaw('DATE(created_at) as label, SUM(seller_earnings) as revenue')
                    ->groupBy('label')
                    ->orderBy('label')
                    ->get();
                break;
        }

        $revenueTrend = [
            'labels' => $trend->pluck('label'),
            'values' => $trend->pluck('revenue'),
        ];

        $ordersReturns = Order::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->selectRaw('
                SUM(CASE WHEN status = ' . OrderStatus::DELIVERED->value . ' THEN 1 ELSE 0 END) AS orders,
                SUM(CASE WHEN status = ' . OrderStatus::RETURNED->value . ' THEN 1 ELSE 0 END) AS returns
            ')
            ->first();

        $ordersReturnsChart = [
            'orders' => $ordersReturns->orders ?? 0,
            'returns' => $ordersReturns->returns ?? 0,
        ];

        $chartData = [
            'revenueTrend' => $revenueTrend,
            'ordersReturns' => $ordersReturnsChart,
        ];

        $orders = Order::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd]);

        $totalOrders = $orders->count();

        $refundRate = $orders->where('status', OrderStatus::REFUNDED->value)
            ->count();
        $refundRatePercent = $totalOrders > 0 ? round(($refundRate / $totalOrders) * 100, 2) : 0;

        $bestSalesDay = Order::where('seller_id', $seller->id)
            ->whereBetween('created_at', [$currentStart, $currentEnd])
            ->selectRaw('DATE(created_at) as order_date, SUM(total) as total_sales')
            ->groupBy('order_date')
            ->orderByDesc('total_sales')
            ->first();

        $quickFacts = [
            'total_orders' => $totalOrders,
            'refund_rate' => $refundRatePercent,
            'best_sales_day' => $bestSalesDay
                ? Carbon::parse($bestSalesDay->order_date)->format('M d') . ' (' . money($bestSalesDay->total_sales) . ')'
                : null,
        ];

        $topProducts = OrderItem::with('product')
            ->whereHas('order', function ($q) use ($seller, $currentStart, $currentEnd) {
                $q->where('seller_id', $seller->id)
                    ->whereBetween('created_at', [$currentStart, $currentEnd])
                    ->whereIn('status', [OrderStatus::DELIVERED->value, OrderStatus::COMPLETED->value]);
            })
            ->selectRaw('product_id, SUM(quantity) as units_sold, SUM(unit_price * quantity) as total_sales')
            ->groupBy('product_id')
            ->orderByDesc('units_sold')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product->name ?? 'N/A',
                    'units_sold' => $item->units_sold,
                    'sales' => $item->total_sales,
                    'stock' => ($item->product->stock_in -$item->product->stock_out) ?? 0,
                ];
            });

        return view('seller.reports.overview', compact('calculateMetrics', 'chartData', 'quickFacts', 'filter','topProducts'));
    }



}
