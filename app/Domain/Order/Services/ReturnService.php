<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Enums\ReturnEventType;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Enums\ReturnType;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Models\ReturnEvent;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Models\ReturnRequestItem;
use App\Domain\Order\Models\ReturnShipment;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Services\StockManagerService;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\VendorTransaction;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class ReturnService
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly StockManagerService $stockManager,
        private readonly RefundService $refundService,
    ) {}

    public function createReturnRequest(
        Order $order,
        int $userId,
        string $type,
        string $reason,
        ?string $exchangeNote = null,
        array $lineItems = [],
    ): ReturnRequest {
        $typeEnum = ReturnType::tryFrom($type)
            ?? throw new InvalidArgumentException('Invalid return type.');

        if ($typeEnum !== ReturnType::FULL && empty($lineItems)) {
            throw new InvalidArgumentException('Please select at least one item to return.');
        }

        if ($typeEnum === ReturnType::EXCHANGE && empty($exchangeNote)) {
            throw new InvalidArgumentException('Please describe what you want in exchange.');
        }

        $exists = ReturnRequest::where('order_id', $order->id)
            ->whereIn('status', [
                ReturnStatus::PENDING->value,
                ReturnStatus::APPROVED->value,
                ReturnStatus::AWAITING_SHIPMENT->value,
                ReturnStatus::ITEM_RECEIVED->value,
                ReturnStatus::REFUND_INITIATED->value,
            ])
            ->exists();

        if ($exists) {
            throw new RuntimeException('An active return already exists for this order.');
        }

        $windowDays = (int) config('marketplace.return_window_days', 7);
        $deliveryLog = $order->statusLogs()
            ->where('new_status', OrderStatus::DELIVERED->value)
            ->orderByDesc('id')
            ->first();

        $deliveredAt = $deliveryLog?->created_at;
        if ($deliveredAt && $deliveredAt->diffInDays(now()) > $windowDays) {
            throw new RuntimeException("Return window of {$windowDays} days has passed.");
        }

        return DB::transaction(function () use ($order, $userId, $typeEnum, $reason, $exchangeNote, $lineItems, $windowDays) {
            $return = ReturnRequest::create([
                'order_id' => $order->id,
                'user_id' => $userId,
                'type' => $typeEnum->value,
                'reason' => $reason,
                'exchange_note' => $exchangeNote,
                'status' => ReturnStatus::PENDING->value,
                'return_window_end' => now()->addDays($windowDays),
            ]);

            if ($typeEnum === ReturnType::FULL) {
                foreach ($order->items as $item) {
                    ReturnRequestItem::create([
                        'return_request_id' => $return->id,
                        'order_item_id' => $item->id,
                        'quantity' => (int) $item->quantity,
                        'refund_amount' => (float) $item->total,
                    ]);
                }
            } else {
                foreach ($lineItems as $raw) {
                    $item = OrderItem::where('id', $raw['id'])
                        ->where('order_id', $order->id)
                        ->firstOrFail();

                    $qty = min((int) $raw['quantity'], (int) $item->quantity);
                    $unit = (float) $item->total / max(1, (int) $item->quantity);
                    ReturnRequestItem::create([
                        'return_request_id' => $return->id,
                        'order_item_id' => $item->id,
                        'quantity' => $qty,
                        'refund_amount' => $unit * $qty,
                    ]);
                }
            }

            $this->orderRepo->update($order, [
                'status' => OrderStatus::RETURN_REQUESTED->value,
            ]);
            $this->orderRepo->createStatusLog($order, [
                'old_status' => $order->status->value,
                'new_status' => OrderStatus::RETURN_REQUESTED->value,
                'changed_by' => 'customer',
                'remarks' => "Return request {$return->rma_number} submitted",
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::CREATED->value,
                'customer',
                $userId,
                null,
                ReturnStatus::PENDING->value,
                'Customer submitted return request',
                ['rma' => $return->rma_number],
            );

            notify_seller(
                $order->seller_id,
                'New Return Request',
                "A new return request {$return->rma_number} has been submitted for order #{$order->invoice_id}.",
                'return',
                $return->id,
            );

            return $return;
        });
    }

    public function approve(ReturnRequest $return, string $actorType, ?int $actorId, ?string $note = null): ReturnRequest
    {
        if (! in_array($return->status, [ReturnStatus::PENDING, ReturnStatus::PENDING->value], true)) {
            throw new RuntimeException('Only pending returns can be approved.');
        }

        return DB::transaction(function () use ($return, $actorType, $actorId, $note) {
            $from = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            $return->update([
                'status' => ReturnStatus::APPROVED->value,
                'approved_at' => now(),
                'admin_note' => $note,
            ]);

            $order = $return->order;
            $old = $order->status->value;
            $this->orderRepo->update($order, [
                'status' => OrderStatus::RETURN_APPROVED->value,
            ]);
            $this->orderRepo->createStatusLog($order, [
                'old_status' => $old,
                'new_status' => OrderStatus::RETURN_APPROVED->value,
                'changed_by' => $actorType,
                'remarks' => $note ?: 'Return approved',
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::APPROVED->value,
                $actorType,
                $actorId,
                $from,
                ReturnStatus::APPROVED->value,
                $note,
            );

            $this->revertSellerEarning($order, $actorId, 'approved');

            notify_user(
                $return->user_id,
                'Return Approved',
                "Your return request {$return->rma_number} has been approved.",
                'return',
                $return->id,
            );

            return $return->fresh();
        });
    }

    public function reject(ReturnRequest $return, string $actorType, ?int $actorId, string $reason): ReturnRequest
    {
        if (! in_array($return->status, [ReturnStatus::PENDING, ReturnStatus::PENDING->value], true)) {
            throw new RuntimeException('Only pending returns can be rejected.');
        }

        return DB::transaction(function () use ($return, $actorType, $actorId, $reason) {
            $from = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            $return->update([
                'status' => ReturnStatus::REJECTED->value,
                'rejected_at' => now(),
                'rejection_reason' => $reason,
            ]);

            $order = $return->order;
            $old = $order->status->value;
            $this->orderRepo->update($order, [
                'status' => OrderStatus::DELIVERED->value,
            ]);
            $this->orderRepo->createStatusLog($order, [
                'old_status' => $old,
                'new_status' => OrderStatus::DELIVERED->value,
                'changed_by' => $actorType,
                'remarks' => 'Return rejected: '.$reason,
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::REJECTED->value,
                $actorType,
                $actorId,
                $from,
                ReturnStatus::REJECTED->value,
                $reason,
            );

            notify_user(
                $return->user_id,
                'Return Rejected',
                "Your return request {$return->rma_number} was rejected. Reason: {$reason}",
                'return',
                $return->id,
            );

            return $return->fresh();
        });
    }

    public function markAwaitingShipment(ReturnRequest $return, string $actorType, ?int $actorId): ReturnRequest
    {
        if ($return->status !== ReturnStatus::APPROVED && $return->status !== ReturnStatus::APPROVED->value) {
            throw new RuntimeException('Only approved returns can be marked as awaiting shipment.');
        }

        return DB::transaction(function () use ($return, $actorType, $actorId) {
            $from = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            $return->update(['status' => ReturnStatus::AWAITING_SHIPMENT->value]);

            ReturnEvent::log(
                $return,
                ReturnEventType::ITEM_SHIPPED->value,
                $actorType,
                $actorId,
                $from,
                ReturnStatus::AWAITING_SHIPMENT->value,
                'Customer/buyer instructed to ship the item back',
            );

            return $return->fresh();
        });
    }

    public function recordShipment(
        ReturnRequest $return,
        string $carrier,
        ?string $trackingNumber,
        string $actorType,
        ?int $actorId,
        ?string $notes = null,
    ): ReturnShipment {
        return DB::transaction(function () use ($return, $carrier, $trackingNumber, $actorType, $actorId, $notes) {
            $shipment = ReturnShipment::create([
                'return_request_id' => $return->id,
                'direction' => ReturnShipment::DIRECTION_TO_SELLER,
                'carrier' => $carrier,
                'tracking_number' => $trackingNumber,
                'status' => ReturnShipment::STATUS_IN_TRANSIT,
                'shipped_at' => now(),
                'notes' => $notes,
            ]);

            $this->markAwaitingShipment($return, $actorType, $actorId);

            return $shipment;
        });
    }

    public function markItemReceived(ReturnRequest $return, string $actorType, ?int $actorId, ?string $note = null): ReturnRequest
    {
        if (! in_array($return->status, [
            ReturnStatus::APPROVED,
            ReturnStatus::APPROVED->value,
            ReturnStatus::AWAITING_SHIPMENT,
            ReturnStatus::AWAITING_SHIPMENT->value,
        ], true)) {
            throw new RuntimeException('Cannot mark received from current state: '.$return->label());
        }

        return DB::transaction(function () use ($return, $actorType, $actorId, $note) {
            $from = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            $return->update([
                'status' => ReturnStatus::ITEM_RECEIVED->value,
            ]);

            $this->restoreOrderStock($return);

            $order = $return->order;
            $old = $order->status->value;
            $this->orderRepo->update($order, [
                'status' => OrderStatus::RETURNED->value,
            ]);
            $this->orderRepo->createStatusLog($order, [
                'old_status' => $old,
                'new_status' => OrderStatus::RETURNED->value,
                'changed_by' => $actorType,
                'remarks' => 'Item received from customer; stock restored',
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::ITEM_RECEIVED->value,
                $actorType,
                $actorId,
                $from,
                ReturnStatus::ITEM_RECEIVED->value,
                $note,
            );

            $autoRefund = (bool) config('marketplace.refund.require_item_received', true);
            if ($autoRefund && ! $return->isExchange()) {
                $this->refundService->initiate($return, $actorType, $actorId);
            }

            return $return->fresh();
        });
    }

    public function cancel(ReturnRequest $return, string $actorType, ?int $actorId, ?string $reason = null): ReturnRequest
    {
        if ($return->status === ReturnStatus::REFUNDED || $return->status === ReturnStatus::REFUNDED->value) {
            throw new RuntimeException('Refunded returns cannot be cancelled.');
        }

        return DB::transaction(function () use ($return, $actorType, $actorId, $reason) {
            $from = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            $return->update([
                'status' => ReturnStatus::CANCELLED->value,
                'cancellation_reason' => $reason ?: 'Cancelled by '.$actorType,
            ]);

            $order = $return->order;
            $old = $order->status->value;
            $this->orderRepo->update($order, [
                'status' => OrderStatus::DELIVERED->value,
            ]);
            $this->orderRepo->createStatusLog($order, [
                'old_status' => $old,
                'new_status' => OrderStatus::DELIVERED->value,
                'changed_by' => $actorType,
                'remarks' => 'Return cancelled: '.($reason ?: $actorType),
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::CANCELLED->value,
                $actorType,
                $actorId,
                $from,
                ReturnStatus::CANCELLED->value,
                $reason,
            );

            return $return->fresh();
        });
    }

    public function revertSellerEarning(Order $order, ?int $actorId, string $context = ''): void
    {
        if (! $order->seller_earning_added) {
            return;
        }

        $seller = Seller::find($order->seller_id);
        if (! $seller) {
            return;
        }

        $balanceBefore = (float) $seller->balance;
        $earning = (float) $order->seller_earnings;
        $seller->balance = max(0, $balanceBefore - $earning);
        $seller->save();

        VendorTransaction::record(
            $seller,
            VendorTransaction::TYPE_REFUND,
            -$earning,
            $balanceBefore,
            $order,
            'Return '.($context ?: $order->status->title())." for order #{$order->invoice_id}",
        );
    }

    private function restoreOrderStock(ReturnRequest $return): void
    {
        $return->loadMissing('items.orderItem');

        foreach ($return->items as $item) {
            $orderItem = $item->orderItem;
            if (! $orderItem) {
                continue;
            }

            $variant = null;
            $product = null;

            if (! empty($orderItem->product_variant_id)) {
                $variant = ProductVariant::find($orderItem->product_variant_id);
                $product = $variant?->product ?? Product::find($orderItem->product_id);
            } else {
                $product = Product::find($orderItem->product_id);
            }

            if (! $product) {
                continue;
            }

            $this->stockManager->restoreStock(
                $product,
                $variant,
                (int) $item->quantity,
                "Return {$return->rma_number} received",
            );
        }
    }
}
