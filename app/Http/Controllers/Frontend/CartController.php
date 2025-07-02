<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $userId     = Auth::id();
        $productId  = $request->product_id;
        $variantId = $request->variant_id;
        $quantity   = (int) ($request->quantity ?? 1);
        $optionIds  = collect($request->option_ids)->sort()->values()->toArray();

        $product = Product::find($productId);

        if (! $product) {
            return response()->json(['success' => false, 'error' => 'Product not found']);
        }

        if ($variantId) {
            $variant = ProductVariant::find($variantId);
            if (! $variant) {
                return response()->json(['success' => false, 'error' => 'Variant not found']);
            }
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'seller_id' => $product->seller_id],
            ['user_id' => $userId, 'seller_id' => $product->seller_id]
        );

        $price = floatval($request->price);

        $price = number_format($price, 2, '.', '');

        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'cart_id'             => $cart->id,
                'product_id'          => $productId,
                'quantity'            => $quantity,
                'price'               => $price,
                'product_variant_id' => $variant->id ?? null,
            ]);
        }

        Wishlist::where([
            'user_id'    => $userId,
            'product_id' => $productId,
        ])->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'action'  => 'add_to_cart',
        ]);
    }

    public function details(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->with('cart_items.product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->cart_items->first()->product->seller_id ?? null;
            });

        $grand_total = 0;
        $sub_total   = 0;

        foreach ($carts as $seller_id => $cartGroup) {
            foreach ($cartGroup as $cart) {
                foreach ($cart->cart_items as $item) {
                    $item_grand_total = $item->quantity * $item->product_original_price;
                    $grand_total += $item_grand_total;
                    $itemPrice      = $item->quantity * $item->price;
                    $item_sub_total = $itemPrice;
                    $sub_total += $item_sub_total;
                }
            }
        }

        $discount             = $grand_total - $sub_total;
        $total_products_count = $carts->flatten()->pluck('cart_items')->flatten()->count();

        $interest_products = Product::latest()->limit(6)->get();

        $products = $interest_products->map(fn($product) => $product->toDetailsArray());

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

            $updatedPrice = money($cartItem->price * $cartItem->quantity);

            return response()->json([
                'success'              => true,
                'message'              => 'Cart updated successfully',
                'updatedPrice'         => $updatedPrice,
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
