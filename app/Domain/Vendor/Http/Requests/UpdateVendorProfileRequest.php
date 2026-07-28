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
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,svg', 'max:2048'],
            ],
            'password' => [
                'current_password' => ['required', 'string'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ],
            default => [],
        };
    }
}
