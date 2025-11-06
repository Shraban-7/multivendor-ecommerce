<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentListenerDevice extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'last_sync_at' => 'datetime'
    ];

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 2;

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING,
            self::STATUS_ACTIVE,
            self::STATUS_INACTIVE,
        ];
    }
}
