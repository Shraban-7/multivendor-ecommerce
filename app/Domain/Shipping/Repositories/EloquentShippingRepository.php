<?php

namespace App\Domain\Shipping\Repositories;

use App\Domain\Shipping\Models\Shipment;
use App\Domain\Shipping\Models\ShippingMethod;
use App\Domain\Shipping\Models\TrackingLog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class EloquentShippingRepository implements ShippingRepositoryInterface
{
    public function findActiveMethodForDistrict(?int $districtId): ?ShippingMethod
    {
        return ShippingMethod::query()
            ->where('is_active', true)
            ->when($districtId, fn ($q) => $q->where(function ($q) use ($districtId) {
                $q->whereNull('district_id')->orWhere('district_id', $districtId);
            }))
            ->orderByRaw('district_id is null')
            ->first();
    }

    public function getShipmentsBySeller(int $sellerId, array $filters = []): LengthAwarePaginator
    {
        $query = Shipment::with(['order', 'carrier'])
            ->forSeller($sellerId)
            ->latest();

        if (! empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }
        if (! empty($filters['tracking_number'])) {
            $query->where('tracking_number', 'like', '%'.$filters['tracking_number'].'%');
        }
        if (! empty($filters['order_id'])) {
            $query->where('order_id', $filters['order_id']);
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($filters['per_page'] ?? 20);
    }

    public function findShipmentById(int $id, int $sellerId): ?Shipment
    {
        return Shipment::with(['order', 'carrier', 'trackingLogs'])
            ->forSeller($sellerId)
            ->find($id);
    }

    public function createShipment(array $data): Shipment
    {
        return Shipment::create($data);
    }

    public function updateShipment(Shipment $shipment, array $data): bool
    {
        return $shipment->update($data);
    }

    public function createTrackingLog(int $shipmentId, string $status, ?string $location = null, ?string $description = null): void
    {
        TrackingLog::create([
            'shipment_id' => $shipmentId,
            'status' => $status,
            'location' => $location,
            'description' => $description,
            'logged_at' => now(),
        ]);
    }

    public function getShipmentsByOrder(int $orderId): Collection
    {
        return Shipment::with('trackingLogs')
            ->where('order_id', $orderId)
            ->latest()
            ->get();
    }
}
