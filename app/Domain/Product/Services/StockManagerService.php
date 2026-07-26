<?php

namespace App\Domain\Product\Services;

use App\Domain\Product\Enums\StockType;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductStock;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\StockHistory;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class StockManagerService
{
    /**
     * Adjust stock for a product or variant atomically.
     *
     * product_stocks is the single source of truth for incoming stock.
     * Adjustments are wrapped in a DB transaction with row-level locking
     * to prevent race conditions and ensure stock never goes negative.
     */
    public function adjustStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        StockType $type,
        string $note = ''
    ): void {
        DB::transaction(function () use ($product, $variant, $quantity, $type, $note) {
            if ($variant !== null) {
                $this->adjustVariantStock($product, $variant, $quantity, $type, $note);
            } else {
                $this->adjustProductStock($product, $quantity, $type, $note);
            }
        });
    }

    /**
     * Atomically increment stock for a product or variant.
     */
    public function incrementStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null
    ): void {
        if ($quantity <= 0) {
            throw new RuntimeException('Increment quantity must be positive.');
        }

        $this->adjustStock($product, $variant, $quantity, StockType::ADD_STOCK, $note ?? 'Stock increment');
    }

    /**
     * Atomically decrement stock. Throws if insufficient stock.
     */
    public function decrementStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null
    ): void {
        if ($quantity <= 0) {
            throw new RuntimeException('Decrement quantity must be positive.');
        }

        $this->adjustStock($product, $variant, $quantity, StockType::REMOVE_STOCK, $note ?? 'Stock decrement');
    }

    /**
     * Set exact stock level for a product or variant.
     */
    public function setStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null
    ): void {
        if ($quantity < 0) {
            throw new RuntimeException('Stock quantity cannot be negative.');
        }

        $this->adjustStock($product, $variant, $quantity, StockType::SET_EXACT_STOCK, $note ?? 'Stock set');
    }

    /**
     * Restore stock after a cancelled sale/order by reducing stock_out (not increasing stock_in).
     */
    public function restoreStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null
    ): void {
        if ($quantity <= 0) {
            throw new RuntimeException('Restore quantity must be positive.');
        }

        DB::transaction(function () use ($product, $variant, $quantity, $note) {
            $historyNote = $note ?? 'Stock restored';

            if ($variant !== null) {
                /** @var ProductVariant $locked */
                $locked = ProductVariant::lockForUpdate()->findOrFail($variant->id);
                $locked->stock_out = max(0, ($locked->stock_out ?? 0) - $quantity);
                $locked->save();

                StockHistory::create([
                    'product_id' => $product->id,
                    'product_variant_id' => $locked->id,
                    'quantity' => $quantity,
                    'type' => StockType::ADD_STOCK,
                    'note' => $historyNote,
                ]);
            } else {
                /** @var Product $locked */
                $locked = Product::lockForUpdate()->findOrFail($product->id);
                $locked->stock_out = max(0, ($locked->stock_out ?? 0) - $quantity);
                $locked->save();

                StockHistory::create([
                    'product_id' => $locked->id,
                    'quantity' => $quantity,
                    'type' => StockType::ADD_STOCK,
                    'note' => $historyNote,
                ]);
            }
        });
    }

    /**
     * Get the current available stock for a product (without variants).
     */
    public function availableStock(Product $product): int
    {
        $freshProduct = Product::lockForUpdate()->find($product->id);

        return max(0, ($freshProduct->stock_in ?? 0) - ($freshProduct->stock_out ?? 0));
    }

    /**
     * Get available stock for a variant.
     */
    public function variantAvailableStock(ProductVariant $variant): int
    {
        $freshVariant = ProductVariant::lockForUpdate()->find($variant->id);

        return max(0, ($freshVariant->stock_in ?? 0) - ($freshVariant->stock_out ?? 0));
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function adjustProductStock(
        Product $product,
        int $quantity,
        StockType $type,
        string $note
    ): void {
        /** @var Product $locked */
        $locked = Product::lockForUpdate()->findOrFail($product->id);

        $currentStock = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);

        if ($type === StockType::REMOVE_STOCK && $quantity > $currentStock) {
            throw new RuntimeException(
                "Insufficient stock. Available: {$currentStock}, requested: {$quantity}."
            );
        }

        StockHistory::create([
            'product_id' => $locked->id,
            'quantity' => $quantity,
            'type' => $type,
            'note' => $note,
        ]);

        match ($type) {
            StockType::SET_EXACT_STOCK => $this->applySetExactToProduct($locked, $quantity),
            StockType::ADD_STOCK => $this->applyAddToProduct($locked, $quantity),
            StockType::REMOVE_STOCK => $this->applyRemoveFromProduct($locked, $quantity),
        };
    }

    private function adjustVariantStock(
        Product $product,
        ProductVariant $variant,
        int $quantity,
        StockType $type,
        string $note
    ): void {
        /** @var ProductVariant $locked */
        $locked = ProductVariant::lockForUpdate()->findOrFail($variant->id);

        $currentStock = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);

        if ($type === StockType::REMOVE_STOCK && $quantity > $currentStock) {
            throw new RuntimeException(
                "Insufficient variant stock. Available: {$currentStock}, requested: {$quantity}."
            );
        }

        StockHistory::create([
            'product_id' => $product->id,
            'product_variant_id' => $locked->id,
            'quantity' => $quantity,
            'type' => $type,
            'note' => $note,
        ]);

        match ($type) {
            StockType::SET_EXACT_STOCK => $this->applySetExactToVariant($product, $locked, $quantity),
            StockType::ADD_STOCK => $this->applyAddToVariant($product, $locked, $quantity),
            StockType::REMOVE_STOCK => $this->applyRemoveFromVariant($locked, $quantity),
        };
    }

    // --- Product mutations ---

    private function applySetExactToProduct(Product $product, int $quantity): void
    {
        ProductStock::where('product_id', $product->id)
            ->whereNull('product_variant_id')
            ->delete();

        ProductStock::create([
            'product_id' => $product->id,
            'seller_id' => $product->seller_id,
            'quantity' => $quantity,
            'cost_price' => $product->cost_price ?? 0,
            'sub_total' => ($product->cost_price ?? 0) * $quantity,
        ]);

        $product->stock_in = $quantity;
        $product->stock_out = 0;
        $product->save();
    }

    private function applyAddToProduct(Product $product, int $quantity): void
    {
        ProductStock::create([
            'product_id' => $product->id,
            'seller_id' => $product->seller_id,
            'quantity' => $quantity,
            'cost_price' => $product->cost_price ?? 0,
            'sub_total' => ($product->cost_price ?? 0) * $quantity,
        ]);

        $product->stock_in = ($product->stock_in ?? 0) + $quantity;
        $product->save();
    }

    private function applyRemoveFromProduct(Product $product, int $quantity): void
    {
        $product->stock_out = ($product->stock_out ?? 0) + $quantity;
        $product->save();
    }

    // --- Variant mutations ---

    private function applySetExactToVariant(Product $product, ProductVariant $variant, int $quantity): void
    {
        ProductStock::where('product_id', $product->id)
            ->where('product_variant_id', $variant->id)
            ->delete();

        ProductStock::create([
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'seller_id' => $product->seller_id,
            'quantity' => $quantity,
            'cost_price' => $variant->cost_price ?? $product->cost_price ?? 0,
            'sub_total' => ($variant->cost_price ?? $product->cost_price ?? 0) * $quantity,
        ]);

        $variant->stock_in = $quantity;
        $variant->stock_out = 0;
        $variant->save();
    }

    private function applyAddToVariant(Product $product, ProductVariant $variant, int $quantity): void
    {
        ProductStock::create([
            'product_id' => $product->id,
            'product_variant_id' => $variant->id,
            'seller_id' => $product->seller_id,
            'quantity' => $quantity,
            'cost_price' => $variant->cost_price ?? $product->cost_price ?? 0,
            'sub_total' => ($variant->cost_price ?? $product->cost_price ?? 0) * $quantity,
        ]);

        $variant->stock_in = ($variant->stock_in ?? 0) + $quantity;
        $variant->save();
    }

    private function applyRemoveFromVariant(ProductVariant $variant, int $quantity): void
    {
        $variant->stock_out = ($variant->stock_out ?? 0) + $quantity;
        $variant->save();
    }
}
