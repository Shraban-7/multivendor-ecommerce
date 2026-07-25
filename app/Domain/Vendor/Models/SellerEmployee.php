<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Order\Models\Order;
use Database\Factories\SellerEmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        return $query->where('is_active', 1);
    }

    public function hasPermission(string $routeName): bool
    {
        return in_array($routeName, $this->permissions ?? []);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_employee_id');
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    protected static function newFactory()
    {
        return SellerEmployeeFactory::new();
    }
}
