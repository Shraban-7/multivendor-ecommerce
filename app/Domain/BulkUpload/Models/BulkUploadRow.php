<?php

namespace App\Domain\BulkUpload\Models;

use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BulkUploadRow extends Model
{
    protected $guarded = ['id'];

    protected $table = 'bulk_upload_rows';

    protected $casts = [
        'errors' => 'array',
        'data' => 'array',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_FAILED = 'failed';

    public function bulkUpload(): BelongsTo
    {
        return $this->belongsTo(BulkUpload::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
