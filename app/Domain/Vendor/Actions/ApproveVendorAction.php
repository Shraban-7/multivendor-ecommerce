<?php

namespace App\Domain\Vendor\Actions;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;

class ApproveVendorAction
{
    public function __construct(
        private readonly SellerRepositoryInterface $sellerRepo,
    ) {}

    public function execute(Seller $seller, array $data = []): Seller
    {
        $this->sellerRepo->update($seller, array_merge($data, [
            'status' => Seller::ACTIVE,
        ]));

        return $this->sellerRepo->findById($seller->id);
    }
}
