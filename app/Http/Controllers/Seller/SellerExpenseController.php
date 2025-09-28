<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerExpense;
use App\Models\SellerExpenseCategory;
use Illuminate\Http\Request;

class SellerExpenseController extends Controller
{
    public function index()
    {
        $expenses = SellerExpense::latest()->paginate(25);

        $categories = SellerExpenseCategory::get();

        return view('seller.expenses.index', compact('expenses', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'seller_expense_category_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        $categoryId = $this->getOrCreateCategory($request->seller_expense_category_id);

        SellerExpense::create([
            'seller_id' => get_seller_id(),
            'seller_expense_category_id' => $categoryId,
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->back()->with('success', 'Expense created successfully.');
    }

    public function update(Request $request, SellerExpense $expense)
    {
        $request->validate([
            'seller_expense_category_id' => 'required',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        $categoryId = $this->getOrCreateCategory($request->seller_expense_category_id);

        $expense->update([
            'seller_expense_category_id' => $categoryId,
            'amount' => $request->amount,
            'description' => $request->description,
            'expense_date' => $request->expense_date,
        ]);

        return redirect()->back()->with('success', 'Expense updated successfully.');
    }

    public function destroy(SellerExpense $expense)
    {
        $expense->delete();

        return redirect()->back()->with('success', 'Expense deleted successfully.');
    }

    private function getOrCreateCategory($categoryInput)
    {
        if (is_numeric($categoryInput)) {
            return (int) $categoryInput;
        }

        $category = SellerExpenseCategory::firstOrCreate(
            ['name' => $categoryInput, 'seller_id' => get_seller_id()],
            ['name' => $categoryInput, 'seller_id' => get_seller_id()]
        );

        return $category->id;
    }
}
