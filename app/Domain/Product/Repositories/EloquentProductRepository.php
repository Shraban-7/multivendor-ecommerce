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
        return Product::with(['brand', 'images', 'category', 'unit', 'variants.color', 'variants.size'])
            ->active()
            ->latest('id')
            ->paginate($perPage);
    }

    public function getForSeller(int $sellerId, array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = Product::with(['category', 'variants.color', 'variants.size', 'unit'])
            ->where('seller_id', $sellerId);

        if (! empty($filters['search'])) {
            $term = '%'.$filters['search'].'%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                    ->orWhere('sku', 'like', $term);
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '' && $filters['status'] !== null) {
            $status = match ($filters['status']) {
                'pending' => Product::STATUS_PENDING_APPROVAL,
                'active' => Product::STATUS_ACTIVE,
                'draft' => Product::STATUS_DRAFT,
                'inactive' => Product::STATUS_INACTIVE,
                'deleted' => Product::STATUS_DELETED,
                default => null,
            };

            if ($status === null) {
                $query->where('status', '!=', Product::STATUS_DELETED);
            } elseif ($filters['status'] === 'deleted') {
                $query->where('status', Product::STATUS_DELETED);
            } else {
                $query->where('status', $status);
            }
        } else {
            $query->where('status', '!=', Product::STATUS_DELETED);
        }

        return $query->latest('id')->paginate($perPage);
    }

    public function getStatusCountsForSeller(int $sellerId): array
    {
        $counts = Product::query()
            ->where('seller_id', $sellerId)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'pending' => (int) ($counts[Product::STATUS_PENDING_APPROVAL] ?? 0),
            'active' => (int) ($counts[Product::STATUS_ACTIVE] ?? 0),
            'inactive' => (int) ($counts[Product::STATUS_INACTIVE] ?? 0),
            'draft' => (int) ($counts[Product::STATUS_DRAFT] ?? 0),
            'deleted' => (int) ($counts[Product::STATUS_DELETED] ?? 0),
        ];
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
            ->with(['brand', 'images', 'variants.color', 'variants.size'])
            ->latest('id')
            ->limit($limit)
            ->get();
    }

    public function getTrending(int $limit = 12): Collection
    {
        return Product::active()
            ->trending()
            ->with(['brand', 'images', 'variants.color', 'variants.size'])
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
