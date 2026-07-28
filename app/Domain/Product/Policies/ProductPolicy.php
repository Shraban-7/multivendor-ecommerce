<?php

namespace App\Domain\Product\Policies;

use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\Seller;

class ProductPolicy
{
    public function viewAny(Seller $seller): bool
    {
        return true;
    }

    public function view(Seller $seller, Product $product): bool
    {
        return $seller->id === $product->seller_id;
    }

    public function create(Seller $seller): bool
    {
        return true;
    }

    public function update(Seller $seller, Product $product): bool
    {
        return $seller->id === $product->seller_id;
    }

    public function delete(Seller $seller, Product $product): bool
    {
        return $seller->id === $product->seller_id;
    }
}
