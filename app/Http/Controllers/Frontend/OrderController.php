<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\User;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\District;
use App\Models\Division;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\ReviewImage;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\BillingAddress;
use App\Models\PaymentGateway;
use App\Models\ProductVariant;
use App\Services\AamarpayService;
use App\Models\AffiliateCommission;
use App\Models\OrderBillingAddress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class OrderController extends Controller
{
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

        $orders = $query->get();

        $interest_products = Product::with([
            'category',
            'subcategory',
            'images',
            'seller',
            'variants.option_values.option',
            'reviews.user',
        ])->inRandomOrder()->limit(8)->get();

        $products = $interest_products->map(fn($product) => $product->toDetailsArray());

        return view('frontend.orders.index', [
            'orders'   => $orders,
            'status'   => $statusLabel,
            'products' => $products,
        ]);
    }

    public function details($invoice_id)
    {
        $user = Auth::user();

        $order = Order::where('invoice_id', $invoice_id)->with('seller')->first();

        $order->load('items.product');

        return view('frontend.orders.details', compact('order', 'user'));
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
        $cart   = Cart::where('user_id', $user->id)
            ->where('seller_id', $selectedSellerId)
            ->with('cart_items.product')
            ->first();

        if (! $cart) {
            if ($request->ajax()) {
                return response()->json([
                    'status'  => false,
                    'message' => 'No cart found for the selected seller.',
                ], 404);
            }

            return redirect()->route('home');
        }


        $sub_total = 0;
        $total = 0;
        $discount = 0;
        $vat_amount = 0;
        $orderItems = [];
        $shipping_fee = $seller->shipping_cost;

        // $payment_type = PaymentType::COD_ONLY->value;

        foreach ($cart->cart_items as $cartItem) {
            $product = $cartItem->product;
            $variant = $cartItem->variant;
            $unitPrice = $cartItem->price;
            $itemSubtotal = $cartItem->quantity * $variant->selling_price;
            $itemTotal = $cartItem->quantity * $unitPrice;
            $itemDiscount = $cartItem->quantity * ($cartItem->original_price - $cartItem->discounted_price);
            $vat_amount += floatval(($product->vat_percent * $unitPrice) / 100) * $cartItem->quantity;
            $sub_total += $itemSubtotal;
            $total += $itemTotal;
            $discount += $itemDiscount;
            $grand_total = $sub_total + $discount;

            $payment_type = Order::getPaymentType($product);

            $orderItems[] = [
                'product_id' => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'sku' => $variant->sku,
                'product_name' => $product->name,
                'variant_name' => $variant->fullName,
                'buying_price' => $variant ? $variant->buying_price : $product->buying_price,
                'selling_price' => $variant->selling_price,
                'unit_price' => $cartItem->price,
                'quantity' => $cartItem->quantity,
                'discount' => $itemDiscount,
                'sub_total' => $itemSubtotal + $itemDiscount,
                'total' => $itemTotal,
                'vat_percent' => $product->vat_percent,
                'vat_amount' => floatval(($product->vat_percent * $unitPrice) / 100) * $cartItem->quantity,
            ];
        }

        if ($request->isMethod('GET')) {
            $payment_gateways = PaymentGateway::where('is_enabled', true)->get();
            $divisions = Division::get();
            $districts = District::get();
            $billingAddresses = BillingAddress::where('user_id', $user->id)
                ->latest()
                ->get();

            return view('frontend.pages.checkout', compact('user', 'selectedSellerId', 'sub_total', 'discount', 'vat_amount', 'shipping_fee', 'payment_gateways', 'grand_total', 'divisions', 'districts', 'billingAddresses', 'total'));
        }

        $billingData = collect($validated)->except('seller_id')->toArray();
        $billingData['user_id'] = $user->id;

        $billingAddress = BillingAddress::find($validated['billing_address_id']);

        $seller = Seller::where('id', $selectedSellerId)->first();

        $commissionData = $seller->calculateEarning($total, $vat_amount);

        $total_commission = $commissionData['total_commission'];
        $sellerEarning = $commissionData['seller_earning'];

        $invoiceId = Order::generateInvoiceID($selectedSellerId);
        $payableAmount = $total + $shipping_fee + $vat_amount;

        $payment = Order::calculatePaymentAmounts($product, $payableAmount, $shipping_fee);

        $paid_amount = $payment['paid'];
        $due_amount  = $payment['due'];

        $order = Order::create([
            'user_id' => $user->id,
            'seller_id' => $selectedSellerId,
            'invoice_id' => $invoiceId,
            'sub_total' => $sub_total,
            'total' => $total,
            'discount' => $discount,
            'vat_amount' => $vat_amount,
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

        $order_billing_address = OrderBillingAddress::create([
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

        if ($payment_type == PaymentType::COD_ONLY->value) {
            $paymentGateway = [
                'message' => 'Order placed successfully',
                'payment_url' => route('orders.index'),
            ];
        } else {
            $paymentGateway = $this->initiatePaymentGateway($invoiceId, $payableAmount, $order_billing_address->customer_name,$order_billing_address->customer_phone);
        }

        $this->processAffiliateCommissions($order->items, auth()->user(), $order->id);

        $affiliate = AffiliateCommission::where('order_id', $order->id)->first();

        $order->affiliate_id = $affiliate->affiliate_id ?? null;

        $order->save();

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

    private function processAffiliateCommissions($orderItems, $user, $invoiceId)
    {
        $cookieValue = Cookie::get('affiliate_refs');
        $affiliateRefs = json_decode($cookieValue, true) ?: [];

        foreach ($orderItems as $item) {

            if (!isset($item->product) || !isset($item->product->slug)) {
                continue;
            }

            $productSlug = $item->product->slug;

            if (isset($affiliateRefs[$productSlug])) {
                $referralCodes = $affiliateRefs[$productSlug];

                foreach ($referralCodes as $refCode) {
                    $affiliateUser = User::where('referral_code', $refCode)->first();

                    if (!$affiliateUser || $affiliateUser->id === $user->id) {
                        continue;
                    }

                    $commissionAmount = $item->unit_price * $item->quantity * self::AFFILIATE_COMMISSION_PERCENTAGE;

                    AffiliateCommission::create([
                        'user_id' => $user->id,
                        'order_id' => $invoiceId,
                        'product_id' => $item->product_id,
                        'affiliate_id' => $affiliateUser->id,
                        'commission_amount' => $commissionAmount,
                        'commission_date' => now(),
                    ]);
                }
            }
        }


        Cookie::queue(Cookie::forget('affiliate_refs'));
    }

    private function initiatePaymentGateway($invoiceId, $amount, $customer_name,$customer_phone)
    {
        $user = Auth::user();
        $customerName  = $customer_name;
        $customerEmail = $user->email;
        $customerPhone = $customer_phone;

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
                'customer_phone' => $customerEmail,
            ]);
        }

        if ($payment->status == Payment::SUCCESSFUL) {
            return redirect()->route('orders.index')->with('success', 'Payment already completed for this order.');
        }

        if ($payment->status == Payment::FAILED) {
            $payment->update(['status' => Payment::PENDING]);
        }

        $aamarpay = (new AamarpayService);

        $message    = 'Redirecting to payment gateway';
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
                'cus_email' => $customerEmail,
                'cus_add1' => '',
                'cus_add2' => '',
                'cus_city' => '',
                'cus_state' => '',
                'cus_postcode' => '',
                'cus_country' => 'Bangladesh',
                'cus_phone' => $customerPhone,
                'opt_a' => base64_encode(json_encode([
                    'user_id'    => $user->id,
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
            'rating' => $request->rating,
            'description' => $request->description,
        ]);

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

    public function payNow(Order $order)
    {
        $billingAddress = OrderBillingAddress::where('order_id',$order->id)->first();

        $paymentGateway = $this->initiatePaymentGateway(
            $order->invoice_id,
            $order->payable,
            $billingAddress->customer_name,
            $billingAddress->customer_phone,
        );

        $this->processAffiliateCommissions($order->items, auth()->user(), $order->id);

        return redirect()->away($paymentGateway['payment_url']);
    }
}
