<?php

namespace App\Http\Controllers\Frontend;

use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Review\Models\Review;
use App\Domain\Review\Models\ReviewImage;
use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Http\Controllers\Controller;
use App\Models\AffiliateCommission;
use App\Models\BillingAddress;
use App\Models\Cart;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderBillingAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PaymentGateway;
use App\Models\Seller;
use App\Models\User;
use App\Services\AamarpayService;
use App\Services\AffiliateService;
use App\Services\BkashService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $affiliateService;

    public function __construct(AffiliateService $affiliateService)
    {
        $this->affiliateService = $affiliateService;
    }

    const AFFILIATE_COMMISSION_PERCENTAGE = 0.05;

    public function index(Request $request)
    {
        $statusLabel = (string) $request->input('status', 'all');

        $statusValue = OrderStatus::valueFromLabel($statusLabel);

        $query = Order::with('seller')->withCount('items')
            ->where('user_id', Auth::user()->id)
            ->where('invoice_id', '!=', null);

        if ($statusLabel !== 'all') {
            $query->where('status', $statusValue);
        }

        $orders = $query->latest('id')->get();

        $interest_products = Product::with([
            'category',
            'subcategory',
            'images',
            'seller',
            'variants.option_values.option',
            'reviews.user',
        ])->inRandomOrder()->limit(8)->get();

        // $products = $interest_products->map(fn($product) => $product->toDetailsArray());

        return view('frontend.orders.index', [
            'orders' => $orders,
            'status' => $statusLabel,
            'products' => [],
        ]);
    }

    public function details($invoice_id)
    {
        $user = Auth::user();

        $order = Order::where('invoice_id', $invoice_id)->with('seller', 'payment', 'items.review')->first();

        $products = Product::latest('id')->limit(8)->get();

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
        $cart = Cart::where('user_id', $user->id)
            ->where('seller_id', $selectedSellerId)
            ->with('cart_items.product')
            ->first();

        if (! $cart) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No cart found for the selected seller.',
                ], 404);
            }

            return redirect()->route('home');
        }

        $sub_total = 0;
        $total = 0;
        $discount = 0;
        $orderItems = [];
        $shipping_fee = $seller->shipping_cost;

        // $payment_type = PaymentType::COD_ONLY->value;
        $allCod = true;
        $cartProducts = [];
        foreach ($cart->cart_items as $cartItem) {
            $product = $cartItem->product;
            $cartProducts[] = $product;
            $variant = $cartItem->variant;
            $unitPrice = $cartItem->price;
            $sellingPrice = $variant ? $variant->selling_price : $product->selling_price;
            $itemSubtotal = $cartItem->quantity * $sellingPrice;
            $itemTotal = $cartItem->quantity * $unitPrice;
            $itemDiscount = $cartItem->quantity * ($cartItem->original_price - $cartItem->discounted_price);
            $sub_total += $itemSubtotal;
            $total += $itemTotal;
            $discount += $itemDiscount;
            $grand_total = $sub_total + $discount;
            $sku = $variant ? $variant->sku : $product->sku;

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'sku' => $sku,
                'product_name' => $product->name,
                'variant_name' => $variant->fullName ?? null,
                'buying_price' => $variant ? $variant->buying_price : $product->buying_price,
                'selling_price' => $sellingPrice,
                'unit_price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemSubtotal + $itemDiscount,
                'total' => $itemTotal,
            ];

            if ($product->payment_type->value !== PaymentType::COD_ONLY->value) {
                $allCod = false;
            }
        }

        if ($request->isMethod('GET')) {
            $payment_gateways = PaymentGateway::where('is_enabled', true)->get();
            $divisions = Division::get();
            $districts = District::get();
            $billingAddresses = BillingAddress::where('user_id', $user->id)
                ->latest()
                ->get();

            return view('frontend.pages.checkout', compact('user', 'selectedSellerId', 'sub_total', 'discount', 'shipping_fee', 'payment_gateways', 'grand_total', 'divisions', 'districts', 'billingAddresses', 'total', 'allCod'));
        }

        $billingData = collect($validated)->except('seller_id')->toArray();
        $billingData['user_id'] = $user->id;

        $billingAddress = BillingAddress::find($validated['billing_address_id']);

        $seller = Seller::where('id', $selectedSellerId)->first();

        $commissionData = $seller->calculateEarning($total);

        $total_commission = $commissionData['total_commission'];
        $sellerEarning = $commissionData['seller_earning'];

        $invoiceId = Order::generateInvoiceID($selectedSellerId);
        $payableAmount = $total + $shipping_fee;

        $payment_type = Order::getPaymentType($cartProducts);

        $paid_amount = 0;
        $due_amount = $payableAmount;

        $order = Order::create([
            'user_id' => $user->id,
            'seller_id' => $selectedSellerId,
            'invoice_id' => $invoiceId,
            'sub_total' => $sub_total,
            'total' => $total,
            'discount' => $discount,
            'shipping_fee' => $shipping_fee,
            'payable' => $payableAmount,
            'paid' => $paid_amount,
            'due' => $due_amount,
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
            $product = Product::find($item['product_id']);

            if (isset($item['product_variant_id'])) {
                $variant = ProductVariant::find($item['product_variant_id']);

                if ($variant) {
                    $variant->increment('stock_out', $item['quantity']);
                }
            } else {
                if ($product) {
                    $product->increment('stock_out', $item['quantity']);
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

        if ($payment_type == PaymentType::COD_WITH_DELIVERY_CHARGE->value) {
            $payableAmount = $shipping_fee;
        }

        $this->affiliateService->processCommissions(
            $order->items,
            $user,
            $order->invoice_id
        );

        $this->affiliateService->updateOrderAffiliateId($order);

        if ($request->payment_method == 'pay_now') {
            $paymentData = $this->preparePaymentData($order);
            $paymentGatewayResponse = Http::post(env('SLASHPAY_PAYMENT_URL'), $paymentData);
            $jsonResponse = $paymentGatewayResponse->json();
            if ($paymentGatewayResponse->successful()) {
                $order->payment_id = $jsonResponse['payment_id'];
                $order->save();

                DB::commit();

                return redirect()->away($jsonResponse['payment_url']);
            } else {
                DB::rollBack();
                Log::error('Checkout error: Payment gateway response unsuccessful', [
                    'order_id' => $order->id,
                    'response' => $jsonResponse,
                    'paymentData' => $paymentData,
                ]);

                return back()->withInput()->with('error', 'Payment gateway error. Please try again.');
            }
        } else {
            DB::commit();
            $paymentGateway = [
                'message' => 'Order placed successfully',
                'payment_url' => route('orders.index'),
            ];
        }

        // if ($payment_type == PaymentType::COD_ONLY->value) {
        //     $paymentGateway = [
        //         'message' => 'Order placed successfully',
        //         'payment_url' => route('orders.index'),
        //     ];
        // } else if ($request->payment == 'aamarpay') {
        //     $paymentGateway = $this->initiateAmarpayGateway(
        //         $user,
        //         $invoiceId,
        //         $payableAmount,
        //         $orderBillingAddress->customer_name,
        //         $orderBillingAddress->customer_phone,
        //     );
        // } else if ($request->payment == 'bkash') {
        //     $paymentGateway = $this->initiateBkashGateway(
        //         $user,
        //         $invoiceId,
        //         $payableAmount,
        //         $orderBillingAddress->customer_name,
        //         $orderBillingAddress->customer_phone,
        //     );
        // }

        // $this->processAffiliateCommissions($order->items, auth()->user(), $order->id);

        // $affiliate = AffiliateCommission::where('order_id', $order->id)->first();

        // $order->affiliate_id = $affiliate->affiliate_id ?? null;

        // $order->save();

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

        return response()->json([
            'status' => true,
            'message' => $paymentGateway['message'],
            'payment_url' => $paymentGateway['payment_url'],
            'order' => $order,
        ]);
    }

    private function preparePaymentData(Order $order): array
    {
        return [
            'api_key' => env('SLASHPAY_API_KEY'),
            'order_id' => (string) $order->invoice_id,
            'amount' => $order->total,
            'cus_name' => $order->billing_address->customer_name,
            'cus_email_mobile' => $order->shipping_phone,
            'ipn_url' => route('payment.ipn'),
            'cancel_url' => route('payment.cancelled'),
            'success_url' => route('payment.success'),
            'fail_url' => route('payment.failed'),
            'currency' => 'BDT',
        ];
    }

    private function initiateAmarpayGateway($user, $invoiceId, $amount, $customerName, $customerPhone)
    {
        $payment = Payment::where('transaction_id', $invoiceId)->first();
        $order = Order::where('invoice_id', $invoiceId)->first();

        if (! $payment) {
            $payment = Payment::create([
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
            return redirect()->route('orders.index')->with('success', 'Payment already completed for this order.');
        }

        if ($payment->status == Payment::FAILED) {
            $order->paid = 0;
            $order->due = $amount;
            $order->save();
            $payment->update(['status' => Payment::PENDING]);
        }

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
                'desc' => 'Test Payment',
                'cus_name' => $customerName,
                'cus_email' => 'user@gmail.com',
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

            // dd($response);

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

    private function initiateBkashGateway($user, $invoiceId, $amount, $customerName, $customerPhone)
    {
        $payment = Payment::where('transaction_id', $invoiceId)->first();
        $order = Order::where('invoice_id', $invoiceId)->first();

        if (! $payment) {
            $payment = Payment::create([
                'user_id' => $user->id,
                'gateway' => 'bkash',
                'transaction_id' => $invoiceId,
                'status' => Payment::PENDING,
                'amount' => $amount,
                'currency' => 'BDT',
                'customer_name' => $customerName,
                'customer_email' => $user->email,
                'customer_phone' => $customerPhone,
            ]);
        }

        if ($payment->status === Payment::SUCCESSFUL) {
            return [
                'message' => 'Payment already completed.',
                'payment_url' => null,
            ];
        }

        if ($payment->status === Payment::FAILED) {
            $order->update([
                'paid' => 0,
                'due' => $amount,
            ]);

            $payment->update(['status' => Payment::PENDING]);
        }

        try {
            $bkash = app(BkashService::class);

            $response = $bkash->createPayment(
                $amount,
                $invoiceId
            );

            if (! isset($response['bkashURL'])) {
                throw new \Exception('bKash payment URL not found');
            }

            return [
                'message' => 'Redirecting to bKash',
                'payment_url' => $response['bkashURL'],
            ];
        } catch (\Exception $e) {
            Log::error('bKash Init Error', [
                'invoice' => $invoiceId,
                'error' => $e->getMessage(),
            ]);

            return [
                'message' => $e->getMessage(),
                'payment_url' => null,
            ];
        }
    }

    public function success($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();

        return view('frontend.orders.success', compact('order'));
    }

    public function tracking($invoice_id)
    {
        $order = Order::withCount('items')->where('invoice_id', $invoice_id)->first();

        return view('frontend.orders.tracking', compact('order'));
    }

    public function review(Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('GET')) {
            return view('frontend.orders.review', compact('user', 'product'));
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'order_item_id' => 'required|exists:order_items,id',
            'description' => 'required|string',
            'images' => 'nullable|array',
            'images.*' => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $orderItem = OrderItem::find($request->order_item_id);

        $reviewExists = Review::where('order_item_id', $orderItem->id)->first();

        if ($reviewExists) {
            return redirect()->back()->with('error', 'You have already reviewed this product.');
        }

        $review = Review::create([
            'product_id' => $orderItem->product_id,
            'order_id' => $orderItem->order_id,
            'order_item_id' => $orderItem->id,
            'user_id' => $user->id,
            'seller_id' => $orderItem->order->seller_id,
            'rating' => $request->rating,
            'description' => $request->description,
            'is_reviewed' => 1,
        ]);

        $review->product->addRating($review->rating);

        $reviewedProductSeller = Seller::where('id', $review->product->seller_id)->first();

        $reviewedProductSeller->addRating($review->rating);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                ReviewImage::create([
                    'review_id' => $review->id,
                    'image' => upload_file($file, 'images/reviews'),
                ]);
            }
        }

        $orderItem->is_reviewed = 1;
        $orderItem->save();

        return redirect()->back()->with('success', 'Review submitted successfully.');
    }

    public function getDistricts($divisionId)
    {
        $districts = District::where('division_id', $divisionId)->pluck('name', 'id');

        return response()->json($districts);
    }

    // public function payNow(Order $order)
    // {
    //     $orderBillingAddress = OrderBillingAddress::where('order_id', $order->id)->first();

    //     $paymentGateway = $this->initiatePaymentGateway(
    //         $order->user,
    //         $order->invoice_id,
    //         $order->payable,
    //         $orderBillingAddress->customer_name,
    //         $orderBillingAddress->customer_phone,
    //     );

    //     $this->processAffiliateCommissions($order->items, auth()->user(), $order->id);

    //     return redirect()->away($paymentGateway['payment_url']);
    // }

    public function payNow(Order $order)
    {
        $orderBillingAddress = OrderBillingAddress::where('order_id', $order->id)->first();

        if (! $orderBillingAddress) {
            return back()->with('error', 'Billing address not found.');
        }

        if ($order->due <= 0) {
            return back()->with('success', 'This order is already paid.');
        }

        $paymentGateway = $this->initiatePaymentGateway(
            $order->user,
            $order->invoice_id,
            $order->due,
            $orderBillingAddress->customer_name,
            $orderBillingAddress->customer_phone
        );

        if (empty($paymentGateway['payment_url'])) {
            return back()->with('error', $paymentGateway['message'] ?? 'Unable to initiate payment.');
        }

        return redirect()->away($paymentGateway['payment_url']);
    }
}
