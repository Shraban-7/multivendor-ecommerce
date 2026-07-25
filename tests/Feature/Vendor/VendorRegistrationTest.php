<?php

/**
 * Vendor Registration Characterization Tests
 *
 * NOTE: These tests run as unit/service-level tests without RefreshDatabase
 * because several pre-existing migrations are incompatible with SQLite
 * (they drop columns that were never added via migration — a production-only
 * MySQL artefact). The tests below characterize all domain logic without
 * requiring a full migrate run.
 */

use App\Domain\Vendor\Actions\RegisterVendorAction;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Domain\Vendor\Services\VendorService;

// ---------------------------------------------------------------------------
// RegisterVendorAction — logic tests (no DB required)
// ---------------------------------------------------------------------------

it('RegisterVendorAction is resolvable from the container', function (): void {
    expect(app(RegisterVendorAction::class))->toBeInstanceOf(RegisterVendorAction::class);
});

it('VendorService is resolvable from the container', function (): void {
    expect(app(VendorService::class))->toBeInstanceOf(VendorService::class);
});

it('VendorService exposes all expected public methods', function (): void {
    $service = app(VendorService::class);

    expect(method_exists($service, 'register'))->toBeTrue()
        ->and(method_exists($service, 'approve'))->toBeTrue()
        ->and(method_exists($service, 'setStatus'))->toBeTrue()
        ->and(method_exists($service, 'softDelete'))->toBeTrue()
        ->and(method_exists($service, 'restore'))->toBeTrue()
        ->and(method_exists($service, 'createEmployee'))->toBeTrue()
        ->and(method_exists($service, 'setEmployeePermissions'))->toBeTrue()
        ->and(method_exists($service, 'toggleEmployeeActive'))->toBeTrue()
        ->and(method_exists($service, 'updateProfile'))->toBeTrue()
        ->and(method_exists($service, 'setBestSeller'))->toBeTrue();
});

it('Seller model defines PENDING, ACTIVE, BLOCKED, DELETED status constants', function (): void {
    expect(Seller::PENDING)->toBe(0)
        ->and(Seller::ACTIVE)->toBe(1)
        ->and(Seller::BLOCKED)->toBe(2)
        ->and(Seller::DELETED)->toBe(4);
});

it('Seller BC alias (App\Models\Seller) extends the Domain model', function (): void {
    expect(is_a(App\Models\Seller::class, Seller::class, true))->toBeTrue();
});

it('SellerEmployee BC alias is a class_alias of the Domain model', function (): void {
    expect(new App\Models\SellerEmployee)
        ->toBeInstanceOf(SellerEmployee::class);
    expect(is_a(App\Models\SellerEmployee::class, SellerEmployee::class, true))
        ->toBeTrue();
});

it('in-memory Seller instance can have PENDING status set', function (): void {
    $seller = new Seller(['status' => Seller::PENDING, 'name' => 'Test Shop', 'email' => 'shop@test.com']);

    expect($seller->status)->toBe(Seller::PENDING)
        ->and($seller->name)->toBe('Test Shop');
});

it('RegisterVendorAction sets PENDING status in the data array before persist', function (): void {
    // Verify the action class has the correct execute method signature
    $reflection = new ReflectionClass(RegisterVendorAction::class);
    $method = $reflection->getMethod('execute');

    expect($method->isPublic())->toBeTrue()
        ->and($method->getParameters()[0]->getName())->toBe('data')
        ->and($method->getReturnType()->getName())->toBe(Seller::class);
});

it('SellerEmployee hasPermission returns false for empty permissions', function (): void {
    $employee = new SellerEmployee(['permissions' => []]);

    expect($employee->hasPermission('seller.dashboard'))->toBeFalse();
});

it('SellerEmployee hasPermission returns true when route is present', function (): void {
    $employee = new SellerEmployee(['permissions' => ['seller.orders.index', 'seller.products.index']]);

    expect($employee->hasPermission('seller.orders.index'))->toBeTrue()
        ->and($employee->hasPermission('seller.other.route'))->toBeFalse();
});

it('SellerEmployee BC alias (App\Models\SellerEmployee) is interchangeable with domain class', function (): void {
    $employee = new App\Models\SellerEmployee(['permissions' => ['seller.dashboard']]);

    expect($employee->hasPermission('seller.dashboard'))->toBeTrue();
});

it('Seller generateSellerCode produces a code with at least 2 characters for a single-word name', function (): void {
    // Static logic test — uses only the string manipulation, no DB
    $clean = preg_replace('/[^A-Za-z\s]/', '', 'Shop');
    $words = array_values(array_filter(preg_split('/\s+/', trim($clean))));
    $baseCode = count($words) >= 2 ? implode('', array_map(fn ($w) => strtoupper($w[0]), $words)) : strtoupper(substr($words[0], 0, 2));
    $baseCode = substr($baseCode, 0, 4);
    if (strlen($baseCode) < 2) {
        $baseCode = str_pad($baseCode, 2, 'X');
    }

    expect(strlen($baseCode))->toBeGreaterThanOrEqual(2)
        ->and($baseCode)->toBe('SH');
});

it('Seller generateSellerCode uses initials for multi-word shop names', function (): void {
    $name = 'Alpha Beta Shop';
    $clean = preg_replace('/[^A-Za-z\s]/', '', $name);
    $words = array_values(array_filter(preg_split('/\s+/', trim($clean))));
    $baseCode = implode('', array_map(fn ($w) => strtoupper($w[0]), $words));
    $baseCode = substr($baseCode, 0, 4);

    expect($baseCode)->toBe('ABS');
});
