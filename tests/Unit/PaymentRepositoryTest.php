<?php

use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Domain\Payment\Repositories\EloquentPaymentRepository;
use App\Domain\Payment\Services\PaymentService;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->paymentRepo = new EloquentPaymentRepository;
});

it('implements PaymentRepositoryInterface', function (): void {
    expect($this->paymentRepo)->toBeInstanceOf(PaymentRepositoryInterface::class);
});

it('PaymentRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(PaymentRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findByTransactionId'))->toBeTrue()
        ->and($reflection->hasMethod('create'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('getByUser'))->toBeTrue()
        ->and($reflection->hasMethod('getEnabledGateways'))->toBeTrue()
        ->and($reflection->hasMethod('getDefaultGateway'))->toBeTrue()
        ->and($reflection->hasMethod('findGatewayById'))->toBeTrue()
        ->and($reflection->hasMethod('getEnabledManualMethods'))->toBeTrue()
        ->and($reflection->hasMethod('findListenerDeviceBySeller'))->toBeTrue()
        ->and($reflection->hasMethod('findListenerDeviceByPhone'))->toBeTrue()
        ->and($reflection->hasMethod('findListenerDeviceByCode'))->toBeTrue()
        ->and($reflection->hasMethod('getListenerDevicesBySeller'))->toBeTrue()
        ->and($reflection->hasMethod('createListenerPayment'))->toBeTrue()
        ->and($reflection->hasMethod('getListenerPayments'))->toBeTrue()
        ->and($reflection->hasMethod('getListenerPaymentsBySeller'))->toBeTrue()
        ->and($reflection->hasMethod('createSubscriptionPayment'))->toBeTrue();
});

it('PaymentServiceProvider binds correct implementation', function (): void {
    expect(app(PaymentRepositoryInterface::class))
        ->toBeInstanceOf(EloquentPaymentRepository::class);
});

it('can mock PaymentRepositoryInterface', function (): void {
    $repo = Mockery::mock(PaymentRepositoryInterface::class);
    $payment = new Payment(['id' => 1, 'transaction_id' => 'TXN-001']);

    $repo->shouldReceive('findById')->with(1)->once()->andReturn($payment);

    expect($repo->findById(1))->toBe($payment);
});

it('can mock findByTransactionId', function (): void {
    $repo = Mockery::mock(PaymentRepositoryInterface::class);

    $repo->shouldReceive('findByTransactionId')
        ->with('TXN-001')
        ->once()
        ->andReturn(new Payment(['transaction_id' => 'TXN-001']));

    expect($repo->findByTransactionId('TXN-001'))->not->toBeNull();
});

it('PaymentService is resolvable from container', function (): void {
    expect(app(PaymentService::class))
        ->toBeInstanceOf(PaymentService::class);
});
