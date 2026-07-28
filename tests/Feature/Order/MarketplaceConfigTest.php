<?php

test('marketplace config loads with return window defaults', function () {
    expect(config('marketplace.return_window_days'))->toBeInt()->toBeGreaterThan(0);
    expect(config('marketplace.allow_partial_returns'))->toBeTrue();
    expect(config('marketplace.allow_exchange'))->toBeTrue();
    expect(config('marketplace.refund.require_item_received'))->toBeTrue();
});

test('refund auto-credit fallback is enabled by default', function () {
    expect(config('marketplace.refund.auto_credit_wallet_when_gateway_fails'))->toBeTrue();
    expect(config('marketplace.refund.wallet_fallback'))->toBeTrue();
});
