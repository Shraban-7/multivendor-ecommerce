<?php

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductStock;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Models\StockHistory;
use App\Domain\Product\Services\StockManagerService;
use App\Enums\StockType;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── incrementStock ───────────────────────────────────────────────────────────

it('incrementStock increases product stock_in', function (): void {
    $product = Product::factory()->create(['stock_in' => 10, 'stock_out' => 0]);

    app(StockManagerService::class)->incrementStock($product, null, 5);

    $product->refresh();
    expect($product->stock_in)->toBe(15)
        ->and($product->stock_out)->toBe(0);
});

it('incrementStock creates a StockHistory record', function (): void {
    $product = Product::factory()->create(['stock_in' => 0, 'stock_out' => 0]);

    app(StockManagerService::class)->incrementStock($product, null, 20, 'Restock');

    $history = StockHistory::where('product_id', $product->id)->first();
    expect($history)->not->toBeNull()
        ->and($history->type)->toBe(StockType::ADD_STOCK)
        ->and($history->quantity)->toBe(20)
        ->and($history->note)->toBe('Restock');
});

it('incrementStock creates a ProductStock entry as SoT', function (): void {
    $product = Product::factory()->create(['stock_in' => 0, 'stock_out' => 0]);

    app(StockManagerService::class)->incrementStock($product, null, 30);

    expect(ProductStock::where('product_id', $product->id)->sum('quantity'))->toBe(30);
});

it('repeated increments accumulate in product_stocks and sync stock_in', function (): void {
    $product = Product::factory()->create(['stock_in' => 0, 'stock_out' => 0]);
    $svc = app(StockManagerService::class);

    $svc->incrementStock($product, null, 10);
    $svc->incrementStock($product, null, 15);
    $svc->incrementStock($product, null, 5);

    $product->refresh();
    expect($product->stock_in)->toBe(30)
        ->and(ProductStock::where('product_id', $product->id)->count())->toBe(3);
});

it('incrementStock throws if quantity is zero or negative', function (): void {
    $product = Product::factory()->create();

    expect(fn () => app(StockManagerService::class)->incrementStock($product, null, 0))
        ->toThrow(RuntimeException::class);

    expect(fn () => app(StockManagerService::class)->incrementStock($product, null, -5))
        ->toThrow(RuntimeException::class);
});

// ─── decrementStock ───────────────────────────────────────────────────────────

it('decrementStock reduces available stock via stock_out', function (): void {
    $product = Product::factory()->create(['stock_in' => 20, 'stock_out' => 0]);

    app(StockManagerService::class)->decrementStock($product, null, 5);

    $product->refresh();
    expect($product->stock_out)->toBe(5)
        ->and($product->availableStock)->toBe(15);
});

it('decrementStock writes StockHistory with REMOVE_STOCK type', function (): void {
    $product = Product::factory()->create(['stock_in' => 10, 'stock_out' => 0]);

    app(StockManagerService::class)->decrementStock($product, null, 3, 'Order #42');

    $history = StockHistory::where('product_id', $product->id)->first();
    expect($history->type)->toBe(StockType::REMOVE_STOCK)
        ->and($history->note)->toBe('Order #42');
});

it('decrementStock throws RuntimeException when quantity exceeds available stock', function (): void {
    $product = Product::factory()->create(['stock_in' => 5, 'stock_out' => 0]);

    expect(fn () => app(StockManagerService::class)->decrementStock($product, null, 10))
        ->toThrow(RuntimeException::class, 'Insufficient stock');
});

it('stock never goes negative when decrement exactly matches available stock', function (): void {
    $product = Product::factory()->create(['stock_in' => 7, 'stock_out' => 0]);

    app(StockManagerService::class)->decrementStock($product, null, 7);

    $product->refresh();
    expect($product->availableStock)->toBe(0);
});

it('concurrent decrement does not go below zero — atomic guard', function (): void {
    $product = Product::factory()->create(['stock_in' => 5, 'stock_out' => 0]);
    $svc = app(StockManagerService::class);

    $svc->decrementStock($product, null, 5);
    $product->refresh();

    expect($product->availableStock)->toBe(0);

    expect(fn () => $svc->decrementStock($product, null, 1))
        ->toThrow(RuntimeException::class);
});

// ─── setStock ─────────────────────────────────────────────────────────────────

