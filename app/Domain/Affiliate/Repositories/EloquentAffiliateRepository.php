<?php

namespace App\Domain\Affiliate\Repositories;

use App\Domain\Affiliate\Models\AffiliateClick;
use App\Domain\Affiliate\Models\AffiliateCommission;
use App\Domain\Affiliate\Models\AffiliatePayout;
use App\Domain\Affiliate\Repositories\Contracts\AffiliateRepositoryInterface;
use Illuminate\Support\Collection;

class EloquentAffiliateRepository implements AffiliateRepositoryInterface
{
    public function findCommissionById(int $id): ?AffiliateCommission
    {
        return AffiliateCommission::find($id);
    }

    public function createCommission(array $data): AffiliateCommission
    {
        return AffiliateCommission::create($data);
    }

    public function updateCommission(AffiliateCommission $commission, array $data): bool
    {
        return $commission->update($data);
    }

    public function getCommissionsByAffiliate(int $affiliateId): Collection
    {
        return AffiliateCommission::where('affiliate_id', $affiliateId)->get();
    }

    public function getPendingCommissions(int $affiliateId): Collection
    {
        return AffiliateCommission::where('affiliate_id', $affiliateId)
            ->where('status', AffiliateCommission::PENDING)
            ->get();
    }

    public function getUnpaidCommissions(): Collection
    {
        return AffiliateCommission::whereIn('status', [
            AffiliateCommission::PENDING,
            AffiliateCommission::APPROVED,
        ])->get();
    }

    public function findPayoutById(int $id): ?AffiliatePayout
    {
        return AffiliatePayout::find($id);
    }

    public function createPayout(array $data): AffiliatePayout
    {
        return AffiliatePayout::create($data);
    }

    public function updatePayout(AffiliatePayout $payout, array $data): bool
    {
        return $payout->update($data);
    }

    public function getPayoutsByUser(int $userId): Collection
    {
        return AffiliatePayout::where('user_id', $userId)->get();
    }

    public function createClick(array $data): AffiliateClick
    {
        return AffiliateClick::create($data);
    }

    public function getClicksByAffiliate(int $affiliateId): Collection
    {
        return AffiliateClick::where('affiliate_id', $affiliateId)->get();
    }

    public function getClickCountByAffiliate(int $affiliateId): int
    {
        return AffiliateClick::where('affiliate_id', $affiliateId)->count();
    }
}
