<?php

namespace App\Http\Controllers\Api;

use App\Domain\Order\Models\Cart;
use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use App\Domain\Product\Http\Resources\ProductListResource;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepo,
    ) {}

    public function index()
    {
        $carts = Cart::query()
            ->where('user_id', Auth::id())
            ->with([
                'cart_items.product.category',
                'cart_items.product.subcategory',
                'cart_items.variant',
                'seller.district',
                'seller.division',
            ])
            ->get();

        return apiResourceResponse(CartResource::collection($carts));
    }

    public function suggestions()
    {
        $cartProductIds = CartItem::whereHas('cart', fn ($q) => $q->where('user_id', Auth::id()))
            ->pluck('product_id')
            ->unique()
            ->values()
            ->toArray();

        $categoryIds = Product::whereIn('id', $cartProductIds)
            ->pluck('category_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $subcategoryIds = Product::whereIn('id', $cartProductIds)
            ->pluck('subcategory_id')
            ->filter()
            ->unique()
            ->values()
            ->toArray();

        $suggestedProducts = Product::query()
            ->whereNotIn('id', $cartProductIds)
            ->where(function ($query) use ($categoryIds, $subcategoryIds) {
                $query->whereIn('category_id', $categoryIds)
                    ->orWhereIn('subcategory_id', $subcategoryIds);
            })
            ->with(['category', 'subcategory', 'seller.district', 'seller.division'])
            ->paginate(15);

        return apiResourceResponse(ProductListResource::collection($suggestedProducts));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required',
            'variant_id' => 'nullable',
            'quantity' => 'required|integer|min:1',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $variant = $variantId = null;
        $userId = Auth::id();
        $data = $validator->validated();
        $product = Product::find($data['product_id']);
        $isDefault = $request->is_default ?? false;

        if (! $product) {
            return errorResponse('Product not found!');
        }

        if ($isDefault) {
            $variant = ProductVariant::where('product_id', $product->id)
                ->where('is_default', 1)
                ->first();
        }

        if ($data['variant_id'] != null) {
            $variant = ProductVariant::find($data['variant_id']);
            if (! $variant) {
                return errorResponse('Variant not found!');
            }
        }

        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'seller_id' => $product->seller_id],
        );

        if (! is_null($variant)) {
            $price = $variant->discounted_price ?? $variant->selling_price;
            $variantId = $variant->id;
        } else {
            $price = $product->discounted_price ?? $product->selling_price;
        }

        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity', $data['quantity']);
        } else {
            $this->cartRepo->addItem($cart, [
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        return apiResponse([
            'cart_count' => $this->cartRepo->getCount($userId),
        ], 'Added to cart successfully');
    }

    public function deleteItem(CartItem $item)
    {
        $cart = Cart::withCount('cart_items')->find($item->cart_id);

        $this->cartRepo->removeItem($item->id);

        if ($cart && ($cart->cart_items_count - 1) === 0) {
            $this->cartRepo->delete($cart->id);
        }

        return apiResponse([
            'cart_count' => $this->cartRepo->getCount(Auth::id()),
        ], 'Item removed successfully');
    }

    public function updateQuantity(CartItem $item, Request $request)
    {
        $validator = validateRequest($request, [
            'quantity' => 'required|numeric|min:1',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $item->update([
            'quantity' => $request->quantity,
        ]);

        return apiResponse([
            'cart_count' => $this->cartRepo->getCount(Auth::id()),
        ], 'Cart updated successfully');
    }
}
