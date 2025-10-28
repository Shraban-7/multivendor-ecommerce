<?php
namespace App\Traits;

use App\Models\SellerSubscription;
use Carbon\Carbon;

trait HasSubscription
{
    public function activeSubscription()
    {
        return $this->hasOne(SellerSubscription::class, 'seller_id')
            ->where('status', SellerSubscription::ACTIVE)
            ->whereDate('end_date', '>=', Carbon::today());
    }

    public function currentPlan()
    {
        return $this->activeSubscription?->plan;
    }

    public function hasFeature(string $feature): bool
    {
        $plan = $this->currentPlan();

        return $plan ? (bool) $plan->{$feature} : false;
    }

    public function canAddProduct(): bool
    {
        $plan = $this->currentPlan();
        
        if (!$plan) return false;

        // Unlimited products
        if ($plan->product_limit === 0) return true;

        $currentProductCount = $this->products()->count();

        return $currentProductCount < $plan->product_limit;
    }

    public function commissionRate(): float
    {
        return $this->currentPlan()?->commission_rate ?? 10.00;
    }

    public function hasPosAccess(): bool
    {
        return $this->hasFeature('pos_access');
    }

    public function hasAnalyticsAccess(): bool
    {
        return $this->hasFeature('analytics_access');
    }

    public function hasPrioritySupport(): bool
    {
        return $this->hasFeature('priority_support');
    }

    public function subscriptionExpired(): bool
    {
        $sub = $this->activeSubscription()->first();
        
        return !$sub || Carbon::now()->gt($sub->end_date);
    }
}
