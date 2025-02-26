<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::find($request->product_id);

        if (!$product) {
            return response()->json(['success' => false, 'error' => 'Product not found']);
        }
        if ($product->discount_type != null) {
            if (
                $product->discount_type ==
                \App\Enums\DiscountType::FLAT
            ) {
                $price =
                    $product->selling_price -
                    $product->discount_amount;
            } elseif (
                $product->discount_type ==
                \App\Enums\DiscountType::PERCENTAGE
            ) {
                $price =
                    $product->selling_price -
                    ($product->selling_price *
                        $product->discount_amount) /
                    100;
            }
        } else {
            $price = $product->selling_price;
        }

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $request->quantity ?? 1;
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $request->quantity ?? 1,
                "discount_price" => $price,
                "selling_price" => $product->selling_price,
                "thumbnail" => $product->thumbnail
            ];
        }

        $request->session()->put('cart', $cart);

        return response()->json(['success' => true, 'message' => 'Product added to cart']);
    }

    public function details(Request $request)
    {
        $cart = collect(request()->session()->get('cart'));
        $sub_total = collect($cart)->sum(fn($product) => $product['quantity'] * $product['discount_price']);
        $grand_total = collect($cart)->sum(fn($product) => $product['quantity'] * $product['selling_price']);
        $discount = $grand_total - $sub_total;
        $total_products_count = count($cart);

        // return $sub_total;

        return view('frontend.pages.cart_details', compact('cart', 'grand_total', 'total_products_count', 'sub_total', 'discount'));
    }

    public function update(Request $request)
    {
        $id = $request->product_id;
        $quantity = $request->quantity;

        $cart = $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $quantity;
            $request->session()->put('cart', $cart);

            $cartCollection = collect($cart);
            $subTotal = $cartCollection->sum(fn($product) => $product['quantity'] * $product['discount_price']);
            $grandTotal = $cartCollection->sum(fn($product) => $product['quantity'] * $product['selling_price']);
            $discount = $grandTotal - $subTotal;
            $totalProductsCount = $cartCollection->count();

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
        $id = $request->product_id;
        $cart = $request->session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            $request->session()->put('cart', $cart);
            return response()->json(['success' => true, 'message' => 'Product removed from cart']);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }
}
