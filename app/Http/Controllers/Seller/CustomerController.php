<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $seller = Seller::where('id', Auth::guard('seller')->user()->id)->first();
        $orders = Order::where('seller_id', $seller->id)->get();
        $userIds = $orders->pluck('user_id')->unique();

        $users = User::with('country')->whereIn('id', $userIds)->get();

        return view('seller.customers.index', compact('users'));
    }
}
