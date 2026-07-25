<?php

use App\Domain\Order\Services\OrderService;
use App\Enums\OrderStatus;

test('order status workflow uses pending enum value', function () {
    expect(app(OrderService::class)->pendingStatus())->toBe(OrderStatus::PENDING->value);
});
