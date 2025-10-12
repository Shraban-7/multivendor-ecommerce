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

Route::prefix('mails')->as('mails.')->group(function(){
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
