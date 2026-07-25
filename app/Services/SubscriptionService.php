<?php

namespace App\Services;

use App\Domain\Vendor\Models\SellerSubscription;
use App\Domain\Vendor\Models\SubscriptionHistory;
use App\Enums\SubscriptionStatus;
use App\Models\Seller;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    /**
     * Create trial subscription for new seller
     */
    public function createTrialSubscription(Seller $seller, int $trialDays = 14): SellerSubscription
    {
        $trialPlan = SubscriptionPlan::latest('id')->first();

        $startDate = now();
        $trialEndDate = now()->addDays($trialDays);

        return DB::transaction(function () use ($seller, $trialPlan, $startDate, $trialEndDate, $trialDays) {
            $subscription = SellerSubscription::create([
                'seller_id' => $seller->id,
                'subscription_plan_id' => $trialPlan->id,
                'start_date' => $startDate,
                'end_date' => $trialEndDate,
                'trial_end_date' => $trialEndDate,
                'is_trial' => true,
                'status' => SubscriptionStatus::TRIAL->value,
            ]);

            SubscriptionHistory::create([
                'seller_subscription_id' => $subscription->id,
                'new_plan_id' => $trialPlan->id,
                'action' => 'created',
                'notes' => "Trial subscription created for {$trialDays} days",
            ]);

            return $subscription;
        });
    }

    public function changePlan(
        SellerSubscription $subscription,
        SubscriptionPlan $newPlan,
        ?int $performedBy = null,
        ?string $notes = null
    ): SellerSubscription {
        return DB::transaction(function () use ($subscription, $newPlan, $performedBy, $notes) {
            $oldPlan = $subscription->plan;

            $action = $this->determineAction($oldPlan, $newPlan);

            $startDate = now();
            $endDate = $this->calculateEndDate($startDate, $newPlan->duration_type);

            $subscription->update([
                'subscription_plan_id' => $newPlan->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'is_trial' => false,
                'trial_end_date' => null,
                'status' => SubscriptionStatus::ACTIVE->value,
                'upgraded_by' => $performedBy,
                'admin_notes' => $notes,
            ]);

            SubscriptionHistory::create([
                'seller_subscription_id' => $subscription->id,
                'old_plan_id' => $oldPlan->id,
                'new_plan_id' => $newPlan->id,
                'action' => $action,
                'performed_by' => $performedBy,
                'notes' => $notes ?? "{$action} from {$oldPlan->name} to {$newPlan->name}",
            ]);

            return $subscription->fresh();
        });
    }

    public function renewSubscription(SellerSubscription $subscription): SellerSubscription
    {
        return DB::transaction(function () use ($subscription) {
            $plan = $subscription->plan;
            $newEndDate = $this->calculateEndDate($subscription->end_date, $plan->duration_type);

            $subscription->update([
                'end_date' => $newEndDate,
                'status' => SubscriptionStatus::ACTIVE->value,
            ]);

            SubscriptionHistory::create([
                'seller_subscription_id' => $subscription->id,
                'new_plan_id' => $plan->id,
                'action' => 'renewed',
                'notes' => "Subscription renewed until {$newEndDate->format('Y-m-d')}",
            ]);

            return $subscription->fresh();
        });
    }

    public function cancelSubscription(SellerSubscription $subscription, ?string $reason = null, ?int $performedBy = null): SellerSubscription
    {
        return DB::transaction(function () use ($subscription, $reason, $performedBy) {
            $subscription->update([
                'status' => SubscriptionStatus::CANCELLED->value,
                'cancellation_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            SubscriptionHistory::create([
                'seller_subscription_id' => $subscription->id,
                'old_plan_id' => $subscription->subscription_plan_id,
                'new_plan_id' => $subscription->subscription_plan_id,
                'action' => 'cancelled',
                'performed_by' => $performedBy,
                'notes' => $reason ?? 'Subscription cancelled',
            ]);

            return $subscription->fresh();
        });
    }

    public function expireSubscriptions(): int
    {
        $expiredCount = 0;

        $expiredSubscriptions = SellerSubscription::active()
            ->where('end_date', '<', now()->toDateString())
            ->get();

        foreach ($expiredSubscriptions as $subscription) {
            DB::transaction(function () use ($subscription) {
                $subscription->update(['status' => SubscriptionStatus::EXPIRED->value]);

                SubscriptionHistory::create([
                    'seller_subscription_id' => $subscription->id,
                    'old_plan_id' => $subscription->subscription_plan_id,
                    'new_plan_id' => $subscription->subscription_plan_id,
                    'action' => 'expired',
                    'notes' => 'Subscription expired automatically',
                ]);
            });

            $expiredCount++;
        }

        return $expiredCount;
    }

    protected function calculateEndDate(Carbon $startDate, string $durationType): Carbon
    {
        return match ($durationType) {
            'monthly' => $startDate->copy()->addMonth(),
            'yearly' => $startDate->copy()->addYear(),
            'lifetime' => $startDate->copy()->addYears(100),
            default => $startDate->copy()->addMonth(),
        };
    }

    protected function determineAction(SubscriptionPlan $oldPlan, SubscriptionPlan $newPlan): string
    {
        if ($oldPlan->price < $newPlan->price) {
            return 'upgraded';
        } elseif ($oldPlan->price > $newPlan->price) {
            return 'downgraded';
        }

        return 'upgraded';
    }
}
