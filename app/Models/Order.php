<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use Carbon\Carbon;
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
        'billing_information' => 'array',
        'delivery_status' => 'integer',
    ];

    public const ORDER_TYPE_CUSTOMER = 'C';
    public const ORDER_TYPE_POS = 'P';

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

    public function generateInvoiceID($sellerId, $orderType = self::ORDER_TYPE_CUSTOMER)
    {
        $date = Carbon::now()->format('ymd');
        $vendorCode = 'V' . str_pad($sellerId, 2, '0', STR_PAD_LEFT);

        $latestOrder = Order::where('seller_id', $sellerId)
            ->whereDate('created_at', Carbon::today())
            ->latest()
            ->first();

        $sequenceNumber = $latestOrder ? str_pad(($latestOrder->id + 1), 3, '0', STR_PAD_LEFT) : '001';
        $invoiceId = "{$orderType}-{$vendorCode}-{$date}-{$sequenceNumber}";

        return $invoiceId;
    }
}
