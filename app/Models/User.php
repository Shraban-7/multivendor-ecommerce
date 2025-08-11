<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->referral_code = static::generateReferralCode();
        });
    }

    public static function generateReferralCode()
    {
        do {
            $code = strtoupper(substr(str_replace('.', '', uniqid()), -8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function getAvatarAttribute()
    {
        return is_null($this->attributes['image']) ? asset('assets/frontend/images/user-avatar-1.png') : storage_url($this->attributes['image']);
    }

    public function followedSellers()
    {
        return $this->belongsToMany(Seller::class, 'seller_followers', 'user_id', 'seller_id');
    }

    public function isAffiliate()
    {
        return $this->role === UserRole::AFFILIATE->value;
    }
}
