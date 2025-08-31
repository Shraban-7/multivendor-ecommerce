<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $seller_id = seller()->id;
        $type      = $request->segment(3);

        $statusValue = OrderStatus::valueFromLabel($type);

        if ($statusValue === null && $type != 'pos') {
            return redirect()->route('seller.dashboard');
        }

        $orders = Order::where('seller_id', $seller_id)
            ->where('status', $statusValue)
            ->whereNotNull('user_id')
            ->latest('id')
            ->get();

        return view('seller.orders.index', compact('orders', 'type'));
    }

    public function details($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();
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
            'delivery_status' => $request->delivery_status ?? $order->delivery_status,
        ]);

        if ($order->status == OrderStatus::DELIVERED) {
            $affiliate_commission = AffiliateCommission::where('order_id', $order->id)->first();

            $affiliate_commission->status = AffiliateCommission::APPROVED;
            $affiliate_commission->save();
            if ($affiliate_commission->status == AffiliateCommission::APPROVED) {
                $user = User::find($affiliate_commission->affiliate_id);

                $user->balance += $affiliate_commission->commission_amount;
                $user->save();
            }
        }

        return redirect()->back()->with('success', 'Order update successfully');
    }


    public function posInvoice($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();
        $seller_id = seller()->id;
        if ($seller_id == $order->seller_id) {
            return view('seller.orders.pos_invoice', compact('order'));
        }
        return redirect()->back();
    }
}
