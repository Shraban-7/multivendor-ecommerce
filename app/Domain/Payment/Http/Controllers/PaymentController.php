<?php

namespace App\Domain\Payment\Http\Controllers;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Payment\Enums\PaymentStatus;
use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly PaymentRepositoryInterface $paymentRepo,
    ) {}

    public function ipn(Request $request)
    {
        Log::info('Received IPN:', $request->all());

        $secret = config('services.payment.ipn_secret');
        if ($secret && $request->input('secret') !== $secret) {
            Log::warning('IPN rejected: invalid secret', ['ip' => $request->ip()]);

            return response()->json(['status' => 'error', 'message' => 'Invalid secret'], 403);
        }

        $invoiceId = $request->input('order_id');
        if (! $invoiceId) {
            return response()->json(['status' => 'error', 'message' => 'Missing order_id'], 400);
        }

        $order = $this->orderRepo->findByInvoiceId($invoiceId);
        if (! $order) {
            Log::error('Order not found for IPN:', ['invoice_id' => $invoiceId]);

            return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
        }

        $gatewayStatus = $request->input('status');
        $gatewayAmount = (float) ($request->input('amount', 0));
        $expectedAmount = (float) ($order->due > 0 ? $order->due : $order->payable);

        if ($gatewayStatus === PaymentStatus::COMPLETED->value || $gatewayStatus === PaymentStatus::PAID->value) {
            if ($gatewayAmount > 0 && abs($gatewayAmount - $expectedAmount) > 0.01) {
                Log::error('IPN amount mismatch', [
                    'invoice_id' => $invoiceId,
                    'expected' => $expectedAmount,
                    'received' => $gatewayAmount,
                ]);

                return response()->json(['status' => 'error', 'message' => 'Amount mismatch'], 422);
            }

            $existingPayment = $this->paymentRepo->findByTransactionId($invoiceId);
            if ($existingPayment && $existingPayment->status === Payment::SUCCESSFUL) {
                return response()->json(['status' => 'success', 'message' => 'Already processed']);
            }

            if (! $existingPayment) {
                $this->paymentRepo->create([
                    'transaction_id' => $invoiceId,
                    'gateway' => $request->input('payment_method', 'unknown'),
                    'status' => Payment::SUCCESSFUL,
                    'amount' => $expectedAmount,
                    'currency' => 'BDT',
                ]);
            } else {
                $this->paymentRepo->update($existingPayment, ['status' => Payment::SUCCESSFUL]);
            }

            $this->orderRepo->update($order, [
                'payment_id' => $request->input('payment_id'),
                'payment_status' => 'Paid',
                'payment_method_name' => $request->input('payment_method'),
                'paid' => $expectedAmount,
                'due' => 0,
                'paid_at' => now(),
            ]);

            Log::info('IPN processed successfully', ['invoice_id' => $invoiceId]);
        }

        return response()->json(['status' => 'success'], 200);
    }

    public function success(Request $request)
    {
        $order = $this->resolveOrderFromCallback($request);

        if (! $order) {
            Log::error('Order not found for success callback', $request->all());

            return view('errors.404');
        }

        if ($request->input('pay_status') === 'Successful') {
            $this->recordSuccessfulPayment($order, $request);
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        return view('payment.success', ['order' => $order->fresh()]);
    }

    public function cancelled(Request $request)
    {
        $order = $this->resolveOrderFromCallback($request);

        if (! $order) {
            Log::error('Order not found for cancelled callback', $request->all());

            return view('errors.404');
        }

        if ($order->payment_status !== 'Paid') {
            $this->orderRepo->update($order, ['payment_status' => 'Cancelled']);
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        return view('payment.cancelled', ['order' => $order->fresh()]);
    }

    public function failed(Request $request)
    {
        $order = $this->resolveOrderFromCallback($request);

        if (! $order) {
            Log::error('Order not found for failed callback', $request->all());

            return view('errors.404');
        }

        if ($order->payment_status !== 'Paid') {
            $this->orderRepo->update($order, ['payment_status' => 'Failed']);
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        return view('payment.failed', ['order' => $order->fresh()]);
    }

    /**
     * Aamarpay posts the merchant invoice back as mer_txnid; older gateways used
     * invoice_id/order_id/payment_id. Try all of them.
     */
    private function resolveOrderFromCallback(Request $request): ?Order
    {
        $invoiceId = $request->input('mer_txnid')
            ?? $request->input('invoice_id')
            ?? $request->input('order_id')
            ?? $request->input('tran_id');

        if ($invoiceId) {
            $order = $this->orderRepo->findByInvoiceId($invoiceId);
            if ($order) {
                return $order;
            }
        }

        if ($request->filled('payment_id')) {
            return Order::where('payment_id', $request->input('payment_id'))->first();
        }

        return null;
    }

    private function recordSuccessfulPayment(Order $order, Request $request): void
    {
        $gatewayAmount = (float) $request->input('amount', 0);
        $expectedAmount = (float) ($order->due > 0 ? $order->due : $order->payable);

        if ($gatewayAmount > 0 && abs($gatewayAmount - $expectedAmount) > 0.01) {
            Log::error('Success callback amount mismatch', [
                'invoice_id' => $order->invoice_id,
                'expected' => $expectedAmount,
                'received' => $gatewayAmount,
            ]);

            return;
        }

        $payment = $this->paymentRepo->findByTransactionId($order->invoice_id);

        if ($payment && $payment->status === Payment::SUCCESSFUL) {
            return;
        }

        $paymentData = [
            'status' => Payment::SUCCESSFUL,
            'gateway_trxid' => $request->input('pg_txnid'),
            'payment_method' => $request->input('card_type'),
        ];

        if ($payment) {
            $this->paymentRepo->update($payment, $paymentData);
        } else {
            $payment = $this->paymentRepo->create($paymentData + [
                'transaction_id' => $order->invoice_id,
                'user_id' => $order->user_id,
                'gateway' => 'aamarpay',
                'amount' => $expectedAmount,
                'currency' => 'BDT',
            ]);
        }

        $this->orderRepo->update($order, [
            'payment_id' => $payment->id,
            'payment_status' => 'Paid',
            'payment_method_name' => $request->input('card_type', 'aamarpay'),
            'paid' => $expectedAmount,
            'due' => 0,
            'paid_at' => now(),
        ]);

        Log::info('Payment recorded from success callback', ['invoice_id' => $order->invoice_id]);
    }
}
