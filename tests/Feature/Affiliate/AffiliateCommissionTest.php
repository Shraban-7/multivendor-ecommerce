<?php

use App\Domain\Affiliate\Services\AffiliateService as DomainAffiliateService;
use App\Domain\Order\Models\Order;
use App\Services\AffiliateService as LegacyAffiliateService;

test('affiliate domain service wraps legacy commission approval', function () {
    $legacy = Mockery::mock(LegacyAffiliateService::class);
    $legacy->shouldReceive('approveCommission')->once();

    $service = new DomainAffiliateService($legacy);
    $order = Mockery::mock(Order::class);

    $service->approveCommission($order);

    expect(true)->toBeTrue();
});
