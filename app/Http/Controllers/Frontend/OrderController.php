<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Payment;
use App\Models\Product;
use App\Models\District;
use App\Models\OrderItem;
use App\Enums\OrderStatus;
use App\Models\ReviewImage;
use App\Models\Notification;
use Illuminate\Http\Request;
use App\Enums\CommissionType;
use App\Models\BillingAddress;
use App\Models\PaymentGateway;
use App\Models\ProductVariant;
use App\Services\AamarpayService;
use App\Http\Controllers\Controller;
use App\Models\Division;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
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
        $order = Order::where('invoice_id', $invoice_id)->first();

        $order->load('items.product');

        return view('frontend.orders.details', compact('order'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
            'customer_name' => 'nullable|string',
            'customer_phone' => 'nullable|string',
            'division_id' => 'nullable|numeric',
            'district_id' => 'nullable|numeric',
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
            return response()->json([
                'status'  => false,
                'message' => 'No cart found for the selected seller.',
            ], 404);
        }

        $sub_total    = 0;
        $discount     = 0;
        $tax          = 0;
        $orderItems   = [];
        $shipping_fee = $seller->shipping_cost;

        foreach ($cart->cart_items as $cartItem) {
            $product      = $cartItem->product;
            $variant      = $cartItem->variant;
            $unitPrice    = $cartItem->price;
            $itemTotal    = $cartItem->quantity * $unitPrice;
            $itemDiscount = $cartItem->quantity * ($cartItem->original_price - $cartItem->discounted_price);
            $tax += floatval($product->tax) * $cartItem->quantity;
            $sub_total += $itemTotal;
            $discount += $itemDiscount;

            $grand_total = $sub_total + $discount;

            $orderItems[] = [
                'product_id'         => $product->id,
                'product_variant_id' => $cartItem->product_variant_id ?? null,
                'buying_price'       => $variant ? $variant->buying_price : $product->buying_price,
                'unit_price'         => $cartItem->price,
                'quantity'           => $cartItem->quantity,
                'discount'           => $itemDiscount,
                'sub_total'          => $itemTotal,
            ];
        }

        if ($request->isMethod('GET')) {
            $customer_addresses = BillingAddress::where('user_id', $user->id)->get();
            $payment_gateways   = PaymentGateway::where('is_enabled', true)->get();
            $divisions = Division::get();
            $districts = District::get();
            $billingAddress = BillingAddress::where('user_id', Auth::id())
                ->latest()
                ->first();

            $billingAddresses = BillingAddress::where('user_id', Auth::id())
                ->latest()
                ->get();

            return view('frontend.pages.checkout', compact('user', 'customer_addresses', 'selectedSellerId', 'sub_total', 'discount', 'tax', 'shipping_fee', 'payment_gateways', 'grand_total', 'divisions', 'districts', 'billingAddress', 'billingAddresses'));
        }

        $billingData = collect($validated)->except('seller_id')->toArray();
        $billingData['user_id'] =$user->id;

        $billingInformation = BillingAddress::create($billingData);

        $seller = Seller::where('id', $selectedSellerId)->first();

        $total_commission = 0;

        if ($seller->commission_amount != null && $seller->commission_type != null) {
            if ($seller->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = ($sub_total + $tax + $shipping_fee) * ($seller->commission_amount / 100);
            } else if ($seller->commission_type === CommissionType::FLAT->value) {
                $total_commission = $seller->commission_amount;
            }
        }

        $payableAmount = $sub_total + $shipping_fee + $tax;

        $sellerEarning = $payableAmount - $total_commission;
        $invoiceId = Order::generateInvoiceID();

        $order = Order::create([
            'user_id'           => $user->id,
            'seller_id'         => $selectedSellerId,
            'billing_address_id' => $billingInformation->id,
            'billing_information' => json_encode($billingInformation),
            'invoice_id'        => $invoiceId,
            'sub_total'         => $sub_total,
            'total'             => $sub_total + $tax + $shipping_fee,
            'discount'          => $discount,
            'tax'               => $tax,
            'shipping_fee'      => $shipping_fee,
            'payable'           => $payableAmount,
            'due'               => $sub_total + $shipping_fee + $tax,
            'commission_type'   => $seller->commission_type,
            'commission_amount' => $seller->commission_amount,
            'seller_earnings'   => $sellerEarning,
            'total_commission'  => $total_commission,
            'status'            => OrderStatus::PENDING->value,
            'delivery_status'   => OrderStatus::ORDER_PLACED->value,
        ]);

        $order->items()->createMany($orderItems);

        foreach ($order->items as $item) {
            $product = optional(Product::find($item['product_id']));
            if (isset($item['product_variant_id'])) {
                $variant = optional(ProductVariant::find($item['product_variant_id']));

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

        $paymentGateway = $this->initiatePaymentGateway($request, $invoiceId, $payableAmount);

        sendNotification(
            $user->id,
            'Order Placed Successfully',
            "Your order #{$invoiceId} has been placed successfully.",
            Notification::TARGET_ORDER,
            $invoiceId,
        );

        sendNotification(
            $selectedSellerId,
            'New Order Received',
            "You have received a new order #{$invoiceId}.",
            Notification::TARGET_ORDER,
            $invoiceId,
        );

        return response()->json([
            'status'      => true,
            'message'     => $paymentGateway['message'],
            'payment_url' => $paymentGateway['payment_url'],
            'order'       => $order,
        ]);
    }

    private function initiatePaymentGateway(Request $request, $invoiceId, $amount)
    {
        $user          = Auth::user();
        $customerName  = $request->input('customer_name', $user->name);
        $customerEmail = $user->email;
        $customerPhone = $request->input('customer_phone') ?? '';

        $payment = Payment::create([
            'gateway'        => 'aamarpay',
            'transaction_id' => $invoiceId,
            'status'         => Payment::PENDING,
            'amount'         => $amount,
            'currency'       => 'BDT',
            'customer_name'  => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
        ]);

        $aamarpay = (new AamarpayService);

        $message    = 'Redirecting to payment gateway';
        $paymentUrl = '';

        try {
            $response = $aamarpay->initiate([
                'tran_id'      => $invoiceId,
                'success_url'  => route('payment.success'),
                'fail_url'     => route('payment.cancel'),
                'cancel_url'   => route('payment.cancel'),
                'amount'       => $amount,
                'desc'         => 'Test Payment',
                'cus_name'     => $customerName,
                'cus_email'    => $customerEmail,
                'cus_add1'     => '',
                'cus_add2'     => '',
                'cus_city'     => '',
                'cus_state'    => '',
                'cus_postcode' => '',
                'cus_country'  => 'Bangladesh',
                'cus_phone'    => $customerPhone,
                'opt_a'        => base64_encode(json_encode([
                    'user_id'    => $user->id,
                    'return_url' => route('orders.index'),
                ])),
            ]);

            if (isset($response['payment_url'])) {
                $paymentUrl = $response['payment_url'];
                //return redirect()->away($response['payment_url']);

            } else {
                $message = 'Payment URL not received.';
            }

            //return back()->with('error', 'Payment URL not received.');
        } catch (\Exception $e) {
            $message = $e->getMessage();
            //return back()->with('error', $e->getMessage());
        }

        return [
            'message'     => $message,
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

    public function review(Product $product, Request $request)
    {
        $user = Auth::user();

        if ($request->isMethod('GET')) {
            return view('frontend.orders.review', compact('user', 'product'));
        }

        $request->validate([
            'rating'      => 'required|integer|min:1|max:5',
            'description' => 'required|string',
            'images'      => 'nullable|array',
            'images.*'    => 'mimes:jpeg,png,jpg,gif,pdf,doc,docx,zip|max:4000',
        ]);

        $review_exist = Review::where('product_id', $product->id)->where('user_id', $user->id)->first();

        if ($review_exist) {
            return redirect()->back();
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

        return redirect()->route('orders.index');
    }

    public function getDistricts($divisionId)
    {
        $districts = District::where('division_id', $divisionId)->pluck('name', 'id');
        return response()->json($districts);
    }
}
