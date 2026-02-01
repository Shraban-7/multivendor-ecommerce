<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class SellerEmployee extends Authenticatable
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
        'permissions' => 'array',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active',1);
    }

    public function hasPermission($routeName): bool
    {
        return in_array($routeName, $this->permissions);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'seller_employee_id');
    }
}
