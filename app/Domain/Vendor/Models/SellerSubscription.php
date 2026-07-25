<?php

namespace App\Domain\Vendor\Models;

use App\Enums\SubscriptionStatus;
use App\Models\SubscriptionPayment;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SellerSubscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'trial_end_date' => 'date',
        'is_trial' => 'boolean',
        'cancelled_at' => 'datetime',
    ];

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(SubscriptionHistory::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SubscriptionPayment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', SubscriptionStatus::ACTIVE->value);
    }

    public function isActive(): bool
    {
        return $this->status === SubscriptionStatus::ACTIVE->value && Carbon::now()->lte($this->end_date);
    }

    public function isInTrial(): bool
    {
        return $this->is_trial
            && $this->trial_end_date
            && $this->trial_end_date >= now()->toDateString();
    }

    public function isTrialExpired(): bool
    {
        return $this->trial_end_date && $this->trial_end_date < now()->toDateString();
    }

    public function daysRemaining(): int
    {
        if ($this->isInTrial()) {
            return now()->diffInDays($this->trial_end_date, false);
        }

        return now()->diffInDays($this->end_date, false);
    }

    public function canAccess(string $featureKey): bool
    {
        if (! $this->isActive() && ! $this->isInTrial()) {
            return false;
        }

        return $this->plan->getFeature($featureKey) ?? false;
    }
}
