<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Domain\Vendor\Repositories\SellerEmployeeRepositoryInterface;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorService
{
    public function __construct(
        private readonly SellerRepositoryInterface $sellerRepo,
        private readonly SellerEmployeeRepositoryInterface $employeeRepo,
    ) {}

    public function register(array $data): Seller
    {
        $data['username'] = str_slug('sellers', 'username', $data['name']);
        $data['code'] = Seller::generateSellerCode($data['name']);
        $data['status'] = Seller::PENDING;

        return $this->sellerRepo->store($data);
    }

    public function approve(Seller $seller, array $data): void
    {
        $this->sellerRepo->update($seller, array_merge($data, ['status' => Seller::ACTIVE]));
    }

    public function setStatus(Seller $seller, int $status): void
    {
        $this->sellerRepo->setStatus($seller, $status);
    }

    public function softDelete(Seller $seller): void
    {
        $this->sellerRepo->softDelete($seller);
    }

    public function restore(Seller $seller): void
    {
        $this->sellerRepo->restore($seller);
    }

    public function permanentDelete(Seller $seller): void
    {
        DB::transaction(function () use ($seller) {
            $seller->orders()->delete();
            $seller->employees()->delete();
            $seller->products()->delete();
            $seller->banner_images()->delete();
            $seller->followers()->delete();
            $seller->categories()->detach();
            $seller->chats()->delete();
            $seller->expenses()->delete();
            $seller->seller_expense_categories()->delete();

            $this->sellerRepo->permanentDelete($seller);
        });
    }

    public function updateProfile(Seller $seller, string $section, array $data): void
    {
        $usernameForPath = $seller->username;

        if ($section === 'personal') {
            if (isset($data['name']) && $seller->name !== $data['name']) {
                $data['username'] = str_slug('sellers', 'username', $data['name']);
                $usernameForPath = $data['username'];
            } else {
                $data['username'] = $seller->username;
            }

            foreach (['image', 'nid_front_image', 'nid_back_image'] as $field) {
                if (isset($data[$field]) && $data[$field] !== null) {
                    if ($seller->$field) {
                        delete_file($seller->$field);
                    }
                    $data[$field] = upload_file($data[$field], "images/{$usernameForPath}/profile");
                } else {
                    $data[$field] = $seller->$field;
                }
            }
        } elseif ($section === 'password') {
            $data['password'] = Hash::make($data['password']);
            unset($data['current_password'], $data['password_confirmation']);
        }

        $this->sellerRepo->update($seller, $data);
    }

    public function createEmployee(Seller $seller, array $data): SellerEmployee
    {
        $data['seller_id'] = $seller->id;

        return $this->employeeRepo->store($data);
    }

    public function setEmployeePermissions(SellerEmployee $employee, array $permissions): void
    {
        $this->employeeRepo->setPermissions($employee, $permissions);
    }

    public function toggleEmployeeActive(SellerEmployee $employee): void
    {
        $this->employeeRepo->toggleActive($employee);
    }

    public function setBestSeller(Seller $seller, bool $isBestSeller): void
    {
        $this->sellerRepo->setBestSeller($seller, $isBestSeller);
    }
}
