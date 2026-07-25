<?php

namespace App\Domain\Vendor\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerExpense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'expense_date' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(SellerExpenseCategory::class, 'seller_expense_category_id');
    }
}
