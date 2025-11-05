<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required',
            'variant_id' => 'nullable|integer',
            'quantity'   => 'required|integer|min:1',
            'is_default' => 'nullable|boolean',
        ]);

        $variant   = ProductVariant::find($data['variant_id']);
        $userId    = Auth::id();
        $product   = Product::find($data['product_id']);

        if (! $product) {
            return response()->json(['success' => false, 'error' => 'Product not found']);
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'seller_id' => $product->seller_id],
        );

        if (!empty($variant)) {
            $price = $variant->discounted_price ?? $variant->selling_price;
        } else {
            $price = $product->discounted_price ?? $product->selling_price;
        }
        
        $variantId = $variant->id ?? null;

        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_variant_id', $variantId)->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $data['quantity']);
        } else {
            CartItem::create([
                'cart_id'            => $cart->id,
                'product_id'         => $product->id,
                'quantity'           => $data['quantity'],
                'price'              => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'action'  => 'add_to_cart',
        ]);
    }

    public function details(Request $request)
    {
        $categoryIds = $subcategoryIds =  $brandIds = $addedItemIds = [];

        $carts = Cart::where('user_id', Auth::id())
            ->with('cart_items.product', 'cart_items.variant')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->cart_items->first()->product->seller_id ?? null;
            });

        $grand_total = 0;
        $sub_total   = 0;

        foreach ($carts as $seller_id => $cartGroup) {
            $seller = Seller::find($seller_id);
            foreach ($cartGroup as $cart) {
                foreach ($cart->cart_items as $item) {
                    $quantity = $item->quantity;
                    $base_price = $item->original_price;
                    $discounted_price = $item->discounted_price;
                    $sub_total += $base_price * $quantity;
                    $grand_total += $discounted_price * $quantity;

                    $addedItemIds[] = $item->product->id;
                    if (!is_null($item->product->category_id)) $categoryIds[] = $item->product->category_id;
                    if (!is_null($item->product->subcategory_id)) $subcategoryIds[] = $item->product->subcategory_id;
                    if (!is_null($item->product->brand_id)) $brandIds[] = $item->product->brand_id;
                }
            }
        }

        $similarProducts = Product::query()
            ->withDefaultRelations()
            ->whereNotIn('id', $addedItemIds)
            ->where(function ($query) use ($categoryIds, $subcategoryIds, $brandIds) {
                $query->when(!empty($categoryIds), fn($q) => $q->orWhereIn('category_id', $categoryIds))
                    ->when(!empty($subcategoryIds), fn($q) => $q->orWhereIn('subcategory_id', $subcategoryIds))
                    ->when(!empty($brandIds), fn($q) => $q->orWhereIn('brand_id', $brandIds));
            })
            ->latest('id')
            ->limit(50)
            ->get()
            ->sortByDesc(function ($product) use ($categoryIds, $subcategoryIds, $brandIds) {
                $score = 0;
                if (in_array($product->subcategory_id, $subcategoryIds ?? [])) $score += 3;
                if (in_array($product->category_id, $categoryIds ?? [])) $score += 2;
                if (in_array($product->brand_id, $brandIds ?? [])) $score += 1;
                return $score;
            })
            ->take(16)
            ->values();

        $discount = $sub_total - $grand_total;

        $total_products_count = $carts->flatten()->pluck('cart_items')->flatten()->count();

        $products = $similarProducts->map(fn($product) => $product->toDetailsArray());

        return view('frontend.cart.details', compact('carts', 'grand_total', 'total_products_count', 'sub_total', 'discount', 'products'));
    }

    public function update(Request $request)
    {
        $cartItem = CartItem::where('id', $request->id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);

            $subTotal = CartItem::where('cart_id', $request->cart_id)
                ->with('product', 'variant')
                ->get()
                ->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

            $grandTotal = CartItem::where('cart_id', $request->cart_id)
                ->with('product', 'variant')
                ->get()
                ->sum(function ($item) {
                    return $item->quantity * $item->product_original_price;
                });

            $discount           = $grandTotal - $subTotal;
            $totalProductsCount = CartItem::where('cart_id', $request->cart_id)->count();

            return response()->json([
                'success'              => true,
                'message'              => 'Cart updated successfully',
                'order_subtotal'       => number_format($subTotal, 2),
                'order_total'          => number_format($grandTotal, 2),
                'discount'             => number_format($discount, 2),
                'total_products_count' => $totalProductsCount,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    public function delete(Request $request)
    {
        $cartItem = CartItem::where('id', $request->id)->first();

        $cartId = $cartItem->cart_id;

        if ($cartItem) {
            $cartItem->delete();
            $remainingItems = CartItem::where('cart_id', $cartId)->count();

            if ($remainingItems === 0) {
                Cart::where('id', $cartId)->delete();
            }

            return response()->json(['success' => true, 'message' => 'Product removed from cart']);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    public function getLiveCartData()
    {
        $cartCount   = 0;
        $sub_total   = 0;
        $grand_total = 0;

        if (Auth::check()) {
            $carts = Cart::where('user_id', Auth::id())
                ->with('cart_items.product')
                ->get();

            foreach ($carts as $cart) {
                foreach ($cart->cart_items as $item) {
                    $item_total = $item->quantity * $item->price;
                    $sub_total += $item_total;
                    $grand_total += $item_total;
                    $cartCount++;
                }
            }
        } else {
            $carts = collect();
        }

        return response()->json([
            'cartCount'  => $cartCount,
            'totalPrice' => money($grand_total),
        ]);
    }
}
