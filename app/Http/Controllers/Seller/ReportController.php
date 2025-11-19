<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\OrderItem;
use App\Models\SellerExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        return view('seller.reports.sales');
    }
}
