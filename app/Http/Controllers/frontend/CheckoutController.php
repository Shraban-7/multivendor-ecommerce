<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CustomerAddress;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cart = collect(session()->get('cart', []));

        $groupedCart = $cart->groupBy('seller_id')->map(function ($items) {
            $seller = Seller::find($items->first()['seller_id']);

            return [
                'seller' => $seller,
                'items' => $items,
                'subtotal' => $items->sum(fn($item) => $item['quantity'] * $item['discount_price']),
                'total' => $items->sum(fn($item) => $item['quantity'] * $item['selling_price']),
                'discount' => $items->sum(fn($item) => $item['quantity'] * ($item['selling_price'] - $item['discount_price'])),
                'item_count' => $items->sum('quantity')
            ];
        });

        $sub_total = $groupedCart->sum('subtotal');
        $grand_total = $groupedCart->sum('total');
        $discount = $groupedCart->sum('discount');
        $total_products_count = $groupedCart->sum('item_count');

        return view('frontend.pages.checkout', compact('user', 'groupedCart', 'sub_total', 'grand_total', 'discount', 'total_products_count'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = collect(session()->get('cart', []));
        $selectedSellers = collect($request->seller_ids);


        CustomerAddress::create([
            'user_id'=>$user->id,
            'type' => $request->type,
            'address' => $request->address
        ]);

        $orders = $cart->groupBy('seller_id')
        ->filter(fn($items, $sellerId) => $selectedSellers->contains($sellerId))
            ->map(function ($items, $sellerId) use ($request, $user) {
                $seller = Seller::find($sellerId);
                $subtotal = $items->sum(fn($item) => $item['quantity'] * $item['discount_price']);
                $discount = $items->sum(fn($item) => $item['quantity'] * ($item['selling_price'] - $item['discount_price']));
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

                foreach ($items as $item) {
                    $product = Product::find($item['id']);

                    $order->items()->create([
                        'product_id' => $item['id'],
                        'product_variant' => $item['variant'] ?? null,
                        'product_variant_price' => $item['selling_price'],
                        'buying_price' => $product->buying_price ?? 0,
                        'unit_price' => $item['discount_price'],
                        'quantity' => $item['quantity'],
                        'discount' => $item['quantity'] * ($item['selling_price'] - $item['discount_price']),
                        'sub_total' => $item['quantity'] * $item['discount_price']
                    ]);

                    $product->decrement('stock', $item['quantity']);
                }

                return $order;
            });

        session()->put('cart', $cart->reject(fn($item) => $selectedSellers->contains($item['seller_id']))->toArray());

        return response()->json([
            'success' => true,
            'message' => 'Orders placed successfully',
            'orders' => $orders->pluck('tracking_id')
        ]);
    }
}
