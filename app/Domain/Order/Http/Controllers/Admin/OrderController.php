<?php

namespace App\Domain\Order\Http\Controllers\Admin;

use App\Domain\Order\Enums\OrderStatus;
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

        if ($request->filled('status') && OrderStatus::valueFromLabel((string) $request->status) !== null) {
            $query->where('status', (int) $request->status);
        }

        $perPage = (int) $request->query('per_page', 25);
        $orders = $query->latest()->paginate($perPage)->withQueryString();

        $counts = [
            'total'     => Order::count(),
            'pending'   => Order::where('status', OrderStatus::PENDING->value)->count(),
            'accepted'  => Order::where('status', OrderStatus::ACCEPTED->value)->count(),
            'shipped'   => Order::where('status', OrderStatus::SHIPPED->value)->count(),
            'delivered' => Order::where('status', OrderStatus::DELIVERED->value)->count(),
            'cancelled' => Order::where('status', OrderStatus::CANCELLED->value)->count(),
        ];

        return view('admin.orders.index', compact('orders', 'counts'));
    }
}
