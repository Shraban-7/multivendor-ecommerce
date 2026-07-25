<?php

namespace App\Domain\Vendor\Actions;

use App\Domain\Vendor\Models\Seller;

class RegisterVendorAction
{
    /**
     * Register a new vendor (sets status to PENDING, generates code and username).
     *
     * @param  array  $data  Validated data — must include name, email, password (hashed), username (optional).
     *                       Image fields should already be stored paths or null.
     */
    public function execute(array $data): Seller
    {
        if (empty($data['username'])) {
            $data['username'] = str_slug('sellers', 'username', $data['name']);
        }

        if (empty($data['code'])) {
            $data['code'] = Seller::generateSellerCode($data['name']);
        }

        $data['status'] = Seller::PENDING;

        return Seller::create($data);
    }
}
