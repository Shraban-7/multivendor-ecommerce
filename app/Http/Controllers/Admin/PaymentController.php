<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::latest();

        if ($request->filled('user_name')) {
            $query->where('customer_name', 'like', '%' . $request->user_name . '%');
        }

        if ($request->filled('user_phone')) {
            $query->where('customer_phone', 'like', '%' . $request->user_phone . '%');
        }

        $payments = $query->paginate(20);

        return view('admin.payments.index', compact('payments'));
    }

}
