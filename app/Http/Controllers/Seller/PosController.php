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
        $vat_amount = 0;
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
                ->with('items.variant.product')
                ->first();

            if ($order) {
                $orderItems = $order ? $order->items()->with('variant.product')->get() : collect();
                $cart = PosCart::where('order_id', $order->id)->first();
                $cartItems = $cart ? $cart->items()->with('variant.product')->get() : collect();
                $orderItems = $orderItems->merge($cartItems);

                $cartSubtotal = $cartItems->sum(fn($item) => $item->variant->selling_price * $item->quantity);
                $cart_vat_amount = $cartItems->sum(fn($item) => ($item->variant->product->vat_percent * $item->price / 100) * $item->quantity);
                $cartDiscount = $cartItems->sum(fn($item) => ($item->variant->discounted_price ? $item->variant->selling_price - $item->variant->discounted_price : 0) * $item->quantity);
                $cartTotal = $cartSubtotal + $cart_vat_amount - $cartDiscount;

                $subtotal = $order->sub_total + $cartSubtotal;
                $vat_amount = $order->vat_amount + $cart_vat_amount;
                $discount = $order->discount + $cartDiscount;

                $total = $order->total + $cartTotal;
                $paid = $order->paid;
                $previousPaid = $order->paid;
                $additionalDiscount = $order->additional_discount;

                if ($total > $paid) {
                    $due = $total - $order->paid;
                } else {
                    $due = $order->due;
                }

                if ($order->customer_id) {
                    $customer_name = $order->customer->name;
                    $customer_phone = $order->customer->phone;
                }
            }
        }
        if (request()->has('draft_cart_id')) {
            $cart = PosCart::where('seller_id', $seller->id)
                ->where('id', request('draft_cart_id'))
                ->where('is_draft', 1)
                ->with('items.variant.product')
                ->first();

            $cartItems = $cart ? $cart->items : collect();

            foreach ($cartItems as $item) {
                $unitPrice = $item->variant->calculatedPrice;
                $subtotal += $item->variant->selling_price * $item->quantity;
                $vat_amount += calculate_vat($item->variant->product->vat_percent, $unitPrice) * $item->quantity;
                $discount += ($item->variant->calculatedDiscount * $item->quantity);
            }

            $total = $subtotal + $vat_amount - $discount;
        } else {
            $cart = PosCart::where('seller_id', $seller->id)->where('is_draft', 0)->whereNull('order_id')->first();
            $cartItems = $cart ? $cart->items()->with('variant.product')->get() : collect();
            foreach ($cartItems as $item) {
                $unitPrice = $item->variant->calculatedPrice;
                $subtotal += $item->variant->selling_price * $item->quantity;
                $vat_amount += calculate_vat($item->variant->product->vat_percent, $unitPrice);
                $discount += ($item->variant->calculatedDiscount * $item->quantity);
            }
            $total = $subtotal + $vat_amount - $discount;
        }

        $orders = Order::where('seller_id', $seller->id)
            ->whereDate('created_at', Carbon::today())
            ->with('items.variant.product')
            ->latest('id')
            ->get();

        $draftCarts = PosCart::where('seller_id', $seller->id)
            ->where('is_draft', true)
            ->with(['items.variant.product'])
            ->latest()
            ->get();

        return view('seller.pos.index', compact(
            'products',
            'categories',
            'cartItems',
            'orderItems',
            'subtotal',
            'discount',
            'vat_amount',
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
        $data = $request->validate([
            'variant_id' => 'nullable',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = PosCart::firstOrCreate(
            [
                'seller_id' => get_seller_id(),
                'order_id' => null,
                'is_draft' => 0
            ],
        );

        $variantId = $data['variant_id'];
        $variant = ProductVariant::find($variantId);
        $price = $variant->discounted_price ?? $variant->selling_price;

        $cartItem = PosCartItem::where('pos_cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            PosCartItem::create([
                'pos_cart_id' => $cart->id,
                'quantity' => $data['quantity'],
                'price' => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        $cartItems = $cart->items()->with('variant.product')->get();

        $subtotal = $cartItems->sum(fn($item) => $item->variant->selling_price * $item->quantity);
        $vat_amount = $cartItems->sum(fn($item) => ($item->variant->product->vat_percent * $item->price / 100) * $item->quantity);
        $discount = $cartItems->sum(fn($item) => ($item->variant->discounted_price ? $item->variant->selling_price - $item->variant->discounted_price : 0) * $item->quantity);
        $total = $subtotal + $vat_amount - $discount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
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

        $item = PosCartItem::find($request->id);
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
        $cartItems = $cart->items()->with('variant.product')->get();

        $subtotal = $cartItems->sum(fn($i) => $i->variant->selling_price * $i->quantity);
        $vat_amount = $cartItems->sum(fn($i) => ($i->variant->product->vat_percent * $i->price / 100) * $i->quantity);
        $discount = $cartItems->sum(fn($i) => ($i->variant->selling_price - $i->price) * $i->quantity);
        $total = $subtotal - $discount + $vat_amount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
            'discount' => $discount,
            'total' => $total,
            'due' => $total,
            'cart_items' => $cartItems
        ], "Cart Updated Successfully");
    }

    public function removeCartItem(Request $request)
    {
        $itemId = $request->id;
        $item = PosCartItem::find($itemId);

        if (!$item) {
            return errorResponse("Item not found", 404);
        }

        $cart = $item->pos_cart;

        if (!$cart) {
            return errorResponse("Cart not found", 404);
        }

        $item->delete();

        $cartItems = $cart->items()->with('variant.product')->get();

        $subtotal = $cartItems->sum(fn($item) => $item->variant->selling_price * $item->quantity);
        $vat_amount = $cartItems->sum(fn($item) => ($item->variant->product->vat_percent * $item->price / 100) * $item->quantity);
        $discount = $cartItems->sum(fn($item) => ($item->variant->discounted_price ? $item->variant->selling_price - $item->variant->discounted_price : 0) * $item->quantity);
        $total = $subtotal + $vat_amount - $discount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
            'discount' => $discount,
            'total' => $total,
            'due' => $total,
            'cart_items' => $cartItems
        ], "Item Remove From Cart Successfully!");
    }

    public function cartClear(Request $request)
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

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name'  => 'nullable|string|max:255|required_with:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
            'paid' => 'required|numeric',
            'due' => 'nullable|numeric',
            'discount' => 'nullable'
        ]);

        $seller = Seller::find(get_seller_id());

        $data['seller_id'] = $seller->id;

        $cart = PosCart::where('seller_id', get_seller_id())->first();

        if (!$cart) {
            return errorResponse("No items found in the cart!");
        }

        $cartItems = $cart->items()->with('variant.product')->get();

        $totalVat = 0;
        $sub_total = 0;
        $total = 0;
        $discount = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = $item->variant->product;
            $variant = $item->variant;

            $unitPrice = $variant->calculatedPrice;
            $itemTotal = $item->quantity * $unitPrice;
            $itemSubtotal = $item->quantity * $variant->selling_price;

            $discountAmount = $variant->calculatedDiscount;
            $itemDiscount = $item->quantity * ($discountAmount);
            $vatAmount = calculate_vat($product->vat_percent, $unitPrice) * $item->quantity;
            $totalVat += $vatAmount;

            $sub_total += $variant->selling_price * $item->quantity;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $item->product_variant_id ?? null,
                'sku' => $variant->sku,
                'product_name' => $product->name,
                'variant_name' => $variant->fullName,
                'buying_price' => $variant->buying_price,
                'selling_price' => $variant->selling_price,
                'unit_price' => $unitPrice,
                'quantity' => $item->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemSubtotal,
                'total' => $itemTotal,
                'vat_percent' => $product->vat_percent,
                'vat_amount' => $vatAmount
            ];
        }

        if (empty($orderItems)) {
            return errorResponse("No items found in the cart!");
        }


        $total = ($sub_total + $totalVat) - ($discount + $data['discount']);
        $payableAmount = $total;

        $commissionData = $seller->calculateEarning($payableAmount, $totalVat);

        $total_commission = $commissionData['total_commission'];
        $sellerEarning = $commissionData['seller_earning'];

        $invoiceId = Order::generateInvoiceID($seller->id, Order::ORDER_TYPE_POS);

        $orderData = [
            'seller_id' => $seller->id,
            'seller_employee_id' => $employee->id ??  null,
            'invoice_id' => $invoiceId,
            'sub_total' => $sub_total,
            'vat_amount' => $totalVat,
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
            'delivery_status' => OrderStatus::ORDER_PLACED->value,
        ];

        $order = Order::create($orderData);

        $order->items()->createMany($orderItems);

        $updatedVariants = [];

        foreach ($order->items as $item) {
            if (isset($item['product_variant_id'])) {
                $variant = ProductVariant::find($item['product_variant_id']);

                if ($variant) {
                    $variant->increment('stock_out', $item['quantity']);

                    $updatedVariants[] = [
                        'id' => $variant->id,
                        'availableStock' => $variant->availableStock,
                    ];
                }
            }
        }

        $cart->items()->delete();
        $cart->delete();

        $sellerOrderIds = Order::where('seller_id', $seller->id)->pluck('id');
        $sellerOrderCount = OrderItem::whereIn('order_id', $sellerOrderIds)->count();

        $seller->update(['total_sold' => $sellerOrderCount]);

        $order->update(['status' => OrderStatus::DELIVERED->value]);
        $order->addSellerEarningToBalance();

        if (!empty($data['customer_name']) || !empty($data['customer_phone'])) {

            $exist_customer = Customer::where('name', $data['customer_name'] ?? null)
                ->where('phone', $data['customer_phone'] ?? null)
                ->first();

            if (!$exist_customer) {
                $customer = Customer::create([
                    'name'  => $data['customer_name'] ?? null,
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
