<?php

namespace App\Domain\Vendor\Repositories;

use App\Domain\Vendor\Models\SellerEmployee;
use Illuminate\Database\Eloquent\Collection;

interface SellerEmployeeRepositoryInterface
{
    public function findById(int $id): ?SellerEmployee;

    /**
     * @return Collection<int, SellerEmployee>
     */
    public function getEmployeesForSeller(int $sellerId): Collection;

    public function store(array $data): SellerEmployee;

    public function update(SellerEmployee $employee, array $data): bool;

    public function setPermissions(SellerEmployee $employee, array $permissions): bool;

    public function toggleActive(SellerEmployee $employee): bool;

    public function delete(SellerEmployee $employee): bool;
}
