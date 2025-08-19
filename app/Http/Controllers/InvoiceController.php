<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function invoice($invoice_id)
    {
        $order = Order::where('invoice_id',$invoice_id)->first();
        $order->load('items.product', 'seller', 'user.country','items.variant');

        return view('invoice', compact('order'));
    }

    public function receipt($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();
        $seller_id = seller()->id;
        if ($seller_id == $order->seller_id) {
            return view('seller.orders.receipt', compact('order'));
        }
        return redirect()->back();
    }
}
