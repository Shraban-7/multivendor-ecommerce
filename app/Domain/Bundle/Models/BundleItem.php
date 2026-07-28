<?php

namespace App\Domain\Bundle\Models;

use App\Domain\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundleItem extends Model
{
    protected $guarded = ['id'];

    protected $table = 'bundle_items';

    protected $casts = [
        'quantity' => 'integer',
        'is_optional' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
