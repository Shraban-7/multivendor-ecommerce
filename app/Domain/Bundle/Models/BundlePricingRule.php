<?php

namespace App\Domain\Bundle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BundlePricingRule extends Model
{
    protected $guarded = ['id'];

    protected $table = 'bundle_pricing_rules';

    protected $casts = [
        'min_items' => 'integer',
        'max_items' => 'integer',
        'discount_percent' => 'decimal:2',
    ];

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }
}
