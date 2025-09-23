<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_type' => PaymentType::class,
        'created_at' => 'datetime',
        'billing_information' => 'json',
        'delivery_status' => 'integer',
    ];

    public function billing_address()
    {
        return $this->belongsTo(BillingAddress::class);
    }

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function products()
    {
        return $this->hasManyThrough(Product::class, OrderItem::class);
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function review()
    {
        return $this->hasOneThrough(
            Review::class,
            OrderItem::class,
            'order_id',
            'product_id',
            'id',
            'product_id'
        );
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(OrderTracking::class);
    }

    public static function generateInvoiceID()
    {
        return uniqid('SM');
    }
}
