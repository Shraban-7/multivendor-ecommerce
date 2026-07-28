<?php

namespace App\Domain\Order\Models;

use App\Domain\Auth\Models\User;
use App\Domain\Order\Enums\DisputeResolution;
use App\Domain\Order\Enums\DisputeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $return_request_id
 * @property int $raised_by
 * @property int|null $assigned_admin_id
 * @property string $reason
 * @property string|null $description
 * @property string|null $status
 * @property string|null $resolution
 * @property float|null $resolution_amount
 * @property string|null $admin_note
 * @property string|null $seller_response
 * @property Carbon|null $seller_responded_at
 * @property Carbon|null $resolved_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Dispute extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'status' => DisputeStatus::class,
        'resolution' => DisputeResolution::class,
        'resolved_at' => 'datetime',
        'resolution_amount' => 'float',
        'seller_responded_at' => 'datetime',
    ];

    public function returnRequest(): BelongsTo
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    public function raisedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'raised_by');
    }

    public function assignedAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_admin_id');
    }

    public function isOpen(): bool
    {
        return ($this->status instanceof DisputeStatus)
            ? $this->status->isOpen()
            : in_array($this->status, ['open', 'under_review', 'seller_response'], true);
    }

    public function isResolved(): bool
    {
        return $this->status === DisputeStatus::RESOLVED
            || $this->status === 'resolved';
    }

    public function hasSellerResponse(): bool
    {
        return ! empty($this->seller_response);
    }
}
