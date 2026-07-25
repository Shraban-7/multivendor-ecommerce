<?php

namespace App\Domain\Order\Http\Controllers;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;

class InvoiceController extends Controller
{
    public function invoice($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();

        $order->load('items.product', 'seller', 'user.country', 'items.variant', 'customer');

        return view('invoice', compact('order'));
    }

    public function receipt($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->with('customer', 'items')->first();

        if (get_seller_id() != $order->seller_id) {
            return redirect()->back();
        }

        return view('seller.orders.receipt', compact('order'));
    }
}
