<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\ProductListResource;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
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

        $cartProductIds = [];
        $categoryIds = [];
        $subcategoryIds = [];

        foreach ($carts as $cart) {
            foreach ($cart->cart_items as $item) {
                $cartProductIds[] = $item->product->id;
                $categoryIds[] = $item->product->category->id ?? null;
                $subcategoryIds[] = $item->product->subcategory->id ?? null;
            }
        }

        $categoryIds = array_filter(array_unique($categoryIds));
        $subcategoryIds = array_filter(array_unique($subcategoryIds));

        $suggestedProducts = Product::query()
            ->whereNotIn('id', $cartProductIds)
            ->where(function ($query) use ($categoryIds, $subcategoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereIn('subcategory_id', $subcategoryIds);
            })
            ->with('category', 'subcategory')
            ->inRandomOrder()
            ->take(10)
            ->get();

        $data['carts'] = CartResource::collection($carts);

        $data['products'] = ProductListResource::collection($suggestedProducts);

        return apiResponse($data);
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required',
            'variant_id' => 'nullable',
            'quantity' => 'required|integer|min:1',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $variant = $variantId = null;
        $userId = Auth::id();
        $data = $validator->validated();
        $product = Product::find($data['product_id']);
        $isDefault = $request->is_default ?? false;

        if (! $product) {
            return errorResponse('Product not found!');
        }

        if ($isDefault) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('is_default', 1)
                ->first();
        }

        if ($data['variant_id'] != null) {
            $variant = ProductVariant::find($data['variant_id']);
            if (! $variant) {
                return errorResponse('Variant not found!');
            }
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'seller_id' => $product->seller_id],
        );

        if (!is_null($variant)) {
            $price = $variant->discounted_price ?? $variant->selling_price;
            $variantId = $variant->id;
        } else {
            $price = $product->discounted_price ?? $product->selling_price;
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $data['quantity']);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        return apiResponse([
            'cart_count' => Cart::getCount($userId),
        ], "Added to cart successfully");
    }

    public function deleteItem(CartItem $item)
    {
        $cart = $item->cart;
        
        $item->delete();

        if ($cart->cart_items()->count() === 0) {
            $cart->delete();
        }

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