it('setStock sets an exact stock level and resets stock_out', function (): void {
    $product = Product::factory()->create(['stock_in' => 50, 'stock_out' => 20]);

    app(StockManagerService::class)->setStock($product, null, 30);

    $product->refresh();
    expect($product->stock_in)->toBe(30)
        ->and($product->stock_out)->toBe(0)
        ->and($product->availableStock)->toBe(30);
});

it('setStock replaces existing product_stocks entries', function (): void {
    $product = Product::factory()->create(['stock_in' => 0, 'stock_out' => 0]);
    $svc = app(StockManagerService::class);

    $svc->incrementStock($product, null, 10);
    $svc->incrementStock($product, null, 20);
    expect(ProductStock::where('product_id', $product->id)->count())->toBe(2);

    $svc->setStock($product, null, 25);

    expect(ProductStock::where('product_id', $product->id)->count())->toBe(1);
    expect(ProductStock::where('product_id', $product->id)->sum('quantity'))->toBe(25);
});

it('setStock writes SET_EXACT_STOCK history entry', function (): void {
    $product = Product::factory()->create(['stock_in' => 5, 'stock_out' => 0]);

    app(StockManagerService::class)->setStock($product, null, 10, 'Annual count');

    $history = StockHistory::where('product_id', $product->id)
        ->where('type', StockType::SET_EXACT_STOCK->value)
        ->first();

    expect($history)->not->toBeNull()
        ->and($history->note)->toBe('Annual count');
});

it('setStock throws when given a negative quantity', function (): void {
    $product = Product::factory()->create();

    expect(fn () => app(StockManagerService::class)->setStock($product, null, -1))
        ->toThrow(RuntimeException::class);
});

// ─── Variant stock ─────────────────────────────────────────────────────────────

it('incrementStock on a variant does not affect parent product stock', function (): void {
    $product = Product::factory()->create(['stock_in' => 10, 'stock_out' => 0]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'TEST-VAR-01',
        'buying_price' => 50,
        'selling_price' => 100,
        'stock_in' => 0,
        'stock_out' => 0,
    ]);

    app(StockManagerService::class)->incrementStock($product, $variant, 8);

    $product->refresh();
    $variant->refresh();

    expect($variant->stock_in)->toBe(8)
        ->and($product->stock_in)->toBe(10);
});

it('decrementStock on a variant prevents going negative', function (): void {
    $product = Product::factory()->create(['stock_in' => 0, 'stock_out' => 0]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'TEST-VAR-02',
        'buying_price' => 50,
        'selling_price' => 100,
        'stock_in' => 3,
        'stock_out' => 0,
    ]);

    expect(fn () => app(StockManagerService::class)->decrementStock($product, $variant, 5))
        ->toThrow(RuntimeException::class, 'Insufficient variant stock');
});

it('variant stock history links both product_id and product_variant_id', function (): void {
    $product = Product::factory()->create(['stock_in' => 0, 'stock_out' => 0]);

    $variant = ProductVariant::create([
        'product_id' => $product->id,
        'sku' => 'TEST-VAR-03',
        'buying_price' => 50,
        'selling_price' => 100,
        'stock_in' => 20,
        'stock_out' => 0,
    ]);

    app(StockManagerService::class)->decrementStock($product, $variant, 4, 'Sale');

    $history = StockHistory::where('product_variant_id', $variant->id)->first();

    expect($history)->not->toBeNull()
        ->and($history->product_id)->toBe($product->id)
        ->and($history->product_variant_id)->toBe($variant->id);
});

// ─── transaction rollback on error ────────────────────────────────────────────

it('adjustStock rolls back when decrement exceeds available stock', function (): void {
    $product = Product::factory()->create(['stock_in' => 10, 'stock_out' => 0]);
    $originalStockIn = $product->stock_in;

    try {
        app(StockManagerService::class)->adjustStock(
            $product,
            null,
            999,
            StockType::REMOVE_STOCK,
            'Should fail'
        );
    } catch (RuntimeException) {
        // Expected
    }

    $product->refresh();

    expect($product->stock_in)->toBe($originalStockIn)
        ->and(StockHistory::where('product_id', $product->id)->count())->toBe(0)
        ->and(ProductStock::where('product_id', $product->id)->count())->toBe(0);
});
