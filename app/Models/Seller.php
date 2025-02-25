<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    
    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }
}
