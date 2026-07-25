<?php

use App\Domain\Shipping\Models\ShippingMethod;
use App\Domain\Shipping\Repositories\LocationRepositoryInterface;
use App\Domain\Shipping\Repositories\ShippingRepositoryInterface;
use App\Domain\Shipping\Services\ShippingService;
use Illuminate\Support\Collection;
use Tests\TestCase;

uses(TestCase::class);

test('ShippingService divisions returns collection from location repository', function () {
    $locationRepo = Mockery::mock(LocationRepositoryInterface::class);
    $shippingRepo = Mockery::mock(ShippingRepositoryInterface::class);

    $locationRepo->shouldReceive('getAllDivisions')
        ->once()
        ->andReturn(new Collection(['Dhaka', 'Chittagong']));

    $service = new ShippingService($locationRepo, $shippingRepo);

    expect($service->divisions())->toBeInstanceOf(Collection::class);
});

test('ShippingService calculateCharge computes charge via shipping repository', function () {
    $locationRepo = Mockery::mock(LocationRepositoryInterface::class);
    $shippingRepo = Mockery::mock(ShippingRepositoryInterface::class);

    $method = new ShippingMethod([
        'charge' => 60.0,
        'free_above' => 1000.0,
    ]);

    $shippingRepo->shouldReceive('findActiveMethodForDistrict')
        ->with(1)
        ->twice()
        ->andReturn($method);

    $service = new ShippingService($locationRepo, $shippingRepo);

    expect($service->calculateCharge(1, 500.0))->toBe(60.0);
    expect($service->calculateCharge(1, 1500.0))->toBe(0.0);
});
