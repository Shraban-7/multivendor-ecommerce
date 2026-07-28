<?php

namespace App\Domain\Order\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\DisputeResolution;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Enums\ReturnType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $rma_number
 * @property int $order_id
 * @property int $user_id
 * @property ReturnType|null $type
 * @property string|null $reason
 * @property string|null $exchange_note
 * @property ReturnStatus|null $status
 * @property string|null $admin_note
 * @property string|null $rejection_reason
 * @property string|null $cancellation_reason
 * @property Carbon|null $approved_at
 * @property Carbon|null $rejected_at
 * @property Carbon|null $refunded_at
 * @property float|null $refunded_amount
 * @property string|null $refund_method
 * @property string|null $refund_reference
 * @property Carbon|null $return_window_end
 * @property bool $is_disputed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ReturnRequest extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => ReturnStatus::class,
        'type' => ReturnType::class,
        'resolution' => DisputeResolution::class,
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'refunded_at' => 'datetime',
        'return_window_end' => 'datetime',
        'is_disputed' => 'boolean',
        'refunded_amount' => 'float',
        'resolution_amount' => 'float',
    ];

    protected static function booted(): void
    {
        static::creating(function (ReturnRequest $return) {
            if (empty($return->rma_number)) {
                $return->rma_number = static::generateRmaNumber();
            }
            if (empty($return->status)) {
                $return->status = ReturnStatus::PENDING->value;
            }
        });
    }

    public static function generateRmaNumber(): string
    {
        $prefix = 'RMA';
        $date = now()->format('ymd');
        $last = static::whereDate('created_at', today())->count();
        $sequence = str_pad($last + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}-{$date}-{$sequence}";
    }

    public function totalRefundAmount(): float
    {
        return (float) $this->items()->sum('refund_amount');
    }

    public function needsExchangeNote(): bool
    {
        return $this->type === ReturnType::EXCHANGE;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller()
    {
        return $this->order->seller();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnRequestItem::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(ReturnEvent::class)->latest('created_at');
    }

    public function shipments(): HasMany
    {
        return $this->hasMany(ReturnShipment::class);
    }

    public function latestShipment(): HasOne
    {
        return $this->hasOne(ReturnShipment::class)->latestOfMany('id');
    }

    public function refundTransactions(): HasMany
    {
        return $this->hasMany(RefundTransaction::class);
    }

    public function latestRefund(): HasOne
    {
        return $this->hasOne(RefundTransaction::class)->latestOfMany('id');
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }

    public function isFull(): bool
    {
        return $this->type === ReturnType::FULL;
    }

    public function isPartial(): bool
    {
        return $this->type === ReturnType::PARTIAL;
    }

    public function isExchange(): bool
    {
        return $this->type === ReturnType::EXCHANGE;
    }

    public function typeLabel(): string
    {
        return $this->type?->label() ?? 'Full Refund';
    }

    public function isPending(): bool
    {
        return $this->status === ReturnStatus::PENDING
            || $this->status === 'pending';
    }

    public function isAwaitingShipment(): bool
    {
        return $this->status === ReturnStatus::AWAITING_SHIPMENT;
    }

    public function isApproved(): bool
    {
        return in_array($this->status, [
            ReturnStatus::APPROVED,
            ReturnStatus::ITEM_RECEIVED,
        ], true) || $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === ReturnStatus::REJECTED || $this->status === 'rejected';
    }

    public function isRefunded(): bool
    {
        return $this->status === ReturnStatus::REFUNDED || $this->status === 'refunded';
    }

    public function isTerminal(): bool
    {
        return ($this->status instanceof ReturnStatus) && $this->status->isTerminal();
    }

    public function statusColor(): string
    {
        if ($this->status instanceof ReturnStatus) {
            return $this->status->color();
        }

        return match ($this->status) {
            'pending' => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
            'refunded' => 'info',
            default => 'secondary',
        };
    }

    public function label(): string
    {
        if ($this->status instanceof ReturnStatus) {
            return $this->status->label();
        }

        return ucfirst(str_replace('_', ' ', (string) $this->status));
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', ReturnStatus::PENDING->value);
    }

    public function scopeForSeller(Builder $query, int $sellerId): Builder
    {
        return $query->whereHas('order', fn ($q) => $q->where('seller_id', $sellerId));
    }

    public function scopeDisputed(Builder $query): Builder
    {
        return $query->where('is_disputed', true);
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereNotIn('status', [
            ReturnStatus::REFUNDED->value,
            ReturnStatus::CANCELLED->value,
            ReturnStatus::REJECTED->value,
            ReturnStatus::COMPLETED->value,
        ]);
    }
}
