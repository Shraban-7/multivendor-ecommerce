<?php

namespace App\Domain\Order\Repositories\Contracts;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderBillingAddress;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Models\OrderStatusLog;
use Illuminate\Support\Collection;

interface OrderRepositoryInterface
{
    public function findById(int $id, array $relations = []): ?Order;

    public function findByInvoiceId(string $invoiceId): ?Order;

    public function create(array $data): Order;

    public function update(Order $order, array $data): bool;

    public function delete(int $id): bool;

    public function getOrdersByUser(int $userId, array $relations = []): Collection;

    public function getOrdersBySeller(int $sellerId, array $relations = []): Collection;

    public function getOrdersByStatus($status): Collection;

    public function getPendingOrders(): Collection;

    public function createOrderItem(Order $order, array $data): OrderItem;

    public function createOrderItems(Order $order, array $data): Collection;

    public function createStatusLog(Order $order, array $data): OrderStatusLog;

    public function createBillingAddress(array $data): OrderBillingAddress;
}
