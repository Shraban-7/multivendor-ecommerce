<?php

namespace App\Domain\Order\Http\Controllers\Admin;

use App\Domain\Order\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminCouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::with('seller');

        if ($request->filled('type')) {
            if ($request->type === 'global') {
                $query->whereNull('seller_id');
            } elseif ($request->type === 'seller') {
                $query->whereNotNull('seller_id');
            }
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('code', 'like', '%'.$request->search.'%')
                    ->orWhere('title', 'like', '%'.$request->search.'%');
            });
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code',
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->boolean('status', true);
        $data['used_count'] = 0;

        Coupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Global coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $coupon->load('seller');

        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,'.$coupon->id,
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
        ]);

        $data['status'] = $request->boolean('status');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Coupon deleted.');
    }
}
