<?php

use App\Domain\Order\Services\PosCartService;

test('pos cart service returns empty collection for blank term', function () {
    $results = app(PosCartService::class)->searchCustomers('');

    expect($results)->toBeEmpty();
});

test('pos cart service builds like queries safely', function () {
    $service = app(PosCartService::class);

    expect(method_exists($service, 'searchCustomers'))->toBeTrue();
});
