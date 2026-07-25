<?php

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Domain\Vendor\Repositories\EloquentSellerEmployeeRepository;
use App\Domain\Vendor\Repositories\EloquentSellerRepository;
use App\Domain\Vendor\Repositories\SellerEmployeeRepositoryInterface;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use App\Domain\Vendor\Services\VendorService;
use Illuminate\Database\Eloquent\Collection;
use Tests\TestCase;

uses(TestCase::class);

beforeEach(function (): void {
    $this->sellerRepo = new EloquentSellerRepository;
    $this->employeeRepo = new EloquentSellerEmployeeRepository;
});

it('implements SellerRepositoryInterface', function (): void {
    expect($this->sellerRepo)->toBeInstanceOf(SellerRepositoryInterface::class);
});

it('implements SellerEmployeeRepositoryInterface', function (): void {
    expect($this->employeeRepo)->toBeInstanceOf(SellerEmployeeRepositoryInterface::class);
});

it('SellerRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(SellerRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('findByUsername'))->toBeTrue()
        ->and($reflection->hasMethod('getPaginated'))->toBeTrue()
        ->and($reflection->hasMethod('getPendingPaginated'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('setStatus'))->toBeTrue()
        ->and($reflection->hasMethod('setBestSeller'))->toBeTrue()
        ->and($reflection->hasMethod('softDelete'))->toBeTrue()
        ->and($reflection->hasMethod('restore'))->toBeTrue()
        ->and($reflection->hasMethod('permanentDelete'))->toBeTrue();
});

it('SellerEmployeeRepositoryInterface defines all required methods', function (): void {
    $reflection = new ReflectionClass(SellerEmployeeRepositoryInterface::class);

    expect($reflection->hasMethod('findById'))->toBeTrue()
        ->and($reflection->hasMethod('getEmployeesForSeller'))->toBeTrue()
        ->and($reflection->hasMethod('store'))->toBeTrue()
        ->and($reflection->hasMethod('update'))->toBeTrue()
        ->and($reflection->hasMethod('setPermissions'))->toBeTrue()
        ->and($reflection->hasMethod('toggleActive'))->toBeTrue()
        ->and($reflection->hasMethod('delete'))->toBeTrue();
});

it('VendorService is resolvable with repository bindings', function (): void {
    $service = app(VendorService::class);

    expect($service)->toBeInstanceOf(VendorService::class);
});

it('VendorServiceProvider binds correct implementations', function (): void {
    $sellerRepo = app(SellerRepositoryInterface::class);
    $employeeRepo = app(SellerEmployeeRepositoryInterface::class);

    expect($sellerRepo)->toBeInstanceOf(EloquentSellerRepository::class)
        ->and($employeeRepo)->toBeInstanceOf(EloquentSellerEmployeeRepository::class);
});

it('can mock SellerRepositoryInterface and return a seller', function (): void {
    $seller = new Seller(['id' => 1, 'name' => 'Test Shop', 'email' => 'test@shop.com']);

    $repo = Mockery::mock(SellerRepositoryInterface::class);
    $repo->shouldReceive('findById')->with(1)->once()->andReturn($seller);

    expect($repo->findById(1))->toBe($seller);
});

it('can mock SellerEmployeeRepositoryInterface and return employees', function (): void {
    $employees = new Collection([
        new SellerEmployee(['id' => 1, 'name' => 'Emp 1']),
        new SellerEmployee(['id' => 2, 'name' => 'Emp 2']),
    ]);

    $repo = Mockery::mock(SellerEmployeeRepositoryInterface::class);
    $repo->shouldReceive('getEmployeesForSeller')->with(1)->once()->andReturn($employees);

    $result = $repo->getEmployeesForSeller(1);

    expect($result)->toBeInstanceOf(Collection::class)
        ->and($result)->toHaveCount(2);
});

it('stores and retrieves seller via repository mock', function (): void {
    $repo = Mockery::mock(SellerRepositoryInterface::class);
    $seller = new Seller(['id' => 1, 'name' => 'New Shop', 'email' => 'new@shop.com', 'status' => 0]);

    $repo->shouldReceive('store')->once()->andReturn($seller);

    $result = $repo->store(['name' => 'New Shop', 'email' => 'new@shop.com']);

    expect($result)->toBeInstanceOf(Seller::class)
        ->and($result->name)->toBe('New Shop');
});

it('update seller via repository mock returns bool', function (): void {
    $repo = Mockery::mock(SellerRepositoryInterface::class);
    $seller = new Seller(['id' => 1, 'name' => 'Shop']);

    $repo->shouldReceive('update')->with($seller, ['name' => 'Updated'])->once()->andReturn(true);

    expect($repo->update($seller, ['name' => 'Updated']))->toBeTrue();
});

it('setStatus returns bool via repository mock', function (): void {
    $repo = Mockery::mock(SellerRepositoryInterface::class);
    $seller = new Seller(['id' => 1, 'status' => 0]);

    $repo->shouldReceive('setStatus')->with($seller, 1)->once()->andReturn(true);

    expect($repo->setStatus($seller, 1))->toBeTrue();
});

it('setBestSeller returns bool via repository mock', function (): void {
    $repo = Mockery::mock(SellerRepositoryInterface::class);
    $seller = new Seller(['id' => 1]);

    $repo->shouldReceive('setBestSeller')->with($seller, true)->once()->andReturn(true);

    expect($repo->setBestSeller($seller, true))->toBeTrue();
});

it('toggles employee active status via repository mock', function (): void {
    $repo = Mockery::mock(SellerEmployeeRepositoryInterface::class);
    $employee = new SellerEmployee(['id' => 1, 'is_active' => true]);

    $repo->shouldReceive('toggleActive')->with($employee)->once()->andReturn(true);

    expect($repo->toggleActive($employee))->toBeTrue();
});

it('sets employee permissions via repository mock', function (): void {
    $repo = Mockery::mock(SellerEmployeeRepositoryInterface::class);
    $employee = new SellerEmployee(['id' => 1]);
    $permissions = ['seller.dashboard', 'seller.products.index'];

    $repo->shouldReceive('setPermissions')->with($employee, $permissions)->once()->andReturn(true);

    expect($repo->setPermissions($employee, $permissions))->toBeTrue();
});

it('deletes employee via repository mock', function (): void {
    $repo = Mockery::mock(SellerEmployeeRepositoryInterface::class);
    $employee = new SellerEmployee(['id' => 1]);

    $repo->shouldReceive('delete')->with($employee)->once()->andReturn(true);

    expect($repo->delete($employee))->toBeTrue();
});
