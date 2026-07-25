<?php

namespace App\Domain\Product\Repositories;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class EloquentProductRepository implements ProductRepositoryInterface
{
    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::with(['variants', 'images', 'seller', 'category'])
            ->where('slug', $slug)
            ->first();
    }

    public function findOrFail(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function getActivePaginated(int $perPage = 25): LengthAwarePaginator
    {
        return Product::with(['brand', 'images', 'category', 'unit', 'variants.option_values'])
            ->active()
            ->latest('id')
            ->paginate($perPage);
    }

    public function getForSeller(int $sellerId, int $perPage = 25): LengthAwarePaginator
    {
        return Product::with(['variants.option_values', 'unit'])
            ->where('seller_id', $sellerId)
            ->latest('id')
            ->paginate($perPage);
    }

    public function store(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): bool
    {
        return $product->update($data);
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function getFeatured(int $limit = 12): Collection
    {
        return Product::active()
            ->featured()
            ->with(['brand', 'images', 'variants.option_values'])
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function getTrending(int $limit = 12): Collection
    {
        return Product::active()
            ->trending()
            ->with(['brand', 'images', 'variants.option_values'])
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function search(string $term, int $perPage = 25): LengthAwarePaginator
    {
        return Product::with(['brand', 'images', 'category', 'unit'])
            ->active()
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            })
            ->latest('id')
            ->paginate($perPage);
    }

    public function incrementViews(Product $product): void
    {
        $product->increment('views');
    }
}
