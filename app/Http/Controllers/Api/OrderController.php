<?php

namespace App\Http\Controllers\Api;

use App\Enums\CommissionType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Review;
use App\Models\ReviewImage;
use App\Models\Seller;
use App\Services\AamarpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
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

        $orders = $query->with('seller', 'items')->latest('id')->paginate(15);

        return apiResourceResponse(OrderResource::collection($orders));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'seller_id' => 'required|exists:sellers,id',
            'billing_address_id' => 'required|exists:billing_addresses,id'
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user = Auth::user();

        $selectedSellerId = $request->input('seller_id');

        $seller = Seller::findOrFail($selectedSellerId);

        $cart = Cart::with('cart_items.product', 'cart_items.variant')
            ->where('user_id', $user->id)
            ->where('seller_id', $selectedSellerId)
            ->first();

        if (! $cart || $cart->cart_items->isEmpty()) {
            return errorResponse('Cart is empty or not found for the selected seller.');
        }

        $sub_total    = 0;
        $discount     = 0;
        $tax          = 0;
        $shipping_fee = $seller->shipping_cost ?? 0;
        $orderItems   = [];

        foreach ($cart->cart_items as $cartItem) {
            $product      = $cartItem->product;
            $variant      = $cartItem->variant;
            $unitPrice    = $cartItem->price;
            $itemTotal    = $cartItem->quantity * $unitPrice;
            $itemDiscount = $cartItem->quantity * ($cartItem->original_price - $cartItem->discounted_price);
            $tax += floatval($product->tax) * $cartItem->quantity;
            $sub_total += $itemTotal;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id'         => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'buying_price'       => $variant ? $variant->buying_price : $product->buying_price,
                'unit_price'         => $cartItem->price,
                'quantity'           => $cartItem->quantity,
                'discount'           => $itemDiscount,
                'sub_total'          => $itemTotal,
            ];

            if ($variant) {
                $variant->decrement('stock_in', $cartItem->quantity);
                $variant->increment('stock_out', $cartItem->quantity);
            } else {
                $product->decrement('stock_in', $cartItem->quantity);
                $product->increment('stock_out', $cartItem->quantity);
            }
        }

        $seller = Seller::where('id', $selectedSellerId)->first();

        $total_commission = 0;

        if ($seller->commission_amount != null && $seller->commission_type != null) {
            if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = ($sub_total + $tax + $shipping_fee) * ($seller->commission_amount / 100);
            } else if ($seller->commission_type === CommissionType::FLAT->value) {
                $total_commission = $seller->commission_amount;
            }
        }

        $invoiceId = Order::generateInvoiceID();
        $payableAmount = $sub_total + $shipping_fee + $tax;

        $order = Order::create([
            'user_id'           => $user->id,
            'seller_id'         => $selectedSellerId,
            'billing_address_id' => $request->billing_address_id,
            'invoice_id'        => $invoiceId,
            'sub_total'         => $sub_total,
            'total'             => $sub_total + $tax + $shipping_fee,
            'discount'          => $discount,
            'tax'               => $tax,
            'shipping_fee'      => $shipping_fee,
            'payable'           => $payableAmount,
            'due'               => $payableAmount,
            'commission_type'   => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'total_commission'  => $total_commission,
            'status'            => OrderStatus::PENDING->value,
            'delivery_status'   => OrderStatus::ORDER_PLACED->value,
        ]);

        $order->items()->createMany($orderItems);

        $cart->cart_items()->delete();
        $cart->delete();

        $seller = Seller::find($selectedSellerId);

        $sellerOrderIds = Order::where('seller_id', $seller->id)->pluck('id');

        $sellerOrderCount = OrderItem::whereIn('order_id', $sellerOrderIds)->count();

        $seller->update([
            'total_sold' => $sellerOrderCount,
        ]);

        $paymentGateway = $this->initiatePaymentGateway($request, $invoiceId, $payableAmount);

        notify_user(
            $user->id,
            'Order Placed Successfully',
            "Your order #{$invoiceId} has been placed successfully.",
            Notification::TARGET_ORDER,
            $invoiceId,
        );

        notify_seller(
            $selectedSellerId,
            'New Order Received',
            "You have received a new order #{$invoiceId}.",
            Notification::TARGET_ORDER,
            $invoiceId,
        );

        return apiResponse([
            'status' => true,
            'message' => $paymentGateway['message'],
            'payment_url' => $paymentGateway['payment_url'],
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.cancel'),
            'cancel_url' => route('payment.cancel'),
        ]);
    }

    private function initiatePaymentGateway(Request $request, $invoiceId, $amount)
    {
        $user = Auth::user();
        $customerName  = $request->input('customer_name', $user->name);
        $customerEmail = $request->input('customer_email', $user->customer_email);
        $customerPhone = $request->input('customer_phone') ?? '';

        $payment = Payment::create([
            'gateway' => 'aamarpay',
            'transaction_id' => $invoiceId,
            'status' => Payment::PENDING,
            'amount' => $amount,
            'currency' => 'BDT',
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
        ]);

        $aamarpay = (new AamarpayService);

        $message = 'Redirecting to payment gateway';
        $paymentUrl = '';

        try {
            $response = $aamarpay->initiate([
                'tran_id' => $invoiceId,
                'success_url' => route('payment.success'),
                'fail_url' => route('payment.cancel'),
                'cancel_url' => route('payment.cancel'),
                'amount' => $amount,
                'desc' => 'order Payment',
                'cus_name' => $customerName,
                'cus_email' => $customerEmail,
                'cus_add1' => '',
                'cus_add2' => '',
                'cus_city' => '',
                'cus_state' => '',
                'cus_postcode' => '',
                'cus_country' => 'Bangladesh',
                'cus_phone' => $customerPhone,
                'opt_a' => base64_encode(json_encode([
                    'user_id' => $user->id,
                    'return_url' => route('orders.index'),
                ])),
            ]);

            if (isset($response['payment_url'])) {
                $paymentUrl = $response['payment_url'];
            } else {
                $message = 'Payment URL not received.';
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return [
            'message' => $message,
            'payment_url' => $paymentUrl,
        ];
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
            'product_id'  => 'required',
            'rating'      => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'images'      => 'nullable|array',
            'images.*'    => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $product = Product::find($request->product_id);

        $review_exist = Review::where('product_id', $product->id)
            ->where('user_id', $user->id)
            ->first();

        if ($review_exist) {
            return errorResponse('You have already reviewed this product.');
        }

        $review = Review::create([
            'product_id'  => $product->id,
            'user_id'     => $user->id,
            'rating'      => $request->rating,
            'description' => $request->description,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image'     => upload_file($file, 'images/reviews'),
                ]);
            }
        }

        return successResponse('Review Submit Successfully');
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'seller', 'user.country');

        return apiResourceResponse(InvoiceResource::make($order));
    }
}
