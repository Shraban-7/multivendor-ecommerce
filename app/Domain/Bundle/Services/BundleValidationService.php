<?php

namespace App\Domain\Bundle\Services;

use App\Domain\Bundle\Models\Bundle;
use App\Domain\Product\Models\Product;

class BundleValidationService
{
    public function validateItems(array $items, int $sellerId): array
    {
        $errors = [];
        $productIds = [];

        if (count($items) < 2) {
            $errors[] = 'A bundle must contain at least 2 products';
            return $errors;
        }

        foreach ($items as $index => $item) {
            $productId = $item['product_id'] ?? null;
            $quantity = (int) ($item['quantity'] ?? 0);

            if (! $productId) {
                $errors[] = "Row " . ($index + 1) . ": Product is required";
                continue;
            }

            if (in_array($productId, $productIds)) {
                $product = Product::find($productId);
                $name = $product?->name ?? "ID {$productId}";
                $errors[] = "Duplicate product: '{$name}' can only be added once";
                continue;
            }
            $productIds[] = $productId;

            $product = Product::find($productId);
            if (! $product) {
                $errors[] = "Row " . ($index + 1) . ": Product not found";
                continue;
            }

            if ($product->trashed()) {
                $errors[] = "Row " . ($index + 1) . ": '{$product->name}' has been deleted";
                continue;
            }

            if ($product->seller_id !== $sellerId) {
                $errors[] = "Row " . ($index + 1) . ": '{$product->name}' belongs to another seller";
                continue;
            }

            if ($product->status === Product::STATUS_INACTIVE || $product->status === Product::STATUS_DELETED) {
                $errors[] = "Row " . ($index + 1) . ": '{$product->name}' is not available";
            }

            if ($quantity < 1) {
                $errors[] = "Row " . ($index + 1) . ": Quantity must be at least 1";
            }

            if ($quantity > 999) {
                $errors[] = "Row " . ($index + 1) . ": Quantity must not exceed 999";
            }
        }

        return $errors;
    }

    public function canBePurchased(Bundle $bundle): array
    {
        $errors = [];

        if ($bundle->status !== Bundle::STATUS_ACTIVE) {
            $errors[] = 'Bundle is not active';
        }

        if (! $bundle->is_visible) {
            $errors[] = 'Bundle is not visible';
        }

        foreach ($bundle->items as $item) {
            $product = $item->product;
            if (! $product) {
                $errors[] = "'{$item->product?->name}' is no longer available";
                continue;
            }
            if ($product->trashed()) {
                $errors[] = "'{$product->name}' has been removed";
                continue;
            }
            if ($product->status !== Product::STATUS_ACTIVE) {
                $errors[] = "'{$product->name}' is not available for purchase";
                continue;
            }
            $available = (int) $product->stock_in - (int) $product->stock_out;
            $needed = (int) $item->quantity;
            if ($available < $needed) {
                $errors[] = "Insufficient stock for '{$product->name}' (available: {$available}, needed: {$needed})";
            }
        }

        return $errors;
    }
}
