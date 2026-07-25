<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Repositories\Contracts\BrandRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentBrandRepository implements BrandRepositoryInterface
{
    public function findById(int $id): ?Brand
    {
        return Brand::find($id);
    }

    public function findBySlug(string $slug): ?Brand
    {
        return Brand::where('slug', $slug)->first();
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return Brand::latest()->paginate($perPage);
    }

    public function getAll(): Collection
    {
        return Brand::all();
    }

    public function store(array $data): Brand
    {
        return Brand::create($data);
    }

    public function update(Brand $brand, array $data): bool
    {
        return $brand->update($data);
    }

    public function delete(Brand $brand): bool
    {
        return $brand->delete();
    }
}
