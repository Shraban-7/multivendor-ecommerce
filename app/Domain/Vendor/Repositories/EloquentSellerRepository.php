<?php

namespace App\Domain\Vendor\Repositories;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class EloquentSellerRepository implements SellerRepositoryInterface
{
    public function findById(int $id): ?Seller
    {
        return Seller::find($id);
    }

    public function findByUsername(string $username): ?Seller
    {
        return Seller::where('username', $username)->first();
    }

    public function getPaginated(int $perPage = 30): LengthAwarePaginator
    {
        return Seller::with('plan')->latest('id')->paginate($perPage);
    }

    public function getPendingPaginated(int $perPage = 30): LengthAwarePaginator
    {
        return Seller::pending()->latest('id')->paginate($perPage);
    }

    public function store(array $data): Seller
    {
        return Seller::create($data);
    }

    public function update(Seller $seller, array $data): bool
    {
        return $seller->update($data);
    }

    public function setStatus(Seller $seller, int $status): bool
    {
        return $seller->update(['status' => $status]);
    }

    public function setBestSeller(Seller $seller, bool $isBestSeller): bool
    {
        return $seller->update(['is_best_seller' => $isBestSeller]);
    }

    public function softDelete(Seller $seller): bool
    {
        return $seller->update(['status' => Seller::DELETED]);
    }

    public function restore(Seller $seller): bool
    {
        return $seller->update(['status' => Seller::ACTIVE]);
    }

    public function permanentDelete(Seller $seller): bool
    {
        return $seller->forceDelete();
    }
}
