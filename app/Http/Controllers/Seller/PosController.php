<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Models\PosCart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Customer;
use Dotenv\Parser\Value;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\PosCartItem;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::where('seller_id', get_seller_id())
            ->with('variants.option_values')
            ->get();

        $categories = Category::limit(5)->get();

        $cartItems = collect();
        $orderItems = collect();
        $subtotal = 0;
        $vat_amount = 0;
        $discount = 0;
        $total = 0;
        $customer_name = null;
        $customer_phone = null;

        if ($request->has('order_id')) {
            $order = Order::where('invoice_id', $request->order_id)
                ->where('seller_id', get_seller_id())
                ->with('items.variant.product')
                ->first();

            if ($order) {
                $orderItems = $order->items;
                $subtotal = $orderItems->sum(fn($item) => $item->original_price * $item->quantity);
                $vat_amount = $orderItems->sum(fn($item) => ($item->variant->product->vat_percent * $item->price / 100) * $item->quantity);
                $discount = $orderItems->sum(fn($item) => ($item->variant->discounted_price ? $item->variant->selling_price - $item->variant->discounted_price : 0) * $item->quantity);
                $total = $subtotal + $vat_amount - $discount;
                $paid = $order->paid;
                $due = $order->due;

                if($order->customer_id)
                {
                    $customer_name = $order->customer->name; 
                    $customer_phone = $order->customer->phone; 
                }
            }
        } else {
            $cart = PosCart::where('seller_id', get_seller_id())->first();
            $cartItems = $cart ? $cart->items()->with('variant.product')->get() : collect();

            $subtotal = $cartItems->sum(fn($item) => $item->variant->selling_price * $item->quantity);
            $vat_amount = $cartItems->sum(fn($item) => ($item->variant->product->vat_percent * $item->price / 100) * $item->quantity);
            $discount = $cartItems->sum(fn($item) => ($item->variant->discounted_price ? $item->variant->selling_price - $item->variant->discounted_price : 0) * $item->quantity);
            $total = $subtotal + $vat_amount - $discount;
        }

        $orders = Order::where('seller_id', get_seller_id())
            ->whereDate('created_at', Carbon::today())
            ->with('items.variant.product')
            ->latest('id')
            ->get();

        return view('seller.pos.index', compact(
            'products',
            'categories',
            'cartItems',
            'orderItems',
            'subtotal',
            'discount',
            'vat_amount',
            'total',
            'orders',
            'due',
            'paid',
            'customer_name',
            'customer_phone'
        ));
    }

    public function cartAdd(Request $request)
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
            return $item->variant->selling_price * $item->quantity;
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

        $total = $subtotal + $vat_amount - $discount;

        $html = view('components.seller.pos-cart-items', compact('cartItems'))->render();

        return apiResponse([
            'html'    => $html,
            'subtotal'     => money($subtotal),
            'vat_amount'   => money($vat_amount),
            'discount'     => money($discount),
            'total'        => money($total),
        ], "Product added to cart");
    }

    public function cartUpdate(Request $request)
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

        $subtotal = $cartItems->sum(fn($i) => $i->variant->selling_price * $i->quantity);
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

    public function removeCartItem(Request $request)
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

    public function cartClear(Request $request)
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

    public function placeOrder(Request $request)
    {
        $data = $request->validate([
            'customer_name'  => 'nullable|string|max:255|required_with:customer_phone',
            'customer_phone' => 'nullable|string|max:20|required_with:customer_name',
            'paid' => 'required|numeric',
            'due' => 'nullable|numeric',
        ]);

        $seller = Seller::find(get_seller_id());

        $data['seller_id'] = $seller->id;

        $cart = PosCart::where('seller_id', get_seller_id())->first();

        if (!$cart) {
            return errorResponse("No items found in the cart!");
        }

        $cartItems = $cart->items()->with('variant.product')->get();

        $vat_amount = 0;
        $sub_total = 0;
        $discount = 0;
        $orderItems = [];

        foreach ($cartItems as $item) {
            $product = $item->variant->product;
            $variant = $item->variant;

            if ($variant->discounted_price != null || $variant->discounted_price != 0) {
                $discount_amount = $variant->selling_price - $variant->discounted_price;
            } else {
                $discount_amount = 0;
            }

            $unitPrice = $item->price;
            $itemTotal = $item->quantity * $unitPrice;
            $itemDiscount = $item->quantity * ($discount_amount);
            $vat_amount += floatval(($product->vat_percent * $unitPrice) / 100) * $variant->quantity;
            $sub_total += $itemTotal;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $item->product_variant_id ?? null,
                'buying_price' => $variant->buying_price,
                'unit_price' => $item->price,
                'quantity' => $item->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemTotal,
                'vat_percent' => $product->vat_percent,
                'vat_amount' => floatval(($product->vat_percent * $unitPrice) / 100) * $item->quantity,
            ];
        }

        if (empty($orderItems)) {
            return errorResponse("No items found in the cart!");
        }

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
        $sellerId = get_seller_id();

        $invoiceId = Order::generateInvoiceID($sellerId, Order::ORDER_TYPE_POS);

        $order = Order::create([
            'seller_id' => get_seller_id(),
            'seller_employee_id' => $employee->id ??  null,
            'invoice_id' => $invoiceId,
            'sub_total' => $sub_total,
            'total' => $sub_total + $vat_amount,
            'discount' => $discount,
            'vat_amount' => $vat_amount,
            'payable' => $payableAmount,
            'paid' => $data['paid'],
            'due' => $data['due'],
            'commission_type' => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'seller_earnings' => $sellerEarning,
            'total_commission' => $total_commission,
            'status' => OrderStatus::PENDING->value,
            'delivery_status' => OrderStatus::ORDER_PLACED->value,
        ]);

        $order->items()->createMany($orderItems);

        $updatedVariants = [];

        foreach ($order->items as $item) {
            if (isset($item['product_variant_id'])) {
                $variant = ProductVariant::find($item['product_variant_id']);

                if ($variant) {
                    $variant->increment('stock_out', $item['quantity']);

                    $updatedVariants[] = [
                        'id' => $variant->id,
                        'availableStock' => $variant->availableStock,
                    ];
                }
            }
        }

        $cart->items()->delete();

        $cart->delete();

        $seller = Seller::find(get_seller_id());

        $sellerOrderIds = Order::where('seller_id', $seller->id)->pluck('id');

        $sellerOrderCount = OrderItem::whereIn('order_id', $sellerOrderIds)->count();

        $seller->update([
            'total_sold' => $sellerOrderCount,
        ]);

        if (!empty($data['customer_name']) || !empty($data['customer_phone'])) {

            $exist_customer = Customer::where('name', $data['customer_name'] ?? null)
                ->where('phone', $data['customer_phone'] ?? null)
                ->first();

            if (!$exist_customer) {
                $customer = Customer::create([
                    'name'  => $data['customer_name'] ?? null,
                    'phone' => $data['customer_phone'] ?? null,
                    'seller_id' => $seller->id
                ]);
            } else {
                $customer = $exist_customer;
            }

            $order->update([
                'customer_id' => $customer->id
            ]);
        } else {
            $order->update([
                'customer_id' => null
            ]);
        }

        return apiResponse([
            'invoice_id' => $order->invoice_id,
            'variants' => $updatedVariants
        ], "Order Placed Successfully");
    }

    public function customerSearch(Request $request)
    {
        $term = $request->get('term', '');
        $customers = Customer::where('name', 'LIKE', "%{$term}%")
            ->orWhere('phone', 'LIKE', "%{$term}%")
            ->take(10)
            ->get();

        $results = [];
        foreach ($customers as $c) {
            $results[] = [
                'label' => $c->name . ' (' . $c->phone . ')',
                'value' => $c->name,
                'phone' => $c->phone
            ];
        }

        return response()->json($results);
    }
}
