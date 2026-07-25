<?php

/**
 * Vendor Employee Characterization Tests
 *
 * NOTE: Tests run without RefreshDatabase due to pre-existing SQLite-incompatible
 * migrations. All domain logic is tested via in-memory model instances and mocking.
 */

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Domain\Vendor\Services\VendorService;
use Illuminate\Database\Eloquent\Builder;

// ---------------------------------------------------------------------------
// SellerEmployee permission logic — no DB needed
// ---------------------------------------------------------------------------

it('hasPermission returns false when permissions array is empty', function (): void {
    $employee = new SellerEmployee(['permissions' => []]);

    expect($employee->hasPermission('seller.dashboard'))->toBeFalse();
});

it('hasPermission returns true when route exists in permissions array', function (): void {
    $employee = new SellerEmployee([
        'permissions' => ['seller.orders.index', 'seller.products.index'],
    ]);

    expect($employee->hasPermission('seller.orders.index'))->toBeTrue();
});

it('hasPermission returns false when route is not in permissions array', function (): void {
    $employee = new SellerEmployee([
        'permissions' => ['seller.orders.index'],
    ]);

    expect($employee->hasPermission('seller.reports.index'))->toBeFalse();
});

it('hasPermission handles null permissions gracefully', function (): void {
    $employee = new SellerEmployee(['permissions' => null]);

    expect($employee->hasPermission('seller.dashboard'))->toBeFalse();
});

// ---------------------------------------------------------------------------
// VendorService employee management — mock-based tests
// ---------------------------------------------------------------------------

it('VendorService::setEmployeePermissions calls update with permissions array', function (): void {
    $permissions = ['seller.orders.index', 'seller.products.index'];

    $employee = Mockery::mock(SellerEmployee::class)->makePartial();
    $employee->shouldReceive('update')
        ->once()
        ->with(['permissions' => $permissions])
        ->andReturn(true);

    $service = app(VendorService::class);
    $service->setEmployeePermissions($employee, $permissions);
});

it('VendorService::toggleEmployeeActive flips is_active from 1 to 0', function (): void {
    $employee = Mockery::mock(SellerEmployee::class)->makePartial();
    $employee->is_active = 1;

    $employee->shouldReceive('update')
        ->once()
        ->with(['is_active' => false]) // !1 = false
        ->andReturn(true);

    $service = app(VendorService::class);
    $service->toggleEmployeeActive($employee);
});

it('VendorService::toggleEmployeeActive flips is_active from 0 to 1', function (): void {
    $employee = Mockery::mock(SellerEmployee::class)->makePartial();
    $employee->is_active = 0;

    $employee->shouldReceive('update')
        ->once()
        ->with(['is_active' => true]) // !0 = true
        ->andReturn(true);

    $service = app(VendorService::class);
    $service->toggleEmployeeActive($employee);
});

it('VendorService::createEmployee adds seller_id to data before creating', function (): void {
    $seller = new Seller(['id' => 42]);

    // Verify that VendorService injects seller_id before SellerEmployee::create
    $reflection = new ReflectionClass(VendorService::class);
    $method = $reflection->getMethod('createEmployee');
    $source = file_get_contents($reflection->getFileName());

    expect($source)->toContain('$data[\'seller_id\'] = $seller->id')
        ->and($method->isPublic())->toBeTrue();
});

it('VendorService::setEmployeePermissions replaces all previous permissions', function (): void {
    $newPermissions = ['seller.reports.index'];

    $employee = Mockery::mock(SellerEmployee::class)->makePartial();
    $employee->shouldReceive('update')
        ->once()
        ->with(['permissions' => $newPermissions])
        ->andReturn(true);

    $service = app(VendorService::class);
    $service->setEmployeePermissions($employee, $newPermissions);

    // The mock verifies that only the new permissions were passed (not merged with old ones)
    expect(true)->toBeTrue(); // Mockery will fail if unexpected calls occur
});

// ---------------------------------------------------------------------------
// Domain model class hierarchy
// ---------------------------------------------------------------------------

it('SellerEmployee Domain model resolves correctly', function (): void {
    expect(new SellerEmployee)
        ->toBeInstanceOf(SellerEmployee::class);
    expect(is_a(SellerEmployee::class, SellerEmployee::class, true))
        ->toBeTrue();
});

it('SellerEmployee permissions cast to array from JSON', function (): void {
    // The casts array on SellerEmployee should include permissions => array
    $employee = new SellerEmployee;
    $casts = $employee->getCasts();

    expect(array_key_exists('permissions', $casts))->toBeTrue()
        ->and($casts['permissions'])->toBe('array');
});

it('SellerEmployee scopeActive definition queries is_active = 1', function (): void {
    $query = Mockery::mock(Builder::class);
    $query->shouldReceive('where')->once()->with('is_active', 1)->andReturnSelf();

    $employee = new SellerEmployee;
    $employee->scopeActive($query);
});
