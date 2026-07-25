<?php

namespace App\Domain\Vendor\Actions;

use App\Domain\Vendor\Models\Seller;

class ApproveVendorAction
{
    /**
     * Approve a pending vendor, set commission settings and mark active.
     *
     * @param  array  $data  Should include: commission_type, commission_amount.
     *                       status is forced to ACTIVE regardless of input.
     */
    public function execute(Seller $seller, array $data = []): Seller
    {
        $seller->update(array_merge($data, [
            'status' => Seller::ACTIVE,
        ]));

        return $seller->fresh();
    }
}
