<?php

namespace App\Domain\Shipping\Repositories;

use App\Domain\Shipping\Models\Shipment;
use App\Domain\Shipping\Models\ShippingMethod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface ShippingRepositoryInterface
{
    public function findActiveMethodForDistrict(?int $districtId): ?ShippingMethod;

    public function getShipmentsBySeller(int $sellerId, array $filters = []): LengthAwarePaginator;

    public function findShipmentById(int $id, int $sellerId): ?Shipment;

    public function createShipment(array $data): Shipment;

    public function updateShipment(Shipment $shipment, array $data): bool;

    public function createTrackingLog(int $shipmentId, string $status, ?string $location = null, ?string $description = null): void;

    public function getShipmentsByOrder(int $orderId): Collection;
}
