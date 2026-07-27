<?php

namespace App\Domain\Order\Models;

use App\Domain\Auth\Models\Customer;
use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Payment\Models\Payment;
use App\Domain\Product\Models\Product;
use App\Domain\Review\Models\Review;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Enums\PaymentType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => OrderStatus::class,
        'payment_type' => PaymentType::class,
        'created_at' => 'datetime',
        'delivery_status' => 'integer',
        'refund_amount' => 'float',
    ];

    public const ORDER_TYPE_CUSTOMER = 'C';

    public const ORDER_TYPE_POS = 'P';

    public function billing_address(): HasOne
    {
        return $this->hasOne(OrderBillingAddress::class, 'order_id');
    }

    public function statusLogs()
    {
        return $this->hasMany(OrderStatusLog::class);
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

    public function employee(): BelongsTo
    {
        return $this->belongsTo(SellerEmployee::class, 'seller_employee_id');
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

    public function status_logs(): HasMany
    {
        return $this->hasMany(OrderStatusLog::class);
    }

    public function trackings(): HasMany
    {
        return $this->hasMany(OrderTracking::class);
    }

    public function returnRequest(): HasOne
    {
        return $this->hasOne(ReturnRequest::class);
    }

    public static function generateInvoiceID($sellerId, $orderType = self::ORDER_TYPE_CUSTOMER)
    {
        $date = Carbon::now()->format('ymd');
        $vendorCode = 'V'.str_pad($sellerId, 2, '0', STR_PAD_LEFT);

        $todayOrderCount = Order::where('seller_id', $sellerId)->whereDate('created_at', Carbon::today())->count();

        $sequenceNumber = str_pad($todayOrderCount + 1, 3, '0', STR_PAD_LEFT);

        if ($orderType == self::ORDER_TYPE_CUSTOMER) {
            $invoiceId = "{$orderType}-{$vendorCode}-{$date}-{$sequenceNumber}";
        } else {
            $invoiceId = "{$orderType}{$date}-{$sequenceNumber}";
        }

        return $invoiceId;
    }

    public static function getPaymentType($products)
    {
        $hasFullPayment = false;
        $hasCodWithDeliveryCharge = false;

        foreach ($products as $product) {

            if (! $product || ! $product->payment_type) {
                continue;
            }

            $type = $product->payment_type->value;

            if ($type === PaymentType::FULL_PAYMENT->value) {
                $hasFullPayment = true;
            } elseif ($type === PaymentType::COD_WITH_DELIVERY_CHARGE->value) {
                $hasCodWithDeliveryCharge = true;
            }
        }

        if ($hasFullPayment) {
            return PaymentType::FULL_PAYMENT->value;
        }

        if ($hasCodWithDeliveryCharge) {
            return PaymentType::COD_WITH_DELIVERY_CHARGE->value;
        }

        return PaymentType::COD_ONLY->value;
    }

    public static function calculatePaymentAmounts($product, $payable, $shipping_fee = 0)
    {
        $payment_type = self::getPaymentType($product);

        if ($payment_type === PaymentType::FULL_PAYMENT->value) {
            return [
                'paid' => $payable,
                'due' => 0,
            ];
        } elseif ($payment_type === PaymentType::COD_WITH_DELIVERY_CHARGE->value) {
            return [
                'paid' => $shipping_fee,
                'due' => $payable - $shipping_fee,
            ];
        } else {
            return [
                'paid' => 0,
                'due' => $payable,
            ];
        }
    }

    public function addSellerEarningToBalance($commission = 0)
    {
        if ($this->status->value != OrderStatus::DELIVERED->value || $this->seller_earning_added ?? false) {
            return;
        }

        if (is_null($this->seller_id) || $this->seller_earnings == 0) {
            return false;
        }

        $seller = Seller::find($this->seller_id);
        if (! $seller) {
            return false;
        }

        if ($this->user_id != null) {
            $seller->balance = $seller->balance + $this->seller_earnings;
        } else {
            $seller->balance = $seller->balance - $commission;
        }

        $seller->save();

        $this->seller_earning_added = true;
        $this->save();

        return true;
    }
}
