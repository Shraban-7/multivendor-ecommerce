<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use App\Enums\DiscountType;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('GET')) {
            return view('frontend.pages.checkout', compact('user'));
        }

        $carts = Cart::where('user_id', $user->id)
            ->with('cartItems.product')
            ->get()
            ->groupBy(fn($cart) => $cart->cartItems->first()->product->seller_id ?? null);

        $selectedSellers = $carts->keys();

        CustomerAddress::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'address' => $request->address
        ]);

        $orders = $carts->filter(fn($cartGroup, $sellerId) => $selectedSellers->contains($sellerId))
            ->map(function ($cartGroup, $sellerId) use ($user) {
                $seller = Seller::find($sellerId);

                $discountPrice = function ($product) {
                    if ($product->discount_type === DiscountType::FLAT) {
                        return $product->selling_price - $product->discount_amount;
                    } elseif ($product->discount_type === DiscountType::PERCENTAGE) {
                        return $product->selling_price - ($product->selling_price * $product->discount_amount) / 100;
                    }
                    return $product->selling_price;
                };

                $subtotal = $cartGroup->sum(fn($cart) => $cart->cartItems->sum(fn($item) => $item->quantity * $discountPrice($item->product)));

                $discount = $cartGroup->sum(fn($cart) => $cart->cartItems->sum(fn($item) => $item->quantity * ($item->product->selling_price - $discountPrice($item->product))));
                $shippingFee = $seller->shipping_cost ?? 0;

                $order = Order::create([
                    'user_id' => $user->id,
                    'seller_id' => $sellerId,
                    'tracking_id' => 'TRK-' . strtoupper(uniqid()),
                    'sub_total' => $subtotal,
                    'discount' => $discount,
                    'tax' => 0,
                    'shipping_fee' => $shippingFee,
                    'payable' => $subtotal + $shippingFee,
                    'due' => $subtotal + $shippingFee,
                    'status' => 1
                ]);

                foreach ($cartGroup as $cart) {
                    foreach ($cart->cartItems as $item) {
                        $product = Product::find($item->product_id);

                        $unitPrice = $discountPrice($item->product);

                        $order->items()->create([
                            'product_id' => $item->product_id,
                            'product_variant' => $item->variant ?? null,
                            'product_variant_price' => $item->product->selling_price,
                            'buying_price' => $product->buying_price ?? 0,
                            'unit_price' => $unitPrice,
                            'quantity' => $item->quantity,
                            'discount' => $item->quantity * ($item->product->selling_price - $unitPrice),
                            'sub_total' => $item->quantity * $unitPrice
                        ]);

                        $product->decrement('stock_in', $item->quantity);
                        $product->increment('stock_out', $item->quantity);
                    }

                    $cart->cartItems()->delete();
                    $cart->delete();
                }

                return $order;
            });

       return redirect()->route('order.success')->with('Order placed successfully');
    }

}
