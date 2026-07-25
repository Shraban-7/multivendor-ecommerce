<?php

namespace App\Http\Controllers\Seller;

use App\Domain\Shipping\Models\Division;
use App\Domain\Vendor\Http\Requests\UpdateVendorProfileRequest;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Services\VendorService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class SellerController extends Controller
{
    public function __construct(private readonly VendorService $vendorService) {}

    public function profile(UpdateVendorProfileRequest $request)
    {
        $seller = Seller::find(get_seller_id());
        $divisions = Division::all();

        if ($request->isMethod('GET')) {
            return view('seller.profile-information', compact('seller', 'divisions'));
        }

        $section = $request->input('section');

        try {
            if ($section === 'password') {
                if (! Hash::check($request->current_password, $seller->password)) {
                    return response()->json([
                        'errors' => ['current_password' => ['Current password is incorrect.']],
                    ], 422);
                }
            }

            if (! in_array($section, ['personal', 'business', 'documents', 'password'])) {
                return errorResponse('Invalid section provided.');
            }

            $this->vendorService->updateProfile($seller, $section, $request->validated());

            return successResponse('Profile updated successfully.');
        } catch (\Exception $e) {
            return errorResponse($e->getMessage());
        }
    }
}
