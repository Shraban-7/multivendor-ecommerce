<?php

namespace App\Domain\Vendor\Observers;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Models\ReturnRequestItem;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerChat;
use App\Domain\Vendor\Models\SellerChatMessage;
use App\Domain\Vendor\Services\SellerPerformanceService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Observer that recomputes SellerPerformanceScore when underlying signals change.
 * Registered on multiple models; each model's events call the same `created()`/`updated()`
 * methods, which dispatch internally based on model class.
 */
class SellerPerformanceObserver
{
    public function __construct(protected SellerPerformanceService $service) {}

    public function created(Model $model): void
    {
        if ($model instanceof Review) {
            $this->recomputeById((int) $model->seller_id);
        } elseif ($model instanceof ReturnRequest) {
            $this->recomputeByOrderId((int) $model->order_id);
        } elseif ($model instanceof ReturnRequestItem) {
            $this->recomputeByReturnRequest((int) $model->return_request_id);
        } elseif ($model instanceof SellerChat) {
            $this->recomputeById((int) $model->seller_id);
        } elseif ($model instanceof SellerChatMessage) {
            $this->recomputeBySellerChat((int) $model->seller_chat_id);
        }
    }

    public function updated(Model $model): void
    {
        if ($model instanceof Review && $model->isDirty('is_approved')) {
            $this->recomputeById((int) $model->seller_id);
        } elseif ($model instanceof ReturnRequest && $model->isDirty(['status', 'is_disputed'])) {
            $this->recomputeByOrderId((int) $model->order_id);
        } elseif ($model instanceof Order && $model->isDirty('status')) {
            $this->onOrderStatusChanged($model);
        }
    }

    protected function onOrderStatusChanged(Order $order): void
    {
        if (! in_array($order->status->value, [
            OrderStatus::CANCELLED->value,
            OrderStatus::SHIPPED->value,
            OrderStatus::DELIVERED->value,
            OrderStatus::COMPLETED->value,
            OrderStatus::RETURNED->value,
            OrderStatus::REFUNDED->value,
        ], true)) {
            return;
        }

        $this->recomputeById((int) $order->seller_id);
    }

    protected function recomputeById(int $sellerId): void
    {
        if (! config('marketplace.performance.auto_recompute', true)) {
            return;
        }

        $seller = Seller::find($sellerId);
        if (! $seller) {
            return;
        }

        try {
            $this->service->recompute($seller);
        } catch (\Throwable $e) {
            Log::warning('seller performance recompute failed', [
                'seller_id' => $sellerId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function recomputeByOrderId(int $orderId): void
    {
        $order = Order::find($orderId);
        if ($order && $order->seller_id) {
            $this->recomputeById((int) $order->seller_id);
        }
    }

    protected function recomputeByReturnRequest(int $returnRequestId): void
    {
        $return = ReturnRequest::find($returnRequestId);
        if ($return) {
            $this->recomputeByOrderId((int) $return->order_id);
        }
    }

    protected function recomputeBySellerChat(int $chatId): void
    {
        $chat = SellerChat::find($chatId);
        if ($chat) {
            $this->recomputeById((int) $chat->seller_id);
        }
    }
}
