<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\BkashService;
use App\Http\Controllers\Controller;

class BkashController extends Controller
{
    protected $bkash;

    public function __construct(BkashService $bkash)
    {
        $this->bkash = $bkash;
    }

    public function create(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'customer_phone' => 'required|string',
        ]);

        $transactionId = uniqid('SM');
        ;

        // Get token
        $tokenData = $this->bkash->getToken();

        // Create bKash payment
        $bkashPayment = $this->bkash->createPayment(
            $tokenData['id_token'],
            $request->amount,
            $transactionId
        );

        // Save payment
        Payment::create([
            'user_id' => auth()->id(),
            'gateway' => 'bkash',
            'transaction_id' => $transactionId,
            'gateway_trxid' => $bkashPayment['paymentID'],
            'amount' => $request->amount,
            'currency' => 'BDT',
            'customer_phone' => $request->customer_phone,
            'status' => Payment::PENDING,
            'response' => [
                'token' => $tokenData['id_token'],
                'create_response' => $bkashPayment,
            ],
        ]);

        return response()->json($bkashPayment);
    }

    public function execute(Request $request)
    {
        $request->validate([
            'paymentID' => 'required|string',
        ]);

        $payment = Payment::where('gateway', 'bkash')
            ->where('gateway_trxid', $request->paymentID)
            ->where('status', Payment::PENDING)
            ->firstOrFail();

        $token = $payment->response['token'];

        $result = $this->bkash->executePayment($token, $payment->gateway_trxid);

        if ($result['statusCode'] === '0000') {

            $payment->update([
                'gateway_trxid' => $result['trxID'],
                'status' => Payment::SUCCESSFUL,
                'response' => array_merge($payment->response ?? [], [
                    'execute_payment' => $result,
                ]),
            ]);

            // ✅ Mark order paid here

            return response()->json([
                'message' => 'Payment successful',
                'trx_id' => $result['trxID'],
            ]);
        }

        $payment->update([
            'status' => Payment::FAILED,
            'response' => array_merge($payment->response ?? [], [
                'execute_payment' => $result,
            ]),
        ]);

        return response()->json(['message' => 'Payment failed'], 400);
    }

    public function verify($transactionId)
    {
        $payment = Payment::where('transaction_id', $transactionId)
            ->where('gateway', 'bkash')
            ->firstOrFail();

        $token = $payment->response['token'];

        $result = $this->bkash->queryPayment($token, $payment->gateway_trxid);

        return response()->json($result);
    }
}
