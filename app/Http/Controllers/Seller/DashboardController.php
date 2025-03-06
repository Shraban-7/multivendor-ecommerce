<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $data = [];
        $data['total_products'] = Product::where('seller_id', seller()->id)->count();
        $data['total_orders'] = Order::where('seller_id', seller()->id)->count();
        $data['pending_orders'] = Order::pending()->where('seller_id', seller()->id)->count();
        $data['shipped_orders'] = Order::shipped()->where('seller_id', seller()->id)->count();
        $data['cancelled_orders'] = Order::cancelled()->where('seller_id', seller()->id)->count();
        $data['delivered_orders'] = Order::delivered()->where('seller_id', seller()->id)->count();
        $data['total_revenue'] = Order::delivered()->where('seller_id', seller()->id)->sum('total');
        $data['total_customers'] = Order::where('seller_id', seller()->id)->distinct('user_id')->count('user_id');

        return view('seller.dashboard', $data);
    }
}
