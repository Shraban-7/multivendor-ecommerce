<?php

namespace App\Domain\Tax\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxClass extends Model
{
    protected $guarded = ['id'];

    protected $table = 'tax_classes';

    public function rates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }

    public function activeRates()
    {
        return $this->rates()->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->where(function ($q) {
                $q->whereNull('valid_from')
                    ->orWhere('valid_from', '<=', now());
            });
    }
}
