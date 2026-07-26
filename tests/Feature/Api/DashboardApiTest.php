<?php

use App\Domain\Product\Models\Banner;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Observers\BannerObserver;
use App\Domain\Product\Observers\CategoryObserver;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('invalidates banner cache when a banner is created', function () {
    Cache::put('dashboard.banners', ['cached'], 900);
    expect(Cache::has('dashboard.banners'))->toBeTrue();

    $banner = new Banner();
    $observer = app(BannerObserver::class);
    $observer->created($banner);

    expect(Cache::has('dashboard.banners'))->toBeFalse();
});

it('invalidates banner cache when a banner is updated', function () {
    Cache::put('dashboard.banners', ['cached'], 900);
    $banner = new Banner();
    $observer = app(BannerObserver::class);
    $observer->updated($banner);

    expect(Cache::has('dashboard.banners'))->toBeFalse();
});

it('invalidates banner cache when a banner is deleted', function () {
    Cache::put('dashboard.banners', ['cached'], 900);
    $banner = new Banner();
    $observer = app(BannerObserver::class);
    $observer->deleted($banner);

    expect(Cache::has('dashboard.banners'))->toBeFalse();
});

it('invalidates category cache when a category is created', function () {
    Cache::put('dashboard.categories', ['cached'], 900);
    expect(Cache::has('dashboard.categories'))->toBeTrue();

    $category = new Category();
    $observer = app(CategoryObserver::class);
    $observer->created($category);

    expect(Cache::has('dashboard.categories'))->toBeFalse();
});

it('invalidates category cache when a category is updated', function () {
    Cache::put('dashboard.categories', ['cached'], 900);
    $category = new Category();
    $observer = app(CategoryObserver::class);
    $observer->updated($category);

    expect(Cache::has('dashboard.categories'))->toBeFalse();
});

it('invalidates category cache when a category is deleted', function () {
    Cache::put('dashboard.categories', ['cached'], 900);
    $category = new Category();
    $observer = app(CategoryObserver::class);
    $observer->deleted($category);

    expect(Cache::has('dashboard.categories'))->toBeFalse();
});
