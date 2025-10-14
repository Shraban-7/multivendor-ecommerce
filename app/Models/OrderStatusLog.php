<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'old_status' => OrderStatus::class,
        'new_status' => OrderStatus::class,
    ];
}
