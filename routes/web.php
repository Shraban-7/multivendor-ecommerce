<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PaymentController;

Route::get('mails/test', function () {
    $data['customerName'] = 'John Doe';
    $data['orderId'] = '223';
    $data['orderDate'] = '223';
    $data['totalAmount'] = '223';
    $data['items'] = [];
    $data['trackOrderUrl'] = '';

    return view('emails.order-delivered', $data);
});

Route::prefix('mails')->as('mails.')->group(function () {
    Route::get('/welcome', function () {
        $data['customerName'] = 'John Doe';
        $data['orderId'] = '223';
        $data['orderDate'] = '223';
        $data['totalAmount'] = '223';
        $data['items'] = [];
        $data['trackOrderUrl'] = '';

        return view('emails.welcome', $data);
    })->name('welcome');

    Route::get('/test', function () {
        $data['customerName'] = 'John Doe';
        $data['orderId'] = '223';
        $data['orderDate'] = '223';
        $data['totalAmount'] = '223';
        $data['items'] = [];
        $data['trackOrderUrl'] = '';

        return view('emails.test', $data);
    })->name('test');

    Route::get('/order-delivered', function () {
        $data['customerName'] = 'John Doe';
        $data['order_id'] = '223';
        $data['orderDate'] = '223';
        $data['totalAmount'] = '223';
        $data['items'] = [];
        $data['trackOrderUrl'] = '';
        $data['orderDate'] = '2025-10-12';
        $data['delivery_date'] = '2025-10-15';
        $data['review_url'] = '#';
        $data['item_count'] = 6;
        $data['total_amount'] = "1200.00";
        $data['order_details_url'] = '#';
        $data['shop_url'] = '#';

        return view('emails.order-delivered', $data);
    })->name('order_delivered');

    Route::get('/order-cancellation', function () {
        $data['customerName'] = 'John Doe';
        $data['order_id'] = '223';
        $data['orderDate'] = '223';
        $data['totalAmount'] = '223';
        $data['items'] = [];
        $data['trackOrderUrl'] = '';
        $data['orderDate'] = '2025-10-12';
        $data['delivery_date'] = '2025-10-15';
        $data['cancellation_date'] = '2025-10-15';
        $data['review_url'] = '#';
        $data['item_count'] = 6;
        $data['item_total'] = "4800.00";
        $data['total_amount'] = "1200.00";
        $data['refund_amount'] = "1200.00";
        $data['refund_days'] = "10";
        $data['order_details_url'] = '#';
        $data['shop_url'] = '#';
        $data['cancellation_reason'] = '';
        $data['product_name'] = 'abcd';
        $data['quantity'] = 1;
        $data['price'] = "1000.00";

        return view('emails.order-cancellation', $data);
    })->name('order_cancellation');

    Route::get('/order-confirmation', function () {
        $data['customer_name'] = 'John Doe';
        $data['order_id'] = '223';
        $data['orderDate'] = '223';
        $data['total_amount'] = '223';
        $data['items'] = [];
        $data['track_order_url'] = '';
        $data['order_date'] = '2025-10-12';
        $data['delivery_date'] = '2025-10-15';
        $data['cancellation_date'] = '2025-10-15';
        $data['review_url'] = '#';
        $data['item_count'] = 6;
        $data['item_total'] = "4800.00";
        $data['item_total_1'] = "4800.00";
        $data['subtotal'] = "4800.00";
        $data['total_amount'] = "1200.00";
        $data['refund_amount'] = "1200.00";
        $data['shipping_cost'] = "1200.00";
        $data['tax'] = "10.00";
        $data['refund_days'] = "10";
        $data['order_details_url'] = '#';
        $data['shop_url'] = '#';
        $data['cancellation_reason'] = '';
        $data['product_name'] = 'abcd';
        $data['quantity'] = 1;
        $data['quantity_1'] = 1;
        $data['price'] = "1000.00";
        $data['price_1'] = "1000.00";
        $data['product_image_1'] = "";
        $data['product_name_1'] = "";
        $data['vendor_name_1'] = "";
        $data['address_line_1'] = "geqwf";
        $data['address_line_2'] = "geqwf";
        $data['city'] = "Mymensingh";
        $data['state'] = "Mymensingh";
        $data['zip'] = "2200";
        $data['country'] = "Bangladesh";

        return view('emails.order-confirmation', $data);
    })->name('order_confirmation');

    Route::get('/shipping-update', function () {
        $data['customer_name'] = 'John Doe';
        $data['order_id'] = '223';
        $data['tracking_number'] = '223';
        $data['tracking_url'] = '#';
        $data['carrier_name'] = 'abcd';
        $data['estimated_delivery'] = '30';
        $data['orderDate'] = '223';
        $data['total_amount'] = '223';
        $data['items'] = [];
        $data['track_order_url'] = '';
        $data['order_date'] = '2025-10-12';
        $data['delivery_date'] = '2025-10-15';
        $data['cancellation_date'] = '2025-10-15';
        $data['review_url'] = '#';
        $data['item_count'] = 6;
        $data['item_total'] = "4800.00";
        $data['item_total_1'] = "4800.00";
        $data['subtotal'] = "4800.00";
        $data['total_amount'] = "1200.00";
        $data['refund_amount'] = "1200.00";
        $data['shipping_cost'] = "1200.00";
        $data['tax'] = "10.00";
        $data['refund_days'] = "10";
        $data['order_details_url'] = '#';
        $data['shop_url'] = '#';
        $data['cancellation_reason'] = '';
        $data['product_name'] = 'abcd';
        $data['quantity'] = 1;
        $data['quantity_1'] = 1;
        $data['price'] = "1000.00";
        $data['price_1'] = "1000.00";
        $data['product_image_1'] = "";
        $data['product_name_1'] = "";
        $data['vendor_name_1'] = "";
        $data['address_line_1'] = "geqwf";
        $data['address_line_2'] = "geqwf";
        $data['city'] = "Mymensingh";
        $data['state'] = "Mymensingh";
        $data['zip'] = "2200";
        $data['country'] = "Bangladesh";

        return view('emails.shipping-update', $data);
    })->name('shipping_update');

    Route::get('/refund-processed', function () {
        $data['customer_name'] = 'John Doe';
        $data['order_id'] = '223';
        $data['transaction_id'] = 'A3h&890';
        $data['tracking_number'] = '223';
        $data['tracking_url'] = '#';
        $data['carrier_name'] = 'abcd';
        $data['estimated_delivery'] = '30';
        $data['orderDate'] = '223';
        $data['total_amount'] = '223';
        $data['items'] = [];
        $data['track_order_url'] = '';
        $data['order_date'] = '2025-10-12';
        $data['refund_date'] = '2025-10-12';
        $data['delivery_date'] = '2025-10-15';
        $data['cancellation_date'] = '2025-10-15';
        $data['review_url'] = '#';
        $data['item_count'] = 6;
        $data['item_total'] = "4800.00";
        $data['item_total_1'] = "4800.00";
        $data['subtotal'] = "4800.00";
        $data['total_amount'] = "1200.00";
        $data['refund_amount'] = "1200.00";
        $data['shipping_cost'] = "1200.00";
        $data['tax'] = "10.00";
        $data['refund_days'] = "10";
        $data['order_details_url'] = '#';
        $data['shop_url'] = '#';
        $data['cancellation_reason'] = '';
        $data['product_name'] = 'abcd';
        $data['quantity'] = 1;
        $data['quantity_1'] = 1;
        $data['price'] = "1000.00";
        $data['price_1'] = "1000.00";
        $data['product_image_1'] = "";
        $data['product_name_1'] = "";
        $data['vendor_name_1'] = "";
        $data['address_line_1'] = "geqwf";
        $data['address_line_2'] = "geqwf";
        $data['city'] = "Mymensingh";
        $data['state'] = "Mymensingh";
        $data['zip'] = "2200";
        $data['country'] = "Bangladesh";
        $data['payment_method'] = "B Kash";
        $data['refund_processing_days'] = "7";
        $data['account_url'] = "#";

        return view('emails.refund-processed', $data);
    })->name('refund_processed');

    Route::prefix('vendors')->as('vendors')->group(function () {
        Route::get('/welcome', function () {
            $data['customerName'] = 'John Doe';
            $data['orderId'] = '223';
            $data['orderDate'] = '223';
            $data['totalAmount'] = '223';
            $data['items'] = [];
            $data['trackOrderUrl'] = '';
            $data['vendor_name'] = 'spinner fashion';
            $data['commission_rate'] = '12.5%';
            $data['payout_cycle'] = '30d';
            $data['vendor_dashboard_url'] = '#';
            $data['seller_guide_url'] = '#';

            return view('emails.vendors.welcome', $data);
        })->name('welcome');

        Route::get('/product-review-notification', function () {
            $data['customer_name'] = 'John Doe';
            $data['order_id'] = '223';
            $data['orderDate'] = '223';
            $data['total_amount'] = '223';
            $data['items'] = [];
            $data['track_order_url'] = '';
            $data['order_date'] = '2025-10-12';
            $data['delivery_date'] = '2025-10-15';
            $data['cancellation_date'] = '2025-10-15';
            $data['review_date'] = '2025-10-15';
            $data['review_url'] = '#';
            $data['respond_to_review_url'] = '#';
            $data['review_text'] = 'This product is good';
            $data['item_count'] = 6;
            $data['item_total'] = "4800.00";
            $data['item_total_1'] = "4800.00";
            $data['subtotal'] = "4800.00";
            $data['total_amount'] = "1200.00";
            $data['refund_amount'] = "1200.00";
            $data['shipping_cost'] = "1200.00";
            $data['tax'] = "10.00";
            $data['refund_days'] = "10";
            $data['order_details_url'] = '#';
            $data['shop_url'] = '#';
            $data['cancellation_reason'] = '';
            $data['product_name'] = 'abcd';
            $data['product_image'] = '';
            $data['product_sku'] = '2Nad789';
            $data['star_rating'] = '****';
            $data['rating_number'] = '4';
            $data['quantity'] = 1;
            $data['quantity_1'] = 1;
            $data['price'] = "1000.00";
            $data['vendor_name'] = 'spinner fashion';
            $data['commission_rate'] = '12.5%';
            $data['payout_cycle'] = '30d';
            $data['vendor_dashboard_url'] = '#';
            $data['seller_guide_url'] = '#';

            return view('emails.vendors.product-review-notification', $data);
        })->name('welcome');

        Route::get('/payment-released', function () {
            $data['customer_name'] = 'John Doe';
            $data['order_id'] = '223';
            $data['payout_id'] = '223';
            $data['orderDate'] = '223';
            $data['total_amount'] = '223';
            $data['items'] = [];
            $data['track_order_url'] = '';
            $data['order_date'] = '2025-10-12';
            $data['delivery_date'] = '2025-10-15';
            $data['cancellation_date'] = '2025-10-15';
            $data['review_date'] = '2025-10-15';
            $data['release_date'] = '2025-10-15';
            $data['review_url'] = '#';
            $data['respond_to_review_url'] = '#';
            $data['review_text'] = 'This product is good';
            $data['item_count'] = 6;
            $data['item_total'] = "4800.00";
            $data['item_total_1'] = "4800.00";
            $data['subtotal'] = "4800.00";
            $data['payout_amount'] = "1200.00";
            $data['refund_amount'] = "1200.00";
            $data['shipping_cost'] = "1200.00";
            $data['tax'] = "10.00";
            $data['refund_days'] = "10";
            $data['order_details_url'] = '#';
            $data['shop_url'] = '#';
            $data['cancellation_reason'] = '';
            $data['product_name'] = 'abcd';
            $data['product_image'] = '';
            $data['product_sku'] = '2Nad789';
            $data['star_rating'] = '****';
            $data['rating_number'] = '4';
            $data['quantity'] = 1;
            $data['quantity_1'] = 1;
            $data['price'] = "1000.00";
            $data['vendor_name'] = 'spinner fashion';
            $data['commission_rate'] = '12.5%';
            $data['payout_cycle'] = '30d';
            $data['vendor_dashboard_url'] = '#';
            $data['seller_guide_url'] = '#';
            $data['payment_method'] = 'Bank';
            $data['account_last_4'] = '1234';
            $data['total_sales'] = '4800.00';
            $data['estimated_arrival'] = '12d';
            $data['platform_fee_percent'] = '12%';
            $data['platform_fee'] = '12';
            $data['processing_fee'] = '12';
            $data['refunds'] = '12';
            $data['payout_amount'] = '120';
            $data['order_count'] = '2';
            $data['period_start'] = '2';
            $data['period_end'] = '7';
            $data['payout_details_url'] = '#';
            $data['download_statement_url'] = '#';

            return view('emails.vendors.payment-released', $data);
        })->name('welcome');

        Route::get('/new-order', function () {
            $data['customer_name'] = 'John Doe';
            $data['order_id'] = '223';
            $data['payout_id'] = '223';
            $data['orderDate'] = '223';
            $data['total_amount'] = '223';
            $data['items'] = [];
            $data['track_order_url'] = '';
            $data['order_date'] = '2025-10-12';
            $data['delivery_date'] = '2025-10-15';
            $data['cancellation_date'] = '2025-10-15';
            $data['review_date'] = '2025-10-15';
            $data['release_date'] = '2025-10-15';
            $data['review_url'] = '#';
            $data['respond_to_review_url'] = '#';
            $data['review_text'] = 'This product is good';
            $data['item_count'] = 6;
            $data['item_price'] = "1200.00";
            $data['item_total'] = "4800.00";
            $data['item_total_1'] = "4800.00";
            $data['subtotal'] = "4800.00";
            $data['payout_amount'] = "1200.00";
            $data['refund_amount'] = "1200.00";
            $data['shipping_cost'] = "1200.00";
            $data['tax'] = "10.00";
            $data['refund_days'] = "10";
            $data['order_details_url'] = '#';
            $data['shop_url'] = '#';
            $data['cancellation_reason'] = '';
            $data['product_name'] = 'abcd';
            $data['product_image'] = '';
            $data['product_sku'] = '2Nad789';
            $data['star_rating'] = '****';
            $data['rating_number'] = '4';
            $data['quantity'] = 1;
            $data['quantity_1'] = 1;
            $data['price'] = "1000.00";
            $data['vendor_name'] = 'spinner fashion';
            $data['commission_rate'] = '12.5%';
            $data['payout_cycle'] = '30d';
            $data['vendor_dashboard_url'] = '#';
            $data['seller_guide_url'] = '#';
            $data['payment_method'] = 'Bank';
            $data['account_last_4'] = '1234';
            $data['total_sales'] = '4800.00';
            $data['vendor_earnings'] = '4800.00';
            $data['estimated_arrival'] = '12d';
            $data['platform_fee_percent'] = '12%';
            $data['platform_fee'] = '12';
            $data['processing_fee'] = '12';
            $data['refunds'] = '12';
            $data['payout_amount'] = '120';
            $data['order_count'] = '2';
            $data['period_start'] = '2';
            $data['period_end'] = '7';
            $data['payout_details_url'] = '#';
            $data['download_statement_url'] = '#';
            $data['vendor_name_1'] = "";
            $data['address_line_1'] = "geqwf";
            $data['address_line_2'] = "geqwf";
            $data['city'] = "Mymensingh";
            $data['state'] = "Mymensingh";
            $data['zip'] = "2200";
            $data['country'] = "Bangladesh";
            $data['payment_method'] = "B Kash";
            $data['refund_processing_days'] = "7";
            $data['account_url'] = "#";
            $data['process_order_url'] = "#";
            $data['print_packing_slip_url'] = "#";
            $data['customer_phone'] = "0170000000";

            return view('emails.vendors.new-order', $data);
        })->name('welcome');


        Route::get('/low-stock-alert', function () {
            $data['customer_name'] = 'John Doe';
            $data['order_id'] = '223';
            $data['payout_id'] = '223';
            $data['orderDate'] = '223';
            $data['total_amount'] = '223';
            $data['items'] = [];
            $data['track_order_url'] = '';
            $data['order_date'] = '2025-10-12';
            $data['delivery_date'] = '2025-10-15';
            $data['cancellation_date'] = '2025-10-15';
            $data['review_date'] = '2025-10-15';
            $data['release_date'] = '2025-10-15';
            $data['review_url'] = '#';
            $data['respond_to_review_url'] = '#';
            $data['review_text'] = 'This product is good';
            $data['item_count'] = 6;
            $data['additional_count'] = 6;
            $data['item_price'] = "1200.00";
            $data['item_total'] = "4800.00";
            $data['item_total_1'] = "4800.00";
            $data['subtotal'] = "4800.00";
            $data['payout_amount'] = "1200.00";
            $data['refund_amount'] = "1200.00";
            $data['shipping_cost'] = "1200.00";
            $data['tax'] = "10.00";
            $data['refund_days'] = "10";
            $data['order_details_url'] = '#';
            $data['shop_url'] = '#';
            $data['cancellation_reason'] = '';
            $data['product_name_1'] = 'abcd';
            $data['product_name_2'] = 'abcd';
            $data['product_image_1'] = '';
            $data['product_image_2'] = '';
            $data['product_sku_1'] = '2Nad789';
            $data['product_sku_2'] = '2Nad789';
            $data['stock_1'] = '5';
            $data['stock_2'] = '5';
            $data['star_rating'] = '****';
            $data['rating_number'] = '4';
            $data['quantity'] = 1;
            $data['quantity_1'] = 1;
            $data['price'] = "1000.00";
            $data['vendor_name'] = 'spinner fashion';
            $data['commission_rate'] = '12.5%';
            $data['payout_cycle'] = '30d';
            $data['vendor_dashboard_url'] = '#';
            $data['seller_guide_url'] = '#';
            $data['payment_method'] = 'Bank';
            $data['account_last_4'] = '1234';
            $data['total_sales'] = '4800.00';
            $data['vendor_earnings'] = '4800.00';
            $data['estimated_arrival'] = '12d';
            $data['platform_fee_percent'] = '12%';
            $data['platform_fee'] = '12';
            $data['processing_fee'] = '12';
            $data['refunds'] = '12';
            $data['payout_amount'] = '120';
            $data['order_count'] = '2';
            $data['period_start'] = '2';
            $data['period_end'] = '7';
            $data['payout_details_url'] = '#';
            $data['download_statement_url'] = '#';
            $data['vendor_name_1'] = "";
            $data['address_line_1'] = "geqwf";
            $data['address_line_2'] = "geqwf";
            $data['city'] = "Mymensingh";
            $data['state'] = "Mymensingh";
            $data['zip'] = "2200";
            $data['country'] = "Bangladesh";
            $data['payment_method'] = "B Kash";
            $data['refund_processing_days'] = "7";
            $data['account_url'] = "#";
            $data['process_order_url'] = "#";
            $data['print_packing_slip_url'] = "#";
            $data['update_inventory_url'] = "#";
            $data['view_all_products_url'] = "#";
            $data['customer_phone'] = "0170000000";

            return view('emails.vendors.low-stock-alert', $data);
        })->name('welcome');
    });
});

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/invoice/{invoice_id}', [InvoiceController::class, 'invoice'])->name('invoice');
Route::get('/receipt/{invoice_id}', [InvoiceController::class, 'receipt'])->name('receipt');

Route::get('/get-districts/{divisionId}', [LocationController::class, 'getDistricts'])->name('get.districts');

Route::prefix('payment')->as('payment.')->group(function () {
    Route::get('/pay', [PaymentController::class, 'pay'])->name('pay');
    Route::middleware('aamarpay')->group(function () {
        Route::post('/success', [PaymentController::class, 'confirm'])->name('success');
        Route::match(['get', 'post'], '/cancel', [PaymentController::class, 'cancel'])->name('cancel');
        Route::post('/notify', [PaymentController::class, 'notify'])->name('notify');
    });
    Route::get('/test', function () {
        return view('payment.test');
    })->middleware('auth');
    Route::get('/mail', function () {
        return view('payment.mail');
    });
    Route::get('/manual', [PaymentController::class, 'manual']);
});
