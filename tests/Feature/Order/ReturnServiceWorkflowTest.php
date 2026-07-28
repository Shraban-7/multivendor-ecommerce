<?php

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Enums\ReturnEventType;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Enums\ReturnType;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Models\RefundTransaction;
use App\Domain\Order\Models\ReturnEvent;
use App\Domain\Order\Models\ReturnRequestItem;
use App\Domain\Order\Services\ReturnService;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function makeCustomer(array $attrs = []): User
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

function makeSeller(array $attrs = []): Seller
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

function makeDeliveredOrder(User $user, Seller $seller, float $price = 1000): Order
{
    $order = Order::create([
        'user_id' => $user->id,
        'seller_id' => $seller->id,
        'invoice_id' => 'TEST-INV-'.uniqid(),
        'sub_total' => $price,
        'total' => $price,
        'payable' => $price,
        'paid' => $price,
        'due' => 0,
        'seller_earnings' => $price * 0.9,
        'total_commission' => $price * 0.1,
        'seller_earning_added' => true,
        'payment_type' => 1,
        'status' => OrderStatus::DELIVERED->value,
    ]);

    OrderItem::create([
        'order_id' => $order->id,
        'product_id' => 1,
        'product_name' => 'Test Product',
        'sku' => 'SKU-1',
        'quantity' => 2,
        'price' => $price / 2,
        'cost_price' => $price / 4,
        'unit_price' => $price / 2,
        'discount' => 0,
        'sub_total' => $price,
        'total' => $price,
    ]);

    return $order->fresh('items');
}

test('creates a full return request and transitions order to return_requested', function () {
    $user = makeCustomer();
    $seller = makeSeller(['balance' => 500]);
    $order = makeDeliveredOrder($user, $seller);

    $return = app(ReturnService::class)->createReturnRequest(
        $order,
        $user->id,
        'full',
        'Defective unit',
    );

    expect($return->status)->toBe(ReturnStatus::PENDING)
        ->and($return->type)->toBe(ReturnType::FULL)
        ->and($return->rma_number)->toStartWith('RMA-')
        ->and(ReturnRequestItem::where('return_request_id', $return->id)->count())->toBe(1)
        ->and($return->items->first()->quantity)->toBe(2)
        ->and((float) $return->items->first()->refund_amount)->toBe(1000.0)
        ->and($order->fresh()->status)->toBe(OrderStatus::RETURN_REQUESTED);

    expect(ReturnEvent::where('return_request_id', $return->id)
        ->where('type', ReturnEventType::CREATED->value)
        ->exists())->toBeTrue();
});

test('approves a pending return and reverts the seller earnings', function () {
    $user = makeCustomer();
    $seller = makeSeller(['balance' => 500]);
    $order = makeDeliveredOrder($user, $seller);

    $return = app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Defective');

    $balanceBefore = (float) $seller->fresh()->balance;

    app(ReturnService::class)->approve($return, 'admin', null, 'Approved by admin test');

    $return->refresh();
    expect($return->status)->toBe(ReturnStatus::APPROVED)
        ->and($return->approved_at)->not->toBeNull()
        ->and($return->order->fresh()->status)->toBe(OrderStatus::RETURN_APPROVED);

    expect((float) $seller->fresh()->balance)->toBeLessThan($balanceBefore);
});

test('rejects a pending return with rejection reason and resets order to delivered', function () {
    $user = makeCustomer();
    $seller = makeSeller(['balance' => 500]);
    $order = makeDeliveredOrder($user, $seller);

    $return = app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Test reason');

    app(ReturnService::class)->reject($return, 'seller', $seller->id, 'Wrong item shipped');

    $return->refresh();
    expect($return->status)->toBe(ReturnStatus::REJECTED)
        ->and($return->rejection_reason)->toBe('Wrong item shipped')
        ->and($return->order->fresh()->status)->toBe(OrderStatus::DELIVERED);
});

test('initiates wallet refund for non-exchange returns when gateway unavailable', function () {
    config(['marketplace.refund.auto_credit_wallet_when_gateway_fails' => true]);

    $user = makeCustomer(['balance' => 0]);
    $seller = makeSeller(['balance' => 500]);
    $order = makeDeliveredOrder($user, $seller);

    $return = app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Damaged');
    app(ReturnService::class)->approve($return, 'admin', null, 'Approved');
    app(ReturnService::class)->markItemReceived($return, 'seller', $seller->id, 'Stock restored');

    $return->refresh();
    expect($return->status)->toBe(ReturnStatus::REFUNDED)
        ->and((float) $user->fresh()->balance)->toBe(1000.0);

    $refund = RefundTransaction::where('return_request_id', $return->id)->first();
    expect($refund)->not->toBeNull()
        ->and($refund->method)->toBe(RefundTransaction::METHOD_WALLET)
        ->and($refund->status)->toBe(RefundTransaction::STATUS_SUCCESS);
});

test('exchange returns are not auto-refunded when item is received', function () {
    $user = makeCustomer(['balance' => 0]);
    $seller = makeSeller(['balance' => 200]);
    $order = makeDeliveredOrder($user, $seller);

    $return = app(ReturnService::class)->createReturnRequest(
        $order,
        $user->id,
        'exchange',
        'Need different size',
        'Need size L instead',
        [['id' => $order->items->first()->id, 'quantity' => 1]],
    );

    app(ReturnService::class)->approve($return, 'admin', null, 'Approve');
    app(ReturnService::class)->markItemReceived($return, 'seller', $seller->id);

    $return->refresh();
    expect($return->status)->toBe(ReturnStatus::ITEM_RECEIVED)
        ->and(RefundTransaction::where('return_request_id', $return->id)->count())->toBe(0);
});

test('rejects marking item received twice', function () {
    $user = makeCustomer();
    $seller = makeSeller();
    $order = makeDeliveredOrder($user, $seller);

    $return = app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Defect');
    app(ReturnService::class)->approve($return, 'admin', null);
    app(ReturnService::class)->markItemReceived($return, 'seller', $seller->id);
    $return->refresh();

    expect(fn () => app(ReturnService::class)->markItemReceived($return, 'seller', $seller->id))
        ->toThrow(RuntimeException::class);
});

test('cannot create return request twice for the same order', function () {
    $user = makeCustomer();
    $seller = makeSeller();
    $order = makeDeliveredOrder($user, $seller);

    app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'First reason');

    expect(fn () => app(ReturnService::class)->createReturnRequest($order, $user->id, 'full', 'Second reason'))
        ->toThrow(RuntimeException::class);
});
