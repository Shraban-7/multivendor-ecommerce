<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $carts = Cart::query()
            ->where('user_id', Auth::id())
            ->with('cart_items.product.category', 'cart_items.product.subcategory', 'seller')
            ->get();

        return apiResourceResponse(CartResource::collection($carts));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required|exists:products,id',
            'option_ids' => 'nullable|array',
            'quantity'   => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user_id = Auth::id();

        $product = Product::find($request->product_id);

        $option_ids = collect($request->option_ids)->sort()->values()->toArray();

        $cart = Cart::query()->firstOrCreate([
            'user_id'   => $user_id,
            'seller_id' => $product->seller_id,
        ]);

        $price = (float) $request->price;
        $price = ($price <= 0) ? $product->discounted_price : number_format($price, 2, '.', '');

        $cartItem = null;

        if (! empty($option_ids)) {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->get()
                ->first(function ($item) use ($option_ids) {
                    $dbOptionIds = collect($item->product_variant_ids)->sort()->values()->toArray();
                    return $dbOptionIds === $option_ids;
                });
        } else {
            $cartItem = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->whereJsonLength('product_variant_ids', 0)
                ->first();
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $request->quantity + $cartItem->quantity,
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id'             => $cart->id,
                'product_id'          => $product->id,
                'quantity'            => $request->quantity ?? 1,
                'price'               => $price,
                'product_variant_ids' => $option_ids,
            ]);
        }

        Wishlist::where([
            'user_id'    => $user_id,
            'product_id' => $product->id,
        ])->delete();

        return apiResponse([
            'cart_count' => Cart::getCount($user_id)
        ], "Added to cart successfully");
    }

    public function deleteItem(CartItem $item)
    {
        $item->delete();

        return apiResponse([
            'cart_count' => Cart::getCount()
        ], "Item removed successfully");
    }

    public function updateQuantity(CartItem $item, Request $request)
    {
        $validator = validateRequest($request, [
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $item->update([
            'quantity' => $request->quantity
        ]);

        return apiResponse([
            'cart_count' => Cart::getCount()
        ], "Cart updated successfully");
    }
}
