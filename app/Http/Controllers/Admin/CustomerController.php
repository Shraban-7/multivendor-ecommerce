<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
       $customers = User::with('country')->get();

        return view('admin.customer', compact('customers'));
    }
}
