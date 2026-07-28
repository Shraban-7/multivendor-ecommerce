<?php

use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Models\ReturnRequest;

test('returns enum exposes expected complete lifecycle states', function () {
    $values = array_map(fn (ReturnStatus $s) => $s->value, ReturnStatus::cases());

    expect($values)->toContain('pending');
    expect($values)->toContain('awaiting_shipment');
    expect($values)->toContain('item_received');
    expect($values)->toContain('approved');
    expect($values)->toContain('rejected');
    expect($values)->toContain('refund_initiated');
    expect($values)->toContain('refunded');
    expect($values)->toContain('exchange_shipped');
    expect($values)->toContain('completed');
    expect($values)->toContain('cancelled');
});

test('refundable statuses are approved, item_received and refund_initiated', function () {
    $refundable = array_map(
        fn (ReturnStatus $s) => $s->value,
        array_filter(ReturnStatus::cases(), fn (ReturnStatus $s) => $s->isRefundable()),
    );

    sort($refundable);

    expect($refundable)->toBe([
        'approved',
        'item_received',
        'refund_initiated',
    ]);
});

test('terminal statuses mark isTerminal correctly', function () {
    expect(ReturnStatus::REFUNDED->isTerminal())->toBeTrue();
    expect(ReturnStatus::COMPLETED->isTerminal())->toBeTrue();
    expect(ReturnStatus::CANCELLED->isTerminal())->toBeTrue();
    expect(ReturnStatus::REJECTED->isTerminal())->toBeTrue();
    expect(ReturnStatus::APPROVED->isTerminal())->toBeFalse();
    expect(ReturnStatus::PENDING->isTerminal())->toBeFalse();
});

test('return request model aliases legacy strings', function () {
    $return = new ReturnRequest(['status' => 'approved']);

    expect($return->isApproved())->toBeTrue()
        ->and($return->isPending())->toBeFalse()
        ->and($return->statusColor())->toBe('primary');
});

test('return request model understands new enum statuses', function () {
    $return = new ReturnRequest(['status' => ReturnStatus::AWAITING_SHIPMENT]);

    expect($return->status)->toBe(ReturnStatus::AWAITING_SHIPMENT)
        ->and($return->label())->toBe('Awaiting Shipment')
        ->and($return->statusColor())->toBe('warning');
});
