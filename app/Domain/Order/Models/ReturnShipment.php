<?php

namespace App\Domain\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $return_request_id
 * @property string $direction
 * @property string $status
 * @property string|null $carrier
 * @property string|null $tracking_number
 * @property Carbon|null $shipped_at
 * @property Carbon|null $delivered_at
 * @property string|null $notes
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ReturnShipment extends Model
{
    protected $table = 'return_shipments';

    protected $guarded = ['id'];

    public const DIRECTION_TO_SELLER = 'to_seller';

    public const DIRECTION_TO_CUSTOMER = 'to_customer';

    public const STATUS_PENDING = 'pending';

    public const STATUS_IN_TRANSIT = 'in_transit';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_FAILED = 'failed';

    protected $casts = [
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function markInTransit(): void
    {
        $this->update([
            'status' => self::STATUS_IN_TRANSIT,
            'shipped_at' => now(),
        ]);
    }

    public function markDelivered(): void
    {
        $this->update([
            'status' => self::STATUS_DELIVERED,
            'delivered_at' => now(),
        ]);
    }
}
