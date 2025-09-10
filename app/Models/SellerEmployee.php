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

    public function hasPermission($routeName): bool
    {
        return in_array($routeName, $this->permissions);
    }
}

