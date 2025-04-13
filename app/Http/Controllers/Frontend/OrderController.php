<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\CustomerAddress;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $statusLabel = (string) $request->input('status', 'all');

        $statusValue = OrderStatus::valueFromLabel($statusLabel);

        $query = Order::with('seller')->withCount('items')
            ->where('user_id', Auth::user()->id)
            ->where('invoice_id', '!=', null);

        if ($statusLabel !== 'all') {
            $query->where('status', $statusValue);
        }

        $orders = $query->paginate(10);

        $interest_products = Product::with(['category.subcategories', 'images', 'seller', 'productAttributes.options'])->inRandomOrder()->limit(6)->get();

        $products = [];

        foreach ($interest_products as  $product) {
            $products[] = [
                'id' => $product->id,
                'slug' => $product->slug,
                'name' => $product->name,
                'image' => storage_url($product->thumbnail),
                'sold_out' => number_shorten_format($product->stock_out),
                'price' => number_format($product->selling_price)
            ];
        }

        $products = collect($products);

        return view('frontend.orders.index', [
            'orders' => $orders,
            'status' => $statusLabel,
            'products' => $products
        ]);
    }

    public function details(Order $order)
    {
        $order->load('items.product');

        return view('frontend.orders.details', compact('order'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $selectedSellerId = $request->input('seller_id');

        $cart = Cart::where('user_id', $user->id)
            ->where('seller_id', $selectedSellerId)
            ->with('cartItems.product')
            ->first();

        if ($request->isMethod('GET')) {
            $customer_addresses = CustomerAddress::where('user_id', $user->id)->get();
            return view('frontend.pages.checkout', compact('user', 'customer_addresses', 'selectedSellerId'));
        }

        if (!$cart) {
            return response()->json([
                'status' => false,
                'message' => 'No cart found for the selected seller.',
            ], 404);
        }

        $discountPrice = function ($product) {
            if ($product->discount_type === DiscountType::PERCENTAGE) {
                return $product->selling_price - ($product->selling_price * $product->discount_amount / 100);
            } elseif ($product->discount_type === DiscountType::FLAT) {
                return $product->selling_price - $product->discount_amount;
            }
            return $product->selling_price;
        };

        $total = 0;
        $discount = 0;
        $shippingFee = 0;
        $tax = 0;
        $orderItems = [];

        foreach ($cart->cartItems as $cartItem) {
            $product = $cartItem->product;

            $unitPrice = $discountPrice($product);
            $itemTotal = $cartItem->quantity * $unitPrice;
            $itemDiscount = $cartItem->quantity * ($product->selling_price - $unitPrice);

            $shippingFee += floatval($product->shipping_cost);
            $tax += floatval($product->tax) * $cartItem->quantity;

            $total += $itemTotal;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant' => null,
                'product_variant_price' => $product->selling_price,
                'buying_price' => $product->buying_price,
                'unit_price' => $unitPrice,
                'quantity' => $cartItem->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemTotal
            ];

            $product->decrement('stock_in', $cartItem->quantity);
            $product->increment('stock_out', $cartItem->quantity);
        }

        $order = Order::create([
            'user_id' => $user->id,
            'seller_id' => $selectedSellerId,
            'customer_name' => $request->input('customer_name', $user->name),
            'customer_email' => $request->input('customer_email', $user->email),
            'customer_phone' => $request->input('customer_phone'),
            'customer_address' => $request->input('address'),
            'invoice_id' => strtoupper(uniqid()),
            'sub_total' => $total,
            'total' => $total - $discount,
            'discount' => $discount,
            'tax' => $tax,
            'shipping_fee' => $shippingFee,
            'payable' => $total + $shippingFee + $tax - $discount,
            'due' => $total + $shippingFee + $tax - $discount,
            'status' => OrderStatus::PENDING->value,
            'delivery_status' => OrderStatus::ORDER_PLACED->value
        ]);

        $order->items()->createMany($orderItems);

        $cart->cartItems()->delete();
        $cart->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order placed successfully!',
            'order' => $order,
        ]);
    }

    public function success(Order $order)
    {
        return view('frontend.orders.success', compact('order'));
    }

    public function tracking($invoice_id)
    {
        $order = Order::withCount('items')->where('invoice_id', $invoice_id)->first();
        return view('frontend.orders.tracking', compact('order'));
    }

    public function review(Order $order, Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('GET')) {
            return view('frontend.orders.review', compact('user', 'order'));
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'required|string'
        ]);


        foreach ($order->items as $item) {
            Review::create([
                'product_id' => $item->product_id,
                'user_id' => $order->user_id,
                'rating' => $request->rating,
                'review_text' => $request->review_text
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Review submitted successfully']);
    }
}
