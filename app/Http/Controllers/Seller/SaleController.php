<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Customer;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Models\SellerEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function update(Request $request)
    {
        $orderId = $request->input('order_id', $request->query('order_id'));
        $seller = Seller::find(get_seller_id());
        $employee = SellerEmployee::find(auth()->guard('employee')->id());

        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255|required_with:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
            'paid' => 'nullable|numeric|min:0',
            'due' => 'nullable|numeric|min:0',
            'items' => 'nullable|array',
            'items.*.id' => 'nullable|integer',
            'items.*.price' => 'nullable|numeric|min:0',
            'items.*.quantity' => 'nullable|numeric|min:0',
            'additional_discount' => 'nullable|numeric|min:0',
        ]);

        $order = Order::where('invoice_id', $orderId)
            ->where('seller_id', $seller->id)
            ->with('items.variant.product')
            ->firstOrFail();

        DB::beginTransaction();

        try {
            if (!empty($data['customer_name']) && !empty($data['customer_phone'])) {
                $customer = Customer::firstOrCreate([
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone']
                ]);
                $order->update(['customer_id' => $customer->id]);
            }

            $items = $data['items'] ?? [];
            foreach ($items as $item) {
                $orderItem = $order->items()->find($item['id']);
                if ($orderItem) {
                    $orderItem->update([
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total' => $item['price'] * $item['quantity'],
                        'discount' => max(0, $orderItem->selling_price - $item['price']),
                        'sub_total' => $orderItem->selling_price * $item['quantity'],
                    ]);
                } else {
                    $variant = ProductVariant::find($item['id']);
                    if ($variant) {
                        $order->items()->create([
                            'product_id' => $variant->product_id,
                            'product_variant_id' => $variant->id,
                            'sku' => $variant->sku,
                            'product_name' => $variant->product->name,
                            'variant_name' => $variant->fullName,
                            'unit_price' => $item['price'],
                            'quantity' => $item['quantity'],
                            'sub_total' => $variant->selling_price * $item['quantity'],
                            'total' => $item['price'] * $item['quantity'],
                            'selling_price' => $variant->selling_price,
                            'vat_percent' => $variant->product->vat_percent,
                            'vat_amount' => calculate_vat($variant->product->vat_percent, $item['price']) * $item['quantity'],
                            'discount' => $variant->calculatedDiscount * $item['quantity'],
                        ]);
                    }
                }
            }

            $subTotal = $order->items()->sum('sub_total');
            $vat = $order->items()->sum('vat_amount');
            $discount = $order->items()->sum('discount') + ($data['additional_discount'] ?? 0);
            $total = $order->items()->sum('total') - ($data['additional_discount'] ?? 0);
            $paid = min($data['paid'] ?? 0, $total);
            $due = max($total - $paid, 0);

            $commissionData = $seller->calculateEarning($total, $vat);
            $order->update([
                'sub_total' => $subTotal,
                'vat_amount' => $vat,
                'discount' => $discount,
                'additional_discount' => $data['additional_discount'] ?? 0,
                'total' => $total,
                'payable' => $total,
                'paid' => $paid,
                'due' => $due,
                'total_commission' => $commissionData['total_commission'],
                'seller_earnings' => $commissionData['seller_earning'],
                'seller_employee_id' => $employee->id ?? $order->seller_employee_id,
            ]);

            DB::commit();

            $html = view('components.seller.pos-order-items', [
                'orderItems' => $order->items()->with('variant.product')->get(),
            ])->render();

            return apiResponse([
                'invoice_id' => $order->invoice_id,
                'html' => $html,
                'sub_total' => $subTotal,
                'vat_amount' => $vat,
                'discount' => $discount,
                'total' => $total,
                'paid' => $paid,
                'due' => $due,
            ], "Order updated successfully");
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error($e->getMessage());
            return errorResponse("Order update failed, please try again.");
        }
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
            'order_id' => 'required',
            'variant_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::where('invoice_id', $data['order_id'])
            ->where('seller_id', get_seller_id())
            ->with('items.variant.product')
            ->first();

        if (!$order) return errorResponse("Order not found");

        $variant = ProductVariant::find($data['variant_id']);
        if (!$variant) return errorResponse("Variant not found");

        $unitPrice = $variant->discounted_price ?? $variant->selling_price;
        $sellingPrice = $variant->selling_price;
        $quantity = $data['quantity'];
        $product = $variant->product;

        $itemSubtotal = $sellingPrice * $quantity;
        $itemDiscount = ($sellingPrice - $unitPrice) * $quantity;
        $itemVat = ($product->vat_percent * $unitPrice / 100) * $quantity;
        $itemTotal = $unitPrice * $quantity + $itemVat;

        $orderItem = $order->items()
            ->where('product_variant_id', $variant->id)
            ->where('unit_price', $unitPrice)
            ->first();

        if ($orderItem) {
            $orderItem->quantity += $quantity;
            $orderItem->sub_total += $itemSubtotal;
            $orderItem->discount += $itemDiscount;
            $orderItem->vat_amount += $itemVat;
            $orderItem->total += $itemTotal;
            $orderItem->save();
        } else {
            $order->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id,
                'sku' => $variant->sku,
                'product_name' => $product->name,
                'variant_name' => $variant->fullName,
                'buying_price' => $variant->buying_price,
                'selling_price' => $sellingPrice,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'sub_total' => $itemSubtotal,
                'discount' => $itemDiscount,
                'vat_percent' => $product->vat_percent,
                'vat_amount' => $itemVat,
                'total' => $itemTotal,
            ]);
        }

        $orderItems = $order->items()->with('variant.product')->get();
        $subTotal = $orderItems->sum(fn($i) => $i->sub_total);
        $productDiscount = $orderItems->sum(fn($i) => $i->discount);
        $totalDiscount = $productDiscount + ($order->additional_discount ?? 0);
        $vatAmount = $orderItems->sum(fn($i) => $i->vat_amount);
        $total = $orderItems->sum(fn($i) => $i->total) - ($order->additional_discount ?? 0);
        $due = $total - $order->paid;

        $order->update([
            'sub_total' => $subTotal,
            'discount' => $totalDiscount,
            'vat_amount' => $vatAmount,
            'total' => $total,
            'due' => $due,
        ]);

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        return apiResponse([
            'html' => $html,
            'sub_total' => $subTotal,
            'discount' => $totalDiscount,
            'vat_amount' => $vatAmount,
            'total' => $total,
            'due' => $due,
        ], "Item added successfully");
    }

    public function itemUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|string|in:increase,decrease',
        ]);

        $item = OrderItem::find($request->id);
        if (!$item) return errorResponse("Order item not found");

        if ($request->action === 'increase') {
            $item->quantity += 1;
            $item->variant->decrement('stock_out');
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
            $item->variant->increment('stock_out');
        }

        $item->sub_total = $item->selling_price * $item->quantity;
        $item->discount = ($item->selling_price - $item->unit_price) * $item->quantity;
        $item->vat_amount = ($item->vat_percent * $item->unit_price / 100) * $item->quantity;
        $item->total = $item->sub_total - $item->discount + $item->vat_amount;
        $item->save();

        $order = $item->order;
        $orderItems = $order->items()->with('variant.product')->get();

        $subTotal = $orderItems->sum(fn($i) => $i->sub_total);
        $productDiscount = $orderItems->sum(fn($i) => $i->discount);
        $totalDiscount = $productDiscount + ($order->additional_discount ?? 0);
        $vatAmount = $orderItems->sum(fn($i) => $i->vat_amount);
        $total = $subTotal + $vatAmount - $totalDiscount;
        $due = $total - $order->paid;

        $order->update([
            'sub_total' => $subTotal,
            'discount' => $totalDiscount,
            'vat_amount' => $vatAmount,
            'total' => $total,
            'due' => $due,
        ]);

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $order->sub_total,
            'vat_amount' => $order->vat_amount,
            'discount' => $order->discount,
            'total' => $order->total,
            'due' => $order->due,
        ], "Item updated successfully");
    }

    public function itemRemove(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $item = OrderItem::find($request->id);
        if (!$item) return errorResponse("Order item not found");

        $order = $item->order;
        $item->delete();

        $orderItems = $order->items()->with('variant.product')->get();

        $subTotal = $orderItems->sum(fn($i) => $i->sub_total);
        $productDiscount = $orderItems->sum(fn($i) => $i->discount);
        $totalDiscount = $productDiscount + ($order->additional_discount ?? 0);
        $vatAmount = $orderItems->sum(fn($i) => $i->vat_amount);
        $productTotal = $orderItems->sum(fn($i) => $i->total);
        $total = $productTotal - ($order->additional_discount ?? 0);
        $due = $total - $order->paid;

        $order->update([
            'sub_total' => $subTotal,
            'discount' => $totalDiscount,
            'vat_amount' => $vatAmount,
            'total' => $total,
            'due' => $due,
        ]);

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        return apiResponse([
            'html' => $html,
            'sub_total' => $order->sub_total,
            'vat_amount' => $order->vat_amount,
            'discount' => $order->discount,
            'total' => $order->total,
            'due' => $order->due,
        ], "Item removed successfully");
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
