<?php

/**
 * Vendor Approval Characterization Tests
 *
 * NOTE: Tests run without RefreshDatabase due to multiple pre-existing
 * migrations being SQLite-incompatible (drop columns never added via migration).
 * All domain logic is tested via in-memory model instances and mocking.
 */

use App\Domain\Vendor\Actions\ApproveVendorAction;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Services\VendorService;
use App\Enums\CommissionType;
use Illuminate\Database\Eloquent\Builder;

// ---------------------------------------------------------------------------
// ApproveVendorAction — logic tests
// ---------------------------------------------------------------------------

it('ApproveVendorAction is resolvable from the container', function (): void {
    expect(app(ApproveVendorAction::class))->toBeInstanceOf(ApproveVendorAction::class);
});

it('ApproveVendorAction::execute merges ACTIVE status into the data array', function (): void {
    $seller = Mockery::mock(Seller::class)->makePartial();
    $seller->id = 1;
    $seller->status = Seller::PENDING;

    $capturedData = null;
    $repo = Mockery::mock(\App\Domain\Vendor\Repositories\SellerRepositoryInterface::class);
    $repo->shouldReceive('update')
        ->once()
        ->withArgs(function ($s, $data) use (&$capturedData) {
            $capturedData = $data;

            return true;
        })
        ->andReturn(true);
    $repo->shouldReceive('findById')->with(1)->andReturn($seller);

    $action = new ApproveVendorAction($repo);
    $action->execute($seller, [
        'commission_type' => 'percentage',
        'commission_amount' => 10.0,
    ]);

    expect($capturedData['status'])->toBe(Seller::ACTIVE)
        ->and($capturedData['commission_type'])->toBe('percentage')
        ->and($capturedData['commission_amount'])->toBe(10.0);
});

it('ApproveVendorAction always forces status=ACTIVE regardless of caller input', function (): void {
    $seller = Mockery::mock(Seller::class)->makePartial();
    $seller->id = 1;

    $capturedData = null;
    $repo = Mockery::mock(\App\Domain\Vendor\Repositories\SellerRepositoryInterface::class);
    $repo->shouldReceive('update')
        ->once()
        ->withArgs(function ($s, $data) use (&$capturedData) {
            $capturedData = $data;

            return true;
        })
        ->andReturn(true);
    $repo->shouldReceive('findById')->with(1)->andReturn($seller);

    $action = new ApproveVendorAction($repo);
    $action->execute($seller, ['status' => Seller::PENDING]); // caller tries PENDING

    expect($capturedData['status'])->toBe(Seller::ACTIVE);
});

// ---------------------------------------------------------------------------
// VendorService status management — mock-based tests
// ---------------------------------------------------------------------------

it('VendorService::setStatus calls update with the given status', function (): void {
    $seller = Mockery::mock(Seller::class)->makePartial();
    $seller->shouldReceive('update')->once()->with(['status' => Seller::BLOCKED])->andReturn(true);

    $service = app(VendorService::class);
    $service->setStatus($seller, Seller::BLOCKED);
});

it('VendorService::restore calls update with ACTIVE status', function (): void {
    $seller = Mockery::mock(Seller::class)->makePartial();
    $seller->shouldReceive('update')->once()->with(['status' => Seller::ACTIVE])->andReturn(true);

    $service = app(VendorService::class);
    $service->restore($seller);
});

it('VendorService::softDelete calls update with DELETED status', function (): void {
    $seller = Mockery::mock(Seller::class)->makePartial();
    $seller->shouldReceive('update')->once()->with(['status' => Seller::DELETED])->andReturn(true);

    $service = app(VendorService::class);
    $service->softDelete($seller);
});

it('VendorService::setBestSeller calls update with the is_best_seller flag', function (): void {
    $seller = Mockery::mock(Seller::class)->makePartial();
    $seller->shouldReceive('update')->once()->with(['is_best_seller' => true])->andReturn(true);

    $service = app(VendorService::class);
    $service->setBestSeller($seller, true);
});

// ---------------------------------------------------------------------------
// Seller model helper — no DB needed
// ---------------------------------------------------------------------------

it('calculateEarning computes correct percentage commission', function (): void {
    $seller = new Seller([
        'commission_type' => CommissionType::PERCENTAGE->value,
        'commission_amount' => 10,
    ]);

    $result = $seller->calculateEarning(1000);

    expect($result['total_commission'])->toBe(100.0)
        ->and($result['seller_earning'])->toBe(900.0);
});

it('calculateEarning computes correct flat commission', function (): void {
    $seller = new Seller([
        'commission_type' => CommissionType::FLAT->value,
        'commission_amount' => 50,
    ]);

    $result = $seller->calculateEarning(1000);

    expect($result['total_commission'])->toEqual(50)
        ->and($result['seller_earning'])->toEqual(950);
});

it('calculateEarning returns zero commission when commission fields are null', function (): void {
    $seller = new Seller([
        'commission_type' => null,
        'commission_amount' => null,
    ]);

    $result = $seller->calculateEarning(500);

    expect($result['total_commission'])->toEqual(0)
        ->and($result['seller_earning'])->toEqual(500);
});

it('Seller scopeActive definition targets status=ACTIVE constant', function (): void {
    // Verify the scope calls where('status', ACTIVE) — inspect query builder
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('where')->once()->with('status', Seller::ACTIVE)->andReturnSelf();

    $seller = new Seller;
    $seller->scopeActive($query);
});

it('Seller scopePending definition targets status=PENDING constant', function (): void {
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('where')->once()->with('status', Seller::PENDING)->andReturnSelf();

    $seller = new Seller;
    $seller->scopePending($query);
});
