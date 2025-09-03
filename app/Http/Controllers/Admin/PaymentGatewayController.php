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

        return view('admin.payment-gateways.index', compact('paymentGateways'));
    }

    public function create()
    {
        return view('admin.payment-gateways.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string',
            'payment_url'          => 'required|url',
            'image'                => 'nullable|image|max:4096',
            'credentials_keys'     => 'required|array|min:1',
            'credentials_keys.*'   => 'required|string',
            'credentials_values'   => 'required|array|min:1',
            'credentials_values.*' => 'required|string',
            'is_enabled'           => 'required|boolean',
            'is_default'           => 'required|boolean',
        ]);

        $credentials = array_combine($data['credentials_keys'], $data['credentials_values']);
        unset($data['credentials_keys'], $data['credentials_values']);

        $data['credentials'] = $credentials;

        $data['slug'] = str_slug('payment_gateways', 'slug', $data['name']);

        if ($request->hasFile('image')) {
            $data['image'] = upload_file($request->file('image'), 'images/payment-gateways');
        }

        PaymentGateway::create($data);

        return redirect()->route('admin.payment_gateways.index')
            ->with('success', 'Payment gateway added successfully.');
    }

    public function edit(PaymentGateway $gateway)
    {
        return view('admin.payment-gateways.edit', compact('gateway'));
    }

    public function update(Request $request, PaymentGateway $gateway)
    {
        $data = $request->validate([
            'name'                 => 'required|string',
            'payment_url'          => 'required|url',
            'image'                => 'nullable|image|max:4096',
            'credentials_keys'     => 'required|array|min:1',
            'credentials_keys.*'   => 'required|string',
            'credentials_values'   => 'required|array|min:1',
            'credentials_values.*' => 'required|string',
            'is_enabled'           => 'required|boolean',
            'is_default'           => 'required|boolean',
        ]);

        $credentials = array_combine($data['credentials_keys'], $data['credentials_values']);
        unset($data['credentials_keys'], $data['credentials_values']);

        $data['credentials'] = $credentials;

        if ($data['name'] && $data['name'] !== $gateway->name) {
            $data['slug'] = str_slug('payment_gateways', 'slug', $data['name']);
        }
        if ($request->hasFile('image')) {
            if ($gateway->image) {
                delete_file($gateway->image);
            }

            $data['image'] = upload_file($request->file('image'), 'images/payment-gateways');
        }

        $gateway->update($data);

        return redirect()->route('admin.payment_gateways.index')
            ->with('success', 'Payment gateway updated successfully.');
    }

    public function destroy(PaymentGateway $gateway)
    {
        if ($gateway->image != null) {
            delete_file($gateway->image);
        }

        $gateway->delete();

        return redirect()->route('admin.payment_gateways.index')
            ->with('success', 'Payment gateway deleted successfully.');
    }

}
