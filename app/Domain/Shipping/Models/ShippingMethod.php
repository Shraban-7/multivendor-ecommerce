<?php

namespace App\Domain\Shipping\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingMethod extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
        'charge' => 'float',
        'free_above' => 'float',
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
