<?php
namespace App\Http\Controllers\Api;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = validateRequest($request, [
            'seller_id'      => 'required|exists:sellers,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'nullable|email',
            'customer_phone' => 'required|string|max:20',
            'address'        => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $validator->validated();

        $selectedSellerId = $request->input('seller_id');

        $seller = Seller::findOrFail($selectedSellerId);

        $cart = Cart::with('cart_items.product', 'cart_items.variant')
            ->where('user_id', $user->id)
            ->where('seller_id', $selectedSellerId)
            ->first();

        if (! $cart || $cart->cart_items->isEmpty()) {
            return response()->json([
                'status'  => false,
                'message' => 'Cart is empty or not found for the selected seller.',
            ], 404);
        }

        $total        = 0;
        $discount     = 0;
        $tax          = 0;
        $shipping_fee = $seller->shipping_cost ?? 0;

        $orderItems = [];

        foreach ($cart->cart_items as $cartItem) {
            $product = $cartItem->product;
            $variant = $cartItem->variant;

            $unitPrice    = $cartItem->price;
            $itemTotal    = $unitPrice * $cartItem->quantity;
            $itemDiscount = $product->discount * $cartItem->quantity;
            $itemTax      = floatval($product->tax) * $cartItem->quantity;

            $total += $itemTotal;
            $discount += $itemDiscount;
            $tax += $itemTax;

            $orderItems[] = [
                'product_id'            => $product->id,
                'product_variant_ids'   => json_encode($cartItem->product_variant_ids ?? []),
                'product_variant_price' => $unitPrice,
                'buying_price'          => $product->buying_price,
                'unit_price'            => $unitPrice,
                'quantity'              => $cartItem->quantity,
                'discount'              => $itemDiscount,
                'sub_total'             => $itemTotal,
            ];

            if ($variant) {
                $variant->decrement('stock', $cartItem->quantity);
            } else {
                $product->decrement('stock_in', $cartItem->quantity);
                $product->increment('stock_out', $cartItem->quantity);
            }
        }

        $order = Order::create([
            'user_id'          => $user->id,
            'seller_id'        => $seller->id,
            'customer_name'    => $data['customer_name'],
            'customer_email'   => $data['customer_email'] ?? $user->email,
            'customer_phone'   => $data['customer_phone'],
            'customer_address' => $data['address'],
            'invoice_id'       => strtoupper(uniqid('INV-')),
            'sub_total'        => $total + $discount,
            'total'            => $total + $tax + $shipping_fee,
            'discount'         => $discount,
            'tax'              => $tax,
            'shipping_fee'     => $shipping_fee,
            'payable'          => $total + $shipping_fee + $tax,
            'due'              => $total + $shipping_fee + $tax,
            'status'           => OrderStatus::PENDING->value,
            'delivery_status'  => OrderStatus::ORDER_PLACED->value,
        ]);

        $order->items()->createMany($orderItems);

        $cart->cart_items()->delete();
        $cart->delete();

        $totalSoldCount = OrderItem::whereHas('order', function ($q) use ($seller) {
            $q->where('seller_id', $seller->id);
        })->count();

        $seller->update([
            'total_sold' => $totalSoldCount,
        ]);

        return successResponse('Order Placed Successfully');
    }

    public function index(Request $request)
    {
        $statusLabel = (string) $request->input('status', 'all');
        $statusValue = OrderStatus::valueFromLabel($statusLabel);

        $query = Order::with('seller')->withCount('items')
            ->where('user_id', Auth::id())
            ->whereNotNull('invoice_id');

        if ($statusLabel !== 'all') {
            $query->where('status', $statusValue);
        }

        $orders = $query->get();

        return apiResourceResponse(OrderResource::collection($orders));
    }

    public function show(Order $order)
    {
        $order->load('items.product');

        return apiResponse(OrderResource::make($order));
    }

    public function tracking($invoice_id)
    {
        $order = Order::withCount('items')->where('invoice_id', $invoice_id)->first();

        if (! $order) {
            return response()->json([
                'status'  => false,
                'message' => 'Order not found with the given invoice ID.',
            ], 404);
        }

        return apiResponse(OrderResource::make($order));
    }

    public function submitReview(Request $request)
    {
        $user = Auth::user();

        $validator = validateRequest($request, [
            'product_id' => 'required',
            'rating'      => 'required|integer|min:1|max:5',
            'review_text' => 'required|string',
            'images'      => 'nullable|array',
            'images.*'    => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $product = Product::find($request->product_id);

        $review_exist = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        if ($review_exist) {
            return response()->json([
                'status'  => false,
                'message' => 'You have already reviewed this product.',
            ], 409);
        }

        $review = Review::create([
            'product_id'  => $product->id,
            'user_id'     => $user->id,
            'rating'      => $request->rating,
            'review_text' => $request->review_text,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image'     => upload_file($file, 'images/reviews'),
                ]);
            }
        }

        return apiResponse($review);
    }

}
