<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerExpense;
use Illuminate\Http\Request;

class SellerExpenseController extends Controller
{
    public function index()
    {
        $expenses = SellerExpense::latest()->paginate(25);

        return view('seller.expenses.index');
    }
}
