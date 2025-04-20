<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index()
    {
        $paymentGateways = PaymentGateway::get();

        return view('admin.settings.payment_gateway.index', compact('paymentGateways'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = upload_file($request->file('image'), 'images/home_mid_banners');
        }

        $data['image'] = $imagePath;

        PaymentGateway::create($data);

        return redirect()->route('admin.settings.paymentGateways.index')->with('success', 'Promo Poster create successfully');
    }

    public function update(Request $request, PaymentGateway $gateway)
    {
        // return $gateway;
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'link' => 'required|url|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if (!empty($gateway->image)) {
                delete_file($gateway->image);
            }
            $filePath = 'images/home_mid_banners';
            $data['image'] = upload_file($request->file('image'), $filePath);
        } else {
            $data['image'] = $gateway->image;
        }

        $data['status'] = $request->status;



        // return $data;

        $gateway->update($data);

        return redirect()->route('admin.settings.paymentGateways.index')->with('success', 'Promo Poster updated successfully');
    }
}
