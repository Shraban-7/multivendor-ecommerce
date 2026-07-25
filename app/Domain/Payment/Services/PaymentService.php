<?php

namespace App\Domain\Payment\Services;

use App\Domain\Payment\Models\Payment;
use App\Models\Order;
use App\Models\Seller;
use App\Services\AffiliateService;
use App\Services\BkashService;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;
use RuntimeException;

class PaymentService
{
    public function __construct(
        protected BkashService $bkashService,
        protected AffiliateService $affiliateService,
    ) {}

    /**
     * Start a bKash payment using the real order amount from the database.
     *
     * @return array<string, mixed>
     */
    public function initiateBkashPayment(Order $order): array
    {
        $amount = (float) ($order->due > 0 ? $order->due : $order->payable);
        if ($amount <= 0) {
            throw new InvalidArgumentException('Order has no payable amount.');
        }

        $invoiceNumber = $order->invoice_id;

        Payment::updateOrCreate(
            ['transaction_id' => $invoiceNumber],
            [
                'user_id' => $order->user_id,
                'amount' => $amount,
                'status' => Payment::PENDING,
                'payment_method' => 'bkash',
            ]
        );

        return $this->bkashService->createPayment($amount, $invoiceNumber);
    }

    /**
     * Verify and finalize a bKash callback with amount matching and idempotency.
     *
     * @return array{order: Order, payment: Payment, response: array<string, mixed>}
     */
    public function completeBkashPayment(string $paymentId): array
    {
        $response = $this->bkashService->executePayment($paymentId);

        if (! isset($response['statusCode'], $response['transactionStatus'])
            || $response['statusCode'] !== '0000'
            || $response['transactionStatus'] !== 'Completed') {
            throw new RuntimeException('Payment execution failed.');
        }

        $query = $this->bkashService->queryPayment($paymentId);
        if (isset($query['transactionStatus']) && $query['transactionStatus'] !== 'Completed') {
            throw new RuntimeException('Server-side payment verification failed.');
        }

        $invoiceId = $response['merchantInvoiceNumber'] ?? null;
        if (! $invoiceId) {
            throw new RuntimeException('Missing merchant invoice number.');
        }

        return DB::transaction(function () use ($response, $invoiceId, $query) {
            $order = Order::where('invoice_id', $invoiceId)->lockForUpdate()->firstOrFail();
            $payment = Payment::where('transaction_id', $invoiceId)->lockForUpdate()->first();

            if (! $payment) {
                throw new RuntimeException('Payment record not found.');
            }

            // Idempotency: already successful
            if ($payment->status === Payment::SUCCESSFUL) {
                return compact('order', 'payment') + ['response' => $response];
            }

            $paidAmount = (float) ($response['amount'] ?? $query['amount'] ?? 0);
            $expected = (float) $payment->amount;

            if (abs($paidAmount - $expected) > 0.01) {
                $payment->update([
                    'status' => Payment::FAILED,
                    'response' => json_encode($response),
                ]);

                throw new InvalidArgumentException(
                    "Payment amount mismatch: expected {$expected}, got {$paidAmount}."
                );
            }

            $payment->update([
                'status' => Payment::SUCCESSFUL,
                'gateway_trxid' => $response['trxID'] ?? null,
                'response' => json_encode($response),
            ]);

            $due = (float) $order->due;
            $paid = (float) $order->paid;

            if ($due > 0) {
                $due = max(0, $due - (float) $payment->amount);
                $paid = (float) $payment->amount;

                $seller = Seller::find($order->seller_id);
                if ($seller) {
                    $seller->update([
                        'balance' => $seller->balance + $order->seller_earnings,
                    ]);
                }

                $this->affiliateService->approveCommission($order);
            }

            $order->update([
                'payment_id' => $payment->id,
                'due' => $due,
                'paid' => $paid,
                'payment_status' => 'Paid',
            ]);

            return [
                'order' => $order->fresh(),
                'payment' => $payment->fresh(),
                'response' => $response,
            ];
        });
    }
}
