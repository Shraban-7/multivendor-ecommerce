<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_revenue' => Order::where('status', 'completed')
                ->sum('payable'),
            'total_orders' => Order::count(),
            'total_vendors' => Seller::where('is_active', 1)->count(),
            'total_customers' => User::count(),
            'total_products' => Product::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
        ];

        $recent_orders = Order::with(['user', 'seller'])
            ->latest()
            ->take(5)
            ->get();

        $top_vendors = Seller::withCount('orders')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();

        $monthly_revenue = Order::where('status', 'completed')
            ->where('created_at', '>=', now()->subMonths(6))
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(payable) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

            $data = [];
        $data['total_products'] = Product::count();
        $data['total_orders'] = Order::count();
        $data['pending_orders'] = Order::pending()->count();
        $data['shipped_orders'] = Order::shipped()->count();
        $data['cancelled_orders'] = Order::cancelled()->count();
        $data['delivered_orders'] = Order::delivered()->count();
        $data['total_sales'] = Order::delivered()->sum('payable');
        $data['total_sellers'] = Seller::count();
        $data['total_customers'] = Order::distinct('user_id')->count('user_id');
        $data['total_commission'] = Order::sum('total_commission');

        return view('admin.dashboard', compact(
            'stats',
            'recent_orders',
            'top_vendors',
            'monthly_revenue',
            'data'
        ));

        

        return view('admin.dashboard', $data);
    }
}
