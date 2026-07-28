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
    public function adjustStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        StockType $type,
        string $note = '',
        string $reason = 'adjustment',
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): void {
        DB::transaction(function () use ($product, $variant, $quantity, $type, $note, $reason, $referenceType, $referenceId) {
            if ($variant !== null) {
                $this->adjustVariantStock($product, $variant, $quantity, $type, $note, $reason, $referenceType, $referenceId);
            } else {
                $this->adjustProductStock($product, $quantity, $type, $note, $reason, $referenceType, $referenceId);
            }
        });
    }

    public function incrementStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null,
        string $reason = 'addition',
    ): void {
        if ($quantity <= 0) throw new RuntimeException('Increment quantity must be positive.');
        $this->adjustStock($product, $variant, $quantity, StockType::ADD_STOCK, $note ?? 'Stock increment', $reason);
    }

    public function decrementStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null,
        string $reason = 'sale',
    ): void {
        if ($quantity <= 0) throw new RuntimeException('Decrement quantity must be positive.');
        $this->adjustStock($product, $variant, $quantity, StockType::REMOVE_STOCK, $note ?? 'Stock decrement', $reason);
    }

    public function setStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null,
        string $reason = 'adjustment',
    ): void {
        if ($quantity < 0) throw new RuntimeException('Stock quantity cannot be negative.');
        $this->adjustStock($product, $variant, $quantity, StockType::SET_EXACT_STOCK, $note ?? 'Stock set', $reason);
    }

    public function restoreStock(
        Product $product,
        ?ProductVariant $variant,
        int $quantity,
        ?string $note = null,
        string $reason = 'return',
    ): void {
        if ($quantity <= 0) throw new RuntimeException('Restore quantity must be positive.');

        DB::transaction(function () use ($product, $variant, $quantity, $note, $reason) {
            $historyNote = $note ?? 'Stock restored';
            $sellerId = $product->seller_id;

            if ($variant !== null) {
                $locked = ProductVariant::lockForUpdate()->findOrFail($variant->id);
                $stockBefore = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);
                $locked->stock_out = max(0, ($locked->stock_out ?? 0) - $quantity);
                $locked->save();
                $stockAfter = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);

                $this->logStockHistory($product->id, $locked->id, $quantity, StockType::ADD_STOCK, $historyNote, $sellerId, $reason);
                $this->logInventoryTransaction($product->id, $locked->id, $sellerId, 'addition', $quantity, $stockBefore, $stockAfter, $reason, $historyNote);
            } else {
                $locked = Product::lockForUpdate()->findOrFail($product->id);
                $stockBefore = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);
                $locked->stock_out = max(0, ($locked->stock_out ?? 0) - $quantity);
                $locked->save();
                $stockAfter = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);

                $this->logStockHistory($product->id, null, $quantity, StockType::ADD_STOCK, $historyNote, $sellerId, $reason);
                $this->logInventoryTransaction($product->id, null, $sellerId, 'addition', $quantity, $stockBefore, $stockAfter, $reason, $historyNote);
            }
        });
    }

    public function availableStock(Product $product): int
    {
        $fresh = Product::lockForUpdate()->find($product->id);
        return max(0, ($fresh->stock_in ?? 0) - ($fresh->stock_out ?? 0));
    }

    public function variantAvailableStock(ProductVariant $variant): int
    {
        $fresh = ProductVariant::lockForUpdate()->find($variant->id);
        return max(0, ($fresh->stock_in ?? 0) - ($fresh->stock_out ?? 0));
    }

    // -----------------------------------------------------------------------

    private function adjustProductStock(
        Product $product,
        int $quantity,
        StockType $type,
        string $note,
        string $reason,
        ?string $referenceType,
        ?int $referenceId,
    ): void {
        $locked = Product::lockForUpdate()->findOrFail($product->id);
        $currentStock = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);

        if ($type === StockType::REMOVE_STOCK && $quantity > $currentStock) {
            throw new RuntimeException("Insufficient stock. Available: {$currentStock}, requested: {$quantity}.");
        }

        $stockBefore = $currentStock;

        $this->logStockHistory($locked->id, null, $quantity, $type, $note, $locked->seller_id, $reason);

        match ($type) {
            StockType::SET_EXACT_STOCK => $this->applySetExactToProduct($locked, $quantity),
            StockType::ADD_STOCK => $this->applyAddToProduct($locked, $quantity),
            StockType::REMOVE_STOCK => $this->applyRemoveFromProduct($locked, $quantity),
        };

        $stockAfter = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);
        $txType = $type === StockType::ADD_STOCK ? 'addition' : ($type === StockType::REMOVE_STOCK ? 'removal' : 'set');
        $this->logInventoryTransaction($locked->id, null, $locked->seller_id, $txType, $quantity, $stockBefore, $stockAfter, $reason, $note, $referenceType, $referenceId);
    }

    private function adjustVariantStock(
        Product $product,
        ProductVariant $variant,
        int $quantity,
        StockType $type,
        string $note,
        string $reason,
        ?string $referenceType,
        ?int $referenceId,
    ): void {
        $locked = ProductVariant::lockForUpdate()->findOrFail($variant->id);
        $currentStock = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);

        if ($type === StockType::REMOVE_STOCK && $quantity > $currentStock) {
            throw new RuntimeException("Insufficient variant stock. Available: {$currentStock}, requested: {$quantity}.");
        }

        $stockBefore = $currentStock;

        $this->logStockHistory($product->id, $locked->id, $quantity, $type, $note, $locked->product->seller_id, $reason);

        match ($type) {
            StockType::SET_EXACT_STOCK => $this->applySetExactToVariant($product, $locked, $quantity),
            StockType::ADD_STOCK => $this->applyAddToVariant($product, $locked, $quantity),
            StockType::REMOVE_STOCK => $this->applyRemoveFromVariant($locked, $quantity),
        };

        $stockAfter = ($locked->stock_in ?? 0) - ($locked->stock_out ?? 0);
        $txType = $type === StockType::ADD_STOCK ? 'addition' : ($type === StockType::REMOVE_STOCK ? 'removal' : 'set');
        $this->logInventoryTransaction($product->id, $locked->id, $locked->product->seller_id, $txType, $quantity, $stockBefore, $stockAfter, $reason, $note, $referenceType, $referenceId);
    }

    // --- Product mutations ---

    private function applySetExactToProduct(Product $product, int $quantity): void
    {
        ProductStock::where('product_id', $product->id)->whereNull('product_variant_id')->delete();
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
        ProductStock::where('product_id', $product->id)->where('product_variant_id', $variant->id)->delete();
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

    // --- Logging ---

    private function logStockHistory(int $productId, ?int $variantId, int $quantity, StockType $type, string $note, int $sellerId, string $reason): void
    {
        StockHistory::create([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'quantity' => $quantity,
            'type' => $type,
            'note' => $note,
            'seller_id' => $sellerId,
            'reason' => $reason,
        ]);
    }

    private function logInventoryTransaction(
        int $productId,
        ?int $variantId,
        int $sellerId,
        string $type,
        int $quantity,
        int $stockBefore,
        int $stockAfter,
        string $reason,
        ?string $notes = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
    ): void {
        \App\Domain\Product\Models\InventoryTransaction::create([
            'product_id' => $productId,
            'product_variant_id' => $variantId,
            'seller_id' => $sellerId,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reason' => $reason,
            'notes' => $notes,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
        ]);
    }
}
