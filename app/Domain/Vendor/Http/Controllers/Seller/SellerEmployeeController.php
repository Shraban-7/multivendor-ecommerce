<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Vendor\Models\SellerEmployee;
use App\Domain\Vendor\Repositories\SellerEmployeeRepositoryInterface;
use App\Domain\Vendor\Repositories\SellerRepositoryInterface;
use App\Domain\Vendor\Services\VendorService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class SellerEmployeeController extends Controller
{
    public function __construct(
        private readonly VendorService $vendorService,
        private readonly SellerRepositoryInterface $sellerRepo,
        private readonly SellerEmployeeRepositoryInterface $employeeRepo,
    ) {}

    public function index(Request $request)
    {
        $sellerId = get_seller_id();

        $filters = [
            'search'    => trim((string) $request->query('search', '')),
            'status'    => $request->query('status'),
            'sort'      => $request->query('sort'),
            'direction' => $request->query('direction'),
        ];

        $employees   = $this->employeeRepo->paginateForSeller($sellerId, $filters, (int) $request->query('per_page', 25));
        $counts      = $this->employeeRepo->getStatusCountsForSeller($sellerId);
        $permissions = get_seller_routes();

        return view('seller.employees.index', compact('employees', 'counts', 'filters', 'permissions'));
    }

    public function create()
    {
        return view('seller.employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:seller_employees,email',
            'password' => 'required|string|min:5|confirmed',
        ]);

        $seller = $this->sellerRepo->findById(get_seller_id());
        $this->vendorService->createEmployee($seller, $data);

        return redirect()->route('seller.employees.index')->with('success', 'Employee Create Successfully');
    }

    public function edit($id)
    {
        $employee = $this->employeeRepo->findById($id);

        if (! $employee || $employee->seller_id !== get_seller_id()) {
            abort(404);
        }

        return view('seller.employees.edit', compact('employee'));
    }

    public function update(Request $request, $id)
    {
        $employee = $this->employeeRepo->findById($id);

        if (! $employee || $employee->seller_id !== get_seller_id()) {
            abort(404);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|string|email|max:255|unique:seller_employees,email,'.$employee->id,
            'password' => 'nullable|string|min:5|confirmed',
            'is_active' => 'required|in:0,1',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->employeeRepo->update($employee, $data);

        return redirect()->route('seller.employees.index')->with('success', 'Employee Updated Successfully');
    }

    public function toggleActive($id)
    {
        $employee = $this->employeeRepo->findById($id);

        if (! $employee || $employee->seller_id !== seller()->id) {
            abort(404);
        }

        $this->vendorService->toggleEmployeeActive($employee);

        return redirect()->route('seller.employees.index')->with('success', 'Employee status updated successfully');
    }

    public function destroy($id)
    {
        $employee = $this->employeeRepo->findById($id);

        if (! $employee || $employee->seller_id !== get_seller_id()) {
            abort(404);
        }

        $employeeName = $employee->name;
        $this->employeeRepo->delete($employee);

        return redirect()->route('seller.employees.index')->with('success', "Employee {$employeeName} removed");
    }

    public function setPermissions(SellerEmployee $employee, Request $request)
    {
        $request->validate([
            'permissions' => 'required|array',
        ]);

        $this->vendorService->setEmployeePermissions($employee, $request->permissions);

        return redirect()->back()->with('success', 'Permissions updated successfully');
    }

    public function profile()
    {
        $employee = auth('employee')->user();

        return view('seller.employees.profile', compact('employee'));
    }

    public function updateProfile(Request $request)
    {
        $employee = auth('employee')->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:seller_employees,email,'.$employee->id,
            'password' => 'nullable|string|min:5|confirmed',
            'is_active' => 'required',
        ]);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $this->employeeRepo->update($employee, $data);

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function salesReport(Request $request)
    {
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)->startOfDay()
            : Carbon::now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)->endOfDay()
            : Carbon::now()->endOfMonth();

        $employees = SellerEmployee::active()
            ->where('seller_id', get_seller_id())
            ->withSum(['orders as total_sales' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'total')
            ->withCount(['orders as total_orders' => function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->get();

        return view('seller.employees.sales_report', compact('employees', 'startDate', 'endDate'));
    }
}
