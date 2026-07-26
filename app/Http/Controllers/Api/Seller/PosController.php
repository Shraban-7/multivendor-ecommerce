<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Services\OrderService;
use App\Domain\Product\Models\Product;
use App\Domain\Vendor\Models\SellerDraftCart;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService,
    ) {}

    public function index()
    {
        $sellerId = Auth::id();

        return apiResponse([
            'products' => Product::where('seller_id', $sellerId)
                ->where('status', 1)
                ->with(['category'])
                ->get(['id', 'name', 'slug', 'thumbnail', 'price', 'compare_price', 'stock_in', 'stock_out', 'category_id']),
            'drafts' => SellerDraftCart::where('seller_id', $sellerId)->get(),
        ]);
    }

    public function cartAdd(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product = Product::where('id', $request->product_id)->where('seller_id', Auth::id())->firstOrFail();

        if ($product->available_stock < $request->quantity) {
            return errorResponse('Insufficient stock.');
        }

        $cart = session()->get("pos_cart_" . Auth::id(), []);
        $productId = $request->product_id;
        $price = $request->price ?? ($product->compare_price ?? $product->price);

        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += (int) $request->quantity;
            $cart[$productId]['subtotal'] = $cart[$productId]['quantity'] * $cart[$productId]['price'];
        } else {
            $cart[$productId] = [
                'product_id' => $productId,
                'name' => $product->name,
                'thumbnail' => $product->thumbnail,
                'quantity' => (int) $request->quantity,
                'price' => (float) $price,
                'subtotal' => (float) $price * (int) $request->quantity,
            ];
        }

        session()->put("pos_cart_" . Auth::id(), $cart);

        return apiResponse([
            'cart' => array_values($cart),
            'total' => array_sum(array_column($cart, 'subtotal')),
        ], 'Item added to POS cart.');
    }

    public function cartUpdate(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required',
            'quantity' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $cart = session()->get("pos_cart_" . Auth::id(), []);

        if (isset($cart[$request->product_id])) {
            if ($request->quantity === 0) {
                unset($cart[$request->product_id]);
            } else {
                $cart[$request->product_id]['quantity'] = (int) $request->quantity;
                $cart[$request->product_id]['subtotal'] = $cart[$request->product_id]['quantity'] * $cart[$request->product_id]['price'];
            }
        }

        session()->put("pos_cart_" . Auth::id(), $cart);

        return apiResponse([
            'cart' => array_values($cart),
            'total' => array_sum(array_column($cart, 'subtotal')),
        ], 'Cart updated.');
    }

    public function cartItemRemove(Request $request)
    {
        $validator = validateRequest($request, [
            'product_id' => 'required',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $cart = session()->get("pos_cart_" . Auth::id(), []);
        unset($cart[$request->product_id]);
        session()->put("pos_cart_" . Auth::id(), $cart);

        return apiResponse([
            'cart' => array_values($cart),
            'total' => array_sum(array_column($cart, 'subtotal')),
        ], 'Item removed.');
    }

    public function cartClear()
    {
        session()->forget("pos_cart_" . Auth::id());

        return successResponse('Cart cleared.');
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get("pos_cart_" . Auth::id(), []);

        if (empty($cart)) {
            return errorResponse('Cart is empty.');
        }

        $validator = validateRequest($request, [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'nullable|string',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $sellerId = Auth::id();
        $subTotal = array_sum(array_column($cart, 'subtotal'));
        $paidAmount = (float) ($request->paid_amount ?? $subTotal);
        $dueAmount = max(0, $subTotal - $paidAmount);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => Auth::id(),
                'seller_id' => $sellerId,
                'invoice_id' => Order::generateInvoiceID($sellerId),
                'sub_total' => $subTotal,
                'total' => $subTotal,
                'payable' => $subTotal,
                'due' => $dueAmount,
                'paid' => $paidAmount,
                'status' => OrderStatus::COMPLETED->value,
                'payment_type' => $request->payment_method ?? 'cash',
                'pos_order' => true,
            ]);

            foreach ($cart as $item) {
                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'sub_total' => $item['subtotal'],
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->increment('stock_out', $item['quantity']);
                }
            }

            DB::commit();
            session()->forget("pos_cart_" . Auth::id());

            return apiResponse([
                'order_id' => $order->id,
                'invoice_id' => $order->invoice_id,
                'total' => $subTotal,
                'paid' => $paidAmount,
                'due' => $dueAmount,
            ], 'POS order placed successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return errorResponse($e->getMessage());
        }
    }

    public function saveDraft(Request $request)
    {
        $cart = session()->get("pos_cart_" . Auth::id(), []);

        if (empty($cart)) {
            return errorResponse('Cart is empty.');
        }

        $draft = SellerDraftCart::create([
            'seller_id' => Auth::id(),
            'cart_data' => json_encode($cart),
        ]);

        session()->forget("pos_cart_" . Auth::id());

        return apiResponse(['draft' => $draft], 'Draft saved.');
    }

    public function clearDraft($draft)
    {
        SellerDraftCart::where('id', $draft)->where('seller_id', Auth::id())->delete();

        return successResponse('Draft cleared.');
    }

    public function customerSearch(Request $request)
    {
        $q = $request->input('q');

        $customers = \App\Domain\Auth\Models\User::where(function ($w) use ($q) {
            $w->where('name', 'like', "%{$q}%")
              ->orWhere('phone', 'like', "%{$q}%")
              ->orWhere('email', 'like', "%{$q}%");
        })->limit(20)->get(['id', 'name', 'phone', 'email']);

        return apiResponse($customers);
    }

    public function sales(Request $request)
    {
        $orders = Order::where('seller_id', Auth::id())
            ->where('pos_order', true)
            ->with(['items.product'])
            ->latest()
            ->paginate($request->input('limit', 15));

        return apiResourceResponse($orders->through(fn ($o) => [
            'id' => $o->id,
            'invoice_id' => $o->invoice_id,
            'customer_name' => $o->billing_address?->customer_name ?? 'Walk-in',
            'total' => (float) $o->total,
            'paid' => (float) ($o->paid ?? 0),
            'due' => (float) ($o->due ?? 0),
            'payment_type' => $o->payment_type,
            'items_count' => $o->items->count(),
            'created_at' => $o->created_at,
        ]));
    }

    public function pay(Request $request, Order $order)
    {
        if ($order->seller_id !== Auth::id() || ! $order->pos_order) {
            return errorResponse('Unauthorized.', 403);
        }

        $validator = validateRequest($request, [
            'amount' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $newPaid = min((float) ($order->paid ?? 0) + (float) $request->amount, $order->total);
        $order->update([
            'paid' => $newPaid,
            'due' => max(0, $order->total - $newPaid),
        ]);

        return apiResponse([
            'paid' => $order->paid,
            'due' => $order->due,
        ], 'Payment collected successfully.');
    }
}
