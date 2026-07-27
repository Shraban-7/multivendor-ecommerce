<?php

namespace App\Domain\Order\Http\Controllers\Frontend;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\BillingAddress;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\CartRepositoryInterface;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Services\OrderService;
use App\Domain\Payment\Models\PaymentGateway;
use App\Domain\Product\Models\Product;
use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Domain\Support\Models\Notification;
use App\Domain\Vendor\Models\Seller;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly CartRepositoryInterface $cartRepo,
        private readonly OrderService $orderService,
    ) {}

    public function index(Request $request)
    {
        $statusLabel = (string) $request->input('status', 'all');
        $statusValue = OrderStatus::valueFromLabel($statusLabel);

        $orders = $this->orderRepo->searchUserOrders(
            Auth::id(),
            ['status' => $statusLabel !== 'all' ? $statusValue : null],
            ['seller', 'payment', 'returnRequest'],
        );

        $interestProducts = Product::with([
            'category', 'subcategory', 'images', 'seller', 'variants.color', 'variants.size', 'reviews.user',
        ])->inRandomOrder()->limit(8)->get();

        return view('frontend.orders.index', [
            'orders' => $orders,
            'status' => $statusLabel,
            'products' => [],
        ]);
    }

    public function orderData(Request $request)
    {
        $order = Order::where('id', $request->order_id)
            ->where('user_id', Auth::id())
            ->with('items.variant.color', 'items.variant.size')
            ->firstOrFail();

        $items = $order->items->map(fn ($item) => [
            'id' => $item->id,
            'product_name' => $item->product_name,
            'variant_name' => $item->variant?->fullname ?? '',
            'quantity' => $item->quantity,
            'total' => $item->total,
            'total_formatted' => money($item->total),
        ]);

        return response()->json(['items' => $items]);
    }

    public function details($invoice_id)
    {
        $user = Auth::user();
        $order = $this->orderRepo->findByInvoiceId($invoice_id)?->load('seller', 'payment', 'items.review', 'returnRequest');
        $products = Product::active()->latest('id')
            ->withCount('reviews')
            ->withAvg('reviews', 'rating')
            ->limit(12)
            ->get();

        return view('frontend.orders.details', compact('order', 'user', 'products'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'billing_address_id' => 'nullable|exists:billing_addresses,id',
            'type' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $selectedSellerId = $validated['seller_id'];
        $seller = Seller::find($selectedSellerId);

        $cart = $this->cartRepo->findUserCartBySeller($user->id, $selectedSellerId)?->load(
            'cart_items.product',
            'cart_items.variant.color',
            'cart_items.variant.size',
        );

        if (! $cart) {
            if ($request->ajax()) {
                return response()->json(['status' => false, 'message' => 'No cart found for the selected seller.'], 404);
            }

            return redirect()->route('home');
        }

        [$subTotal, $discount, $orderItems] = $this->orderService->buildOrderItemsFromCart($cart);

        $shippingFee = (float) $seller->shipping_cost;
        $total = array_sum(array_column($orderItems, 'total'));
        $allCod = collect($cart->cart_items)->every(fn ($item) => ($item->product->payment_type->value ?? PaymentType::COD_ONLY->value) === PaymentType::COD_ONLY->value);
        $grand_total = $subTotal + $discount;

        if ($request->isMethod('GET')) {
            $paymentGateways = PaymentGateway::where('is_enabled', true)->get();
            $divisions = Division::get();
            $districts = District::get();
            $billingAddresses = BillingAddress::where('user_id', $user->id)->latest()->get();

            $sub_total = $subTotal;
            $shipping_fee = $shippingFee;
            $payment_gateways = $paymentGateways;

            return view('frontend.pages.checkout', compact(
                'user', 'selectedSellerId', 'sub_total', 'discount', 'shipping_fee',
                'payment_gateways', 'divisions', 'districts', 'billingAddresses', 'total', 'allCod', 'grand_total'
            ));
        }

        try {
            $result = $this->orderService->placeFrontendOrder(
                $user,
                $seller,
                $validated,
                ['items' => $orderItems, 'total' => $total],
                $subTotal,
                $discount,
                $request->payment_method,
            );

            $order = $result['order'];

            notify_user(
                $user->id,
                'Order Placed Successfully',
                "Your order #{$order->invoice_id} has been placed successfully.",
                Notification::TARGET_ORDER,
                $order->invoice_id,
            );

            notify_seller(
                $selectedSellerId,
                'New Order Received',
                "You have received a new order #{$order->invoice_id}.",
                Notification::TARGET_ORDER,
                $order->invoice_id,
            );

            return response()->json([
                'status' => true,
                'message' => $result['message'],
                'payment_url' => $result['payment_url'],
                'order' => [
                    'id' => $order->id,
                    'invoice_id' => $order->invoice_id,
                    'total' => $order->total,
                    'payable' => $order->payable,
                ],
            ]);
        } catch (Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
            }

            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function success($invoice_id)
    {
        $order = $this->orderRepo->findByInvoiceId($invoice_id);

        return view('frontend.orders.success', compact('order'));
    }

    public function tracking($invoice_id)
    {
        $order = $this->orderRepo->findByInvoiceId($invoice_id)?->loadCount('items');

        return view('frontend.orders.tracking', compact('order'));
    }

    public function review(Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('GET')) {
            return view('frontend.orders.review', compact('user'));
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'order_item_id' => 'required|exists:order_items,id',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        try {
            $this->orderService->submitReview($user, $data);

            return redirect()->back()->with('success', 'Review submitted successfully.');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getDistricts($divisionId)
    {
        $districts = District::where('division_id', $divisionId)->pluck('name', 'id');

        return response()->json($districts);
    }

    public function payNow(Order $order)
    {
        $order->load('items');
        $orderBillingAddress = $this->orderRepo->findBillingAddressByOrder($order->id);

        if (! $orderBillingAddress) {
            return back()->with('error', 'Billing address not found.');
        }

        if ($order->due <= 0) {
            return back()->with('success', 'This order is already paid.');
        }

        $paymentGateway = $this->orderService->initiatePaymentGateway(
            $order->user,
            $order->invoice_id,
            $order->due,
            $orderBillingAddress->customer_name,
            $orderBillingAddress->customer_phone,
        );

        if (empty($paymentGateway['payment_url'])) {
            return back()->with('error', $paymentGateway['message'] ?? 'Unable to initiate payment.');
        }

        return redirect()->away($paymentGateway['payment_url']);
    }
}

