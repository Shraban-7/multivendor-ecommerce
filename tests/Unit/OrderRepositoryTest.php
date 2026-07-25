<?php

use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Repositories\EloquentCartRepository;
use App\Domain\Order\Repositories\EloquentOrderRepository;
use App\Domain\Order\Services\OrderService;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->orderRepo = new EloquentOrderRepository;
    $this->cartRepo = new EloquentCartRepository;
});

it('implements OrderRepositoryInterface', function (): void {
    expect($this->orderRepo)->toBeInstanceOf(OrderRepositoryInterface::class);
});

it('implements CartRepositoryInterface', function (): void {
    expect($this->cartRepo)->toBeInstanceOf(CartRepositoryInterface::class);
});

it('OrderRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(OrderRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findByInvoiceId'))->toBeTrue()
        ->and($reflection->hasMethod('create'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('getOrdersByUser'))->toBeTrue()
        ->and($reflection->hasMethod('getOrdersBySeller'))->toBeTrue()
        ->and($reflection->hasMethod('getOrdersByStatus'))->toBeTrue()
        ->and($reflection->hasMethod('getPendingOrders'))->toBeTrue()
        ->and($reflection->hasMethod('createOrderItem'))->toBeTrue()
        ->and($reflection->hasMethod('createOrderItems'))->toBeTrue()
        ->and($reflection->hasMethod('createStatusLog'))->toBeTrue()
        ->and($reflection->hasMethod('createBillingAddress'))->toBeTrue()
        ->and($reflection->hasMethod('findBillingAddressByOrder'))->toBeTrue();
});

it('CartRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(CartRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findByUserId'))->toBeTrue()
        ->and($reflection->hasMethod('findUserCartBySeller'))->toBeTrue()
        ->and($reflection->hasMethod('create'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue()
        ->and($reflection->hasMethod('addItem'))->toBeTrue()
        ->and($reflection->hasMethod('removeItem'))->toBeTrue()
        ->and($reflection->hasMethod('clearCart'))->toBeTrue()
        ->and($reflection->hasMethod('getCartItems'))->toBeTrue()
        ->and($reflection->hasMethod('getCount'))->toBeTrue()
        ->and($reflection->hasMethod('getWishlistByUser'))->toBeTrue()
        ->and($reflection->hasMethod('addToWishlist'))->toBeTrue()
        ->and($reflection->hasMethod('removeFromWishlist'))->toBeTrue()
        ->and($reflection->hasMethod('findCouponByCode'))->toBeTrue()
        ->and($reflection->hasMethod('findUserBillingAddress'))->toBeTrue();
});

it('OrderServiceProvider binds all repository interfaces', function (): void {
    expect(app(OrderRepositoryInterface::class))->toBeInstanceOf(EloquentOrderRepository::class);
    expect(app(CartRepositoryInterface::class))->toBeInstanceOf(EloquentCartRepository::class);
});

it('can mock OrderRepositoryInterface', function (): void {
    $repo = Mockery::mock(OrderRepositoryInterface::class);
    $order = new Order(['id' => 1, 'invoice_id' => 'INV-001']);

    $repo->shouldReceive('findById')->with(1)->once()->andReturn($order);

    expect($repo->findById(1))->toBe($order);
});

it('can mock CartRepositoryInterface', function (): void {
    $repo = Mockery::mock(CartRepositoryInterface::class);

    $repo->shouldReceive('getCount')->with(1)->once()->andReturn(3);

    expect($repo->getCount(1))->toBe(3);
});

it('OrderService is resolvable from container', function (): void {
    expect(app(OrderService::class))
        ->toBeInstanceOf(OrderService::class);
});
