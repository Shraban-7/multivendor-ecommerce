<?php

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Services\OrderService;

test('order status workflow uses pending enum value', function () {
    expect(app(OrderService::class)->pendingStatus())->toBe(OrderStatus::PENDING->value);
});
