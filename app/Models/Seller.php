<?php

namespace App\Models;

use App\Enums\CommissionType;
use App\Mail\WelcomeMail;
use App\Traits\HasSubscription;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Support\Facades\Mail;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasSubscription;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    const PENDING = 0;
    const ACTIVE = 1;
    const BLOCKED = 2;
    const DELETED = 4;

    public function scopeActive($query)
    {
        return $query->where('status', self::ACTIVE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::PENDING);
    }

    public function employees()
    {
        return $this->hasMany(SellerEmployee::class, 'seller_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function banner_images()
    {
        return $this->hasMany(SellerBannerImage::class);
    }

    public function chats()
    {
        return $this->hasMany(SellerChat::class);
    }

    public function expenses()
    {
        return $this->hasMany(SellerExpense::class);
    }

    public function seller_expense_categories()
    {
        return $this->hasMany(SellerExpenseCategory::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getAvatarAttribute()
    {
        return is_null($this->attributes['image']) ? asset('assets/frontend/images/user-avatar-1.png') : storage_url($this->attributes['image']);
    }

    public function getBusinessAvatarAttribute()
    {
        return is_null($this->attributes['business_logo']) ? asset('assets/frontend/images/provider-logo-2.png') : storage_url($this->attributes['business_logo']);
    }

    public function followers()
    {
        return $this->hasMany(SellerFollower::class);
    }

    public function followerUsers()
    {
        return $this->belongsToMany(User::class, 'seller_followers', 'seller_id', 'user_id');
    }

    public function campaigns()
    {
        return $this->hasMany(SellerCampaign::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function calculateEarning($total)
    {
        $total_commission = 0;

        if ($this->commission_amount !== null && $this->commission_type !== null) {
            if ($this->commission_type === CommissionType::PERCENTAGE->value) {
                $total_commission = ($total) * ($this->commission_amount / 100);
            } elseif ($this->commission_type === CommissionType::FLAT->value) {
                $total_commission = $this->commission_amount;
            }
        }

        $sellerEarning = ($total) - $total_commission;

        return [
            'total_commission' => $total_commission,
            'seller_earning' => $sellerEarning,
        ];
    }

    public function sendWelcomeMail()
    {
        Mail::to($this->email)->queue(new WelcomeMail($this->name));
    }

    public function profileCompleted(): Attribute
    {
        $completed = true;

        $requiredFields = [
            'nid_no',
            'nid_front_image',
            'nid_back_image',
            'trade_licenso_no',
            'trade_licenso_image',
            'shop_image',
            'email_verified_at',
        ];

        foreach ($requiredFields as $field) {
            if (is_null($this->$field) || empty($this->$field)) {
                $completed = false;
                break;
            }
        }

        return Attribute::make(
            get: fn() => $completed
        );
    }
}
