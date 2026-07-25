<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Models\Order;
use App\Domain\Product\Models\Category;
use App\Domain\Product\Models\Product;
use App\Domain\Review\Models\Review;
use App\Domain\Shipping\Models\Country;
use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Enums\CommissionType;
use App\Mail\WelcomeMail;
use App\Traits\HasSubscription;
use Database\Factories\SellerFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

class Seller extends Authenticatable
{
    use HasApiTokens, HasFactory, HasSubscription, Notifiable, SoftDeletes;

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
                $baseCode = '';
                foreach ($words as $word) {
                    $baseCode .= strtoupper($word[0]);
                }
            } else {
                $baseCode = strtoupper(substr($words[0], 0, 2));
            }

            $baseCode = Str::substr($baseCode, 0, 4);
            if (strlen($baseCode) < 2) {
                $baseCode = str_pad($baseCode, 2, 'X');
            }

            $existing = DB::table('sellers')
                ->where('code', 'like', $baseCode.'%')
                ->pluck('code');

            if ($existing->isEmpty()) {
                return $baseCode;
            }

            $maxSuffix = 1;

            foreach ($existing as $code) {
                if ($code === $baseCode) {
                    $maxSuffix = max($maxSuffix, 1);
                } elseif (preg_match('/^'.preg_quote($baseCode).'(\d+)$/', $code, $m)) {
                    $maxSuffix = max($maxSuffix, (int) $m[1]);
                }
            }

            return $baseCode.($maxSuffix + 1);
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

    public function employees(): HasMany
    {
        return $this->hasMany(SellerEmployee::class, 'seller_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function banner_images(): HasMany
    {
        return $this->hasMany(SellerBannerImage::class);
    }

    public function chats(): HasMany
    {
        return $this->hasMany(SellerChat::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(SellerExpense::class);
    }

    public function seller_expense_categories(): HasMany
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

    public function followers(): HasMany
    {
        return $this->hasMany(SellerFollower::class);
    }

    public function followerUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'seller_followers', 'seller_id', 'user_id');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function calculateEarning($total): array
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

    public function sendWelcomeMail(): void
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
            get: fn () => $completed
        );
    }

    public function addRating($newRating): void
    {
        $this->rating = (($this->rating * $this->rating_count) + $newRating) / ($this->rating_count + 1);
        $this->rating_count += 1;
        $this->save();
    }

    protected static function newFactory()
    {
        return SellerFactory::new();
    }
}
