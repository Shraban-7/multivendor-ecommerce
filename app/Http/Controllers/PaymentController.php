<?php

namespace App\Http\Controllers;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Enums\PaymentStatus;
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
        $invoiceId = $request->input('invoice_id') ?? $request->input('order_id');
        $order = null;

        if ($request->has('payment_id')) {
            $order = Order::where('payment_id', $request->payment_id)->first();
        } elseif ($invoiceId) {
            $order = $this->orderRepo->findByInvoiceId($invoiceId);
        }

        if (! $order) {
            Log::error('Order not found for success callback', $request->all());
            return view('errors.404');
        }

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        return view('payment.success', compact('order'));
    }

    public function cancelled(Request $request)
    {
        $invoiceId = $request->input('invoice_id') ?? $request->input('order_id');
        $order = null;

        if ($request->has('payment_id')) {
            $order = Order::where('payment_id', $request->payment_id)->first();
        } elseif ($invoiceId) {
            $order = $this->orderRepo->findByInvoiceId($invoiceId);
        }

        if (! $order) {
            Log::error('Order not found for cancelled callback', $request->all());
            return view('errors.404');
        }

        $this->orderRepo->update($order, ['payment_status' => 'Cancelled']);

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        return view('payment.cancelled');
    }

    public function failed(Request $request)
    {
        $invoiceId = $request->input('invoice_id') ?? $request->input('order_id');
        $order = null;

        if ($request->has('payment_id')) {
            $order = Order::where('payment_id', $request->payment_id)->first();
        } elseif ($invoiceId) {
            $order = $this->orderRepo->findByInvoiceId($invoiceId);
        }

        if (! $order) {
            Log::error('Order not found for failed callback', $request->all());
            return view('errors.404');
        }

        $this->orderRepo->update($order, ['payment_status' => 'Failed']);

        if ($order->user_id) {
            Auth::loginUsingId($order->user_id);
        }

        return view('payment.failed');
    }
}
