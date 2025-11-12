<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentListenerPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'received_at' => 'datetime'
    ];

    public static function allowed_senders(): array
    {
        return ['NAGAD', 'bKash', 'ROCKET', 'Upay'];
    }
}
