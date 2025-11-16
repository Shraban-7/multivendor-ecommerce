<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function subscription()
    {
        return $this->belongsTo(SellerSubscription::class, 'seller_subscription_id');
    }

    public function oldPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'old_plan_id');
    }

    public function newPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'new_plan_id');
    }

    public function performedBy()
    {
        return $this->belongsTo(Admin::class, 'performed_by');
    }
}
