<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Auth\Models\Admin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerPayout extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'charge' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    const STATUS_PENDING = 0;

    const STATUS_PROCESSING = 1;

    const STATUS_COMPLETED = 2;

    const STATUS_CANCELLED = 3;

    const STATUS_FAILED = 4;

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function payoutMethod(): BelongsTo
    {
        return $this->belongsTo(SellerPayoutMethod::class, 'payout_method_id');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'processed_by');
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', self::STATUS_PROCESSING);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CANCELLED => 'Cancelled',
            self::STATUS_FAILED => 'Failed',
            default => 'Unknown',
        };
    }

    public function statusBadge(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'badge-soft-warning',
            self::STATUS_PROCESSING => 'badge-soft-info',
            self::STATUS_COMPLETED => 'badge-soft-success',
            self::STATUS_CANCELLED => 'badge-soft-danger',
            self::STATUS_FAILED => 'badge-soft-danger',
            default => 'badge-soft-secondary',
        };
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isProcessing(): bool
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public static function statusMetas(): array
    {
        return [
            self::STATUS_PENDING => [
                'label' => 'Pending', 'pill' => 'bg-amber-500 text-white', 'icon' => 'hourglass',
                'tone' => 'warning', 'msg' => 'Waiting for approval', 'sub' => 'Your request is being reviewed',
            ],
            self::STATUS_PROCESSING => [
                'label' => 'Processing', 'pill' => 'bg-blue-500 text-white', 'icon' => 'loader',
                'tone' => 'info', 'msg' => 'Processing', 'sub' => 'Payout is being processed',
            ],
            self::STATUS_COMPLETED => [
                'label' => 'Completed', 'pill' => 'bg-emerald-500 text-white', 'icon' => 'check-circle',
                'tone' => 'success', 'msg' => 'Completed', 'sub' => 'Funds have been sent',
            ],
            self::STATUS_CANCELLED => [
                'label' => 'Cancelled', 'pill' => 'bg-rose-500 text-white', 'icon' => 'x-circle',
                'tone' => 'danger', 'msg' => 'Cancelled', 'sub' => 'Amount has been returned to your balance',
            ],
            self::STATUS_FAILED => [
                'label' => 'Failed', 'pill' => 'bg-rose-600 text-white', 'icon' => 'alert-triangle',
                'tone' => 'danger', 'msg' => 'Failed', 'sub' => 'Amount has been returned to your balance',
            ],
        ];
    }

    public static function statusMeta(int $status): array
    {
        return self::statusMetas()[$status] ?? self::statusMetas()[self::STATUS_PENDING];
    }
}
