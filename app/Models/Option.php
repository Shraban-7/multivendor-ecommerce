<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function option_values()
    {
        return $this->hasMany(OptionValue::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_options', 'option_id', 'category_id');
    }

    public function variants()
    {
        return $this->belongsToMany(ProductVariant::class);
    }
}
