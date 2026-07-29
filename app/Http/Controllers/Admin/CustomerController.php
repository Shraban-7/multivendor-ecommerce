<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('country');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $customers = $query->latest()->paginate(25);

        return view('admin.customers.index', compact('customers'));
    }

    public function profile(User $customer)
    {
        $orders = Order::with('items')->where('user_id', $customer->id)->get();

        $data = [];
        $data['total_orders'] = $orders->count();
        $data['total_spent'] = $orders->sum('total');
        $data['pending_orders'] = Order::pending()->where('user_id', $customer->id)->count();
        $data['shipped_orders'] = Order::shipped()->where('user_id', $customer->id)->count();
        $data['cancelled_orders'] = Order::cancelled()->where('user_id', $customer->id)->count();
        $data['delivered_orders'] = Order::delivered()->where('user_id', $customer->id)->count();

        $data['customer'] = $customer;

        return view('admin.customers.profile', $data);
    }
}
