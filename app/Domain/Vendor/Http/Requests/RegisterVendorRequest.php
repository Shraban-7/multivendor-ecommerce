<?php

namespace App\Domain\Vendor\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterVendorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:sellers,email'],
            'phone' => ['required', 'string', 'max:200'],
            'nid_no' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'min:5', 'confirmed'],
            'business_name' => ['required', 'string', 'max:255'],
            'business_email' => ['required', 'string', 'email', 'max:255', 'unique:sellers,business_email'],
            'business_address' => ['required', 'string', 'max:1000'],
            'division_id' => ['required', 'exists:divisions,id'],
            'district_id' => ['required', 'exists:districts,id'],
            'trade_license_no' => ['required', 'string', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:8000'],
            'nid_front_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:8000'],
            'nid_back_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:8000'],
            'business_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:8000'],
            'trade_license_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:8000'],
            'shop_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:8000'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered.',
            'business_email.unique' => 'This business email is already in use.',
        ];
    }
}
