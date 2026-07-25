<?php

namespace App\Http\Controllers\Api\Admin;

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
            $q = $request->search;
            $query->where(function ($w) use ($q) {
                $w->where('name', 'like', "%{$q}%")
                  ->orWhere('phone', 'like', "%{$q}%")
                  ->orWhere('email', 'like', "%{$q}%");
            });
        }

        $customers = $query->latest()->paginate($request->input('limit', 25));

        return apiResourceResponse($customers->through(fn ($c) => [
            'id' => $c->id,
            'name' => $c->name,
            'username' => $c->username,
            'email' => $c->email,
            'phone' => $c->phone,
            'avatar' => $c->avatar,
            'country' => $c->country?->name,
            'role' => $c->role,
            'email_verified_at' => $c->email_verified_at,
            'created_at' => $c->created_at,
        ]));
    }

    public function update(Request $request)
    {
        $validator = validateRequest($request, [
            'id' => 'required|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,'.$request->id,
            'phone' => 'sometimes|string|max:20',
            'role' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user = User::findOrFail($request->id);
        $user->update($request->only(['name', 'email', 'phone', 'role']));

        return successResponse('Customer updated successfully.');
    }

    public function profile(User $customer)
    {
        $orders = Order::with(['items.product', 'seller'])->where('user_id', $customer->id)->get();

        return apiResponse([
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'username' => $customer->username,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'avatar' => $customer->avatar,
                'role' => $customer->role,
                'email_verified_at' => $customer->email_verified_at,
                'created_at' => $customer->created_at,
            ],
            'stats' => [
                'total_orders' => $orders->count(),
                'total_spent' => (float) $orders->sum('total'),
                'pending_orders' => $orders->where('status', 0)->count(),
                'delivered_orders' => $orders->where('status', 5)->count(),
                'cancelled_orders' => $orders->where('status', 3)->count(),
            ],
            'recent_orders' => $orders->take(5)->map(fn ($o) => [
                'invoice_id' => $o->invoice_id,
                'seller' => $o->seller?->business_name,
                'total' => (float) $o->total,
                'status' => $o->status_label,
                'created_at' => $o->created_at,
            ]),
        ]);
    }
}
