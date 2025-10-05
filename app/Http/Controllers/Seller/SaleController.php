<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Order;
use App\Models\Seller;
use App\Models\PosCart;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\PosCartItem;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('seller_id', get_seller_id())
            ->whereNull('user_id');

        if ($request->filled('invoice_id')) {
            $orders->where('invoice_id', 'like', '%' . $request->invoice_id . '%');
        }

        if ($request->filled('customer_name')) {
            $orders->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->customer_name . '%');
            });
        }

        if ($request->filled('customer_phone')) {
            $orders->whereHas('customer', function ($q) use ($request) {
                $q->where('phone', 'like', '%' . $request->customer_phone . '%');
            });
        }

        if ($request->filled('date_from')) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $orders->latest()->paginate(25);

        return view('seller.pos.orders', compact('orders'));
    }

    // public function update(Request $request)
    // {
    //     $orderId = $request->input('order_id', $request->query('order_id'));
    //     $seller = Seller::find(get_seller_id());
    //     $data = $request->validate([
    //         'customer_name' => 'nullable|string|max:255|required_with:customer_phone',
    //         'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
    //         'paid' => 'nullable|numeric',
    //         'due' => 'nullable|numeric',
    //         'discount' => 'nullable'
    //     ]);

    //     $order = Order::where('invoice_id', $orderId)
    //         ->where('seller_id', get_seller_id())
    //         ->with('items.variant.product')
    //         ->first();

    //     if (!$order) {
    //         return errorResponse("Order not found!");
    //     }

    //     $orderItems = $order->items;
    //     if ($orderItems->isEmpty()) {
    //         return errorResponse("No items found in the order!");
    //     }

    //     $cart = PosCart::where('seller_id', get_seller_id())->first();

    //     if (!$cart) {
    //         return errorResponse("No items found in the cart!");
    //     }

    //     $cartItems = $cart->items()->with('variant.product')->get();

    //     $vat_amount = 0;
    //     $sub_total = 0;
    //     $discount = 0;
    //     $updatedVariants = [];

    //     // foreach ($orderItems as $item) {
    //     //     $variant = $item->variant;
    //     //     $product = $variant->product;

    //     //     $discount_amount = ($variant->discounted_price && $variant->discounted_price != 0)
    //     //         ? $variant->selling_price - $variant->discounted_price
    //     //         : 0;

    //     //     $unitPrice = $item->unit_price;
    //     //     $itemSubtotal = $item->sub_total;
    //     //     $itemDiscount = $item->discount;
    //     //     $itemTotal = $item->total;

    //     //     $vat_amount += ($product->vat_percent * $unitPrice / 100) * $item->quantity;
    //     //     $sub_total += $itemSubtotal;
    //     //     $discount += $itemDiscount;

    //     //     $diff = $item->quantity - $item->getOriginal('quantity');
    //     //     if ($variant && $diff != 0) {
    //     //         $variant->increment('stock_out', $diff);
    //     //         $updatedVariants[] = [
    //     //             'id' => $variant->id,
    //     //             'availableStock' => $variant->availableStock,
    //     //         ];
    //     //     }

    //     //     $item->update([
    //     //         'quantity' => $item->quantity,
    //     //         'unit_price' => $unitPrice,
    //     //         'selling_price' => $item->selling_price,
    //     //         'discount' => $itemDiscount,
    //     //         'sub_total' => $itemSubtotal,
    //     //         'total' => $itemTotal,
    //     //         'vat_amount' => ($product->vat_percent * $unitPrice / 100) * $item->quantity,
    //     //     ]);
    //     // }

    //     $totalVat = 0;
    //     $sub_total = 0;
    //     $total = 0;
    //     $discount = 0;
    //     $orderItems = [];

    //     foreach ($cartItems as $item) {
    //         $product = $item->variant->product;
    //         $variant = $item->variant;

    //         $unitPrice = $variant->calculatedPrice;
    //         $itemTotal = $item->quantity * $unitPrice;
    //         $itemSubtotal = $item->quantity * $variant->selling_price;

    //         $discountAmount = $variant->calculatedDiscount;
    //         $itemDiscount = $item->quantity * ($discountAmount);
    //         $vatAmount = calculate_vat($product->vat_percent, $unitPrice) * $item->quantity;
    //         $totalVat += $vatAmount;

    //         $sub_total += $variant->selling_price * $item->quantity;
    //         $discount += $itemDiscount;

    //         $orderItems[] = [
    //             'product_id' => $product->id,
    //             'product_variant_id' => $item->product_variant_id ?? null,
    //             'sku' => $variant->sku,
    //             'product_name' => $product->name,
    //             'variant_name' => $variant->fullName,
    //             'buying_price' => $variant->buying_price,
    //             'selling_price' => $variant->selling_price,
    //             'unit_price' => $unitPrice,
    //             'quantity' => $item->quantity,
    //             'discount' => $itemDiscount,
    //             'sub_total' => $itemSubtotal,
    //             'total' => $itemTotal,
    //             'vat_percent' => $product->vat_percent,
    //             'vat_amount' => $vatAmount
    //         ];
    //     }

    //     if (empty($orderItems)) {
    //         return errorResponse("No items found in the cart!");
    //     }

    //     $total_commission = 0;

    //     if ($seller->commission_amount != null && $seller->commission_type != null) {
    //         if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
    //             $total_commission = ($sub_total + $totalVat) * ($seller->commission_amount / 100);
    //         } else if ($seller->commission_type === CommissionType::FLAT->value) {
    //             $total_commission = $seller->commission_amount;
    //         }
    //     }

    //     $total = ($sub_total + $totalVat) - ($discount + $data['discount']);
    //     $payableAmount = $total;
    //     $sellerEarning = $payableAmount - $total_commission;

    //     $invoiceId = Order::generateInvoiceID($seller->id, Order::ORDER_TYPE_POS);

    //     $total_discount = (float) $data['discount'] + $discount;
    //     $payableAmount = $sub_total + $vat_amount - $total_discount;

    //     $paid = $data['paid'] + $order->paid;
    //     $due = $payableAmount - $paid;
    //     $sellerEarning = $payableAmount - $total_commission;

    //     $order->update([
    //         'sub_total' => $sub_total+$order->subtotal,
    //         'total' => $payableAmount+$order->total,
    //         'discount' => $total_discount+$order->discount,
    //         'vat_amount' => $vat_amount+$order->vat_amount,
    //         'payable' => $payableAmount + $order->total,
    //         'paid' => $paid,
    //         'due' => $due,
    //         'commission_type' => $seller->commission_type,
    //         'commission_amount' => $seller->commission_amount,
    //         'seller_earnings' => $sellerEarning,
    //         'total_commission' => $total_commission,
    //     ]);

    //     if (!empty($data['customer_name']) || !empty($data['customer_phone'])) {
    //         $customer = Customer::where('name', $data['customer_name'])->where('phone', $data['customer_phone'])->first();

    //         if ($customer) {
    //             $order->update([
    //                 'customer_id' => $customer->id
    //             ]);
    //         }
    //     }

    //     $html = view('components.seller.pos-order-items', [
    //         'orderItems' => $order->items()->with('variant.product')->get(),
    //     ])->render();

    //     return apiResponse([
    //         'invoice_id' => $order->invoice_id,
    //         'variants' => $updatedVariants,
    //         'html' => $html,
    //         'subtotal' => $sub_total,
    //         'vat_amount' => $vat_amount,
    //         'discount' => $discount,
    //         'total' => $payableAmount,
    //         'due' => $due
    //     ], "Order Updated Successfully");
    // }

    public function update(Request $request)
    {
        $orderId = $request->input('order_id', $request->query('order_id'));
        $seller = Seller::find(get_seller_id());

        $data = $request->validate([
            'customer_name'  => 'nullable|string|max:255|required_with:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
            'paid'           => 'nullable|numeric',
            'due'            => 'nullable|numeric',
            'discount'       => 'nullable|numeric',
        ]);

        $order = Order::where('invoice_id', $orderId)
            ->where('seller_id', get_seller_id())
            ->with('items.variant.product')
            ->first();

        if (!$order) {
            return errorResponse("Order not found!");
        }

        $cart = PosCart::where('seller_id', get_seller_id())->first();
        if (!$cart) {
            return errorResponse("No items found in the cart!");
        }

        $cartItems = $cart->items()->with('variant.product')->get();
        if ($cartItems->isEmpty()) {
            return errorResponse("No items found in the cart!");
        }

        // Existing order totals
        $existingSubTotal   = (float) $order->sub_total;
        $existingVat        = (float) $order->vat_amount;
        $existingDiscount   = (float) $order->discount;
        $existingTotal      = (float) $order->total;
        $existingPaid       = (float) $order->paid;
        $existingDue        = (float) $order->due;

        // Start new totals
        $newSubTotal = 0;
        $newVatAmount = 0;
        $newDiscount = 0;
        $newTotal = 0;

        foreach ($cartItems as $cartItem) {
            $variant = $cartItem->variant;
            $product = $variant->product;

            $sellingPrice = (float) $variant->selling_price;
            $unitPrice = (float) ($variant->discounted_price ?? $sellingPrice);
            $discountAmount = $sellingPrice - $unitPrice;

            $quantity = (int) $cartItem->quantity;

            $itemSubtotal = $sellingPrice * $quantity;
            $itemDiscount = $discountAmount * $quantity;
            $itemVat = ($product->vat_percent * $unitPrice / 100) * $quantity;
            $itemTotal = ($unitPrice * $quantity) + $itemVat;

            // ✅ Check if same variant exists in the order
            $existingItem = $order->items()
                ->where('product_variant_id', $variant->id)
                ->where('unit_price', $unitPrice) // if price changed, treat as new item
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity'    => $existingItem->quantity + $quantity,
                    'sub_total'   => $existingItem->sub_total + $itemSubtotal,
                    'discount'    => $existingItem->discount + $itemDiscount,
                    'vat_amount'  => $existingItem->vat_amount + $itemVat,
                    'total'       => $existingItem->total + $itemTotal,
                ]);
            } else {
                $order->items()->create([
                    'product_id'          => $product->id,
                    'product_variant_id'  => $variant->id,
                    'sku'                 => $variant->sku,
                    'product_name'        => $product->name,
                    'variant_name'        => $variant->fullName,
                    'buying_price'        => $variant->buying_price,
                    'selling_price'       => $sellingPrice,
                    'unit_price'          => $unitPrice,
                    'quantity'            => $quantity,
                    'discount'            => $itemDiscount,
                    'sub_total'           => $itemSubtotal,
                    'total'               => $itemTotal,
                    'vat_percent'         => $product->vat_percent,
                    'vat_amount'          => $itemVat,
                ]);
            }

            $newSubTotal += $itemSubtotal;
            $newVatAmount += $itemVat;
            $newDiscount += $itemDiscount;
        }

        // ✅ Calculate updated totals
        $combinedSubTotal = $existingSubTotal + $newSubTotal;
        $combinedVat = $existingVat + $newVatAmount;
        $combinedDiscount = $existingDiscount + $newDiscount + ($data['discount'] ?? 0);

        $payableAmount = ($combinedSubTotal + $combinedVat) - $combinedDiscount;

        $paid = ($data['paid'] ?? 0) + $existingPaid;
        $due = max(0, $payableAmount - $paid);

        // ✅ Commission & earnings
        $total_commission = 0;
        if ($seller->commission_type && $seller->commission_amount !== null) {
            if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = $payableAmount * ($seller->commission_amount / 100);
            } else {
                $total_commission = $seller->commission_amount;
            }
        }

        $sellerEarning = $payableAmount - $total_commission;

        // ✅ Update order totals
        $order->update([
            'sub_total'         => $combinedSubTotal,
            'vat_amount'        => $combinedVat,
            'discount'          => $combinedDiscount,
            'total'             => $payableAmount,
            'payable'           => $payableAmount,
            'paid'              => $paid,
            'due'               => $due,
            'commission_type'   => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'total_commission'  => $total_commission,
            'seller_earnings'   => $sellerEarning,
        ]);

        // ✅ Optional: update customer
        if (!empty($data['customer_name']) && !empty($data['customer_phone'])) {
            $customer = Customer::firstOrCreate([
                'name' => $data['customer_name'],
                'phone' => $data['customer_phone']
            ]);
            $order->update(['customer_id' => $customer->id]);
        }

        // ✅ Clear cart after merging
        $cart->items()->delete();

        // ✅ Render updated order items view
        $html = view('components.seller.pos-order-items', [
            'orderItems' => $order->items()->with('variant.product')->get(),
        ])->render();

        return apiResponse([
            'invoice_id'  => $order->invoice_id,
            'html'        => $html,
            'subtotal'    => $combinedSubTotal,
            'vat_amount'  => $combinedVat,
            'discount'    => $combinedDiscount,
            'total'       => $payableAmount,
            'paid'        => $paid,
            'due'         => $due,
        ], "Order updated successfully");
    }


    public function delete($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $orderItems = $order->items;

        foreach ($orderItems as $item) {
            $variant = ProductVariant::find($item->product_variant_id);
            if ($variant) {
                $variant->decrement('stock_out', $item->quantity);
            }
        }

        $order->delete();

        return successResponse("Order Deleted Successfully!");
    }


    public function itemAdd(Request $request)
    {
        $data = $request->validate([
            'order_id'   => 'required',
            'variant_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $order = Order::where('invoice_id', $data['order_id'])
            ->where('seller_id', get_seller_id())
            ->with('items.variant.product')
            ->first();

        if (!$order) {
            return errorResponse("Order not found!");
        }

        $variant = ProductVariant::find($data['variant_id']);
        $product = $variant->product;

        if (!$variant) {
            return errorResponse("Variant not found!");
        }

        $unitPrice = $variant->discounted_price ?? $variant->selling_price;

        // $orderItem = $order->items()->where('product_variant_id', $data['variant_id'])->first();
        $orderItem = $order->items()
            ->where('product_variant_id', $variant->id)
            ->where('unit_price', $unitPrice)
            ->first();

        $cart = PosCart::create([
            'seller_id' => get_seller_id(),
            'order_id' => $order->id
        ]);

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

        $cartSubtotal = $cartItems->sum(fn($item) => $item->variant->selling_price * $item->quantity);
        $cart_vat_amount = $cartItems->sum(fn($item) => ($item->variant->product->vat_percent * $item->price / 100) * $item->quantity);
        $cartDiscount = $cartItems->sum(fn($item) => ($item->variant->discounted_price ? $item->variant->selling_price - $item->variant->discounted_price : 0) * $item->quantity);
        $cartTotal = $cartSubtotal + $cart_vat_amount - $cartDiscount;


        // if ($orderItem && request()->has('order_id')) {
        //     $orderItem->update([
        //         'sku' => $variant->sku,
        //         'product_name' => $product->name,
        //         'variant_name' => $variant->fullName,
        //         'quantity' => $orderItem->quantity + $data['quantity'],
        //         'buying_price' => $orderItem->buying_price,
        //         'selling_price' => $orderItem->selling_price,
        //         'unit_price' => $orderItem->unit_price,
        //         'sub_total' => ($orderItem->quantity + $data['quantity']) * $orderItem->selling_price,
        //         'total' => ($orderItem->quantity + $data['quantity']) * $orderItem->unit_price,
        //         'discount' => ($variant->selling_price - $unitPrice) * ($data['quantity']),
        //         'vat_amount' => ($variant->product->vat_percent * $unitPrice / 100) * ($data['quantity']),
        //     ]);
        // } else {
        //     $order->items()->create([
        //         'product_id' => $variant->product_id,
        //         'product_variant_id' => $variant->id,
        //         'sku' => $variant->sku,
        //         'product_name' => $product->name,
        //         'variant_name' => $variant->fullName,
        //         'quantity' => $data['quantity'],
        //         'buying_price' => $variant->buying_price,
        //         'selling_price' => $variant->selling_price,
        //         'unit_price' => $unitPrice,
        //         'sub_total' => $data['quantity'] * $variant->selling_price,
        //         'total' => $data['quantity'] * $unitPrice,
        //         'discount' => ($variant->selling_price - $unitPrice) * $data['quantity'],
        //         'vat_amount' => ($variant->product->vat_percent * $unitPrice / 100) * $data['quantity'],
        //         'buying_price' => $variant->buying_price,
        //     ]);
        // }

        $orderItems = $order->items()->with('variant.product')->get();

        // $subtotal = $orderItems->sum(fn($item) => $item->subtotal);
        // $vat_amount = $orderItems->sum(fn($item) => $item->vat_amount);
        // $discount = $orderItems->sum(fn($item) => $item->discount);
        // // $total = $subtotal + $vat_amount - $discount;
        // $total = $orderItems->sum(fn($item) => $item->total);

        $subtotal = $order->sub_total + $cartSubtotal;
        $vat_amount = $order->vat_amount + $cart_vat_amount;
        $discount = $order->discount + $cartDiscount;
        $total = $order->total + $cartTotal;
        $due = $total - $order->paid;

        // dd($subtotal, $vat_amount, $discount, $total);

        $variant->stock_out += $data['quantity'];
        $variant->save();

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
            'discount' => $discount,
            'total' => $total,
            'due' => $due,
        ], "Product added to order");
    }

    public function itemUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'action' => 'required|string|in:increase,decrease',
            'order_id' => 'required'
        ]);

        $item = OrderItem::find($request->id);
        if (!$item) return errorResponse("Order item not found");

        $variant = $item->variant;
        if (!$variant) return errorResponse("Product variant not found");

        if ($request->action === 'increase') {
            $item->quantity += 1;
            $variant->increment('stock_out', 1);
            $item->sub_total = $item->unit_price * $item->quantity;
            $item->discount = $item->variant->discount_amount * $item->quantity;
            $item->save();
            $variant->save();
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
            $variant->decrement('stock_out', 1);
            $item->sub_total = $item->unit_price * $item->quantity;
            $item->discount = $item->variant->discount_amount * $item->quantity;
            $item->save();
            $variant->save();
        }

        // $item->save();
        // $variant->save();

        $orderItems = $item->order->items()->with('variant.product')->get();

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        $subtotal = $orderItems->sum(fn($i) => $i->original_price * $i->quantity);
        $vat_amount = $orderItems->sum(fn($i) => ($i->vat_percent * $i->unit_price / 100) * $i->quantity);
        $discount = $orderItems->sum(fn($i) => $i->discount);
        $total = $subtotal - $discount + $vat_amount;
        $due = $total;

        $item->order->due = $due;
        $item->order->paid = $total - $due;
        $item->order->save();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
            'discount' => $discount,
            'total' => $total,
            'due' => $due,
        ], "Order item updated successfully");
    }

    public function itemRemove(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'order_id' => 'required'
        ]);

        $item = OrderItem::find($request->id);
        if (!$item) return errorResponse("Order item not found", 404);

        $variant = ProductVariant::find($item->product_variant_id);

        $variant_quantity = $item->quantity;

        $order = $item->order;
        $item->delete();

        $variant->decrement('stock_out');

        $orderItems = $order->items()->with('variant.product')->get();

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        $subtotal = $orderItems->sum(fn($i) => $i->unit_price * $i->quantity);
        $vat_amount = $orderItems->sum(fn($i) => ($i->vat_percent * $i->unit_price / 100) * $i->quantity);
        $discount = $orderItems->sum(fn($i) => $i->discount);
        $total = $subtotal - $discount + $vat_amount;
        $due = $total - $item->order->paid;

        if ($orderItems->count() == 0) {
            $order->delete();

            return apiResponse([
                'html' => $html,
                'subtotal' => $subtotal,
                'vat_amount' => $vat_amount,
                'discount' => $discount,
                'total' => $total,
                'due' => $due,
                'redirect' => route('seller.pos.index'),
            ], "Order item removed successfully");
        }

        return apiResponse([
            'html' => $html,
            'subtotal' => $subtotal,
            'vat_amount' => $vat_amount,
            'discount' => $discount,
            'total' => $total,
        ], "Order item removed successfully");
    }

    public function pay(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0'
        ]);

        if ($validated['amount'] > $order->due) {
            return successResponse('Payment amount cannot be greater than remaining due.');
        }

        $order->paid += $validated['amount'];
        $order->due -= $validated['amount'];
        $order->save();

        return successResponse('Payment submitted successfully');
    }
}
