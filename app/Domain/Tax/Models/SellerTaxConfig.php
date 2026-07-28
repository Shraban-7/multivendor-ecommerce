<?php

namespace App\Domain\Tax\Models;

use App\Domain\Vendor\Models\Seller;
use Illuminate\Database\Eloquent\Model;

class SellerTaxConfig extends Model
{
    protected $guarded = ['id'];

    protected $table = 'seller_tax_configs';

    protected $casts = [
        'is_tax_exempt' => 'boolean',
    ];

    public function seller()
    {
        return $this->belongsTo(Seller::class);
    }

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }
}
