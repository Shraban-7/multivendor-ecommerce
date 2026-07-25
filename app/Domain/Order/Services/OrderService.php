<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Services\CartService;
use App\Domain\Vendor\Models\Seller;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected CartService $cartService,
        private readonly OrderRepositoryInterface $orderRepo,
    ) {}

    public function placeOrder(array $orderData, array $items, array $billing): Order
    {
        return DB::transaction(function () use ($orderData, $items, $billing) {
            $order = $this->orderRepo->create($orderData);
            $this->orderRepo->createOrderItems($order, $items);

            $this->orderRepo->createBillingAddress(array_merge($billing, [
                'order_id' => $order->id,
            ]));

            return $order->fresh(['items', 'billing_address']);
        });
    }

    public function transitionStatus(Order $order, int|string $status): Order
    {
        $this->orderRepo->update($order, ['status' => $status]);
        $this->orderRepo->createStatusLog($order, [
            'status' => $status,
            'changed_by' => auth()->id(),
        ]);

        return $order->fresh();
    }

    public function calculateCommission(Seller $seller, float $total): array
    {
        if (method_exists($seller, 'calculateEarning')) {
            return $seller->calculateEarning($total);
        }

        return [
            'total_commission' => 0.0,
            'seller_earning' => $total,
        ];
    }

    public function pendingStatus(): int|string
    {
        return OrderStatus::PENDING->value;
    }
}
