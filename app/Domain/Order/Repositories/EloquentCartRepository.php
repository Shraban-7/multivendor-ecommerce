<?php

namespace App\Domain\Order\Repositories;

use App\Domain\Order\Models\BillingAddress;
use App\Domain\Order\Models\Cart;
use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Models\Coupon;
use App\Domain\Order\Models\Wishlist;
use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function findById(int $id): ?Cart
    {
        return Cart::find($id);
    }

    public function findByUserId(int $userId): Collection
    {
        return Cart::where('user_id', $userId)->get();
    }

    public function findUserCartBySeller(int $userId, int $sellerId): ?Cart
    {
        return Cart::where('user_id', $userId)
            ->where('seller_id', $sellerId)
            ->first();
    }

    public function create(array $data): Cart
    {
        return Cart::create($data);
    }

    public function delete(int $id): bool
    {
        return Cart::destroy($id) > 0;
    }

    public function addItem(Cart $cart, array $data): CartItem
    {
        return $cart->cart_items()->create($data);
    }

    public function removeItem(int $cartItemId): bool
    {
        return CartItem::destroy($cartItemId) > 0;
    }

    public function clearCart(Cart $cart): void
    {
        $cart->cart_items()->delete();
    }

    public function getCartItems(Cart $cart): Collection
    {
        return $cart->cart_items()->with(['product', 'variant'])->get();
    }

    public function getCount(int $userId): int
    {
        return Cart::getCount($userId);
    }

    public function getWishlistByUser(int $userId): Collection
    {
        return Wishlist::where('user_id', $userId)->with('product')->get();
    }

    public function addToWishlist(array $data): Wishlist
    {
        return Wishlist::create($data);
    }

    public function removeFromWishlist(int $id): bool
    {
        return Wishlist::destroy($id) > 0;
    }

    public function findCouponByCode(string $code): ?Coupon
    {
        return Coupon::where('code', $code)->first();
    }

    public function findUserBillingAddress(int $userId): ?BillingAddress
    {
        return BillingAddress::where('user_id', $userId)
            ->where('is_default', true)
            ->first();
    }
}
