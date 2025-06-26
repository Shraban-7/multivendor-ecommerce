<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Seller extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guard = 'seller';

    protected $guarded = ['id'];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active',1);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function banner_images()
    {
        return $this->hasMany(SellerBannerImage::class);
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
}
