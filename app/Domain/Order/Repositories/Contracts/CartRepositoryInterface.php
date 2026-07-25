<?php

namespace App\Domain\Order\Repositories\Contracts;

use App\Domain\Order\Models\BillingAddress;
use App\Domain\Order\Models\Cart;
use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Models\Coupon;
use App\Domain\Order\Models\Wishlist;
use Illuminate\Support\Collection;

interface CartRepositoryInterface
{
    public function findById(int $id): ?Cart;

    public function findByUserId(int $userId): Collection;

    public function findUserCartBySeller(int $userId, int $sellerId): ?Cart;

    public function create(array $data): Cart;

    public function delete(int $id): bool;

    public function addItem(Cart $cart, array $data): CartItem;

    public function removeItem(int $cartItemId): bool;

    public function clearCart(Cart $cart): void;

    public function getCartItems(Cart $cart): Collection;

    public function getCount(int $userId): int;

    public function getWishlistByUser(int $userId): Collection;

    public function addToWishlist(array $data): Wishlist;

    public function removeFromWishlist(int $id): bool;

    public function findCouponByCode(string $code): ?Coupon;

    public function findUserBillingAddress(int $userId): ?BillingAddress;
}
