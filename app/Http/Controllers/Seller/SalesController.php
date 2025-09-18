<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    public function index()
    {
        $orders = Order::where('seller_id', get_seller_id())
            ->whereNull('user_id')
            ->latest('id')
            ->get();

        return view('seller.orders.pos-orders', compact('orders'));
    }

    public function delete($id)
    {
        $order = Order::find($id);
        $order->delete();

        return successResponse("Order Delete Successfully!");
    }
}
