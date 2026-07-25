<?php

namespace App\Domain\Order\Services;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderBillingAddress;
use App\Domain\Vendor\Models\Seller;
use App\Enums\OrderStatus;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(protected CartService $cartService) {}

    /**
     * @param  array<string, mixed>  $orderData
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<string, mixed>  $billing
     */
    public function placeOrder(array $orderData, array $items, array $billing): Order
    {
        return DB::transaction(function () use ($orderData, $items, $billing) {
            $order = Order::create($orderData);
            $order->items()->createMany($items);

            OrderBillingAddress::create(array_merge($billing, [
                'order_id' => $order->id,
            ]));

            return $order->fresh(['items', 'billing_address']);
        });
    }

    public function transitionStatus(Order $order, int|string $status): Order
    {
        $order->update(['status' => $status]);
        $order->status_logs()->create([
            'status' => $status,
            'changed_by' => auth()->id(),
        ]);

        return $order->fresh();
    }

    /**
     * Shared commission calculation for checkout paths.
     *
     * @return array{total_commission: float, seller_earning: float}
     */
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
