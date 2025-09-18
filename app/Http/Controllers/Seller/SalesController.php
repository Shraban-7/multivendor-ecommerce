<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\Seller;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class SalesController extends Controller
{
    public function index()
    {
        $orders = Order::where('seller_id', get_seller_id())
            ->whereNull('user_id')
            ->latest('id')
            ->get();

        return view('seller.orders.pos-orders', compact('orders'));
    }

    public function update(Request $request)
    {
        $orderId = $request->input('order_id', $request->query('order_id'));

        $order = Order::where('id', $orderId)
            ->where('seller_id', get_seller_id())
            ->with('items.variant.product')
            ->first();

        if (!$order) {
            return errorResponse("Order not found!");
        }

        $orderItems = $order->items;
        if ($orderItems->isEmpty()) {
            return errorResponse("No items found in the order!");
        }

        $vat_amount = 0;
        $sub_total = 0;
        $discount = 0;
        $updatedVariants = [];

        foreach ($orderItems as $item) {
            $variant = $item->variant;
            $product = $variant->product;

            $discount_amount = ($variant->discounted_price && $variant->discounted_price != 0)
                ? $variant->selling_price - $variant->discounted_price
                : 0;

            $unitPrice = $item->unit_price;
            $itemTotal = $item->quantity * $unitPrice;
            $itemDiscount = $item->quantity * $discount_amount;

            $vat_amount += ($product->vat_percent * $unitPrice / 100) * $item->quantity;
            $sub_total += $itemTotal;
            $discount += $itemDiscount;

            $diff = $item->quantity - $item->getOriginal('quantity'); 
            if ($variant && $diff != 0) {
                $variant->increment('stock_out', $diff); 
                $updatedVariants[] = [
                    'id' => $variant->id,
                    'availableStock' => $variant->availableStock,
                ];
            }

            $item->update([
                'quantity' => $item->quantity,
                'unit_price' => $unitPrice,
                'discount' => $itemDiscount,
                'sub_total' => $itemTotal,
                'vat_amount' => ($product->vat_percent * $unitPrice / 100) * $item->quantity,
            ]);
        }

        $seller = Seller::find(get_seller_id());
        $total_commission = 0;

        if ($seller->commission_amount && $seller->commission_type) {
            if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = ($sub_total + $vat_amount) * ($seller->commission_amount / 100);
            } elseif ($seller->commission_type === CommissionType::FLAT->value) {
                $total_commission = $seller->commission_amount;
            }
        }

        $payableAmount = $sub_total + $vat_amount;
        $sellerEarning = $payableAmount - $total_commission;

        $order->update([
            'sub_total' => $sub_total,
            'total' => $sub_total + $vat_amount,
            'discount' => $discount,
            'vat_amount' => $vat_amount,
            'payable' => $payableAmount,
            'due' => $payableAmount,
            'commission_type' => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'seller_earnings' => $sellerEarning,
            'total_commission' => $total_commission,
        ]);

        $html = view('components.seller.pos-order-items', [
            'orderItems' => $order->items()->with('variant.product')->get(),
        ])->render();

        return apiResponse([
            'invoice_id' => $order->invoice_id,
            'variants' => $updatedVariants,
            'html' => $html,
            'subtotal' => money($sub_total),
            'vat_amount' => money($vat_amount),
            'discount' => money($discount),
            'total' => money($payableAmount),
        ], "Order Updated Successfully");
    }


    public function delete($id)
    {
        $order = Order::find($id);
        $order->delete();

        return successResponse("Order Delete Successfully!");
    }

    public function itemAdd(Request $request)
    {
        $data = $request->validate([
            'order_id'   => 'required|integer',
            'variant_id' => 'required|integer',
            'quantity'   => 'required|integer|min:1',
        ]);

        $order = Order::where('id', $data['order_id'])
            ->where('seller_id', get_seller_id())
            ->with('items.variant.product')
            ->first();

        if (!$order) {
            return errorResponse("Order not found!");
        }

        $variant = ProductVariant::find($data['variant_id']);
        if (!$variant) {
            return errorResponse("Variant not found!");
        }

        $unitPrice = $variant->discounted_price ?? $variant->selling_price;

        $orderItem = $order->items()->where('product_variant_id', $data['variant_id'])->first();

        if ($orderItem) {
            $orderItem->quantity += $data['quantity'];
            $orderItem->unit_price = $unitPrice;
            $orderItem->sub_total = $orderItem->quantity * $unitPrice;
            $orderItem->discount = ($variant->selling_price - $unitPrice) * $orderItem->quantity;
            $orderItem->vat_amount = ($variant->product->vat_percent * $unitPrice / 100) * $orderItem->quantity;
            $orderItem->save();
        } else {
            $order->items()->create([
                'product_id' => $variant->product_id,
                'product_variant_id' => $variant->id,
                'quantity' => $data['quantity'],
                'unit_price' => $unitPrice,
                'sub_total' => $data['quantity'] * $unitPrice,
                'discount' => ($variant->selling_price - $unitPrice) * $data['quantity'],
                'vat_amount' => ($variant->product->vat_percent * $unitPrice / 100) * $data['quantity'],
                'buying_price' => $variant->buying_price,
            ]);
        }

        $orderItems = $order->items()->with('variant.product')->get();

        $subtotal = $orderItems->sum(fn($item) => $item->unit_price * $item->quantity);
        $vat_amount = $orderItems->sum(fn($item) => $item->vat_amount);
        $discount = $orderItems->sum(fn($item) => $item->discount);
        $total = $subtotal + $vat_amount - $discount;

        $variant->stock_out += $data['quantity'];
        $variant->save();

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        return apiResponse([
            'html'       => $html,
            'subtotal'   => money($subtotal),
            'vat_amount' => money($vat_amount),
            'discount'   => money($discount),
            'total'      => money($total),
        ], "Product added to order");
    }

    public function itemUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|string|in:increase,decrease',
            'order_id' => 'required|integer'
        ]);

        $item = OrderItem::find($request->id);
        if (!$item) return errorResponse("Order item not found");

        if ($request->action === 'increase') {
            $item->quantity += 1;
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }
        $item->save();

        $orderItems = $item->order->items()->with('variant.product')->get();

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        $subtotal = $orderItems->sum(fn($i) => $i->unit_price * $i->quantity);
        $vat_amount = $orderItems->sum(fn($i) => ($i->vat_percent * $i->unit_price / 100) * $i->quantity);
        $discount = $orderItems->sum(fn($i) => $i->discount);
        $total = $subtotal - $discount + $vat_amount;

        return apiResponse([
            'html' => $html,
            'subtotal' => money($subtotal),
            'vat_amount' => money($vat_amount),
            'discount' => money($discount),
            'total' => money($total),
        ], "Order item updated successfully");
    }

    public function itemRemove(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'order_id' => 'required|integer'
        ]);

        $item = OrderItem::find($request->id);
        if (!$item) return errorResponse("Order item not found", 404);

        $order = $item->order;
        $item->delete();

        $orderItems = $order->items()->with('variant.product')->get();
        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        $subtotal = $orderItems->sum(fn($i) => $i->unit_price * $i->quantity);
        $vat_amount = $orderItems->sum(fn($i) => ($i->vat_percent * $i->unit_price / 100) * $i->quantity);
        $discount = $orderItems->sum(fn($i) => $i->discount);
        $total = $subtotal - $discount + $vat_amount;

        return apiResponse([
            'html' => $html,
            'subtotal' => money($subtotal),
            'vat_amount' => money($vat_amount),
            'discount' => money($discount),
            'total' => money($total),
        ], "Order item removed successfully");
    }
}
