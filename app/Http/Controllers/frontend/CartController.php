<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Enums\DiscountType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['success' => false, 'error' => 'Product not found']);
        }

        $newCart = Cart::create([
            'user_id' => Auth::user()->id,
            'seller_id' => $product->seller_id
        ]);

        CartItem::create([
            'cart_id' => $newCart->id,
            'product_id' => $product->id,
            'quantity' => $request->quantity ?? 1
        ]);

        return response()->json(['success' => true, 'message' => 'Product added to cart', 'action' => 'add_to_cart']);
    }

    public function details(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)
            ->with('cartItems.product')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->cartItems->first()->product->seller_id ?? null;
            });

        $grand_total = 0;
        $sub_total = 0;

        foreach ($carts as $seller_id => $cartGroup) {
            foreach ($cartGroup as $cart) {
                foreach ($cart->cartItems as $item) {
                    $item_grand_total = $item->quantity * $item->product->selling_price;
                    $grand_total += $item_grand_total;

                    if ($item->product->discount_type != null) {
                        if ($item->product->discount_type == DiscountType::FLAT) {
                            $item_sub_total = $item->quantity * ($item->product->selling_price - $item->product->discount_amount);
                        } elseif ($item->product->discount_type == DiscountType::PERCENTAGE) {
                            $item_sub_total = $item->quantity * ($item->product->selling_price - ($item->product->selling_price * $item->product->discount_amount) / 100);
                        }
                    } else {
                        $item_sub_total = $item_grand_total;
                    }

                    $sub_total += $item_sub_total;
                }
            }
        }

        $discount = $grand_total - $sub_total;
        $total_products_count = $carts->flatten()->pluck('cartItems')->flatten()->count();

        $products = Product::latest()->limit(6)->get();

        return view('frontend.pages.cart_details', compact('carts', 'grand_total', 'total_products_count', 'sub_total', 'discount', 'products'));
    }

    public function update(Request $request)
    {
        $cartItem = CartItem::where('id', $request->id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);

            $subTotal = CartItem::where('cart_id', $request->cart_id)
                ->with('product')
                ->get()
                ->sum(fn($item) => $item->quantity * ($item->product->selling_price - ($item->product->discount_amount ?? 0)));

            $grandTotal = CartItem::where('cart_id', $request->cart_id)
                ->with('product')
                ->get()
                ->sum(fn($item) => $item->quantity * $item->product->selling_price);

            $discount = $grandTotal - $subTotal;
            $totalProductsCount = CartItem::where('cart_id', $request->cart_id)->count();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'order_subtotal' => number_format($subTotal, 2),
                'order_total' => number_format($grandTotal, 2),
                'discount' => number_format($discount, 2),
                'total_products_count' => $totalProductsCount
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    public function delete(Request $request)
    {
        $cartItem = CartItem::where('id', $request->id)->first();

        if ($cartItem) {
            $cartItem->delete();

            $remainingItems = CartItem::where('id', $request->id)->count();
            if ($remainingItems === 0) {
                Cart::where('id', $request->id)->delete();
            }

            return response()->json(['success' => true, 'message' => 'Product removed from cart']);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }
}
