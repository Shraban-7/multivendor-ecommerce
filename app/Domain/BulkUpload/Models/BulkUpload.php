<?php

namespace App\Domain\BulkUpload\Models;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BulkUpload extends Model
{
    protected $guarded = ['id'];

    protected $table = 'bulk_uploads';

    protected $casts = [
        'summary' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_FAILED = 'failed';
    public const STATUS_CANCELLED = 'cancelled';

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function rows(): HasMany
    {
        return $this->hasMany(BulkUploadRow::class);
    }

    public function successfulRows(): HasMany
    {
        return $this->rows()->where('status', BulkUploadRow::STATUS_SUCCESS);
    }

    public function failedRows(): HasMany
    {
        return $this->rows()->where('status', BulkUploadRow::STATUS_FAILED);
    }

    public function hasPendingImport(Seller $seller): bool
    {
        return self::where('seller_id', $seller->id)
            ->whereIn('status', [self::STATUS_PENDING, self::STATUS_PROCESSING])
            ->exists();
    }

    public function scopeForSeller($query, int $sellerId)
    {
        return $query->where('seller_id', $sellerId);
    }
}
