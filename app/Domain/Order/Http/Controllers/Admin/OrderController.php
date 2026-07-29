<?php

namespace App\Domain\Order\Http\Controllers\Admin;

use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['seller', 'billing_address', 'user', 'items']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('invoice_id', 'like', "%{$search}%");
        }

        $orders = $query->latest()->paginate(25);

        return view('admin.orders.index', compact('orders'));
    }
}
