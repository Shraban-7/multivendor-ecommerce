<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required|exists:products,id',
            'option_ids' => 'nullable|array',
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product = Product::find($request->id);

        //TODO: check product stock

        $option_ids = collect($request->option_ids)->sort()->values()->toArray();

        $cart = Cart::query()->firstOrCreate([
            'user_id' => Auth::id(),
            'seller_id' => $product->id,
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
                ->whereNull('product_variant_ids')
                ->first();
        }

        if ($cartItem) {
            $cartItem->update([
                'quantity' => $request->quantity + $cartItem->quantity,
            ]);
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity ?? 1,
                'price' => $price,
                'product_variant_ids' => $option_ids,
            ]);
        }

        Wishlist::where([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
        ])->delete();

        return successResponse("Added to cart successfully");
    }
}
