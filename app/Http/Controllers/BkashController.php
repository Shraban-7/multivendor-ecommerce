<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Seller;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Services\BkashService;
use App\Services\AffiliateService;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class BkashController extends Controller
{
    protected $bkashService;

    public function __construct(BkashService $bkashService)
    {
        $this->bkashService = $bkashService;
    }

    /**
     * Entry point: User clicks "Pay with bKash"
     */
    public function pay(Request $request)
    {
        $amount = 100;
        $invoiceNumber = 'SM-' . uniqid();

        try {
            $response = $this->bkashService->createPayment($amount, $invoiceNumber);

            if (isset($response['bkashURL'])) {
                return redirect()->away($response['bkashURL']);
            } else {
                return response()->json(['error' => 'Payment Creation Failed', 'details' => $response], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Callback: bKash redirects here after user interaction
     */
    public function callback(Request $request)
    {
        $status = $request->input('status');
        $paymentID = $request->input('paymentID');

        if ($status === 'success') {
            try {
                // Execute the payment to finalize it
                $response = $this->bkashService->executePayment($paymentID);

                // If execute is successful (transaction status is Completed)
                if (isset($response['statusCode']) && $response['statusCode'] === '0000' && $response['transactionStatus'] === 'Completed') {

                    // TODO: Update your database marking the order as paid
                    // $trxID = $response['trxID'];

                    $invoiceId = $response['merchantInvoiceNumber'];

                    $payment = Payment::where('transaction_id', $invoiceId)->first();
                    $order = Order::where('invoice_id', $invoiceId)->firstOrFail();

                    $payment->update([
                        'status' => Payment::SUCCESSFUL,
                        'gateway_trxid' => $response['trxID'] ?? null,
                        'response' => json_encode($response),
                    ]);

                    $due = $order->due;
                    $paid = $order->paid;

                    if ($due > 0 && $payment->status === Payment::SUCCESSFUL) {
                        $due = $due - $payment->amount;
                        $paid = $payment->amount;
                        $seller = Seller::find($order->seller_id);

                        $balance = $seller->balance + $order->seller_earnings;

                        $seller->update([
                            'balance' => $balance
                        ]);

                        app(AffiliateService::class)
                            ->approveCommission($order);
                    }

                    $order->update([
                        'payment_id' => $payment->id,
                        'due' => $due,
                        'paid' => $paid,
                    ]);

                    return response()->json([
                        'message' => 'Payment Successful',
                        'data' => $response
                    ]);
                } else {
                    return response()->json(['error' => 'Payment Execution Failed', 'details' => $response], 400);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'Payment Failed or Cancelled', 'status' => $status], 400);
        }
    }
}

//callback response:
// {
//     "message": "Payment Successful",
//         "data": {
//         "paymentID": "TR0011sqWQFWJ1766387377575",
//         "trxID": "CLM50O2XV5",
//         "transactionStatus": "Completed",
//         "amount": "100",
//         "currency": "BDT",
//         "intent": "sale",
//         "paymentExecuteTime": "2025-12-22T13:11:13:321 GMT+0600",
//         "merchantInvoiceNumber": "SM-6948eeb160276",
//         "payerType": "Customer",
//         "payerReference": "SM-6948eeb160276",
//         "customerMsisdn": "01770618575",
//         "payerAccount": "01770618575",
//         "maxRefundableAmount": "100",
//         "statusCode": "0000",
//         "statusMessage": "Successful"
//     }
// }