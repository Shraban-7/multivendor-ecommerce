<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerSubscription extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $dates = ['start_date', 'end_date'];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public const ACTIVE = 1;
    public const EXPIRED = 0;
    public const CANCELLED = 2;

    public static function statuses(): array
    {
        return [
            self::ACTIVE,
            self::EXPIRED,
            self::CANCELLED,
        ];
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', $this::ACTIVE);
    }

    public function isActive(): bool
    {
        return $this->status === $this::ACTIVE && Carbon::now()->lte($this->end_date);
    }
}
