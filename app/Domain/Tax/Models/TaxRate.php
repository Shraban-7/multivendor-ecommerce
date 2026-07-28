<?php

namespace App\Domain\Tax\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $guarded = ['id'];

    protected $table = 'tax_rates';

    protected $casts = [
        'rate' => 'decimal:2',
        'is_compound' => 'boolean',
        'is_active' => 'boolean',
        'valid_from' => 'date',
        'valid_until' => 'date',
    ];

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }
}
