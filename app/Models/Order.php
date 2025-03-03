<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    
    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::PENDING->value);
    }

    public function scopeShipped($query)
    {
        return $query->where('status', OrderStatus::SHIPPED->value);
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', OrderStatus::DELIVERED->value);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', OrderStatus::CANCELLED->value);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

}
