<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $seller_id = seller()->id;
        $orders = Order::where('seller_id', $seller_id);

        $type = $request->segment(3) ??  null;

        if ($type == null || !in_array($type, OrderStatus::labels())) {
            return redirect()->route('seller.dashboard');
        }

        $orders->$type();

        $orders = $orders->latest('id')->paginate(10);

        return view('seller.orders.index', compact('orders','type'));
    }

    public function details(Order $order)
    {
        $seller_id = seller()->id;
        if ($seller_id == $order->seller_id) {
            $order->load(['review']);
            return view('seller.orders.details', compact('order'));
        }
        return redirect()->back();
    }


    public function updateStatus(Order $order,Request $request)
    {
       $order->update([
            'status' => $request->status,
            'delivery_status' => $request->delivery_status
       ]);

       return redirect()->back()->with('success','Order update successfully');
    }
}
