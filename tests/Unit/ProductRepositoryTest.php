<?php

use App\Domain\Product\Models\Brand;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\FlashSale;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Repositories\Contracts\BrandRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\CategoryRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\FlashSaleRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\OptionRepositoryInterface;
use App\Domain\Product\Repositories\Contracts\ProductRepositoryInterface;
use App\Domain\Product\Repositories\EloquentBrandRepository;
use App\Domain\Product\Repositories\EloquentCategoryRepository;
use App\Domain\Product\Repositories\EloquentFlashSaleRepository;
use App\Domain\Product\Repositories\EloquentOptionRepository;
use App\Domain\Product\Repositories\EloquentProductRepository;
use Tests\TestCase;

uses(TestCase::class);

it('ProductRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(ProductRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findBySlug'))->toBeTrue()
        ->and($reflection->hasMethod('findOrFail'))->toBeTrue()
        ->and($reflection->hasMethod('getActivePaginated'))->toBeTrue()
        ->and($reflection->hasMethod('getForSeller'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('getFeatured'))->toBeTrue()
        ->and($reflection->hasMethod('getTrending'))->toBeTrue()
        ->and($reflection->hasMethod('search'))->toBeTrue()
        ->and($reflection->hasMethod('incrementViews'))->toBeTrue();
});

it('CategoryRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(CategoryRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findBySlug'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('getParentCategories'))->toBeTrue()
        ->and($reflection->hasMethod('getSubcategories'))->toBeTrue()
        ->and($reflection->hasMethod('getNavCategories'))->toBeTrue()
        ->and($reflection->hasMethod('getAllWithSubcategories'))->toBeTrue();
});

it('BrandRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(BrandRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findBySlug'))->toBeTrue()
        ->and($reflection->hasMethod('getPaginated'))->toBeTrue()
        ->and($reflection->hasMethod('getAll'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue();
});

it('FlashSaleRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(FlashSaleRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findOrFail'))->toBeTrue()
        ->and($reflection->hasMethod('getActive'))->toBeTrue()
        ->and($reflection->hasMethod('getPaginated'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('getFlashSaleProducts'))->toBeTrue()
        ->and($reflection->hasMethod('submitProduct'))->toBeTrue()
        ->and($reflection->hasMethod('updateFlashSaleProduct'))->toBeTrue();
});

it('OptionRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(OptionRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('getAll'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('getValuesForOption'))->toBeTrue()
        ->and($reflection->hasMethod('storeValue'))->toBeTrue()
        ->and($reflection->hasMethod('deleteValue'))->toBeTrue();
});

it('implements ProductRepositoryInterface', function (): void {
    $repo = new EloquentProductRepository;
    expect($repo)->toBeInstanceOf(ProductRepositoryInterface::class);
});

it('implements CategoryRepositoryInterface', function (): void {
    $repo = new EloquentCategoryRepository;
    expect($repo)->toBeInstanceOf(CategoryRepositoryInterface::class);
});

it('implements BrandRepositoryInterface', function (): void {
    $repo = new EloquentBrandRepository;
    expect($repo)->toBeInstanceOf(BrandRepositoryInterface::class);
});

it('implements FlashSaleRepositoryInterface', function (): void {
    $repo = new EloquentFlashSaleRepository;
    expect($repo)->toBeInstanceOf(FlashSaleRepositoryInterface::class);
});

it('implements OptionRepositoryInterface', function (): void {
    $repo = new EloquentOptionRepository;
    expect($repo)->toBeInstanceOf(OptionRepositoryInterface::class);
});

it('ProductServiceProvider binds all repository interfaces', function (): void {
    expect(app(ProductRepositoryInterface::class))->toBeInstanceOf(EloquentProductRepository::class);
    expect(app(CategoryRepositoryInterface::class))->toBeInstanceOf(EloquentCategoryRepository::class);
    expect(app(BrandRepositoryInterface::class))->toBeInstanceOf(EloquentBrandRepository::class);
    expect(app(FlashSaleRepositoryInterface::class))->toBeInstanceOf(EloquentFlashSaleRepository::class);
    expect(app(OptionRepositoryInterface::class))->toBeInstanceOf(EloquentOptionRepository::class);
});

it('can mock ProductRepositoryInterface', function (): void {
    $repo = Mockery::mock(ProductRepositoryInterface::class);
    $product = new Product(['id' => 1, 'name' => 'Test Product']);

    $repo->shouldReceive('findById')->with(1)->once()->andReturn($product);

    expect($repo->findById(1))->toBe($product);
});

it('can mock CategoryRepositoryInterface', function (): void {
    $repo = Mockery::mock(CategoryRepositoryInterface::class);
    $categories = new \Illuminate\Database\Eloquent\Collection([
        new Category(['id' => 1, 'name' => 'Electronics']),
    ]);

    $repo->shouldReceive('getParentCategories')->once()->andReturn($categories);

    expect($repo->getParentCategories())->toHaveCount(1);
});

it('can mock BrandRepositoryInterface', function (): void {
    $repo = Mockery::mock(BrandRepositoryInterface::class);

    $repo->shouldReceive('getAll')->once()->andReturn(new \Illuminate\Database\Eloquent\Collection);

    expect($repo->getAll())->toBeInstanceOf(\Illuminate\Database\Eloquent\Collection::class);
});

it('can mock FlashSaleRepositoryInterface', function (): void {
    $repo = Mockery::mock(FlashSaleRepositoryInterface::class);

    $repo->shouldReceive('getActive')->once()->andReturn(null);

    expect($repo->getActive())->toBeNull();
});

it('CatalogService is resolvable from container', function (): void {
    expect(app(\App\Domain\Product\Services\CatalogService::class))
        ->toBeInstanceOf(\App\Domain\Product\Services\CatalogService::class);
});

it('FlashSaleService is resolvable from container', function (): void {
    expect(app(\App\Domain\Product\Services\FlashSaleService::class))
        ->toBeInstanceOf(\App\Domain\Product\Services\FlashSaleService::class);
});

it('ProductService is resolvable from container', function (): void {
    expect(app(\App\Domain\Product\Services\ProductService::class))
        ->toBeInstanceOf(\App\Domain\Product\Services\ProductService::class);
});

it('StockManagerService is resolvable from container', function (): void {
    expect(app(\App\Domain\Product\Services\StockManagerService::class))
        ->toBeInstanceOf(\App\Domain\Product\Services\StockManagerService::class);
});
