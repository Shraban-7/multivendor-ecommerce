<?php

namespace App\Domain\Affiliate\Services;

use App\Domain\Affiliate\Models\AffiliateCommission;
use App\Domain\Affiliate\Models\AffiliatePayout;
use App\Models\Order;
use App\Services\AffiliateService as LegacyAffiliateService;

class AffiliateService
{
    public function __construct(protected LegacyAffiliateService $legacy) {}

    public function processCommissions($orderItems, $user, string $invoiceId): void
    {
        $this->legacy->processCommissions($orderItems, $user, $invoiceId);
    }

    public function approveCommission(Order $order): void
    {
        $this->legacy->approveCommission($order);
    }

    public function requestPayout(int $userId, float $amount): AffiliatePayout
    {
        return AffiliatePayout::create([
            'user_id' => $userId,
            'amount' => $amount,
            'status' => 'pending',
        ]);
    }

    public function pendingCommissions(int $affiliateId)
    {
        return AffiliateCommission::where('affiliate_id', $affiliateId)
            ->where('status', '!=', AffiliateCommission::APPROVED ?? 'approved')
            ->get();
    }
}
