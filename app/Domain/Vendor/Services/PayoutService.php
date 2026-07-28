<?php

namespace App\Domain\Vendor\Services;

use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerPayout;
use App\Domain\Vendor\Models\VendorTransaction;
use Illuminate\Support\Facades\DB;

class PayoutService
{
    public function requestPayout(Seller $seller, array $data): SellerPayout
    {
        return DB::transaction(function () use ($seller, $data) {
            $amount = (float) $data['amount'];

            if ($seller->balance < $amount) {
                throw new \InvalidArgumentException('Insufficient balance.');
            }

            $charge = $this->calculateCharge($amount);
            $netAmount = $amount - $charge;

            $payout = SellerPayout::create([
                'seller_id' => $seller->id,
                'payout_method_id' => $data['payout_method_id'] ?? null,
                'amount' => $amount,
                'charge' => $charge,
                'net_amount' => $netAmount,
                'currency' => 'BDT',
                'status' => SellerPayout::STATUS_PENDING,
                'seller_note' => $data['seller_note'] ?? null,
                'requested_at' => now(),
            ]);

            $balanceBefore = (float) $seller->balance;
            $seller->decrement('balance', $amount);

            VendorTransaction::record(
                $seller,
                VendorTransaction::TYPE_PAYOUT,
                -$amount,
                $balanceBefore,
                $payout,
                "Payout request #{$payout->id} — amount {$amount}, charge {$charge}, net {$netAmount}",
            );

            return $payout;
        });
    }

    public function approve(SellerPayout $payout, int $adminId): void
    {
        DB::transaction(function () use ($payout, $adminId) {
            $payout->update([
                'status' => SellerPayout::STATUS_PROCESSING,
                'processed_by' => $adminId,
                'processed_at' => now(),
            ]);
        });
    }

    public function complete(SellerPayout $payout, int $adminId, ?string $transactionId = null): void
    {
        DB::transaction(function () use ($payout, $adminId, $transactionId) {
            $payout->update([
                'status' => SellerPayout::STATUS_COMPLETED,
                'processed_by' => $adminId,
                'transaction_id' => $transactionId,
                'completed_at' => now(),
            ]);
        });
    }

    public function cancel(SellerPayout $payout, int $adminId, ?string $note = null): void
    {
        DB::transaction(function () use ($payout, $adminId, $note) {
            $payout->update([
                'status' => SellerPayout::STATUS_CANCELLED,
                'processed_by' => $adminId,
                'admin_note' => $note,
            ]);

            $seller = $payout->seller;
            $balanceBefore = (float) $seller->balance;
            $seller->increment('balance', $payout->amount);

            VendorTransaction::record(
                $seller,
                VendorTransaction::TYPE_PAYOUT_CANCELLED,
                (float) $payout->amount,
                $balanceBefore,
                $payout,
                "Payout #{$payout->id} cancelled — balance restored {$payout->amount}",
            );
        });
    }

    public function markFailed(SellerPayout $payout, int $adminId, ?string $note = null): void
    {
        DB::transaction(function () use ($payout, $adminId, $note) {
            $payout->update([
                'status' => SellerPayout::STATUS_FAILED,
                'processed_by' => $adminId,
                'admin_note' => $note,
            ]);

            $seller = $payout->seller;
            $balanceBefore = (float) $seller->balance;
            $seller->increment('balance', $payout->amount);

            VendorTransaction::record(
                $seller,
                VendorTransaction::TYPE_PAYOUT_CANCELLED,
                (float) $payout->amount,
                $balanceBefore,
                $payout,
                "Payout #{$payout->id} failed — balance restored {$payout->amount}",
            );
        });
    }

    public function calculateCharge(float $amount): float
    {
        $chargePercent = 0;
        $chargeFixed = 0;

        if ($amount >= 50000) {
            $chargePercent = 0.5;
        } elseif ($amount >= 10000) {
            $chargePercent = 1;
        } else {
            $chargeFixed = 10;
        }

        $percentCharge = ($amount * $chargePercent) / 100;

        return round(max($percentCharge, $chargeFixed), 2);
    }

    public function getAvailableBalance(Seller $seller): float
    {
        return (float) $seller->balance;
    }

    public function getPendingBalance(Seller $seller): float
    {
        return (float) SellerPayout::where('seller_id', $seller->id)
            ->whereIn('status', [SellerPayout::STATUS_PENDING, SellerPayout::STATUS_PROCESSING])
            ->sum('amount');
    }

    public function getTotalWithdrawn(Seller $seller): float
    {
        return (float) SellerPayout::where('seller_id', $seller->id)
            ->where('status', SellerPayout::STATUS_COMPLETED)
            ->sum('amount');
    }

    public function getPendingEarnings(Seller $seller): float
    {
        return (float) DB::table('orders')
            ->where('seller_id', $seller->id)
            ->where('status', 3)
            ->where('seller_earning_added', false)
            ->sum('seller_earnings');
    }
}
