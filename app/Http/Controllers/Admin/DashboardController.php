<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;

class DashboardController extends Controller
{
    public function dashboard()
    {
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

        return view('admin.dashboard', $data);
    }
}
