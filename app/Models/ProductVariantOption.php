<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function option_value()
    {
        return $this->belongsTo(OptionValue::class);
    }
}
