<?php

namespace App\Domain\Vendor\Repositories;

use App\Domain\Vendor\Models\SellerEmployee;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
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

    public function paginateForSeller(int $sellerId, array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = SellerEmployee::where('seller_id', $sellerId);

        if (! empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('email', 'like', $term)
                    ->orWhere('phone', 'like', $term);
            });
        }

        if (! empty($filters['status']) && in_array($filters['status'], ['active', 'inactive'], true)) {
            $query->where('is_active', $filters['status'] === 'active' ? 1 : 0);
        }

        // Sort mapping
        $sort = $filters['sort'] ?? 'latest';
        $direction = $filters['direction'] ?? 'desc';

        match (true) {
            $sort === 'name'        => $query->orderBy('name', $direction === 'asc' ? 'asc' : 'desc'),
            $sort === 'email'       => $query->orderBy('email', $direction === 'asc' ? 'asc' : 'desc'),
            $sort === 'status'      => $query->orderBy('is_active', $direction === 'asc' ? 'asc' : 'desc'),
            $sort === 'created_at'  => $query->orderBy('created_at', $direction === 'asc' ? 'asc' : 'desc'),
            default                 => $query->latest('id'),
        };

        return $query->paginate($perPage)->withQueryString();
    }

    public function getStatusCountsForSeller(int $sellerId): array
    {
        $base = SellerEmployee::where('seller_id', $sellerId);

        return [
            'total'             => (clone $base)->count(),
            'active'            => (clone $base)->where('is_active', 1)->count(),
            'inactive'          => (clone $base)->where('is_active', 0)->count(),
            'with_permissions'  => (clone $base)
                ->whereNotNull('permissions')
                ->whereRaw($this->jsonNotEmptyExpression('permissions'))
                ->count(),
        ];
    }

    /**
     * Portable JSON non-empty predicate that works on MySQL and PostgreSQL
     * (SQLite fallback to LENGTH > 2 — an empty json is "{}" → length 2).
     */
    private function jsonNotEmptyExpression(string $column): string
    {
        $driver = SellerEmployee::query()->getConnection()->getDriverName();

        return match ($driver) {
            'pgsql'  => "jsonb_array_length({$column}) > 0",
            'sqlite' => "LENGTH({$column}) > 2",
            default  => "JSON_LENGTH({$column}) > 0",
        };
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
