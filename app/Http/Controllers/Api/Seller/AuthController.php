<?php

namespace App\Http\Controllers\Api\Seller;

use App\Domain\Vendor\Models\Seller;
use App\Http\Controllers\Controller;
use App\Http\Resources\Seller\SellerProfileResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = validateRequest($request, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $seller = Seller::where('email', $request->email)->first();

        if (! $seller) {
            return errorResponse('No account found with this email!');
        }

        if (! Hash::check($request->password, $seller->password)) {
            return errorResponse('Incorrect password!');
        }

        if ($seller->status !== Seller::ACTIVE) {
            return errorResponse('Your account is not active. Please contact support.');
        }

        return apiResponse([
            'seller' => new SellerProfileResource($seller),
            'token' => $seller->createToken('seller-api-token')->plainTextToken,
        ], 'Login successful');
    }

    public function register(Request $request)
    {
        $validator = validateRequest($request, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:sellers,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'business_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return errorResponse($validator->errors()->first(), 422);
        }

        $seller = Seller::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'business_name' => $request->business_name ?? $request->name,
            'username' => Seller::generateShortUsername($request->name),
            'code' => Seller::generateSellerCode($request->name),
            'status' => Seller::PENDING,
        ]);

        return apiResponse([
            'seller' => new SellerProfileResource($seller),
            'token' => $seller->createToken('seller-api-token')->plainTextToken,
        ], 'Registration successful. Your account is pending approval.');
    }

    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();

        return successResponse('Logged out successfully.');
    }

    public function profile()
    {
        return apiResponse(new SellerProfileResource(Auth::user()));
    }

    public function updateProfile(Request $request)
    {
        $seller = Auth::user();

        $validator = validateRequest($request, [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:sellers,email,'.$seller->id,
            'phone' => 'nullable|string|max:20',
            'business_name' => 'nullable|string|max:255',
            'business_email' => 'nullable|email|max:255',
            'business_address' => 'nullable|string',
            'shipping_cost' => 'nullable|numeric|min:0',
            'division_id' => 'nullable|exists:divisions,id',
            'district_id' => 'nullable|exists:districts,id',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        $seller->update($validator->validated());

        return apiResponse(new SellerProfileResource($seller->fresh()), 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $seller = Auth::user();

        $validator = validateRequest($request, [
            'current_password' => 'required|string',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return sendValidationError($validator->errors());
        }

        if (! Hash::check($request->current_password, $seller->password)) {
            return errorResponse('Current password is incorrect.');
        }

        $seller->update(['password' => Hash::make($request->password)]);

        return successResponse('Password updated successfully.');
    }
}
