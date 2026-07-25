<?php

namespace App\Domain\Vendor\Repositories;

use App\Domain\Vendor\Models\SellerEmployee;
use Illuminate\Database\Eloquent\Collection;

class EloquentSellerEmployeeRepository implements SellerEmployeeRepositoryInterface
{
    public function findById(int $id): ?SellerEmployee
    {
        return SellerEmployee::find($id);
    }

    public function getEmployeesForSeller(int $sellerId): Collection
    {
        return SellerEmployee::where('seller_id', $sellerId)->get();
    }

    public function store(array $data): SellerEmployee
    {
        return SellerEmployee::create($data);
    }

    public function update(SellerEmployee $employee, array $data): bool
    {
        return $employee->update($data);
    }

    public function setPermissions(SellerEmployee $employee, array $permissions): bool
    {
        return $employee->update(['permissions' => $permissions]);
    }

    public function toggleActive(SellerEmployee $employee): bool
    {
        return $employee->update(['is_active' => ! $employee->is_active]);
    }

    public function delete(SellerEmployee $employee): bool
    {
        return $employee->delete();
    }
}
