<?php
namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $seller_id = seller()->id;
        $type      = $request->segment(3);

        $statusValue = OrderStatus::valueFromLabel($type);

        if ($statusValue === null) {
            return redirect()->route('seller.dashboard');
        }

        $orders = Order::where('seller_id', $seller_id)
            ->where('status', $statusValue)
            ->latest('id')
            ->get();

        return view('seller.orders.index', compact('orders', 'type'));
    }

    public function details(Order $order)
    {
        $seller_id = seller()->id;
        if ($seller_id == $order->seller_id) {
            $order->load(['review', 'items']);
            return view('seller.orders.details', compact('order'));
        }
        return redirect()->back();
    }

    public function updateStatus(Order $order, Request $request)
    {
        $order->update([
            'status'          => $request->status,
            'delivery_status' => $request->delivery_status,
        ]);

        return redirect()->back()->with('success', 'Order update successfully');
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'seller', 'user.country','items.variant');

        return view('seller.orders.invoice', compact('order'));
    }
}
