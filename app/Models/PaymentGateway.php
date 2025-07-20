<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'credentials' => 'array',
        'is_enabled' => 'boolean',
        'is_default' => 'boolean',
    ];
}
