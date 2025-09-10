<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\SellerEmployee;
use Illuminate\Http\Request;

class SellerEmployeeController extends Controller
{
    public function index()
    {
        $seller_id = seller()->id;

        $employees = SellerEmployee::where('seller_id', $seller_id)->get();

        $permissions = get_seller_routes();

        return view('seller.employees.index', compact('employees','permissions'));
    }

    public function create()
    {
        return view('seller.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $data['seller_id'] = seller()->id;

        SellerEmployee::create($data);

        return redirect()->route('seller.employees.index')->with('success', 'Employee Create Successfully');
    }

    public function edit($id)
    {
        $employee = SellerEmployee::where('seller_id', seller()->id)->findOrFail($id);

        return view('seller.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = SellerEmployee::where('seller_id', seller()->id)->findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $employee->id,
            'password' => 'nullable|string|min:5|confirmed',
            'is_active' => 'required|in:0,1'
        ]);

        if (!empty($data['password'])) {
            $employee->password = bcrypt($data['password']);
        }

        $employee->name  = $data['name'];
        $employee->email = $data['email'];
        $employee->is_active = $data['is_active'];
        $employee->seller_id = seller()->id;
        $employee->save();

        return redirect()->route('seller.employees.index')->with('success', 'Employee Updated Successfully');
    }

    public function toggleActive($id)
    {
        $employee = SellerEmployee::where('seller_id', seller()->id)->findOrFail($id);

        $employee->is_active = !$employee->is_active;
        $employee->save();

        return redirect()->route('seller.employees.index')->with('success', 'Employee status updated successfully');
    }

    public function setPermissions(SellerEmployee $employee, Request $request)
    {
        $request->validate([
            'permissions' => 'required|array'
        ]);

        $employee->update([
            'permissions' => $request->permissions
        ]);

        return redirect()->back()->with('success', "Permissions updated successfully");
    }
}
