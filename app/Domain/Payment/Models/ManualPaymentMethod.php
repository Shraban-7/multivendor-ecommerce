<?php

namespace App\Domain\Payment\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManualPaymentMethod extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
