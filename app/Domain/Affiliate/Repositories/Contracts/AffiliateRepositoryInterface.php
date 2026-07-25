<?php

namespace App\Domain\Affiliate\Repositories\Contracts;

use App\Domain\Affiliate\Models\AffiliateClick;
use App\Domain\Affiliate\Models\AffiliateCommission;
use App\Domain\Affiliate\Models\AffiliatePayout;
use Illuminate\Support\Collection;

interface AffiliateRepositoryInterface
{
    public function findCommissionById(int $id): ?AffiliateCommission;

    public function createCommission(array $data): AffiliateCommission;

    public function updateCommission(AffiliateCommission $commission, array $data): bool;

    public function getCommissionsByAffiliate(int $affiliateId): Collection;

    public function getPendingCommissions(int $affiliateId): Collection;

    public function getUnpaidCommissions(): Collection;

    public function findPayoutById(int $id): ?AffiliatePayout;

    public function createPayout(array $data): AffiliatePayout;

    public function updatePayout(AffiliatePayout $payout, array $data): bool;

    public function getPayoutsByUser(int $userId): Collection;

    public function createClick(array $data): AffiliateClick;

    public function getClicksByAffiliate(int $affiliateId): Collection;

    public function getClickCountByAffiliate(int $affiliateId): int;
}
