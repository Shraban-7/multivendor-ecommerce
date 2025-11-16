<?php

namespace App\Traits;

use App\Enums\SubscriptionStatus;
use App\Models\SellerSubscription;
use Carbon\Carbon;

trait HasSubscription
{
    public function subscriptions()
    {
        return $this->hasMany(SellerSubscription::class, 'seller_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(SellerSubscription::class, 'seller_id')
            ->whereIn('status', [SubscriptionStatus::ACTIVE->value, SubscriptionStatus::TRIAL->value])
            ->where('end_date', '>=', now()->toDateString())
            ->latest();
    }
    
    public function isInTrial(): bool
    {
        $subscription = $this->activeSubscription;

        return $subscription && $subscription->isInTrial();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription()->exists();
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
