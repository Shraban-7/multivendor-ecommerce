<?php

namespace App\Domain\Vendor\Repositories;

use App\Domain\Vendor\Models\SellerEmployee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface SellerEmployeeRepositoryInterface
{
    public function findById(int $id): ?SellerEmployee;

    /**
     * @return Collection<int, SellerEmployee>
     */
    public function getEmployeesForSeller(int $sellerId): Collection;

    /**
     * Paginate, search and filter employees for a given seller.
     *
     * @param  array{search?:string, status?:string, sort?:string}  $filters
     */
    public function paginateForSeller(int $sellerId, array $filters = [], int $perPage = 25): LengthAwarePaginator;

    /**
     * Aggregate counts for the employee list KPIs.
     *
     * @return array{total:int, active:int, inactive:int, with_permissions:int}
     */
    public function getStatusCountsForSeller(int $sellerId): array;

    public function store(array $data): SellerEmployee;

    public function update(SellerEmployee $employee, array $data): bool;

    public function setPermissions(SellerEmployee $employee, array $permissions): bool;

    public function toggleActive(SellerEmployee $employee): bool;

    public function delete(SellerEmployee $employee): bool;
}
