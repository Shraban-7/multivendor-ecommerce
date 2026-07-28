<?php

namespace App\Domain\Order\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Payment\Models\Payment;
use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $return_request_id
 * @property int $order_id
 * @property int|null $payment_id
 * @property int $user_id
 * @property int $seller_id
 * @property float $amount
 * @property string $method
 * @property string $status
 * @property string|null $gateway
 * @property string|null $gateway_reference
 * @property string|null $failure_reason
 * @property array|null $gateway_payload
 * @property Carbon|null $processed_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class RefundTransaction extends Model
{
    protected $table = 'refund_transactions';

    protected $guarded = ['id'];

    public const METHOD_GATEWAY = 'gateway';

    public const METHOD_WALLET = 'wallet';

    public const METHOD_MANUAL = 'manual';

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SUCCESS = 'success';

    public const STATUS_FAILED = 'failed';

    protected $casts = [
        'amount' => 'float',
        'gateway_payload' => 'array',
        'processed_at' => 'datetime',
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function isSuccess(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    public function isPending(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PROCESSING], true);
    }
}
