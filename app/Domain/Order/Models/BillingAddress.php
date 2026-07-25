<?php

namespace App\Domain\Order\Models;

use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Models\Division;
use App\Enums\AddressType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BillingAddress extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => AddressType::class,
        'is_default' => 'integer',
    ];

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
