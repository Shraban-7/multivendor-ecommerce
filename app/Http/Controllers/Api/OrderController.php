<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\OrderItem;
use App\Enums\AddressType;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\ReviewImage;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\BillingAddress;
use App\Models\ProductVariant;
use App\Services\AamarpayService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\OrderResource;
use App\Http\Resources\InvoiceResource;
use App\Models\OrderBillingAddress;

use function PHPUnit\Framework\returnSelf;

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

        $orders = $query->with('seller', 'items', 'billing_address')->latest('id')->paginate(15);

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

        $sub_total = 0;
        $discount = 0;
        $vat_amount = 0;
        $orderItems = [];
        $shipping_fee = $seller->shipping_cost;

        $payment_type = PaymentType::COD_ONLY->value;

        foreach ($cart->cart_items as $cartItem) {
            $product = $cartItem->product;
            $variant = $cartItem->variant;
            $unitPrice = $cartItem->price;
            $itemTotal = $cartItem->quantity * $unitPrice;
            $itemDiscount = $cartItem->quantity * ($cartItem->original_price - $cartItem->discounted_price);
            $vat_amount += floatval(($product->vat_percent * $unitPrice) / 100) * $cartItem->quantity;
            $sub_total += $itemTotal;
            $discount += $itemDiscount;
            $grand_total = $sub_total + $discount;

            if ($product->payment_type->value == PaymentType::FULL_PAYMENT->value) {
                $payment_type = PaymentType::FULL_PAYMENT->value;
            } elseif ($product->payment_type->value == PaymentType::COD_WITH_DELIVERY_CHARGE->value) {
                $payment_type = PaymentType::COD_WITH_DELIVERY_CHARGE->value;
            }

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'buying_price' => $variant ? $variant->buying_price : 0,
                'unit_price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemTotal,
                'vat_percent' => $product->vat_percent,
                'vat_amount' => floatval(($product->vat_percent * $unitPrice) / 100) * $cartItem->quantity,
            ];
        }

        $seller = Seller::where('id', $selectedSellerId)->first();

        $total_commission = 0;

        if ($seller->commission_amount != null && $seller->commission_type != null) {
            if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = ($sub_total + $vat_amount) * ($seller->commission_amount / 100);
            } else if ($seller->commission_type === CommissionType::FLAT->value) {
                $total_commission = $seller->commission_amount;
            }
        }

        $invoiceId = Order::generateInvoiceID($seller->id);
        $payableAmount = $sub_total + $shipping_fee + $vat_amount;
        $sellerEarning = $sub_total + $vat_amount - $total_commission;

        $billingAddress = BillingAddress::find($request->billing_address_id);

        try {
            DB::beginTransaction();

            $order = Order::create([
                'user_id' => $user->id,
                'seller_id' => $selectedSellerId,
                'invoice_id' => $invoiceId,
                'sub_total' => $sub_total,
                'total' => $sub_total + $vat_amount + $shipping_fee,
                'discount' => $discount,
                'vat_amount' => $vat_amount,
                'shipping_fee' => $shipping_fee,
                'payable' => $payableAmount,
                'due' => $payableAmount,
                'commission_type' => $seller->commission_type,
                'commission_amount' => $seller->commission_amount,
                'seller_earnings' => $sellerEarning,
                'total_commission' => $total_commission,
                'status' => OrderStatus::PENDING->value,
                'payment_type' => $payment_type,
            ]);

            $order->items()->createMany($orderItems);

            $orderBillingAddress = OrderBillingAddress::create([
                'order_id' => $order->id,
                'customer_name' => $billingAddress->customer_name,
                'customer_phone' => $billingAddress->customer_phone,
                'division_id' => $billingAddress->division_id,
                'district_id' => $billingAddress->district_id,
                'address' => $billingAddress->address,
            ]);

            foreach ($order->items as $item) {
                if (isset($item['product_variant_id'])) {
                    $variant = ProductVariant::find($item['product_variant_id']);

                    if ($variant) {
                        $variant->increment('stock_out', $item['quantity']);
                    }
                }
            }

            $cart->cart_items()->delete();
            $cart->delete();

            $seller = Seller::find($selectedSellerId);

            $sellerOrderIds = Order::where('seller_id', $seller->id)->pluck('id');

            $sellerOrderCount = OrderItem::whereIn('order_id', $sellerOrderIds)->count();

            $seller->update([
                'total_sold' => $sellerOrderCount,
            ]);

            DB::commit();

            $paymentGateway = $this->initiatePaymentGateway(
                $user,
                $invoiceId,
                $payableAmount,
                $orderBillingAddress->customer_name,
                $orderBillingAddress->customer_phone
            );
        } catch (Exception $e) {
            DB::rollBack();
            return errorResponse($e->getMessage());
        }

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

        if (is_null($paymentGateway['payment_url'])) {
            return errorResponse($paymentGateway['message']);
        }

        return apiResponse([
            'message' => $paymentGateway['message'],
            'payment_url' => $paymentGateway['payment_url'],
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.cancel'),
            'cancel_url' => route('payment.cancel'),
        ]);
    }

    private function initiatePaymentGateway($user, $invoiceId, $amount, $customerName, $customerPhone): array
    {
        $payment = Payment::where('transaction_id', $invoiceId)->first();

        if (!$payment) {
            $payment = Payment::create([
                'gateway' => 'aamarpay',
                'transaction_id' => $invoiceId,
                'status' => Payment::PENDING,
                'amount' => $amount,
                'currency' => 'BDT',
                'customer_name' => $customerName,
                'customer_email' => $customerPhone,
                'customer_phone' => $user->email
            ]);
        }

        if ($payment->status == Payment::SUCCESSFUL) {
            return [
                'message' => 'This payment is already complete!',
                'payment_url' => null,
            ];
        }

        if ($payment->status == Payment::FAILED) {
            $payment->update(['status' => Payment::PENDING]);
        }

        $aamarpay = (new AamarpayService);

        $message = 'Redirecting to payment gateway';
        $paymentUrl = null;;

        try {
            $response = $aamarpay->initiate([
                'tran_id' => $invoiceId,
                'success_url' => route('payment.success'),
                'fail_url' => route('payment.cancel'),
                'cancel_url' => route('payment.cancel'),
                'amount' => $amount,
                'desc' => 'order Payment',
                'cus_name' => $customerName,
                'cus_email' => $user->email,
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
        $validator = validateRequest($request, [
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $orderItem = OrderItem::find($request->order_item_id);

        if ($orderItem->is_reviewed) {
            return errorResponse('You have already reviewed this product.');
        }

        $review = Review::create([
            'product_id'  => $orderItem->product_id,
            'order_id'    => $orderItem->order_id,
            'order_item_id' => $orderItem->id,
            'user_id'     => Auth::id(),
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

        $orderItem->is_reviewed = 1;
        $orderItem->save();

        return successResponse('Review Submit Successfully');
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'seller', 'user.country');

        return apiResourceResponse(InvoiceResource::make($order));
    }

    public function payNow(Order $order)
    {
        $orderBillingAddress = OrderBillingAddress::where('order_id', $order->id)->first();

        $paymentGateway = $this->initiatePaymentGateway(
            $order->user,
            $order->invoice_id,
            $order->payable,
            $orderBillingAddress->customer_name,
            $orderBillingAddress->customer_phone,
        );

        if (is_null($paymentGateway['payment_url'])) {
            return errorResponse($paymentGateway['message']);
        }

        return apiResponse([
            'status' => true,
            'message' => $paymentGateway['message'],
            'payment_url' => $paymentGateway['payment_url'],
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.cancel'),
            'cancel_url' => route('payment.cancel'),
        ]);
    }
}
