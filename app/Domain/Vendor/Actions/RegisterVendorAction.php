<?php

namespace App\Domain\Vendor\Actions;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;

class RegisterVendorAction
{
    public function __construct(
        private readonly SellerRepositoryInterface $sellerRepo,
    ) {}

    public function execute(array $data): Seller
    {
        if (empty($data['username'])) {
            $data['username'] = str_slug('sellers', 'username', $data['name']);
        }

        if (empty($data['code'])) {
            $data['code'] = Seller::generateSellerCode($data['name']);
        }

        $data['status'] = Seller::PENDING;

        return $this->sellerRepo->store($data);
    }
}
