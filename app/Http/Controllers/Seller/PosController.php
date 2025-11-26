<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Seller;
use App\Models\PosCart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\PosCartItem;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Models\SellerEmployee;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $seller = Seller::find(get_seller_id());

        $products = Product::where('seller_id', $seller->id)
            ->with('variants.option_values', 'unit')
            ->get();

        $categories = Category::category()->get();

        $cartItems = collect();
        $orderItems = collect();
        $subtotal = 0;
        $discount = 0;
        $total = 0;
        $customer_name = null;
        $customer_phone = null;
        $paid = null;
        $due = null;
        $previousPaid = 0;
        $additionalDiscount = 0;

        if ($request->has('order_id')) {

            $order = Order::where('invoice_id', $request->order_id)
                ->where('seller_id', $seller->id)
                ->with(['items.variant.product', 'items.product'])
                ->first();

            if ($order) {
                $orderItems = $order->items()->with(['variant.product', 'product'])->get();

                $cart = PosCart::where('order_id', $order->id)->first();

                $cartItems = $cart ? $cart->items()->with(['variant.product', 'product'])->get() : collect();

                $orderItems = $orderItems->merge($cartItems);

                $cartSubtotal = $cartItems->sum(function ($item) {
                    $selling = $item->variant->selling_price ?? $item->product->selling_price;
                    return $selling * $item->quantity;
                });

                $cartDiscount = $cartItems->sum(function ($item) {
                    $selling = $item->variant->selling_price ?? $item->product->selling_price;
                    $discounted = $item->variant->discounted_price ?? $item->product->discounted_price ?? $selling;
                    return ($selling - $discounted) * $item->quantity;
                });

                $cartTotal = $cartSubtotal - $cartDiscount;

                $orderItemSubtotal = $order->items->sum('sub_total');
                $subtotal = $orderItemSubtotal + $cartSubtotal;

                $discount = $order->discount + $cartDiscount;

                $total = $order->total + $cartTotal;

                $paid = $order->paid;
                $previousPaid = $order->paid;
                $additionalDiscount = $order->additional_discount;

                $due = ($total > $paid) ? $total - $paid : $order->due;

                if ($order->customer_id) {
                    $customer_name = $order->customer->name;
                    $customer_phone = $order->customer->phone;
                }
            }
        }

        if (request()->has('draft_id')) {

            $cart = PosCart::where('seller_id', $seller->id)
                ->where('id', request('draft_id'))
                ->where('is_draft', 1)
                ->with(['items.variant.product', 'items.product'])
                ->first();

            $cartItems = $cart ? $cart->items : collect();

            foreach ($cartItems as $item) {

                $product = $item->variant->product ?? $item->product;
                $selling = $item->variant->selling_price ?? $item->product->selling_price;
                $discounted = $item->variant->discounted_price ?? $item->product->discounted_price ?? $selling;

                $subtotal += ($selling * $item->quantity);
                $discount += (($selling - $discounted) * $item->quantity);
            }

            $total = $subtotal - $discount;
        } else {

            $cart = PosCart::where('seller_id', $seller->id)
                ->where('is_draft', 0)
                ->whereNull('order_id')
                ->first();

            $cartItems = $cart ? $cart->items()->with(['variant.product', 'product'])->get() : collect();

            foreach ($cartItems as $item) {
                $product = $item->variant->product ?? $item->product;

                $selling = $item->variant->selling_price ?? $item->product->selling_price;
                $discounted = $item->variant->discounted_price ?? $item->product->discounted_price ?? $selling;

                $itemDiscount = $selling - $discounted;

                $subtotal += ($selling * $item->quantity);
                $discount += ($itemDiscount * $item->quantity);
            }

            $total = $subtotal - $discount;
        }

        $orders = Order::where('seller_id', $seller->id)
            ->whereDate('created_at', Carbon::today())
            ->with(['items.variant.product', 'items.product'])
            ->latest('id')
            ->get();

        $draftCarts = PosCart::where('seller_id', $seller->id)
            ->where('is_draft', true)
            ->with(['items.variant.product', 'items.product'])
            ->latest()
            ->get();

        return view('seller.pos.index', compact(
            'products',
            'categories',
            'cartItems',
            'orderItems',
            'subtotal',
            'discount',
            'total',
            'orders',
            'due',
            'paid',
            'customer_name',
            'customer_phone',
            'previousPaid',
            'additionalDiscount',
            'draftCarts'
        ));
    }

    public function cartAdd(Request $request)
    {
        $draftId = $request->draft_id;
        $data = $request->validate([
            'product_id' => 'required',
            'variant_id' => 'nullable',
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = PosCart::firstOrCreate(
            [
                'seller_id' => get_seller_id(),
                'order_id' => null,
                'is_draft' => $draftId ? 1 : 0
            ],
        );

        $product = Product::find($data['product_id']);

        $variant = ProductVariant::find($data['variant_id']);

        if (!empty($variant)) {
            $price = $variant->discounted_price ?? $variant->selling_price;
        } else {
            $price = $product->discounted_price ?? $product->selling_price;
        }

        $variantId = $variant->id ?? null;

        $cartItem = PosCartItem::where('pos_cart_id', $cart->id)->where('product_variant_id', $variantId)->where('product_id', $product->id)->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            PosCartItem::create([
                'pos_cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        $cartItems = $cart->items()->with(['variant.product', 'product'])->get();

        $subtotal = $cartItems->sum(function ($item) {
            $price = $item->variant->selling_price ?? $item->product->selling_price;
            return $price * $item->quantity;
        });

        $discount = $cartItems->sum(function ($item) {
            $variantDiscount = $item->variant->discounted_price ?? null;
            $productDiscount = $item->product->discounted_price ?? null;

            $selling = $item->variant->selling_price ?? $item->product->selling_price;
            $discounted = $variantDiscount ?? $productDiscount;

            return $discounted ? ($selling - $discounted) * $item->quantity : 0;
        });

        $total = $subtotal - $discount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'due' => $total,
            'cart_items' => $cartItems
        ], "Product added to cart");
    }

    public function cartUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|string|in:increase,decrease',
        ]);

        $item = PosCartItem::with(['variant.product', 'product'])->find($request->id);

        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        if ($request->action === 'increase') {
            $item->quantity += 1;
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }

        $item->save();

        $cart = $item->pos_cart;
        $cartItems = $cart->items()->with(['variant.product', 'product'])->get();

        $subtotal = $cartItems->sum(function ($i) {

            $selling = $i->variant->selling_price ?? $i->product->selling_price;
            return $selling * $i->quantity;
        });

        $discount = $cartItems->sum(function ($i) {

            $selling = $i->variant->selling_price ?? $i->product->selling_price;
            $discounted = $i->variant->discounted_price ?? $i->product->discounted_price ?? $selling;

            return ($selling - $discounted) * $i->quantity;
        });

        $total = $subtotal - $discount;


        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'due' => $total,
            'cart_items' => $cartItems
        ], "Cart Updated Successfully");
    }

    public function removeCartItem(Request $request)
    {
        $itemId = $request->id;

        $item = PosCartItem::with(['variant.product', 'product'])->find($itemId);

        if (!$item) {
            return errorResponse("Item not found", 404);
        }

        $cart = $item->pos_cart;

        if (!$cart) {
            return errorResponse("Cart not found", 404);
        }

        $item->delete();

        $cartItems = $cart->items()->with(['variant.product', 'product'])->get();

        $subtotal = $cartItems->sum(function ($i) {

            $selling = $i->variant->selling_price ?? $i->product->selling_price;
            return $selling * $i->quantity;
        });

        $discount = $cartItems->sum(function ($i) {

            $selling = $i->variant->selling_price ?? $i->product->selling_price;
            $discounted = $i->variant->discounted_price ?? $i->product->discounted_price ?? $selling;

            return ($selling - $discounted) * $i->quantity;
        });

        $total = $subtotal - $discount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'due' => $total,
            'cart_items' => $cartItems
        ], "Item Removed From Cart Successfully!");
    }

    public function cartClear()
    {
        $cart = PosCart::where('seller_id', get_seller_id())->first();

        if (!$cart) {
            return errorResponse("No Cart Items Found!");
        }

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return successResponse("Cart Clear Successfully");
    }

    public function draftClear(PosCart $draft)
    {
        $cart = PosCart::where('seller_id', get_seller_id())->where('id', $draft->id)
            ->where('is_draft', 1)->first();

        if (!$cart) {
            return errorResponse("No Cart Items Found!");
        }

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return successResponse("Draft Clear Successfully");
    }

    public function placeOrder(Request $request)
    {
        $draftId = $request->draft_id;
        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255|required_with:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
            'paid' => 'required|numeric',
            'due' => 'nullable|numeric',
            'discount' => 'nullable',
            'items' => 'required|array',
        ]);

        $seller = Seller::find(get_seller_id());

        $employee = SellerEmployee::find(auth()->guard('employee')->id());

        $data['seller_id'] = $seller->id;

        $cart = PosCart::where('seller_id', get_seller_id())->where('is_draft',0)->first();
        if (!is_null($draftId)) {
            $cart = PosCart::where('seller_id', get_seller_id())->where('is_draft',1)->first();
        }

        if (!$cart) {
            return errorResponse("No items found in the cart!");
        }

        $cartItems = $cart->items()->with('variant.product')->get();

        $sub_total = 0;
        $total = 0;
        $discount = 0;
        $orderItems = [];

        $itemsCollection = collect($request->items);

        foreach ($cartItems as $item) {

            $variant = $item->variant;
            $product = $variant->product ?? $item->product;

            $itemPrice = $variant->selling_price ?? $product->selling_price;

            $unitPrice = $itemsCollection->firstWhere('id', $item->id)['price'];

            $itemDiscount = $itemPrice > $unitPrice ? ($itemPrice - $unitPrice) : 0;
            $itemDiscount = $itemDiscount * $item->quantity;
            $discount += $itemDiscount;

            $itemTotal = $item->quantity * $unitPrice;
            $itemSubtotal = $item->quantity * $itemPrice;

            $sub_total += $itemPrice * $item->quantity;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id ?? null,
                'sku' => $variant->sku ?? $product->sku,
                'product_name' => $product->name,
                'variant_name' => $variant->fullName ?? null,
                'buying_price' => $variant->buying_price ?? $product->buying_price,
                'selling_price' => $itemPrice,
                'unit_price' => $unitPrice,
                'quantity' => $item->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemSubtotal,
                'total' => $itemTotal,
            ];
        }

        if (empty($orderItems)) {
            return errorResponse("No items found in the cart!");
        }

        $total = $sub_total - ($discount + $data['discount']);
        $payableAmount = $total;

        $commissionData = $seller->calculateEarning($payableAmount);

        $total_commission = $commissionData['total_commission'];
        $sellerEarning = $commissionData['seller_earning'];

        $invoiceId = Order::generateInvoiceID($seller->id, Order::ORDER_TYPE_POS);

        $orderData = [
            'seller_id' => $seller->id,
            'seller_employee_id' => $employee->id ?? null,
            'invoice_id' => $invoiceId,
            'sub_total' => $sub_total,
            'discount' => $data['discount'] + $discount,
            'additional_discount' => $data['discount'],
            'total' => $total,
            'payable' => $payableAmount,
            'paid' => $data['paid'],
            'due' => $data['due'],
            'commission_type' => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'seller_earnings' => $sellerEarning,
            'total_commission' => $total_commission,
            'status' => OrderStatus::PENDING->value,
        ];

        $order = Order::create($orderData);

        $order->items()->createMany($orderItems);

        $updatedVariants = [];

        $updatedVariants = [];

        foreach ($order->items as $item) {
            if (!empty($item->product_variant_id)) {

                $variant = ProductVariant::find($item->product_variant_id);

                if ($variant) {
                    $variant->increment('stock_out', $item->quantity);
                    $variant->product->increment('stock_out', $item->quantity);

                    $updatedVariants[] = [
                        'id' => $variant->id,
                        'availableStock' => $variant->availableStock,
                    ];
                }

                continue;
            }

            $product = Product::find($item->product_id);

            if ($product) {

                $product->increment('stock_out', $item->quantity);

                $updatedVariants[] = [
                    'id' => $product->id,
                    'availableStock' => $product->availableStock ?? null,
                ];
            }
        }


        $cart->items()->delete();
        $cart->delete();

        $sellerOrderIds = Order::where('seller_id', $seller->id)->pluck('id');
        $sellerOrderCount = OrderItem::whereIn('order_id', $sellerOrderIds)->count();

        $seller->update(['total_sold' => $sellerOrderCount]);

        $order->update(['status' => OrderStatus::COMPLETED->value]);
        $order->addSellerEarningToBalance();

        if (!empty($data['customer_name']) || !empty($data['customer_phone'])) {

            $exist_customer = Customer::where('name', $data['customer_name'] ?? null)
                ->where('phone', $data['customer_phone'] ?? null)
                ->first();

            if (!$exist_customer) {
                $customer = Customer::create([
                    'name' => $data['customer_name'] ?? null,
                    'phone' => $data['customer_phone'] ?? null,
                    'seller_id' => $seller->id
                ]);
            } else {
                $customer = $exist_customer;
            }

            $order->update([
                'customer_id' => $customer->id
            ]);
        } else {
            $order->update([
                'customer_id' => null
            ]);
        }

        return apiResponse([
            'invoice_id' => $order->invoice_id,
            'variants' => $updatedVariants
        ], "Order Placed Successfully");
    }

    public function saveDraft(Request $request)
    {
        $sellerId = get_seller_id();

        $cart = PosCart::with('items.variant.product')
            ->where('seller_id', $sellerId)
            ->whereNull('order_id')
            ->where('is_draft', 0)
            ->first();

        if (!$cart || $cart->items->isEmpty()) {
            return errorResponse("Cart is empty!");
        }

        $cart->update([
            'is_draft' => true,
        ]);

        $cartItems = $cart->items;
        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'cart_items' => $cartItems,
            'is_draft' => $cart->is_draft,
        ], "Draft saved successfully");
    }

    public function customerSearch(Request $request)
    {
        $term = $request->get('term', '');
        $customers = Customer::where('name', 'LIKE', "%{$term}%")
            ->orWhere('phone', 'LIKE', "%{$term}%")
            ->take(10)
            ->get();

        $results = [];
        foreach ($customers as $c) {
            $results[] = [
                'label' => $c->name . ' (' . $c->phone . ')',
                'value' => $c->name,
                'phone' => $c->phone
            ];
        }

        return response()->json($results);
    }
}
