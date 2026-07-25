<?php

namespace App\Domain\Shipping\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $guarded = [];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
