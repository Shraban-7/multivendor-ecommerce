<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ManualPaymentMethod;
use Illuminate\Http\Request;

class ManualPaymentMethodController extends Controller
{
    private const IMAGE_DIR = 'images/payment-gateways';

    public function index()
    {
        $methods = ManualPaymentMethod::get();

        return view('admin.manual-payment-methods.index', compact('methods'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'account_name' => 'required',
            'account_number' => 'required',
            'is_active' => 'boolean',
            'image' => 'required|image|mimes:jpg,jpeg,png,gif,bmp,svg,webp|max:2048',
            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,svg,webp|max:2048',
        ]);

        $data['slug'] = str_slug('manual_payment_methods', 'slug', $data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = upload_file($request->file('image'), $this::IMAGE_DIR);
        }

        if ($request->hasFile('qr_image')) {
            $data['qr_image'] = upload_file($request->file('qr_image'), $this::IMAGE_DIR);
        }

        ManualPaymentMethod::create($data);

        return redirect()->route('admin.manualGateways.index')->with('success', 'Manual payment method create successfully');
    }

    public function update(Request $request, ManualPaymentMethod $manualPayment)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'nullable',
            'account_name' => 'required',
            'account_number' => 'required',
            'is_active' => 'boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,svg,webp|max:2048',
            'qr_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,bmp,svg,webp|max:2048',
        ]);

        if ($request->name != $manualPayment->name) {
            $data['slug'] = str_slug('manual_payment_methods', 'slug', $data['name']);
        } else {
            $data['slug'] = $manualPayment->slug;
        }

        if ($request->hasFile('image')) {
            if (!empty($manualPayment->image)) {
                delete_file($manualPayment->image);
            }
            $data['image'] = upload_file($request->file('image'), $this::IMAGE_DIR);
        }

        if ($request->hasFile('qr_image')) {
            if (!empty($manualPayment->qr_image)) {
                delete_file($manualPayment->qr_image);
            }
            $data['qr_image'] = upload_file($request->file('qr_image'), $this::IMAGE_DIR);
        }

        $manualPayment->update($data);

        return redirect()
            ->route('admin.manual-gateways.index')
            ->with('success', 'Manual payment method updated successfully');
    }

    public function delete(ManualPaymentMethod $manualPayment)
    {
        if (!empty($manualPayment->image)) {
            delete_file($manualPayment->image);
        }
        if (!empty($manualPayment->qr_image)) {
            delete_file($manualPayment->qr_image);
        }

        $manualPayment->delete();

        return redirect()
            ->route('admin.manualGateways.index')
            ->with('success', 'Manual payment method deleted successfully');
    }
}
