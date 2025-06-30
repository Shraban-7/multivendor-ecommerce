<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
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
        $userId     = Auth::id();
        $productId  = $request->product_id;
        $variantSku = $request->variant_sku;
        $quantity   = (int) ($request->quantity ?? 1);
        $optionIds  = collect($request->option_ids)->sort()->values()->toArray();

        $product = Product::find($productId);

        if (! $product) {
            return response()->json(['success' => false, 'error' => 'Product not found']);
        }

        if ($variantSku) {
            $variant = ProductVariant::where('sku', $variantSku)->first();
            if (! $variant) {
                return response()->json(['success' => false, 'error' => 'Variant not found']);
            }
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'seller_id' => $product->seller_id],
            ['user_id' => $userId, 'seller_id' => $product->seller_id]
        );

        $price = floatval($request->price);
        if ($quantity > 1) {
            $price = $price / $quantity;
        }
        if ($price <= 0) {
            $price = $product->discounted_price;
        }
        $price = number_format($price, 2, '.', '');

        $cartItemQuery = CartItem::where('cart_id', $cart->id)->where('product_id', $productId);

        if (! empty($optionIds)) {
            $cartItems = $cartItemQuery->get();
            $cartItem  = $cartItems->first(function ($item) use ($optionIds) {
                return collect($item->product_variant_ids)->sort()->values()->toArray() === $optionIds;
            });
        } else {
            $cartItem = $cartItemQuery->whereJsonLength('product_variant_ids', 0)->first();
        }

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'cart_id'             => $cart->id,
                'product_id'          => $productId,
                'quantity'            => $quantity,
                'price'               => $price,
                'product_variant_ids' => $optionIds,
            ]);
        }

        Wishlist::where([
            'user_id'    => $userId,
            'product_id' => $productId,
        ])->delete();

        return apiResponse([
            'cart_count' => Cart::getCount($userId),
        ], "Added to cart successfully");
    }

    public function deleteItem(CartItem $item)
    {
        $item->delete();

        return apiResponse([
            'cart_count' => Cart::getCount(),
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
            'quantity' => $request->quantity,
        ]);

        return apiResponse([
            'cart_count' => Cart::getCount(),
        ], "Cart updated successfully");
    }
}
