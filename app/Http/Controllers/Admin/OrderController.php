<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('seller')->get();

        return view('admin.orders.index', compact('orders'));
    }
}
