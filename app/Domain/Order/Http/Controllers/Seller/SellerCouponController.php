<?php

namespace App\Domain\Order\Http\Controllers\Seller;

use App\Domain\Order\Models\Coupon;
use App\Domain\Product\Models\Product;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerCouponController extends Controller
{
    public function index()
    {
        $seller = seller();
        $coupons = Coupon::with('products')
            ->where('seller_id', $seller->id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('seller.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $seller = seller();
        $products = Product::where('seller_id', $seller->id)
            ->active()
            ->select('id', 'name')
            ->get();

        return view('seller.coupons.create', compact('products'));
    }

    public function store(Request $request)
    {
        $seller = seller();

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
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id,seller_id,' . $seller->id,
        ]);

        $data['seller_id'] = $seller->id;
        $data['status'] = true;
        $data['used_count'] = 0;

        $products = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $coupon = Coupon::create($data);

        if (!empty($products)) {
            $coupon->products()->attach($products);
        }

        return redirect()->route('seller.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $seller = seller();
        if ($coupon->seller_id !== $seller->id) {
            abort(403);
        }

        $products = Product::where('seller_id', $seller->id)
            ->active()
            ->select('id', 'name')
            ->get();

        $coupon->load('products');

        return view('seller.coupons.edit', compact('coupon', 'products'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $seller = seller();
        if ($coupon->seller_id !== $seller->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'nullable|string|max:255',
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:flat,percentage',
            'discount_value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
            'description' => 'nullable|string|max:1000',
            'status' => 'nullable|boolean',
            'product_ids' => 'nullable|array',
            'product_ids.*' => 'integer|exists:products,id,seller_id,' . $seller->id,
        ]);

        $products = $data['product_ids'] ?? [];
        unset($data['product_ids']);

        $data['status'] = $request->boolean('status');

        $coupon->update($data);
        $coupon->products()->sync($products);

        return redirect()->route('seller.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $seller = seller();
        if ($coupon->seller_id !== $seller->id) {
            abort(403);
        }

        $coupon->delete();

        return redirect()->route('seller.coupons.index')
            ->with('success', 'Coupon deleted.');
    }
}
