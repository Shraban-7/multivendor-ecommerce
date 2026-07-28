<?php

namespace App\Domain\Bundle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundleImage extends Model
{
    protected $guarded = ['id'];

    protected $table = 'bundle_images';

    protected $casts = [
        'sort_order' => 'integer',
    ];

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function imageUrl(): string
    {
        return storage_url($this->image);
    }
}
