<?php

namespace App\Domain\Order\Http\Controllers\Seller;

use App\Domain\Auth\Models\Customer;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Services\StockManagerService;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class SaleController extends Controller
{
    public function __construct(
        private readonly StockManagerService $stockManager,
    ) {}

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

                $product = null;
                $variant = null;
                $sellingPrice = null;
                $unitPrice = null;
                $variantId = null;
                $sku = null;
                $variantName = null;

                if (! empty($item['variant_id'])) {
                    $variant = ProductVariant::with(['product', 'color', 'size'])->find($item['variant_id']);
                    if (! $variant) {
                        throw new \RuntimeException('Variant not found for order item.');
                    }
                    $product = $variant->product;
                    $sellingPrice = $variant->price;
                    $unitPrice = $item['price'] ?? ($variant->compare_price ?? $sellingPrice);
                    $variantId = $variant->id;
                    $sku = $variant->sku;
                    $variantName = $variant->label;
                } else {
                    $product = Product::find($item['product_id'] ?? null);
                    if (! $product) {
                        throw new \RuntimeException('Product not found for order item.');
                    }
                    $sellingPrice = $product->price;
                    $unitPrice = $item['price'] ?? $sellingPrice;
                    $sku = $product->sku;
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
                        'price' => $sellingPrice,
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
            $quantity = (int) $item->quantity;
            if ($quantity <= 0) {
                continue;
            }

            $note = 'Restored: Sale deleted #'.($order->invoice_id ?? $order->id);

            if (! empty($item->product_variant_id)) {
                $variant = ProductVariant::find($item->product_variant_id);
                $product = $variant?->product ?? Product::find($item->product_id);

                if ($variant && $product) {
                    $this->stockManager->restoreStock($product, $variant, $quantity, $note);
                }
            } else {
                $product = Product::find($item->product_id);

                if ($product) {
                    $this->stockManager->restoreStock($product, null, $quantity, $note);
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
            $variant = ProductVariant::with(['product', 'color', 'size'])->find($data['variant_id']);
            if ($variant) {
                $product = $variant->product;
                $sellingPrice = $variant->price;
                $unitPrice = $variant->compare_price ?? $sellingPrice;
            }
        }

        if (! $variant) {
            $product = Product::find($data['product_id']);
            if (! $product) {
                return errorResponse('Product not found');
            }
            $sellingPrice = $product->price;
            $unitPrice = $product->compare_price ?? $sellingPrice;
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
                'variant_name' => $variant->label ?? null,
                'cost_price' => $variant->cost_price ?? $product->cost_price,
                'price' => $sellingPrice,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'sub_total' => $subTotal,
                'discount' => $discount,
                'total' => $total,
            ]);
        }

        try {
            if ($variant) {
                $this->stockManager->decrementStock(
                    $product,
                    $variant,
                    $quantity,
                    'Sale item added #'.($order->invoice_id ?? $order->id)
                );
            } else {
                $this->stockManager->decrementStock(
                    $product,
                    null,
                    $quantity,
                    'Sale item added #'.($order->invoice_id ?? $order->id)
                );
            }
        } catch (RuntimeException $e) {
            return errorResponse($e->getMessage());
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
        $note = 'Sale item qty change #'.($item->order->invoice_id ?? $item->order_id);

        try {
            if ($request->action === 'increase') {
                $item->quantity += 1;
                if ($product) {
                    $this->stockManager->decrementStock($product, $variant, 1, $note);
                }
            } elseif ($request->action === 'decrease' && $item->quantity > 1) {
                $item->quantity -= 1;
                if ($product) {
                    $this->stockManager->restoreStock($product, $variant, 1, 'Restored: '.$note);
                }
            }
        } catch (RuntimeException $e) {
            return errorResponse($e->getMessage());
        }

        $item->sub_total = $item->price * $item->quantity;
        $item->discount = ($item->price - $item->unit_price) * $item->quantity;
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
        $product = $variant?->product ?? $item->product;

        if ($product && (int) $item->quantity > 0) {
            $this->stockManager->restoreStock(
                $product,
                $variant,
                (int) $item->quantity,
                'Restored: Sale item removed #'.($item->order->invoice_id ?? $item->order_id)
            );
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
