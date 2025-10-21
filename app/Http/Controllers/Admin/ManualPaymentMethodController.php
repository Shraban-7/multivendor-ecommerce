<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualPaymentMethod;
use Illuminate\Http\Request;

class ManualPaymentMethodController extends Controller
{
    public function index() 
    {
        $methods = ManualPaymentMethod::get();

        return view('admin.manual-payment-methods.index', compact('methods'));
    }
}
