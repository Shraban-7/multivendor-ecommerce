<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Vendor\Models\SellerEmployee;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = SellerEmployee::where('seller_id', Auth::id())
            ->withCount('orders')
            ->latest()
            ->get();

        return apiResponse($employees->map(fn ($e) => [
            'id' => $e->id,
            'name' => $e->name,
            'email' => $e->email,
            'phone' => $e->phone,
            'is_active' => (bool) $e->is_active,
            'permissions' => $e->permissions ?? [],
            'orders_count' => (int) ($e->orders_count ?? 0),
            'created_at' => $e->created_at,
        ]));
    }

    public function create()
    {
        $routes = $this->getPermissionRoutes();

        return apiResponse(['permission_routes' => $routes]);
    }

    public function store(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:seller_employees,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $employee = SellerEmployee::create([
            'seller_id' => Auth::id(),
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'permissions' => $request->permissions ?? [],
            'is_active' => true,
        ]);

        return apiResponse([
            'id' => $employee->id,
            'name' => $employee->name,
        ], 'Employee created successfully.');
    }

    public function edit($id)
    {
        $employee = SellerEmployee::where('id', $id)->where('seller_id', Auth::id())->firstOrFail();

        return apiResponse([
            'employee' => $employee,
            'permission_routes' => $this->getPermissionRoutes(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $employee = SellerEmployee::where('id', $id)->where('seller_id', Auth::id())->firstOrFail();

        $validator = validateRequest($request, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:seller_employees,email,'.$employee->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'permissions' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $data = $validator->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $employee->update($data);

        return successResponse('Employee updated successfully.');
    }

    public function toggleActive($id)
    {
        $employee = SellerEmployee::where('id', $id)->where('seller_id', Auth::id())->firstOrFail();
        $employee->update(['is_active' => ! $employee->is_active]);

        return successResponse($employee->is_active ? 'Employee activated.' : 'Employee deactivated.');
    }

    public function setPermissions(Request $request, SellerEmployee $employee)
    {
        if ($employee->seller_id !== Auth::id()) {
            return errorResponse('Unauthorized.', 403);
        }

        $validator = validateRequest($request, [
            'permissions' => 'required|array',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $employee->update(['permissions' => $request->permissions]);

        return successResponse('Permissions updated successfully.');
    }

    public function salesReport(Request $request)
    {
        $sellerId = Auth::id();

        $employees = SellerEmployee::where('seller_id', $sellerId)
            ->withCount(['orders' => function ($q) {
                $q->where('status', OrderStatus::COMPLETED->value);
            }])
            ->get()
            ->map(function ($e) {
                $sales = Order::where('seller_id', Auth::id())
                    ->where('employee_id', $e->id)
                    ->where('status', OrderStatus::COMPLETED->value)
                    ->sum('payable');

                return [
                    'id' => $e->id,
                    'name' => $e->name,
                    'email' => $e->email,
                    'orders_count' => (int) ($e->orders_count ?? 0),
                    'total_sales' => (float) $sales,
                ];
            });

        return apiResponse($employees);
    }

    private function getPermissionRoutes()
    {
        return [
            'dashboard' => 'Dashboard',
            'products' => 'Products',
            'orders' => 'Orders',
            'pos' => 'POS',
            'reports' => 'Reports',
            'expenses' => 'Expenses',
            'employees' => 'Employees',
            'settings' => 'Settings',
            'chat' => 'Chat',
            'customers' => 'Customers',
        ];
    }
}
