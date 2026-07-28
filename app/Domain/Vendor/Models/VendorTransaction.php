<?php

namespace App\Domain\Vendor\Models;

use App\Domain\Order\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class VendorTransaction extends Model
{
    protected $table = 'vendor_transactions';

    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    const TYPE_ORDER_EARNED = 'order_earned';
    const TYPE_COMMISSION_DEDUCTED = 'commission_deducted';
    const TYPE_PAYOUT = 'payout';
    const TYPE_PAYOUT_CANCELLED = 'payout_cancelled';
    const TYPE_REFUND = 'refund';
    const TYPE_ADJUSTMENT = 'adjustment';

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public static function record(
        Seller $seller,
        string $type,
        float $amount,
        float $balanceBefore,
        ?Model $reference = null,
        ?string $description = null,
    ): self {
        $balanceAfter = $balanceBefore + $amount;

        return static::create([
            'seller_id' => $seller->id,
            'type' => $type,
            'amount' => $amount,
            'balance_before' => $balanceBefore,
            'balance_after' => $balanceAfter,
            'reference_type' => $reference ? get_class($reference) : null,
            'reference_id' => $reference?->getKey(),
            'description' => $description,
        ]);
    }
}
