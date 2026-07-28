<?php

namespace App\Domain\Shipping\Http\Controllers\Admin;

use App\Domain\Shipping\Models\ShippingCarrier;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminShippingCarrierController extends Controller
{
    public function index()
    {
        $carriers = ShippingCarrier::orderBy('name')->paginate(20);
        return view('admin.shipping.carriers', compact('carriers'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'api_endpoint' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        ShippingCarrier::create($data);

        return redirect()->route('admin.shipping.carriers')
            ->with('success', 'Carrier added successfully.');
    }

    public function update(Request $request, ShippingCarrier $carrier)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|string|max:255',
            'api_endpoint' => 'nullable|string|max:255',
            'api_key' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        $data['slug'] = Str::slug($data['name']);
        $data['is_active'] = $request->boolean('is_active', true);

        $carrier->update($data);

        return redirect()->route('admin.shipping.carriers')
            ->with('success', 'Carrier updated successfully.');
    }

    public function destroy(ShippingCarrier $carrier)
    {
        $carrier->delete();
        return redirect()->route('admin.shipping.carriers')
            ->with('success', 'Carrier deleted.');
    }
}
