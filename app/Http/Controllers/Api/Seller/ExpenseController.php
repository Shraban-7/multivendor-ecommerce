<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Vendor\Models\SellerExpense;
use App\Domain\Vendor\Models\SellerExpenseCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = SellerExpense::where('seller_id', Auth::id())->with('category');

        if ($request->filled('category_id')) {
            $query->where('seller_expense_category_id', $request->category_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $expenses = $query->latest()->paginate(25);

        return apiResourceResponse($expenses->through(fn ($e) => [
            'id' => $e->id,
            'category' => $e->category?->name,
            'amount' => (float) $e->amount,
            'note' => $e->note,
            'created_at' => $e->created_at,
        ]));
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'amount' => 'required|numeric|min:0',
            'category_name' => 'required|string|max:255',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $category = SellerExpenseCategory::firstOrCreate([
            'seller_id' => Auth::id(),
            'name' => $request->category_name,
        ]);

        $expense = SellerExpense::create([
            'seller_id' => Auth::id(),
            'seller_expense_category_id' => $category->id,
            'amount' => $request->amount,
            'note' => $request->note,
        ]);

        return apiResponse([
            'id' => $expense->id,
            'amount' => (float) $expense->amount,
            'category' => $category->name,
        ], 'Expense recorded successfully.');
    }

    public function update(Request $request, SellerExpense $expense)
    {
        if ($expense->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        $validator = validateRequest($request, [
            'amount' => 'sometimes|numeric|min:0',
            'category_name' => 'sometimes|string|max:255',
            'note' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = [];

        if ($request->has('amount')) {
            $data['amount'] = $request->amount;
        }

        if ($request->has('category_name')) {
            $category = SellerExpenseCategory::firstOrCreate([
                'seller_id' => Auth::id(),
                'name' => $request->category_name,
            ]);
            $data['seller_expense_category_id'] = $category->id;
        }

        if ($request->has('note')) {
            $data['note'] = $request->note;
        }

        $expense->update($data);

        return successResponse('Expense updated successfully.');
    }

    public function destroy(SellerExpense $expense)
    {
        if ($expense->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        $expense->delete();

        return successResponse('Expense deleted successfully.');
    }
}
