<?php

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Services\CatalogService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// ─── Model: Product factory & scopes ─────────────────────────────────────────

it('creates a product via factory with correct defaults', function (): void {
    $product = Product::factory()->create();

    expect($product->id)->toBeInt()
        ->and($product->name)->not->toBeEmpty()
        ->and($product->slug)->not->toBeEmpty()
        ->and($product->status)->toBe(Product::STATUS_ACTIVE)
        ->and($product->stock_in)->toBeGreaterThanOrEqual(0)
        ->and($product->stock_out)->toBe(0);
});

it('scopeActive returns only active products', function (): void {
    Product::factory()->count(3)->active()->create();
    Product::factory()->count(2)->inactive()->create();

    $results = Product::active()->get();

    expect($results)->toHaveCount(3);
    $results->each(fn ($product) => expect($product->status)->toBe(Product::STATUS_ACTIVE));
});

it('scopeTrending returns only trending products', function (): void {
    Product::factory()->count(2)->create(['is_trending' => true]);
    Product::factory()->count(3)->create(['is_trending' => false]);

    expect(Product::trending()->count())->toBe(2);
});

it('scopeFeatured returns only featured products', function (): void {
    Product::factory()->count(4)->create(['is_featured' => true]);
    Product::factory()->count(1)->create(['is_featured' => false]);

    expect(Product::featured()->count())->toBe(4);
});

it('availableStock attribute computes correctly', function (): void {
    $product = Product::factory()->create(['stock_in' => 50, 'stock_out' => 15]);

    expect($product->availableStock)->toBe(35);
});

it('availableStock is 0 when stock_out equals stock_in', function (): void {
    $product = Product::factory()->create(['stock_in' => 5, 'stock_out' => 5]);

    expect($product->availableStock)->toBe(0);
});

it('computes status name attribute from integer status', function (): void {
    $product = Product::factory()->create(['status' => Product::STATUS_ACTIVE]);

    expect($product->statusName)->toBe('Active');
});

it('generates a unique slug on factory creation', function (): void {
    $p1 = Product::factory()->create();
    $p2 = Product::factory()->create();

    expect($p1->slug)->not->toBe($p2->slug);
});

// ─── Model: Category ─────────────────────────────────────────────────────────

it('category scopeCategory returns only root categories', function (): void {
    $root = Category::factory()->count(3)->create(['category_id' => null]);
    Category::factory()->count(2)->create(['category_id' => $root->first()->id]);

    $roots = Category::category()->get();

    expect($roots)->toHaveCount(3);
    $roots->each(fn ($c) => expect($c->category_id)->toBeNull());
});

it('category scopeSubcategory returns only non-root categories', function (): void {
    $root = Category::factory()->create(['category_id' => null]);
    Category::factory()->count(4)->create(['category_id' => $root->id]);

    expect(Category::subcategory()->count())->toBe(4);
});

it('category subcategories relationship works', function (): void {
    $parent = Category::factory()->create(['category_id' => null]);
    Category::factory()->count(3)->create(['category_id' => $parent->id]);

    expect($parent->subcategories)->toHaveCount(3);
});

// ─── Model: Brand ─────────────────────────────────────────────────────────────

it('brand factory creates with slug', function (): void {
    $brand = Brand::factory()->create();

    expect($brand->name)->not->toBeEmpty()
        ->and($brand->slug)->not->toBeEmpty();
});

it('brand products relationship returns only its products', function (): void {
    $brand = Brand::factory()->create();
    Product::factory()->count(3)->create(['brand_id' => $brand->id]);
    Product::factory()->count(2)->create();

    expect($brand->products)->toHaveCount(3);
});

// ─── CatalogService ───────────────────────────────────────────────────────────

it('CatalogService::list returns paginated active products', function (): void {
    Product::factory()->count(5)->active()->create();
    Product::factory()->count(3)->inactive()->create();

    $page = app(CatalogService::class)->list();

    expect($page->total())->toBe(5);
});

it('CatalogService::list filters by category_id', function (): void {
    $cat = Category::factory()->create(['category_id' => null]);
    Product::factory()->count(4)->active()->create(['category_id' => $cat->id]);
    Product::factory()->count(3)->active()->create();

    $page = app(CatalogService::class)->list(['category_id' => $cat->id]);

    expect($page->total())->toBe(4);
});

it('CatalogService::list filters by price range', function (): void {
    Product::factory()->active()->create(['price' => 50]);
    Product::factory()->active()->create(['price' => 150]);
    Product::factory()->active()->create(['price' => 300]);

    $page = app(CatalogService::class)->list(['min_price' => 100, 'max_price' => 200]);

    expect($page->total())->toBe(1);
});

it('CatalogService::list filters in_stock products', function (): void {
    Product::factory()->active()->create(['stock_in' => 10, 'stock_out' => 0]);
    Product::factory()->active()->create(['stock_in' => 0, 'stock_out' => 0]);

    $page = app(CatalogService::class)->list(['in_stock' => true]);

    expect($page->total())->toBe(1);
});

it('CatalogService::byCategory includes subcategory products', function (): void {
    $parent = Category::factory()->create(['category_id' => null]);
    $child = Category::factory()->create(['category_id' => $parent->id]);

    Product::factory()->count(2)->active()->create(['category_id' => $parent->id]);
    Product::factory()->count(3)->active()->create(['category_id' => $child->id]);

    $page = app(CatalogService::class)->byCategory($parent);

    expect($page->total())->toBe(5);
});

it('CatalogService::featured returns only featured products', function (): void {
    Product::factory()->count(3)->active()->create(['is_featured' => true]);
    Product::factory()->count(2)->active()->create(['is_featured' => false]);

    $featured = app(CatalogService::class)->featured(10);

    expect($featured)->toHaveCount(3);
});

it('CatalogService::recordView increments view count', function (): void {
    $product = Product::factory()->create(['views' => 0]);

    app(CatalogService::class)->recordView($product);
    $product->refresh();

    expect($product->views)->toBe(1);
});

// ─── BC alias ─────────────────────────────────────────────────────────────────

it('Product domain model instantiates correctly', function (): void {
    expect(new Product)
        ->toBeInstanceOf(Product::class);
});

it('App\\Models\\Category BC alias works for factory creation', function (): void {
    $cat = Category::factory()->create(['category_id' => null]);

    expect($cat)->toBeInstanceOf(Category::class)
        ->and(Category::find($cat->id))->not->toBeNull();
});
