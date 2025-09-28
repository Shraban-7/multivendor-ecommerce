<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerExpense extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'expense_date' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(SellerExpenseCategory::class, 'seller_expense_category_id');
    }
}
