<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $seller = Seller::find(get_seller_id());
        $tab = $request->get('tab', 'pos');

        $customerName = $request->get('customer_name');
        $customerPhone = $request->get('customer_phone');

        $customers = Customer::where('seller_id', $seller->id)
            ->when($customerName, function ($query, $customerName) {
                $query->where('name', 'like', "%{$customerName}%");
            })
            ->when($customerPhone, function ($query, $customerPhone) {
                $query->orWhere('phone', 'like', "%{$customerPhone}%");
            })
            ->paginate(25)
            ->appends($request->all());

        $orders = Order::where('seller_id', $seller->id)->get();
        $userIds = $orders->pluck('user_id')->unique();

        $users = User::with('country')
            ->whereIn('id', $userIds)
            ->when($customerName, function ($query, $customerName) {
                $query->where('name', 'like', "%{$customerName}%");
            })
            ->when($customerPhone, function ($query, $customerPhone) {
                $query->orWhere('phone', 'like', "%{$customerPhone}%");
            })
            ->paginate(25)
            ->appends($request->all());

        return view('seller.customers.index', compact('customers', 'users', 'tab', 'customerName', 'customerPhone'));
    }
}
