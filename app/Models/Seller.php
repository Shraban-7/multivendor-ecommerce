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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

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
        'password' => 'hashed',
    ];

    const PENDING = 0;
    const ACTIVE = 1;
    const BLOCKED = 2;
    const DELETED = 4;

    public static function generateSellerCode(string $sellerName): string
    {
        return DB::transaction(function () use ($sellerName) {

            $clean = preg_replace('/[^A-Za-z\s]/', '', $sellerName);
            $words = array_values(array_filter(preg_split('/\s+/', trim($clean))));

            if (count($words) >= 2) {
                // Multi-word: first letter of each word
                $baseCode = '';
                foreach ($words as $word) {
                    $baseCode .= strtoupper($word[0]);
                }
            } else {
                // Single-word: first 2 letters
                $baseCode = strtoupper(substr($words[0], 0, 2));
            }
            
            $baseCode = Str::substr($baseCode, 0, 4);
            if (strlen($baseCode) < 2) {
                $baseCode = str_pad($baseCode, 2, 'X');
            }

            $existing = DB::table('sellers')
                ->where('code', 'like', $baseCode . '%')
                ->pluck('code');

            if ($existing->isEmpty()) {
                return $baseCode;
            }

            $maxSuffix = 1;

            foreach ($existing as $code) {
                if ($code === $baseCode) {
                    $maxSuffix = max($maxSuffix, 1);
                } elseif (preg_match('/^' . preg_quote($baseCode) . '(\d+)$/', $code, $m)) {
                    $maxSuffix = max($maxSuffix, (int) $m[1]);
                }
            }

            return $baseCode . ($maxSuffix + 1);
        });
    }

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

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
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

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
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

    public function addRating($newRating)
    {
        $this->rating = (($this->rating * $this->rating_count) + $newRating) / ($this->rating_count + 1);
        $this->rating_count += 1;
        $this->save();
    }
}
