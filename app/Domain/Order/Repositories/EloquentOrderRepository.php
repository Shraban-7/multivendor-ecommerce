<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderBillingAddress;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Models\OrderStatusLog;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Enums\OrderStatus;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function getAllOrders(array $relations = []): Collection
    {
        return Order::with($relations)->get();
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

    public function searchSellerOrders(int $sellerId, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Order::with($relations)
            ->where('seller_id', $sellerId)
            ->whereNotNull('user_id')
            ->latest('id');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        if (! empty($filters['invoice_id'])) {
            $query->where('invoice_id', 'like', '%'.$filters['invoice_id'].'%');
        }
        if (! empty($filters['customer_name'])) {
            $query->whereHas('user', fn ($q) => $q->where('name', 'like', '%'.$filters['customer_name'].'%'));
        }
        if (! empty($filters['customer_phone'])) {
            $query->whereHas('user', fn ($q) => $q->where('phone', 'like', '%'.$filters['customer_phone'].'%'));
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($filters['per_page'] ?? 25);
    }

    public function searchUserOrders(int $userId, array $filters = [], array $relations = []): LengthAwarePaginator
    {
        $query = Order::with($relations)
            ->where('user_id', $userId)
            ->whereNotNull('invoice_id')
            ->latest('id');

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate($filters['per_page'] ?? 15);
    }

    public function getSellerOrderItemCount(int $sellerId): int
    {
        return OrderItem::whereIn(
            'order_id',
            Order::where('seller_id', $sellerId)->pluck('id')
        )->count();
    }

    public function deductStock(Order $order): void
    {
        $order->loadMissing('items');

        $variantIds = $order->items->pluck('product_variant_id')->filter()->unique();
        $productIds = $order->items->pluck('product_id')->filter()->unique();

        $variants = ProductVariant::whereIn('id', $variantIds)->get()->keyBy('id');
        $products = Product::whereIn('id', $productIds)->get()->keyBy('id');

        foreach ($order->items as $item) {
            if (! empty($item->product_variant_id) && $variant = $variants->get($item->product_variant_id)) {
                $variant->increment('stock_out', $item->quantity);
                $variant->product->increment('stock_out', $item->quantity);
            } elseif ($product = $products->get($item->product_id)) {
                $product->increment('stock_out', $item->quantity);
            }
        }
    }

    public function updateSellerTotalSold(int $sellerId): void
    {
        $count = $this->getSellerOrderItemCount($sellerId);
        \App\Domain\Vendor\Models\Seller::where('id', $sellerId)->update(['total_sold' => $count]);
    }
}
