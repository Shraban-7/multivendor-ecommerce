<?php

namespace App\Domain\Order\Services;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Enums\ReturnEventType;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\RefundTransaction;
use App\Domain\Order\Models\ReturnEvent;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Payment\Models\Payment;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\VendorTransaction;
use App\Services\Refund\AamarpayRefundGateway;
use App\Services\Refund\BkashRefundGateway;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class RefundService
{
    public function __construct(
        protected OrderRepositoryInterface $orderRepo,
    ) {}

    public function initiate(ReturnRequest $return, string $actorType = 'system', ?int $actorId = null): RefundTransaction
    {
        if (! $return->isApproved()) {
            $status = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;
            throw new RuntimeException("Return must be approved before refund (current: {$status}).");
        }

        $order = $return->order;
        $amount = $return->totalRefundAmount();
        if ($amount <= 0) {
            $amount = (float) ($order->refund_amount ?: $order->payable);
        }

        $payment = $order->payment_id ? Payment::find($order->payment_id) : null;

        $refund = RefundTransaction::create([
            'return_request_id' => $return->id,
            'order_id' => $order->id,
            'payment_id' => $payment?->id,
            'user_id' => $return->user_id,
            'seller_id' => $order->seller_id,
            'amount' => $amount,
            'method' => $this->resolveRefundMethod($payment),
            'status' => RefundTransaction::STATUS_PROCESSING,
            'gateway' => $payment?->gateway,
        ]);

        if ($refund->method === RefundTransaction::METHOD_GATEWAY) {
            try {
                $this->callGateway($refund);
                $refund->update(['status' => RefundTransaction::STATUS_SUCCESS]);
                $this->markRefunded($return, $refund, $actorType, $actorId);

                return $refund->fresh();
            } catch (\Throwable $e) {
                Log::warning('Refund gateway failed; falling back', [
                    'return_id' => $return->id,
                    'error' => $e->getMessage(),
                ]);
                $refund->update([
                    'status' => RefundTransaction::STATUS_FAILED,
                    'failure_reason' => $e->getMessage(),
                ]);

                if (! config('marketplace.refund.auto_credit_wallet_when_gateway_fails', true)) {
                    throw $e;
                }

                $refund = RefundTransaction::create([
                    'return_request_id' => $return->id,
                    'order_id' => $order->id,
                    'payment_id' => $payment?->id,
                    'user_id' => $return->user_id,
                    'seller_id' => $order->seller_id,
                    'amount' => $amount,
                    'method' => RefundTransaction::METHOD_WALLET,
                    'status' => RefundTransaction::STATUS_PENDING,
                    'gateway' => $payment?->gateway,
                ]);
            }
        }

        return $this->creditWallet($refund, $return, $actorType, $actorId);
    }

    public function markRefunded(ReturnRequest $return, RefundTransaction $refund, string $actorType = 'system', ?int $actorId = null): void
    {
        DB::transaction(function () use ($return, $refund, $actorType, $actorId) {
            $from = $return->status instanceof ReturnStatus
                ? $return->status->value
                : (string) $return->status;

            $return->update([
                'status' => ReturnStatus::REFUNDED->value,
                'refunded_at' => now(),
                'refunded_amount' => $refund->amount,
                'refund_method' => $refund->method,
                'refund_reference' => $refund->gateway_reference,
            ]);

            $order = $return->order;
            $old = $order->status->value;

            $this->orderRepo->update($order, [
                'status' => OrderStatus::REFUNDED->value,
                'refund_amount' => $refund->amount,
            ]);
            $this->orderRepo->createStatusLog($order, [
                'old_status' => $old,
                'new_status' => OrderStatus::REFUNDED->value,
                'changed_by' => $actorType,
                'remarks' => "Refund of {$refund->amount} processed for return {$return->rma_number}",
            ]);

            ReturnEvent::log(
                $return,
                ReturnEventType::REFUND_COMPLETED->value,
                $actorType,
                $actorId,
                $from,
                ReturnStatus::REFUNDED->value,
                "Refunded {$refund->amount} via {$refund->method}",
                ['refund_id' => $refund->id],
            );

            $this->revertSellerEarning($order, $actorId, 'refunded');

            notify_user(
                $return->user_id,
                'Refund Completed',
                "{$refund->amount} has been refunded for return {$return->rma_number}.",
                'return',
                $return->id,
            );
        });
    }

    protected function creditWallet(RefundTransaction $refund, ReturnRequest $return, string $actorType, ?int $actorId): RefundTransaction
    {
        try {
            DB::transaction(function () use ($refund, $return, $actorType, $actorId) {
                $user = User::find($return->user_id);
                if (! $user) {
                    throw new RuntimeException('Customer not found for wallet credit.');
                }
                $balanceBefore = (float) ($user->balance ?? 0);
                $user->increment('balance', $refund->amount);

                $refund->update([
                    'status' => RefundTransaction::STATUS_SUCCESS,
                    'gateway_reference' => 'WALLET-'.$return->rma_number,
                    'processed_at' => now(),
                    'gateway_payload' => [
                        'balance_before' => $balanceBefore,
                        'balance_after' => $balanceBefore + $refund->amount,
                    ],
                ]);

                $from = $return->status instanceof ReturnStatus
                    ? $return->status->value
                    : (string) $return->status;

                ReturnEvent::log(
                    $return,
                    ReturnEventType::WALLET_CREDITED->value,
                    $actorType,
                    $actorId,
                    $from,
                    ReturnStatus::REFUND_INITIATED->value,
                    "Credited {$refund->amount} to wallet",
                    ['balance_after' => $balanceBefore + $refund->amount],
                );

                $this->markRefunded($return, $refund, $actorType, $actorId);
            });
        } catch (\Throwable $e) {
            $refund->update([
                'status' => RefundTransaction::STATUS_FAILED,
                'failure_reason' => $e->getMessage(),
            ]);
            throw $e;
        }

        return $refund->fresh();
    }

    protected function revertSellerEarning(Order $order, ?int $actorId, string $context = ''): void
    {
        if (! $order->seller_earning_added) {
            return;
        }

        $seller = Seller::find($order->seller_id);
        if (! $seller) {
            return;
        }

        $balanceBefore = (float) $seller->balance;
        $earning = (float) $order->seller_earnings;
        $seller->balance = max(0, $balanceBefore - $earning);
        $seller->save();

        VendorTransaction::record(
            $seller,
            VendorTransaction::TYPE_REFUND,
            -$earning,
            $balanceBefore,
            $order,
            'Return '.($context ?: $order->status->title()).' for order #'.$order->invoice_id,
        );
    }

    protected function resolveRefundMethod(?Payment $payment): string
    {
        if (! $payment) {
            return config('marketplace.refund.wallet_fallback', true)
                ? RefundTransaction::METHOD_WALLET
                : RefundTransaction::METHOD_MANUAL;
        }

        if (in_array(strtolower((string) $payment->gateway), ['bkash', 'aamarpay', 'sslcommerz', 'stripe'], true)
            && (float) $payment->amount > 0) {
            return RefundTransaction::METHOD_GATEWAY;
        }

        return RefundTransaction::METHOD_WALLET;
    }

    protected function callGateway(RefundTransaction $refund): void
    {
        $gateway = strtolower((string) $refund->gateway);

        $gatewayClass = match ($gateway) {
            'bkash' => BkashRefundGateway::class,
            'aamarpay' => AamarpayRefundGateway::class,
            default => throw new RuntimeException("Gateway '{$gateway}' does not support automated refunds."),
        };

        $response = app($gatewayClass)->refund($refund);

        $refund->update([
            'gateway_reference' => $response['reference'] ?? null,
            'gateway_payload' => $response,
            'processed_at' => now(),
        ]);
    }
}
