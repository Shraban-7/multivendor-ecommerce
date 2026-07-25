<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class VendorService
{
    /**
     * Create a new vendor account (pending approval).
     */
    public function register(array $data): Seller
    {
        $data['username'] = str_slug('sellers', 'username', $data['name']);
        $data['code'] = Seller::generateSellerCode($data['name']);
        $data['status'] = Seller::PENDING;

        return Seller::create($data);
    }

    /**
     * Approve a vendor and set commission details.
     */
    public function approve(Seller $seller, array $data): void
    {
        $seller->update(array_merge($data, ['status' => Seller::ACTIVE]));
    }

    /**
     * Toggle the vendor block/active status.
     */
    public function setStatus(Seller $seller, int $status): void
    {
        $seller->update(['status' => $status]);
    }

    /**
     * Soft-delete a vendor (mark as deleted).
     */
    public function softDelete(Seller $seller): void
    {
        $seller->update(['status' => Seller::DELETED]);
    }

    /**
     * Restore a soft-deleted vendor to active.
     */
    public function restore(Seller $seller): void
    {
        $seller->update(['status' => Seller::ACTIVE]);
    }

    /**
     * Permanently delete a vendor and all related data.
     */
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
            $seller->forceDelete();
        });
    }

    /**
     * Update vendor personal, business, or document profile section.
     */
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
        } elseif ($section === 'business') {
            foreach (['business_logo', 'shop_image'] as $field) {
                if (isset($data[$field]) && $data[$field] !== null) {
                    if ($seller->$field) {
                        delete_file($seller->$field);
                    }
                    $folder = $field === 'business_logo' ? 'logo' : 'shop';
                    $data[$field] = upload_file($data[$field], "images/{$usernameForPath}/{$folder}");
                } else {
                    $data[$field] = $seller->$field;
                }
            }
        } elseif ($section === 'documents') {
            if (isset($data['trade_license_image']) && $data['trade_license_image'] !== null) {
                if ($seller->trade_license_image) {
                    delete_file($seller->trade_license_image);
                }
                $data['trade_license_image'] = upload_file($data['trade_license_image'], "images/{$usernameForPath}/documents");
            } else {
                $data['trade_license_image'] = $seller->trade_license_image;
            }
        } elseif ($section === 'password') {
            $data['password'] = Hash::make($data['password']);
            unset($data['current_password'], $data['password_confirmation']);
        }

        $seller->update($data);
    }

    /**
     * Create an employee for a seller.
     */
    public function createEmployee(Seller $seller, array $data): SellerEmployee
    {
        $data['seller_id'] = $seller->id;

        return SellerEmployee::create($data);
    }

    /**
     * Update employee permissions.
     */
    public function setEmployeePermissions(SellerEmployee $employee, array $permissions): void
    {
        $employee->update(['permissions' => $permissions]);
    }

    /**
     * Toggle employee active status.
     */
    public function toggleEmployeeActive(SellerEmployee $employee): void
    {
        $employee->update(['is_active' => ! $employee->is_active]);
    }

    /**
     * Toggle best-seller flag for a vendor.
     */
    public function setBestSeller(Seller $seller, bool $isBestSeller): void
    {
        $seller->update(['is_best_seller' => $isBestSeller]);
    }
}
