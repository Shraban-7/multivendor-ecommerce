<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\CommissionType;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\AamarpayPayment;
use App\Models\Cart;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
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
            ->where('user_id', Auth::user()->id)
            ->where('invoice_id', '!=', null);

        if ($statusLabel !== 'all') {
            $query->where('status', $statusValue);
        }

        $orders = $query->paginate(10);

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

    public function details(Order $order)
    {
        $order->load('items.product');

        return view('frontend.orders.details', compact('order'));
    }

    public function checkout(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'seller_id' => 'required|exists:sellers,id',
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

        $sub_total        = 0;
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

            $orderItems[] = [
                'product_id'            => $product->id,
                'product_variant_id'   => $cartItem->product_variant_id ?? null,
                'buying_price'          => $variant ? $variant->buying_price : $product->buying_price,
                'unit_price'            => $cartItem->price,
                'quantity'              => $cartItem->quantity,
                'discount'              => $itemDiscount,
                'sub_total'             => $itemTotal
            ];

            if ($variant) {
                $variant->decrement('stock_in', $cartItem->quantity);
                $variant->increment('stock_out', $cartItem->quantity);
            } else {
                $product->decrement('stock_in', $cartItem->quantity);
                $product->increment('stock_out', $cartItem->quantity);
            }
        }

        if ($request->isMethod('GET')) {
            $customer_addresses = CustomerAddress::where('user_id', $user->id)->get();
            return view('frontend.pages.checkout', compact('user', 'customer_addresses', 'selectedSellerId', 'sub_total', 'discount', 'tax', 'shipping_fee'));
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

        $payableAmount = $sub_total + $shipping_fee + $tax;
        $invoiceId = uniqid('SM');

        $order = Order::create([
            'user_id'           => $user->id,
            'seller_id'         => $selectedSellerId,
            'customer_name'     => $request->input('customer_name', $user->name),
            'customer_email'    => $request->input('customer_email', $user->email),
            'customer_phone'    => $request->input('customer_phone'),
            'customer_address'  => $request->input('address'),
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

        return response()->json([
            'status'  => true,
            'message' => $paymentGateway['message'],
            'payment_url' => $paymentGateway['payment_url'],
            'order'   => $order,
        ]);
    }

    private function initiatePaymentGateway(Request $request, $invoiceId, $amount)
    {
        $user = Auth::user();
        $customerName = $request->input('customer_name', $user->name);
        $customerEmail = $request->input('customer_email', $user->customer_email);
        $customerPhone = $request->input('customer_phone') ?? '';

        AamarpayPayment::create([
            'gateway' => 'aamarpay',
            'transaction_id' => $invoiceId,
            'status' => AamarpayPayment::PENDING,
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
                    'user_id' => $user->id,
                    'return_url' => route('orders.index'),
                ]))
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
            'message' => $message,
            'payment_url' => $paymentUrl,
        ];
    }

    public function success(Order $order)
    {
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
}
