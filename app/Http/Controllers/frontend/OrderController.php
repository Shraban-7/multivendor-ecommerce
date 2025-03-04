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
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = Order::with('seller')->withCount('items')->where('user_id', Auth::user()->id)->where('tracking_id', '!=', null);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders = $query->paginate(10);

        return view('frontend.orders.index', compact('orders', 'status'));
    }

    public function details(Order $order)
    {
        $order->load('items.product');
        return view('frontend.orders.details', compact('order'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('GET')) {
            $customer_addresses = CustomerAddress::where('user_id', $user->id)->get();
            return view('frontend.pages.checkout', compact('user', 'customer_addresses'));
        }

        $carts = Cart::where('user_id', $user->id)
            ->with('cartItems.product')
            ->get()
            ->groupBy(fn($cart) => $cart->cartItems->first()->product->seller_id ?? null);

        $selectedSellers = $carts->keys();

        CustomerAddress::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => $request->type,
            ],
            [
                'address' => $request->address
            ]
        );

        $orders = $carts->filter(fn($cartGroup, $sellerId) => $selectedSellers->contains($sellerId))
            ->map(function ($cartGroup, $sellerId) use ($user, $request) {
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
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'customer_address' => $request->address,
                    'tracking_id' => 'TRK-' . strtoupper(uniqid()),
                    'sub_total' => $subtotal,
                    'total' => $subtotal,
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

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully!',
            'orders' => $orders,
        ]);
    }

    public function success()
    {
        return view('frontend.orders.success');
    }

    public function tracking($tracking_id)
    {
        $order = Order::withCount('items')->where('tracking_id', $tracking_id)->first();
        return view('frontend.orders.tracking', compact('order'));
    }
}
