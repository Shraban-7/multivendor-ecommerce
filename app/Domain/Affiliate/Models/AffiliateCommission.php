<?php

namespace App\Domain\Affiliate\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffiliateCommission extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    const PENDING = 0;

    const APPROVED = 1;

    const PAID = 2;

    const REVERSED = 3;
}
