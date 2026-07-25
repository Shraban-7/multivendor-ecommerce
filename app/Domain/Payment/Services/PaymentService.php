<?php

namespace App\Domain\Payment\Services;

use App\Domain\Order\Models\Order;
use App\Domain\Payment\Models\Payment;
use App\Domain\Payment\Repositories\Contracts\PaymentRepositoryInterface;
use App\Domain\Vendor\Models\Seller;
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
        private readonly PaymentRepositoryInterface $paymentRepo,
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

        $existing = $this->paymentRepo->findByTransactionId($invoiceNumber);
        if ($existing) {
            $this->paymentRepo->update($existing, [
                'user_id' => $order->user_id,
                'amount' => $amount,
                'status' => Payment::PENDING,
                'payment_method' => 'bkash',
            ]);
        } else {
            $this->paymentRepo->create([
                'transaction_id' => $invoiceNumber,
                'user_id' => $order->user_id,
                'amount' => $amount,
                'status' => Payment::PENDING,
                'payment_method' => 'bkash',
            ]);
        }

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
            $payment = $this->paymentRepo->findByTransactionId($invoiceId);

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
                $this->paymentRepo->update($payment, [
                    'status' => Payment::FAILED,
                    'response' => json_encode($response),
                ]);

                throw new InvalidArgumentException(
                    "Payment amount mismatch: expected {$expected}, got {$paidAmount}."
                );
            }

            $this->paymentRepo->update($payment, [
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
