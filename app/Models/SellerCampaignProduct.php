<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class SellerCampaignProduct extends Pivot
{
    protected $guarded = ['id'];
}
