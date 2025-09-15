<?php

namespace App\Http\Controllers\Seller;

use App\Models\Order;
use App\Models\Seller;
use App\Models\PosCart;
use App\Models\Product;
use App\Models\Category;
use Dotenv\Parser\Value;
use App\Enums\OrderStatus;
use App\Models\PosCartItem;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::where('seller_id', get_seller_id())->with('variants.option_values')->get();

        $categories = Category::limit(5)->get();

        $cart = PosCart::where('seller_id', get_seller_id())->first();

        $cartItems = $cart ? $cart->items()->with('variant.product')->get() : collect();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $vat_amount = $cartItems->sum(function ($item) {
            $unitPrice = $item->price;
            return (($item->variant->product->vat_percent * $unitPrice) / 100) * $item->quantity;
        });

        $discount = $cartItems->sum(function ($item) {
            $unitPrice = $item->price;
            $originalPrice = $item->variant->selling_price;
            return ($originalPrice - $unitPrice) * $item->quantity;
        });

        $total = $subtotal - $discount + $vat_amount;

        return view('seller.pos', compact('products', 'categories', 'cart', 'cartItems', 'subtotal', 'discount', 'vat_amount', 'total'));
    }

    public function cart_add(Request $request)
    {
        $data = $request->validate([
            'variant_id' => 'nullable',
            'quantity'   => 'required|integer|min:1',
        ]);

        $cart = PosCart::firstOrCreate(
            ['seller_id' => get_seller_id()],
        );

        $variantId = $data['variant_id'];
        $variant = ProductVariant::find($variantId);
        $price = $variant->discounted_price ?? $variant->selling_price;

        $cartItem = PosCartItem::where('pos_cart_id', $cart->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($cartItem) {
            $cartItem->quantity += $data['quantity'];
            $cartItem->save();
        } else {
            PosCartItem::create([
                'pos_cart_id' => $cart->id,
                'quantity' => $data['quantity'],
                'price' => $price,
                'product_variant_id' => $variantId,
            ]);
        }

        $cartItems = $cart->items()->with('variant.product')->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $vat_amount = $cartItems->sum(function ($item) {
            $unitPrice = $item->price;
            return (($item->variant->product->vat_percent * $unitPrice) / 100) * $item->quantity;
        });

        $discount = $cartItems->sum(function ($item) {
            $unitPrice = $item->price;
            $originalPrice = $item->variant->selling_price;
            return ($originalPrice - $unitPrice) * $item->quantity;
        });

        $total = $subtotal - $discount + $vat_amount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html'    => $html,
            'subtotal'     => money($subtotal),
            'vat_amount'   => money($vat_amount),
            'discount'     => money($discount),
            'total'        => money($total),
        ], "Product added to cart");
    }

    public function cart_update(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
            'action' => 'required|string|in:increase,decrease',
        ]);

        $item = PosCartItem::find($request->id);
        if (!$item) {
            return response()->json(['success' => false, 'message' => 'Item not found']);
        }

        if ($request->action === 'increase') {
            $item->quantity += 1;
        } elseif ($request->action === 'decrease' && $item->quantity > 1) {
            $item->quantity -= 1;
        }

        $item->save();

        $cart = $item->pos_cart;
        $cartItems = $cart->items()->with('variant.product')->get();

        $subtotal = $cartItems->sum(fn($i) => $i->price * $i->quantity);
        $vat_amount = $cartItems->sum(fn($i) => ($i->variant->product->vat_percent * $i->price / 100) * $i->quantity);
        $discount = $cartItems->sum(fn($i) => ($i->variant->selling_price - $i->price) * $i->quantity);
        $total = $subtotal - $discount + $vat_amount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html'    => $html,
            'subtotal'     => money($subtotal),
            'vat_amount'   => money($vat_amount),
            'discount'     => money($discount),
            'total'        => money($total),
        ], "Cart Updated Successfully");
    }

    public function remove_cart_item(Request $request)
    {
        $itemId = $request->id;
        $item = PosCartItem::find($itemId);

        if (!$item) {
            return errorResponse("Item not found", 404);
        }

        $cart = $item->pos_cart;

        if (!$cart) {
            return errorResponse("Cart not found", 404);
        }

        $item->delete();

        $cartItems = $cart->items()->with('variant.product')->get();

        $subtotal = $cartItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        $vat_amount = $cartItems->sum(function ($item) {
            $unitPrice = $item->price;
            return (($item->variant->product->vat_percent * $unitPrice) / 100) * $item->quantity;
        });

        $discount = $cartItems->sum(function ($item) {
            $unitPrice = $item->price;
            $originalPrice = $item->variant->selling_price;
            return ($originalPrice - $unitPrice) * $item->quantity;
        });

        $total = $subtotal - $discount + $vat_amount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html'    => $html,
            'subtotal'     => money($subtotal),
            'vat_amount'   => money($vat_amount),
            'discount'     => money($discount),
            'total'        => money($total),
        ], "Item Remove From Cart Successfully!");
    }

    public function cart_clear(Request $request)
    {
        $cart = PosCart::where('seller_id', get_seller_id())->first();

        if (!$cart) {
            return errorResponse("No Cart Items Found!");
        }

        if ($cart) {
            $cart->items()->delete();
            $cart->delete();
        }

        return successResponse("Cart Clear Successfully");
    }

    public function place_order()
    {
        $cart = PosCart::where('seller_id', get_seller_id())->first();

        if (!$cart) {
            return errorResponse("No items found in the cart!");
        }

        $cartItems = $cart->items()->with('variant.product')->get();

        $vat_amount = 0;
        $sub_total = 0;
        $discount = 0;

        foreach ($cartItems as $item) {
            $product = $item->variant->product;
            $variant = $item->variant;
            $unitPrice = $item->price;
            $itemTotal = $item->quantity * $unitPrice;
            $itemDiscount = $item->quantity * ($variant->selling_price - $variant->discounted_price);
            $vat_amount += floatval(($product->vat_percent * $unitPrice) / 100) * $variant->quantity;
            $sub_total += $itemTotal;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'buying_price' => $variant->buying_price,
                'unit_price' => $item->price,
                'quantity' => $item->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemTotal,
                'vat_percent' => $product->vat_percent,
                'vat_amount' => floatval(($product->vat_percent * $unitPrice) / 100) * $item->quantity,
            ];
        }

        $seller = Seller::where('id', get_seller_id())->first();

        $total_commission = 0;

        if ($seller->commission_amount != null && $seller->commission_type != null) {
            if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = ($sub_total + $vat_amount) * ($seller->commission_amount / 100);
            } else if ($seller->commission_type === CommissionType::FLAT->value) {
                $total_commission = $seller->commission_amount;
            }
        }

        $payableAmount = $sub_total + $vat_amount;
        $sellerEarning = $payableAmount - $total_commission;

        $invoiceId = Order::generateInvoiceID();

        $order = Order::create([
            'seller_id' => get_seller_id(),
            'seller_employee_id' => $employee->id ??  null,
            'invoice_id' => $invoiceId,
            'sub_total' => $sub_total,
            'total' => $sub_total + $vat_amount,
            'discount' => $discount,
            'vat_amount' => $vat_amount,
            'payable' => $payableAmount,
            'due' => $payableAmount,
            'commission_type' => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'seller_earnings' => $sellerEarning,
            'total_commission' => $total_commission,
            'status' => OrderStatus::PENDING->value,
            'delivery_status' => OrderStatus::ORDER_PLACED->value,
        ]);

        $order->items()->createMany($orderItems);

        $cart->items()->delete();

        $cart->delete();

        return apiResponse([
            'invoice_id' => $order->invoice_id
        ], "Order Placed Successfully");
    }

    public function orders()
    {
        $orders = Order::where('seller_id', get_seller_id())
            ->whereNull('user_id')
            ->latest('id')
            ->get();

        return view('seller.orders.pos-orders', compact('orders'));
    }
}
