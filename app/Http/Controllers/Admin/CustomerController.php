<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = User::with('country')->get();

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
