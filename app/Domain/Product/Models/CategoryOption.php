<?php

namespace App\Domain\Product\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryOption extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
