<?php

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\DisputeResolution;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Models\Dispute;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Services\DisputeService;
use App\Domain\Order\Services\ReturnService;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function dsCustomer(array $attrs = []): User
{
    static $i = 0;
    $i++;

    return User::create(array_merge([
        'name' => 'Customer '.$i,
        'username' => 'cust'.Str::random(6).$i,
        'email' => 'cust'.$i.'@example.test',
        'phone' => '017'.str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT),
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
        'balance' => 0,
        'role' => 0,
    ], $attrs));
}

function dsSeller(array $attrs = []): Seller
{
    static $i = 0;
    $i++;

    return Seller::create(array_merge([
        'name' => 'Owner '.$i,
        'username' => 'seller'.Str::random(6).$i,
        'email' => 'seller'.$i.'@example.test',
        'phone' => '017'.str_pad((string) random_int(10000000, 99999999), 8, '0', STR_PAD_LEFT),
        'password' => Hash::make('password'),
        'email_verified_at' => now(),
        'business_name' => 'Biz '.$i,
        'business_email' => 'biz'.$i.'@example.test',
        'business_address' => '123 Test Street',
        'nid_no' => str_pad((string) random_int(1, 9999999999), 10, '0', STR_PAD_LEFT),
        'is_active' => 1,
        'status' => Seller::ACTIVE,
        'code' => strtoupper(Str::random(4)),
        'balance' => 0,
    ], $attrs));
}

function makeRejectedReturn(User $user, Seller $seller): array
{
    $order = Order::create([
        'user_id' => $user->id,
        'seller_id' => $seller->id,
        'invoice_id' => 'D-INV-'.uniqid(),
        'sub_total' => 500,
        'total' => 500,
        'payable' => 500,
        'paid' => 500,
        'due' => 0,
        'seller_earnings' => 450,
        'total_commission' => 50,
        'seller_earning_added' => true,
        'payment_type' => 1,
        'status' => OrderStatus::DELIVERED->value,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => 1,
        'product_name' => 'P',
        'sku' => 'P1',
        'quantity' => 1,
        'price' => 500,
        'cost_price' => 200,
        'unit_price' => 500,
        'discount' => 0,
        'sub_total' => 500,
        'total' => 500,
    ]);

    $return = app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Damaged');
    app(ReturnService::class)->reject($return, 'seller', $seller->id, 'After inspection: not eligible');

    return [$order->fresh(), $return->fresh()];
}

test('customer can open a dispute only on rejected return', function () {
    $user = dsCustomer();
    $seller = dsSeller();
    [$order, $return] = makeRejectedReturn($user, $seller);

    $dispute = app(DisputeService::class)->openDispute($return, $user->id, 'Wrong reason given', 'Customer description');

    expect($dispute)->toBeInstanceOf(Dispute::class)
        ->and($return->fresh()->is_disputed)->toBeTrue();
});

test('cannot open dispute on non-rejected returns', function () {
    $user = dsCustomer();
    $seller = dsSeller();
    $order = Order::create([
        'user_id' => $user->id,
        'seller_id' => $seller->id,
        'invoice_id' => 'PEND-'.uniqid(),
        'sub_total' => 100,
        'total' => 100,
        'payable' => 100,
        'paid' => 100,
        'due' => 0,
        'seller_earnings' => 90,
        'total_commission' => 10,
        'payment_type' => 1,
        'status' => OrderStatus::DELIVERED->value,
    ]);
    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => 1,
        'product_name' => 'P',
        'sku' => 'P1',
        'quantity' => 1,
        'price' => 100,
        'cost_price' => 50,
        'unit_price' => 100,
        'discount' => 0,
        'sub_total' => 100,
        'total' => 100,
    ]);

    $return = app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Defect');

    expect(fn () => app(DisputeService::class)->openDispute($return, $user->id, 'Bad order'))
        ->toThrow(RuntimeException::class);
});

test('seller can respond to a dispute', function () {
    $user = dsCustomer();
    $seller = dsSeller();
    [$order, $return] = makeRejectedReturn($user, $seller);

    $dispute = app(DisputeService::class)->openDispute($return, $user->id, 'Customer claims product is a counterfeit', 'Longer description');

    $dispute = app(DisputeService::class)->sellerRespond($dispute, 'Product is genuine; here is the bill of materials.', $seller->id);

    expect($dispute->seller_response)->toContain('genuine')
        ->and($dispute->seller_responded_at)->not->toBeNull();
});

test('admin resolves dispute with approval triggers refund path', function () {
    $user = dsCustomer(['balance' => 0]);
    $seller = dsSeller(['balance' => 200]);
    [$order, $return] = makeRejectedReturn($user, $seller);

    $dispute = app(DisputeService::class)->openDispute($return, $user->id, 'Damaged on arrival', null);
    app(DisputeService::class)->sellerRespond($dispute, 'Tested OK outbound; transit damage', $seller->id);

    app(DisputeService::class)->resolve($dispute, DisputeResolution::APPROVED, 1, 'Approved through dispute');

    expect($dispute->fresh()->isResolved())->toBeTrue()
        ->and($return->fresh()->status)->toBe(ReturnStatus::REFUNDED)
        ->and((float) $user->fresh()->balance)->toBeGreaterThan(0);
});
