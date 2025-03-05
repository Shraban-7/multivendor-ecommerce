<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $seller_id = seller()->id;
        $orders = Order::where('seller_id', $seller_id);

        $type = $request->segment(3) ??  null;

        if ($type == null || !in_array($type, OrderStatus::labels())) { 
            return redirect()->route('seller.dashboard');
        }
        
        $orders->$type();

        $orders = $orders->latest('id')->paginate(10);

        return view('seller.orders', compact('orders','type'));
    }
}
