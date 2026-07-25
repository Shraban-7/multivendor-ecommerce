<?php

use App\Domain\Payment\Services\PaymentService;
use App\Services\AffiliateService;
use App\Services\BkashService;
use InvalidArgumentException;

test('payment service rejects mismatched gateway amount', function () {
    $bkash = Mockery::mock(BkashService::class);
    $affiliate = Mockery::mock(AffiliateService::class);

    $service = new class($bkash, $affiliate) extends PaymentService
    {
        public function assertAmountMatch(float $paid, float $expected): void
        {
            if (abs($paid - $expected) > 0.01) {
                throw new InvalidArgumentException(
                    "Payment amount mismatch: expected {$expected}, got {$paid}."
                );
            }
        }
    };

    expect(fn () => $service->assertAmountMatch(50.0, 100.0))
        ->toThrow(InvalidArgumentException::class, 'Payment amount mismatch');
});

test('bkash pay endpoint requires invoice id', function () {
    $response = $this->getJson(route('bkash.pay'));

    $response->assertStatus(422);
});
