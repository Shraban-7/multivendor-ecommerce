<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SellerController extends Controller
{
    public function index()
    {
        $sellers = Seller::get();

        return view('admin.sellers.index', compact('sellers'));
    }

    public function profile(Seller $seller)
    {
        $data = [];
        $data['total_products'] = Product::where('seller_id', $seller->id)->count();
        $data['total_orders'] = Order::where('seller_id', $seller->id)->count();
        $data['pending_orders'] = Order::pending()->where('seller_id', $seller->id)->count();
        $data['shipped_orders'] = Order::shipped()->where('seller_id', $seller->id)->count();
        $data['cancelled_orders'] = Order::cancelled()->where('seller_id', $seller->id)->count();
        $data['delivered_orders'] = Order::delivered()->where('seller_id', $seller->id)->count();
        $data['total_revenue'] = Order::delivered()->where('seller_id', $seller->id)->sum('total');
        $data['total_customers'] = Order::where('seller_id', $seller->id)->distinct('user_id')->count('user_id');
        $data['products'] = Product::where('seller_id',$seller->id)->paginate(102);
        $data['seller'] = $seller;
        return view('admin.sellers.profile',$data);
    }
}
