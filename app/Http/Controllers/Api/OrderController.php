<?php

namespace App\Http\Controllers\Api;

use App\Domain\Order\Models\BillingAddress;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Services\OrderService;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Domain\Support\Models\Notification;
use App\Domain\Vendor\Models\Seller;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\OrderResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly CartRepositoryInterface $cartRepo,
        private readonly PaymentRepositoryInterface $paymentRepo,
        private readonly OrderService $orderService,
    ) {}

    public function index(Request $request)
    {
        $statusLabel = (string) $request->input('status', 'all');
        $statusValue = OrderStatus::valueFromLabel($statusLabel);
        $filters = [];
        if ($statusLabel !== 'all') {
            $filters['status'] = $statusValue;
        }

        $orders = $this->orderRepo->searchUserOrders(
            Auth::id(),
            $filters,
            ['seller', 'items.product', 'items.variant', 'billing_address'],
        );

        return apiResourceResponse(OrderResource::collection($orders));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'seller_id' => 'required|exists:sellers,id',
            'billing_address_id' => 'required|exists:billing_addresses,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $user = Auth::user();
        $selectedSellerId = $request->input('seller_id');
        $seller = Seller::findOrFail($selectedSellerId);

        $cart = $this->cartRepo->findUserCartBySeller($user->id, $selectedSellerId)?->load('cart_items.product', 'cart_items.variant');

        if (! $cart || $cart->cart_items->isEmpty()) {
            return errorResponse('Cart is empty or not found for the selected seller.');
        }

        [$subTotal, $discount, $orderItems] = $this->orderService->buildOrderItemsFromCart($cart);

        $shippingFee = (float) $seller->shipping_cost;
        $total = $subTotal + $shippingFee;
        $payableAmount = $total;

        $commissionData = $this->orderService->calculateCommission($seller, $subTotal);

        $invoiceId = Order::generateInvoiceID($seller->id);
        $paymentType = Order::getPaymentType($cart->cart_items->pluck('product')->filter());

        try {
            DB::beginTransaction();

            $order = $this->orderRepo->create([
                'user_id' => $user->id,
                'seller_id' => $selectedSellerId,
                'invoice_id' => $invoiceId,
                'sub_total' => $subTotal,
                'total' => $total,
                'discount' => $discount,
                'shipping_fee' => $shippingFee,
                'payable' => $payableAmount,
                'due' => $payableAmount,
                'commission_type' => $seller->commission_type,
                'commission_amount' => $seller->commission_amount,
                'seller_earnings' => $commissionData['seller_earning'],
                'total_commission' => $commissionData['total_commission'],
                'status' => OrderStatus::PENDING->value,
                'payment_type' => $paymentType,
            ]);

            $this->orderRepo->createOrderItems($order, $orderItems);

            $billingAddress = BillingAddress::find($request->billing_address_id);
            $this->orderRepo->createBillingAddress([
                'order_id' => $order->id,
                'customer_name' => $billingAddress->customer_name,
                'customer_phone' => $billingAddress->customer_phone,
                'division_id' => $billingAddress->division_id,
                'district_id' => $billingAddress->district_id,
                'address' => $billingAddress->address,
            ]);

            $this->orderRepo->deductStock($order);
            $this->cartRepo->clearCart($cart);
            $this->cartRepo->delete($cart->id);
            $this->orderRepo->updateSellerTotalSold($selectedSellerId);

            DB::commit();

            $paymentGateway = $this->orderService->initiatePaymentGateway(
                $user,
                $invoiceId,
                $payableAmount,
                $billingAddress->customer_name,
                $billingAddress->customer_phone,
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

    public function show(Order $order)
    {
        $order->load('items.product');

        return apiResponse(OrderResource::make($order));
    }

    public function tracking($invoice_id)
    {
        $order = $this->orderRepo->findByInvoiceId($invoice_id)?->loadCount('items');

        if (! $order) {
            return response()->json([
                'status' => false,
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

        try {
            $this->orderService->submitReview(Auth::user(), $request->all());
            return successResponse('Review Submit Successfully');
        } catch (Exception $e) {
            return errorResponse($e->getMessage());
        }
    }

    public function invoice(Order $order)
    {
        $order->load('items.product', 'seller', 'user.country');

        return apiResourceResponse(InvoiceResource::make($order));
    }

    public function payNow(Order $order)
    {
        $order->load('items');
        $orderBillingAddress = $this->orderRepo->findBillingAddressByOrder($order->id);

        $paymentGateway = $this->orderService->initiatePaymentGateway(
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
