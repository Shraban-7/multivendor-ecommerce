<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::where('seller_id', get_seller_id())->with('employee')
            ->whereNull('user_id');

        if ($request->filled('invoice_id')) {
            $orders->where('invoice_id', 'like', '%'.$request->invoice_id.'%');
        }

        if ($request->filled('customer_name')) {
            $orders->whereHas('customer', function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->customer_name.'%');
            });
        }

        if ($request->filled('customer_phone')) {
            $orders->whereHas('customer', function ($q) use ($request) {
                $q->where('phone', 'like', '%'.$request->customer_phone.'%');
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

        $data = $request->validate([
            'customer_name' => 'nullable|string|max:255|required_with:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
            'paid' => 'nullable|numeric|min:0',
            'due' => 'nullable|numeric|min:0',
            'cash_received' => 'nullable',
            'cash_returned' => 'nullable',
            'items' => 'nullable|array',
            'items.*.id' => 'required|integer',
            'items.*.product_id' => 'required',
            'items.*.variant_id' => 'nullable',
            'items.*.price' => 'nullable|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:1',
            'additional_discount' => 'nullable|numeric|min:0',
            'employee_id' => 'nullable',
        ]);

        $employee = SellerEmployee::find($data['employee_id']);

        $order = Order::where('invoice_id', $orderId)
            ->where('seller_id', $seller->id)
            ->with('items.variant.product')
            ->firstOrFail();

        DB::beginTransaction();

        try {
            if (! empty($data['customer_name']) && ! empty($data['customer_phone'])) {
                $customer = Customer::firstOrCreate([
                    'name' => $data['customer_name'],
                    'phone' => $data['customer_phone'],
                ], [
                    'seller_id' => $seller->id,
                ]);
                $order->update(['customer_id' => $customer->id]);
            }

            $items = $data['items'] ?? [];

            foreach ($items as $item) {
                $orderItem = $order->items()->find($item['id']);

                if (isset($item['variant_id']) && $item['variant_id']) {
                    $variant = ProductVariant::find($item['variant_id']);
                    if ($variant) {
                        $product = $variant->product;
                        $sellingPrice = $variant->selling_price;
                        $unitPrice = $item['price'] ?? ($variant->discounted_price ?? $sellingPrice);
                        $variantId = $variant->id;
                        $sku = $variant->sku;
                        $variantName = $variant->fullName;
                    }
                } else {
                    $product = Product::find($item['product_id']);
                    $sellingPrice = $product->selling_price;
                    $unitPrice = $item['price'] ?? $sellingPrice;
                    $variantId = null;
                    $sku = $product->sku;
                    $variantName = null;
                }

                $quantity = $item['quantity'];
                $discount = max(0, $sellingPrice - $unitPrice) * $quantity;
                $subTotal = $sellingPrice * $quantity;
                $total = $unitPrice * $quantity;

                if ($orderItem) {
                    $orderItem->update([
                        'quantity' => $quantity,
                        'unit_price' => $unitPrice,
                        'total' => $total,
                        'discount' => $discount,
                        'sub_total' => $subTotal,
                    ]);
                } else {
                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_variant_id' => $variantId,
                        'sku' => $sku,
                        'product_name' => $product->name,
                        'variant_name' => $variantName,
                        'selling_price' => $sellingPrice,
                        'unit_price' => $unitPrice,
                        'quantity' => $quantity,
                        'sub_total' => $subTotal,
                        'discount' => $discount,
                        'total' => $total,
                    ]);
                }
            }

            $subTotal = $order->items()->sum('sub_total');
            $discount = $order->items()->sum('discount') + ($data['additional_discount'] ?? 0);
            $total = $order->items()->sum('total') - ($data['additional_discount'] ?? 0);
            $paid = min($data['paid'] ?? 0, $total);
            $due = max($total - $paid, 0);

            $commissionData = $seller->calculateEarning($total);

            $order->update([
                'sub_total' => $subTotal,
                'discount' => $discount,
                'additional_discount' => $data['additional_discount'] ?? 0,
                'total' => $total,
                'payable' => $total,
                'paid' => $paid,
                'due' => $due,
                'cash_received' => $data['cash_received'],
                'cash_returned' => $data['cash_returned'],
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
                'subtotal' => $subTotal,
                'discount' => $discount,
                'total' => $total,
                'paid' => $paid,
                'due' => $due,
            ], 'Order updated successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error($e->getMessage());

            return errorResponse('Order update failed, please try again.');
        }
    }

    public function delete($id)
    {
        $order = Order::with('items')->find($id);

        if (! $order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        foreach ($order->items as $item) {
            if (! empty($item->product_variant_id)) {

                $variant = ProductVariant::find($item->product_variant_id);

                if ($variant) {
                    $variant->decrement('stock_out', $item->quantity);

                    if ($variant->stock_out < 0) {
                        $variant->update(['stock_out' => 0]);
                    }
                }
            } else {
                $product = Product::find($item->product_id);

                if ($product) {
                    $product->decrement('stock_out', $item->quantity);

                    if ($product->stock_out < 0) {
                        $product->update(['stock_out' => 0]);
                    }
                }
            }
        }

        $order->delete();

        return successResponse('Order Deleted Successfully!');
    }

    public function itemAdd(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required',
            'product_id' => 'required|integer',
            'variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::where('invoice_id', $data['order_id'])
            ->where('seller_id', get_seller_id())
            ->with('items.variant.product')
            ->first();

        if (! $order) {
            return errorResponse('Order not found');
        }

        $quantity = $data['quantity'];
        $variant = null;
        $product = null;

        if (! empty($data['variant_id'])) {
            $variant = ProductVariant::with('product')->find($data['variant_id']);
            if ($variant) {
                $product = $variant->product;
                $sellingPrice = $variant->selling_price;
                $unitPrice = $variant->discounted_price ?? $sellingPrice;
            }
        }

        if (! $variant) {
            $product = Product::find($data['product_id']);
            if (! $product) {
                return errorResponse('Product not found');
            }
            $sellingPrice = $product->selling_price;
            $unitPrice = $product->discounted_price ?? $sellingPrice;
        }

        $subTotal = $sellingPrice * $quantity;
        $discount = ($sellingPrice - $unitPrice) * $quantity;
        $total = $unitPrice * $quantity;

        $existing = $order->items()
            ->when($variant, fn ($q) => $q->where('product_variant_id', $variant->id))
            ->when(! $variant, fn ($q) => $q->where('product_id', $product->id)->whereNull('product_variant_id'))
            ->where('unit_price', $unitPrice)
            ->first();

        if ($existing) {
            $existing->quantity += $quantity;
            $existing->sub_total += $subTotal;
            $existing->discount += $discount;
            $existing->total += $total;
            $existing->save();
        } else {
            $order->items()->create([
                'product_id' => $product->id,
                'product_variant_id' => $variant->id ?? null,
                'sku' => $variant->sku ?? $product->sku,
                'product_name' => $product->name,
                'variant_name' => $variant->fullName ?? null,
                'buying_price' => $variant->buying_price ?? $product->buying_price,
                'selling_price' => $sellingPrice,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'total' => $total,
            ]);
        }

        if ($variant) {
            $variant->increment('stock_out', $quantity);
        }

        return $this->refreshOrderSummary($order, 'Item added successfully');
    }

    public function itemUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|string|in:increase,decrease',
        ]);

        $item = OrderItem::with('variant.product', 'product', 'order')->find($request->id);
        if (! $item) {
            return errorResponse('Order item not found');
        }

        $variant = $item->variant;
        $product = $variant?->product ?? $item->product;

        if ($request->action === 'increase') {
            $item->quantity += 1;
            if ($variant) {
                $variant->increment('stock_out');
            }
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
            if ($variant) {
                $variant->decrement('stock_out');
            }
        }

        $item->sub_total = $item->selling_price * $item->quantity;
        $item->discount = ($item->selling_price - $item->unit_price) * $item->quantity;
        $item->total = $item->sub_total - $item->discount;
        $item->save();

        return $this->refreshOrderSummary($item->order, 'Item updated successfully');
    }

    public function itemRemove(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $item = OrderItem::with('variant.product', 'product', 'order')->find($request->id);
        if (! $item) {
            return errorResponse('Order item not found');
        }

        $variant = $item->variant;

        if ($variant) {
            $variant->decrement('stock_out', $item->quantity);
        }

        $order = $item->order;
        $item->delete();

        return $this->refreshOrderSummary($order, 'Item removed successfully');
    }

    public function pay(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validated['amount'] > $order->due) {
            return successResponse('Payment amount cannot be greater than remaining due.');
        }

        $order->paid += $validated['amount'];
        $order->due -= $validated['amount'];
        $order->save();

        return successResponse('Payment submitted successfully');
    }

    private function refreshOrderSummary($order, $message)
    {
        $orderItems = $order->items()->with('variant.product')->get();

        $subTotal = $orderItems->sum('sub_total');
        $productDiscount = $orderItems->sum('discount');
        $totalDiscount = $productDiscount + ($order->additional_discount ?? 0);

        $total = $subTotal - $totalDiscount;
        $due = $total - $order->paid;

        $order->update([
            'sub_total' => $subTotal,
            'discount' => $totalDiscount,
            'total' => $total,
            'due' => $due,
        ]);

        $html = view('components.seller.pos-order-items', compact('orderItems'))->render();

        return apiResponse([
            'html' => $html,
            'subtotal' => $subTotal,
            'discount' => $totalDiscount,
            'total' => $total,
            'due' => $due,
        ], $message);
    }
}
