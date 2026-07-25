<?php

namespace App\Http\Controllers;

use App\Domain\Order\Models\Order;
use App\Domain\Payment\Services\PaymentService;
use Illuminate\Http\Request;
use InvalidArgumentException;
use RuntimeException;

class BkashController extends Controller
{
    public function __construct(protected PaymentService $paymentService) {}

    /**
     * Entry point: User clicks "Pay with bKash"
     */
    public function pay(Request $request)
    {
        $request->validate([
            'invoice_id' => ['required', 'string'],
        ]);

        try {
            $order = Order::where('invoice_id', $request->invoice_id)->firstOrFail();
            $response = $this->paymentService->initiateBkashPayment($order);

            if (isset($response['bkashURL'])) {
                return redirect()->away($response['bkashURL']);
            }

            return response()->json(['error' => 'Payment Creation Failed', 'details' => $response], 400);
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

        if ($status !== 'success') {
            return response()->json(['error' => 'Payment Failed or Cancelled', 'status' => $status], 400);
        }

        try {
            $result = $this->paymentService->completeBkashPayment((string) $paymentID);

            return response()->json([
                'message' => 'Payment Successful',
                'data' => $result['response'],
            ]);
        } catch (InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        } catch (RuntimeException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
