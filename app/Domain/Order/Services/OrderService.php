<?php

namespace App\Domain\Order\Services;

use App\Domain\Affiliate\Models\AffiliateCommission;
use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\BillingAddress;
use App\Domain\Order\Models\Cart;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Domain\Review\Models\Review;
use App\Domain\Review\Models\ReviewImage;
use App\Domain\Vendor\Models\Seller;
use App\Enums\PaymentType;
use App\Services\AamarpayService;
use App\Services\AffiliateService;
use Exception;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(
        protected CartService $cartService,
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly PaymentRepositoryInterface $paymentRepo,
        private readonly AffiliateService $affiliateService,
    ) {}

    public function placeOrder(array $orderData, array $items, array $billing): Order
    {
        return DB::transaction(function () use ($orderData, $items, $billing) {
            $order = $this->orderRepo->create($orderData);
            $this->orderRepo->createOrderItems($order, $items);
            $this->orderRepo->createBillingAddress(array_merge($billing, [
                'order_id' => $order->id,
            ]));

            return $order->fresh(['items', 'billing_address']);
        });
    }

    public function transitionStatus(Order $order, int|string $status): Order
    {
        $this->orderRepo->update($order, ['status' => $status]);
        $this->orderRepo->createStatusLog($order, [
            'status' => $status,
            'changed_by' => auth()->id(),
        ]);

        return $order->fresh();
    }

    public function calculateCommission(Seller $seller, float $total): array
    {
        if (method_exists($seller, 'calculateEarning')) {
            return $seller->calculateEarning($total);
        }

        return [
            'total_commission' => 0.0,
            'seller_earning' => $total,
        ];
    }

    public function buildOrderItemsFromCartItems($cartItems, iterable $requestItems): array
    {
        $subTotal = 0;
        $discount = 0;
        $orderItems = [];
        $itemsCollection = collect($requestItems);

        if ($cartItems instanceof \Illuminate\Support\Collection) {
            $cartItems->loadMissing(['product', 'variant.color', 'variant.size', 'variant.product']);
        }

        foreach ($cartItems as $item) {
            $variant = $item->variant;
            $product = $variant->product ?? $item->product;
            $sellingPrice = (float) ($variant->price ?? $product->price ?? 0);
            $effectivePrice = (float) ($variant->compare_price ?? $variant->price ?? $product->compare_price ?? $product->price ?? 0);
            $unitPrice = (float) ($itemsCollection->firstWhere('id', $item->id)['price'] ?? $item->price ?? $effectivePrice);
            $qty = (int) $item->quantity;
            $itemDiscount = $sellingPrice > $unitPrice ? ($sellingPrice - $unitPrice) * $qty : 0;

            $subTotal += $sellingPrice * $qty;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $variant->id ?? null,
                'sku' => $variant->sku ?? $product->sku,
                'product_name' => $product->name,
                'variant_name' => $this->resolveVariantName($variant),
                'cost_price' => $variant->cost_price ?? $product->cost_price ?? 0,
                'price' => $sellingPrice,
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'discount' => $itemDiscount,
                'sub_total' => $sellingPrice * $qty,
                'total' => $unitPrice * $qty,
            ];
        }

        return [$subTotal, $discount, $orderItems];
    }

    public function buildOrderItemsFromCart($cart): array
    {
        $cart->load([
            'cart_items.product',
            'cart_items.variant' => fn ($q) => $q->with(['color', 'size']),
        ]);

        $subTotal = 0;
        $discount = 0;
        $orderItems = [];

        foreach ($cart->cart_items as $cartItem) {
            $product = $cartItem->product;
            $variant = $cartItem->variant;
            $unitPrice = (float) $cartItem->price;
            $sellingPrice = (float) ($variant?->price ?? $product->price ?? 0);
            $qty = (int) $cartItem->quantity;
            $itemSubtotal = $qty * $sellingPrice;
            $itemTotal = $qty * $unitPrice;
            $itemDiscount = $qty * max(0, (float) $cartItem->original_price - (float) $cartItem->discounted_price);

            $subTotal += $itemSubtotal;
            $discount += $itemDiscount;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'sku' => $variant?->sku ?? $product->sku,
                'product_name' => $product->name,
                'variant_name' => $this->resolveVariantName($variant),
                'cost_price' => $variant?->cost_price ?? $product->cost_price ?? 0,
                'price' => $sellingPrice,
                'unit_price' => $unitPrice,
                'quantity' => $qty,
                'discount' => $itemDiscount,
                'sub_total' => $itemSubtotal + $itemDiscount,
                'total' => $itemTotal,
            ];
        }

        return [$subTotal, $discount, $orderItems];
    }

    public function placeFrontendOrder(
        User $user,
        Seller $seller,
        array $validated,
        array $orderItemsData,
        float $subTotal,
        float $discount,
        string $paymentMethod,
        ?int $couponId = null,
    ): array {
        return DB::transaction(function () use ($user, $seller, $validated, $orderItemsData, $subTotal, $discount, $paymentMethod, $couponId) {
            $sellerData = $seller->calculateEarning($orderItemsData['total'] ?? 0);
            $shippingFee = (float) $seller->shipping_cost;
            $payableAmount = ($orderItemsData['total'] ?? $subTotal) + $shippingFee;
            $invoiceId = Order::generateInvoiceID($seller->id);

            $order = $this->orderRepo->create([
                'user_id' => $user->id,
                'seller_id' => $seller->id,
                'coupon_id' => $couponId,
                'invoice_id' => $invoiceId,
                'sub_total' => $subTotal,
                'total' => $orderItemsData['total'] ?? $subTotal,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'payable' => $payableAmount,
                'paid' => 0,
                'due' => $payableAmount,
                'commission_type' => $seller->commission_type,
                'commission_amount' => $seller->commission_amount,
                'seller_earnings' => $sellerData['seller_earning'],
                'total_commission' => $sellerData['total_commission'],
                'status' => OrderStatus::PENDING->value,
                'payment_type' => PaymentType::COD_ONLY->value,
            ]);

            $this->orderRepo->createOrderItems($order, $orderItemsData['items'] ?? $orderItemsData);

            $billingAddress = BillingAddress::find($validated['billing_address_id']);
            if ($billingAddress) {
                $this->orderRepo->createBillingAddress([
                    'order_id' => $order->id,
                    'customer_name' => $billingAddress->customer_name,
                    'customer_phone' => $billingAddress->customer_phone,
                    'division_id' => $billingAddress->division_id,
                    'district_id' => $billingAddress->district_id,
                    'address' => $billingAddress->address,
                ]);
            }

            $this->orderRepo->deductStock($order);

            $cart = Cart::where('user_id', $user->id)
                ->where('seller_id', $seller->id)->first();
            if ($cart) {
                $cart->cart_items()->delete();
                $cart->delete();
            }

            $this->orderRepo->updateSellerTotalSold($seller->id);

            $order->load('items.product');
            $this->affiliateService->processCommissions($order->items, $user, $order->invoice_id);
            $this->affiliateService->updateOrderAffiliateId($order);

            if ($paymentMethod === 'pay_now') {
                $paymentGateway = $this->initiatePaymentGateway(
                    $user,
                    $order->invoice_id,
                    $payableAmount,
                    $billingAddress?->customer_name ?? $user->name ?? '',
                    $billingAddress?->customer_phone ?? $user->phone ?? '',
                );

                if (empty($paymentGateway['payment_url'])) {
                    throw new Exception($paymentGateway['message'] ?? 'Payment gateway error. Please try again.');
                }

                return [
                    'order' => $order,
                    'payment_url' => $paymentGateway['payment_url'],
                    'message' => $paymentGateway['message'] ?? 'Redirecting to payment gateway',
                ];
            }

            return [
                'order' => $order,
                'payment_url' => route('orders.index'),
                'message' => 'Order placed successfully',
            ];
        });
    }

    public function submitReview(User $user, array $data): Review
    {
        $orderItem = OrderItem::findOrFail($data['order_item_id']);

        $existing = Review::where('order_item_id', $orderItem->id)->first();
        if ($existing) {
            throw new Exception('You have already reviewed this product.');
        }

        $review = Review::create([
            'product_id' => $orderItem->product_id,
            'order_id' => $orderItem->order_id,
            'order_item_id' => $orderItem->id,
            'user_id' => $user->id,
            'seller_id' => $orderItem->order->seller_id ?? null,
            'rating' => $data['rating'],
            'description' => $data['description'],
        ]);

        $review->product->addRating($review->rating);
        $seller = Seller::find($review->product->seller_id);
        $seller?->addRating($review->rating);

        if (! empty($data['images'])) {
            foreach ($data['images'] as $file) {
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => upload_file($file, 'images/reviews'),
                ]);
            }
        }

        $this->orderRepo->update($orderItem->order, ['is_reviewed' => 1]);
        $orderItem->update(['is_reviewed' => 1]);

        return $review;
    }

    public function initiatePaymentGateway(User $user, string $invoiceId, float $amount, string $customerName, string $customerPhone): array
    {
        $payment = $this->paymentRepo->findByTransactionId($invoiceId);
        $order = $this->orderRepo->findByInvoiceId($invoiceId);

        if (! $payment) {
            $payment = $this->paymentRepo->create([
                'user_id' => $user->id,
                'gateway' => 'aamarpay',
                'transaction_id' => $invoiceId,
                'status' => Payment::PENDING,
                'amount' => $amount,
                'currency' => 'BDT',
                'customer_name' => $customerName,
                'customer_email' => $customerPhone,
                'customer_phone' => $user->email,
            ]);
        }

        if ($payment->status == Payment::SUCCESSFUL) {
            return ['message' => 'Payment already completed.', 'payment_url' => null];
        }

        if ($payment->status == Payment::FAILED && $order) {
            $this->orderRepo->update($order, ['paid' => 0, 'due' => $amount]);
            $this->paymentRepo->update($payment, ['status' => Payment::PENDING]);
        }

        try {
            $aamarpay = new AamarpayService;
            $response = $aamarpay->initiate([
                'tran_id' => $invoiceId,
                'success_url' => route('payment.success'),
                'fail_url' => route('payment.cancelled'),
                'cancel_url' => route('payment.cancelled'),
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
                return ['message' => 'Redirecting to payment gateway', 'payment_url' => $response['payment_url']];
            }

            return ['message' => 'Payment URL not received.', 'payment_url' => null];
        } catch (Exception $e) {
            return ['message' => $e->getMessage(), 'payment_url' => null];
        }
    }

    public function approveAffiliateCommission(Order $order): void
    {
        if ($order->status->value != OrderStatus::COMPLETED->value) {
            return;
        }

        $commission = AffiliateCommission::where('order_id', $order->id)->first();
        if ($commission && $commission->status != AffiliateCommission::APPROVED) {
            $commission->status = AffiliateCommission::APPROVED;
            $commission->save();

            $user = User::find($commission->affiliate_id);
            if ($user) {
                $user->increment('balance', $commission->commission_amount);
            }
        }
    }

    public function pendingStatus(): int|string
    {
        return OrderStatus::PENDING->value;
    }

    /**
     * Build a display name without triggering lazy loads on color/size.
     */
    private function resolveVariantName($variant): ?string
    {
        if (! $variant) {
            return null;
        }

        if ($variant->relationLoaded('color') && $variant->relationLoaded('size')) {
            return $variant->label;
        }

        $variant->loadMissing('color', 'size');

        return $variant->label;
    }
}
