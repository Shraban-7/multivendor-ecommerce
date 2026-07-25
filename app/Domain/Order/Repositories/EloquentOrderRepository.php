<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderBillingAddress;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Models\OrderStatusLog;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Enums\OrderStatus;
use Illuminate\Support\Collection;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    public function findById(int $id, array $relations = []): ?Order
    {
        return Order::with($relations)->find($id);
    }

    public function findByInvoiceId(string $invoiceId): ?Order
    {
        return Order::where('invoice_id', $invoiceId)->first();
    }

    public function create(array $data): Order
    {
        return Order::create($data);
    }

    public function update(Order $order, array $data): bool
    {
        return $order->update($data);
    }

    public function delete(int $id): bool
    {
        return Order::destroy($id) > 0;
    }

    public function getOrdersByUser(int $userId, array $relations = []): Collection
    {
        return Order::with($relations)->where('user_id', $userId)->get();
    }

    public function getOrdersBySeller(int $sellerId, array $relations = []): Collection
    {
        return Order::with($relations)->where('seller_id', $sellerId)->get();
    }

    public function getOrdersByStatus($status): Collection
    {
        return Order::where('status', $status)->get();
    }

    public function getPendingOrders(): Collection
    {
        return Order::where('status', OrderStatus::PENDING->value)->get();
    }

    public function createOrderItem(Order $order, array $data): OrderItem
    {
        return $order->items()->create($data);
    }

    public function createOrderItems(Order $order, array $data): Collection
    {
        return $order->items()->createMany($data);
    }

    public function createStatusLog(Order $order, array $data): OrderStatusLog
    {
        return $order->statusLogs()->create($data);
    }

    public function createBillingAddress(array $data): OrderBillingAddress
    {
        return OrderBillingAddress::create($data);
    }

    public function findBillingAddressByOrder(int $orderId): ?OrderBillingAddress
    {
        return OrderBillingAddress::where('order_id', $orderId)->first();
    }
}
