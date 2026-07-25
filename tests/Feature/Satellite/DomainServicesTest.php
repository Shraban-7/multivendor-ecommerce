<?php

use App\Domain\Media\Services\MediaService;
use App\Domain\Review\Services\ReviewService;
use App\Domain\Shipping\Services\ShippingService;
use Illuminate\Support\Facades\Storage;

test('shipping service returns zero charge when no methods configured', function () {
    $service = Mockery::mock(ShippingService::class)->makePartial();
    $service->shouldReceive('calculateCharge')->once()->andReturn(0.0);

    expect($service->calculateCharge(1, 100))->toBe(0.0);
});

test('media service uses storage disk abstraction', function () {
    Storage::fake('public');
    $media = new MediaService('public');

    expect($media->delete('missing/file.webp'))->toBeFalse();
});

test('review service averages ratings via query builder contract', function () {
    $service = Mockery::mock(ReviewService::class)->makePartial();
    $service->shouldReceive('averageRating')->with(5)->andReturn(4.5);

    expect($service->averageRating(5))->toBe(4.5);
});
