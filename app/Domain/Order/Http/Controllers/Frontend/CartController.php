<?php

namespace App\Domain\Order\Http\Controllers\Frontend;

use App\Domain\Order\Models\Cart;
use App\Domain\Order\Models\CartItem;
use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(
        private readonly CartRepositoryInterface $cartRepo,
    ) {}

    public function add(Request $request)
    {
        if (Auth::guard('seller')->check() || Auth::guard('admin')->check()) {
            return response()->json([
                'error' => 'Please login as a user.',
            ], 403);
        }

        if (! Auth::check()) {
            return response()->json([
                'error' => 'Please login to continue cart.',
            ], 401);
        }

        $data = $request->validate([
            'product_id' => 'required',
            'variant_id' => 'nullable|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $variant = ! empty($data['variant_id'])
            ? ProductVariant::find($data['variant_id'])
            : null;
        $userId = Auth::id();
        $product = Product::find($data['product_id']);

        if (! $product) {
            return response()->json(['success' => false, 'warning' => 'Product not found']);
        }

        $hasVariants = $product->variants()->count() > 0;

        if ($hasVariants && empty($data['variant_id'])) {
            return response()->json(['success' => false, 'warning' => 'Please select all product options before adding to cart.']);
        }

        $availableStock = $variant
            ? (int) $variant->available_stock
            : (int) $product->total_stock;

        $existingQty = 0;
        $cart = Cart::firstOrCreate(
            ['user_id' => $userId, 'seller_id' => $product->seller_id],
        );

        $variantId = $variant->id ?? null;
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->where('product_id', $product->id)
            ->first();

        if ($cartItem) {
            $existingQty = (int) $cartItem->quantity;
        }

        if (($existingQty + $data['quantity']) > $availableStock) {
            return response()->json(['success' => false, 'warning' => 'Not Enough stock']);
        }

        if (! empty($variant)) {
            $price = $variant->compare_price ?? $variant->price;
        } else {
            $price = $product->compare_price ?? $product->price;
        }

        if ($cartItem) {
            $cartItem->quantity = $existingQty + $data['quantity'];
            $cartItem->price = $price;
            $cartItem->save();
        } else {
            $this->cartRepo->addItem($cart, [
                'product_id' => $product->id,
                'quantity' => $data['quantity'],
                'price' => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart',
            'action' => 'add_to_cart',
        ]);
    }

    public function details(Request $request)
    {
        $categoryIds = $subcategoryIds = $brandIds = $addedItemIds = [];

        $carts = Cart::where('user_id', Auth::id())
            ->with('cart_items.product', 'cart_items.variant.color', 'cart_items.variant.size')
            ->get()
            ->groupBy(function ($cart) {
                return $cart->cart_items->first()->product->seller_id ?? null;
            });

        $grand_total = 0;
        $sub_total = 0;

        foreach ($carts as $seller_id => $cartGroup) {
            $seller = Seller::find($seller_id);
            foreach ($cartGroup as $cart) {
                foreach ($cart->cart_items as $item) {
                    $quantity = $item->quantity;
                    $base_price = $item->original_price;
                    $paid_price = $item->discounted_price;
                    $sub_total += $base_price * $quantity;
                    $grand_total += $paid_price * $quantity;

                    $addedItemIds[] = $item->product->id;
                    if (! is_null($item->product->category_id)) {
                        $categoryIds[] = $item->product->category_id;
                    }
                    if (! is_null($item->product->subcategory_id)) {
                        $subcategoryIds[] = $item->product->subcategory_id;
                    }
                    if (! is_null($item->product->brand_id)) {
                        $brandIds[] = $item->product->brand_id;
                    }
                }
            }
        }

        $products = Product::query()
            ->withDefaultRelations()
            ->whereNotIn('id', $addedItemIds)
            ->where(function ($query) use ($categoryIds, $subcategoryIds, $brandIds) {
                $query->when(! empty($categoryIds), fn ($q) => $q->orWhereIn('category_id', $categoryIds))
                    ->when(! empty($subcategoryIds), fn ($q) => $q->orWhereIn('subcategory_id', $subcategoryIds))
                    ->when(! empty($brandIds), fn ($q) => $q->orWhereIn('brand_id', $brandIds));
            })
            ->latest('id')
            ->limit(50)
            ->get()
            ->sortByDesc(function ($product) use ($categoryIds, $subcategoryIds, $brandIds) {
                $score = 0;
                if (in_array($product->subcategory_id, $subcategoryIds ?? [])) {
                    $score += 3;
                }
                if (in_array($product->category_id, $categoryIds ?? [])) {
                    $score += 2;
                }
                if (in_array($product->brand_id, $brandIds ?? [])) {
                    $score += 1;
                }

                return $score;
            })
            ->take(16)
            ->values();

        $discount = $sub_total - $grand_total;

        $total_products_count = $carts->flatten()->pluck('cart_items')->flatten()->count();

        return view('frontend.cart.details', compact('carts', 'grand_total', 'total_products_count', 'sub_total', 'discount', 'products'));
    }

    public function update(Request $request)
    {
        $cartItem = CartItem::where('id', $request->id)->first();

        if ($cartItem) {
            $cartItem->update(['quantity' => $request->quantity]);

            $subTotal = CartItem::where('cart_id', $request->cart_id)
                ->with('product', 'variant')
                ->get()
                ->sum(function ($item) {
                    return $item->quantity * $item->price;
                });

            $grandTotal = CartItem::where('cart_id', $request->cart_id)
                ->with('product', 'variant')
                ->get()
                ->sum(function ($item) {
                    return $item->quantity * $item->original_price;
                });

            $discount = $grandTotal - $subTotal;
            $totalProductsCount = CartItem::where('cart_id', $request->cart_id)->count();

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully',
                'order_subtotal' => number_format($subTotal, 2),
                'order_total' => number_format($grandTotal, 2),
                'discount' => number_format($discount, 2),
                'total_products_count' => $totalProductsCount,
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Product not found in cart']);
    }

    public function delete(Request $request)
    {
        $cartItem = CartItem::where('id', $request->id)->first();

        if (! $cartItem) {
            return response()->json(['success' => false, 'message' => 'Product not found in cart']);
        }

        $cartId = $cartItem->cart_id;
        $this->cartRepo->removeItem($cartItem->id);
        $remainingItems = CartItem::where('cart_id', $cartId)->count();

        if ($remainingItems === 0) {
            $this->cartRepo->delete($cartId);
        }

        return response()->json(['success' => true, 'message' => 'Product removed from cart']);
    }

    public function getLiveCartData()
    {
        $cartCount = 0;
        $sub_total = 0;
        $grand_total = 0;

        if (Auth::check()) {
            $carts = $this->cartRepo->findByUserId(Auth::id())->load('cart_items.product', 'cart_items.variant');

            foreach ($carts as $cart) {
                foreach ($cart->cart_items as $item) {
                    $item_total = $item->quantity * $item->price;
                    $sub_total += $item_total;
                    $grand_total += $item_total;
                    $cartCount++;
                }
            }
        }

        return response()->json([
            'cartCount' => $cartCount,
            'totalPrice' => money($grand_total),
        ]);
    }
}
