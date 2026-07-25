<?php

namespace App\Domain\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVendorProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sellerId = $this->route('seller')?->id ?? get_seller_id();

        return match ($this->input('section')) {
            'personal' => [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:sellers,email,'.$sellerId],
                'phone' => ['required', 'string', 'max:20'],
                'nid_no' => ['nullable', 'string', 'max:100'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
                'nid_front_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
                'nid_back_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            ],
            'business' => [
                'business_name' => ['required', 'string', 'max:255'],
                'business_email' => ['nullable', 'email', 'max:255', 'unique:sellers,business_email,'.$sellerId],
                'business_address' => ['nullable', 'string', 'max:255'],
                'division_id' => ['required', 'integer'],
                'district_id' => ['required', 'integer'],
                'business_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
                'shop_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            ],
            'documents' => [
                'trade_license_no' => ['nullable', 'string', 'max:255'],
                'trade_license_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            ],
            'password' => [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            default => [],
        };
    }
}
