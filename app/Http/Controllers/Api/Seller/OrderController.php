<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function baseQuery()
    {
        return Order::where('seller_id', Auth::id())
            ->with(['user', 'billing_address', 'items.product', 'items.variant']);
    }

    public function index(Request $request)
    {
        $orders = $this->baseQuery()
            ->when($request->filled('status') && $request->status !== 'all', fn ($q) => $q->where('status', OrderStatus::valueFromLabel($request->status)))
            ->latest()
            ->paginate($request->input('limit', 15));

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function pending()
    {
        $orders = $this->baseQuery()->where('status', OrderStatus::PENDING->value)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function shipped()
    {
        $orders = $this->baseQuery()->where('status', OrderStatus::SHIPPED->value)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function delivered()
    {
        $orders = $this->baseQuery()->where('status', OrderStatus::DELIVERED->value)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function cancelled()
    {
        $orders = $this->baseQuery()->where('status', OrderStatus::CANCELLED->value)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function refunded()
    {
        $orders = $this->baseQuery()->where('status', OrderStatus::REFUNDED->value)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function returned()
    {
        $orders = $this->baseQuery()->where('status', OrderStatus::RETURNED->value)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function posOrders()
    {
        $orders = $this->baseQuery()->where('pos_order', true)
            ->latest()->paginate(15);

        return apiResourceResponse($orders->through(fn ($o) => $this->formatOrder($o)));
    }

    public function details($invoiceId)
    {
        $order = $this->baseQuery()
            ->with(['payment', 'user.country'])
            ->where('invoice_id', $invoiceId)
            ->firstOrFail();

        return apiResponse([
            'order' => $this->formatOrder($order),
            'items' => $order->items->map(fn ($i) => [
                'id' => $i->id,
                'product_name' => $i->product?->name,
                'thumbnail' => $i->product?->thumbnail,
                'quantity' => $i->quantity,
                'price' => (float) $i->price,
                'discount' => (float) $i->discount,
                'sub_total' => (float) $i->sub_total,
                'variant' => $i->variant?->name,
            ]),
            'billing_address' => $order->billing_address,
            'payment' => $order->payment,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        if ($order->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        $validator = validateRequest($request, [
            'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled,refunded,returned',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $newStatus = OrderStatus::valueFromLabel($request->status);

        DB::transaction(function () use ($order, $newStatus) {
            $oldStatus = $order->status;
            $order->update(['status' => $newStatus]);

            if ($newStatus === OrderStatus::DELIVERED->value && $oldStatus !== OrderStatus::DELIVERED->value) {
                $order->update(['delivery_status' => OrderStatus::DELIVERED->label()]);
            }
        });

        return apiResponse(['status' => $order->fresh()->status], 'Order status updated successfully.');
    }

    private function formatOrder($o)
    {
        return [
            'id' => $o->id,
            'invoice_id' => $o->invoice_id,
            'customer_name' => $o->billing_address?->customer_name ?? $o->user?->name,
            'customer_phone' => $o->billing_address?->customer_phone ?? $o->user?->phone,
            'sub_total' => (float) $o->sub_total,
            'shipping_fee' => (float) $o->shipping_fee,
            'discount' => (float) $o->discount,
            'total' => (float) $o->total,
            'payable' => (float) $o->payable,
            'due' => (float) ($o->due ?? 0),
            'status' => $o->status,
            'payment_type' => $o->payment_type,
            'items_count' => $o->items->count(),
            'created_at' => $o->created_at,
            'updated_at' => $o->updated_at,
        ];
    }
}
