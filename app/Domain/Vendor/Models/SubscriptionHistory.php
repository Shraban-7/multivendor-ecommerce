<?php

namespace App\Domain\Vendor\Models;

use App\Models\Admin;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(SellerSubscription::class, 'seller_subscription_id');
    }

    public function oldPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'old_plan_id');
    }

    public function newPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'new_plan_id');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'performed_by');
    }
}
