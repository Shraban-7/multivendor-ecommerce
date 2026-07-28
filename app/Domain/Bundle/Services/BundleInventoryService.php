<?php

namespace App\Domain\Bundle\Services;

use App\Domain\Bundle\Models\Bundle;
use App\Domain\Product\Models\Product;

class BundleInventoryService
{
    public function calculateStock(Bundle $bundle): int
    {
        $minStock = null;

        foreach ($bundle->items as $item) {
            $product = $item->product;
            if (! $product) {
                return 0;
            }

            $available = (int) $product->stock_in - (int) $product->stock_out;
            $itemLimit = (int) floor($available / max((int) $item->quantity, 1));

            if ($minStock === null || $itemLimit < $minStock) {
                $minStock = $itemLimit;
            }
        }

        return max($minStock ?? 0, 0);
    }

    public function hasSufficientStock(Bundle $bundle, int $bundleQuantity = 1): bool
    {
        foreach ($bundle->items as $item) {
            $product = $item->product;
            if (! $product) {
                return false;
            }

            $available = (int) $product->stock_in - (int) $product->stock_out;
            $needed = (int) $item->quantity * $bundleQuantity;

            if ($available < $needed) {
                return false;
            }
        }

        return true;
    }

    public function getStockStatus(Bundle $bundle): string
    {
        $stock = $this->calculateStock($bundle);

        if ($stock <= 0) {
            return 'out_of_stock';
        }
        if ($stock <= 5) {
            return 'low_stock';
        }
        return 'in_stock';
    }

    public function updateCachedStock(Bundle $bundle): int
    {
        $stock = $this->calculateStock($bundle);
        $bundle->update(['total_stock' => $stock]);
        return $stock;
    }
}
