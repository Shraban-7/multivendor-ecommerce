<?php

namespace App\Domain\Shipping\Http\Controllers\Seller;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderTracking;
use App\Domain\Shipping\Models\SellerShippingZone;
use App\Domain\Shipping\Models\ShippingCarrier;
use App\Domain\Shipping\Models\District;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerShippingController extends Controller
{
    public function zones()
    {
        $seller = seller();
        $zones = SellerShippingZone::where('seller_id', $seller->id)
            ->with('carrier')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $carriers = ShippingCarrier::where('is_active', true)->get();
        $districts = District::orderBy('name')->get();

        return view('seller.shipping.zones', compact('zones', 'carriers', 'districts'));
    }

    public function storeZone(Request $request)
    {
        $seller = seller();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:flat,weight_based,price_based',
            'rate' => 'required|numeric|min:0',
            'free_above' => 'nullable|numeric|min:0',
            'extra_rate_per_kg' => 'nullable|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_order' => 'nullable|numeric|min:0',
            'districts' => 'nullable|array',
            'districts.*' => 'integer|exists:districts,id',
            'is_cod_available' => 'nullable|boolean',
            'carrier_id' => 'nullable|exists:shipping_carriers,id',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $data['seller_id'] = $seller->id;
        $data['is_cod_available'] = $request->boolean('is_cod_available');
        $data['is_active'] = $request->boolean('is_active', true);

        SellerShippingZone::create($data);

        return redirect()->route('seller.shipping.zones')
            ->with('success', 'Shipping zone created successfully.');
    }

    public function updateZone(Request $request, SellerShippingZone $zone)
    {
        $seller = seller();
        if ($zone->seller_id !== $seller->id) {
            abort(403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:flat,weight_based,price_based',
            'rate' => 'required|numeric|min:0',
            'free_above' => 'nullable|numeric|min:0',
            'extra_rate_per_kg' => 'nullable|numeric|min:0',
            'min_weight' => 'nullable|numeric|min:0',
            'max_weight' => 'nullable|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_order' => 'nullable|numeric|min:0',
            'districts' => 'nullable|array',
            'districts.*' => 'integer|exists:districts,id',
            'is_cod_available' => 'nullable|boolean',
            'carrier_id' => 'nullable|exists:shipping_carriers,id',
            'estimated_days_min' => 'nullable|integer|min:1',
            'estimated_days_max' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);

        $data['is_cod_available'] = $request->boolean('is_cod_available');
        $data['is_active'] = $request->boolean('is_active', true);

        $zone->update($data);

        return redirect()->route('seller.shipping.zones')
            ->with('success', 'Shipping zone updated successfully.');
    }

    public function destroyZone(SellerShippingZone $zone)
    {
        $seller = seller();
        if ($zone->seller_id !== $seller->id) {
            abort(403);
        }

        $zone->delete();

        return redirect()->route('seller.shipping.zones')
            ->with('success', 'Shipping zone deleted.');
    }

    public function trackingForm(Order $order)
    {
        $seller = seller();
        if ($order->seller_id !== $seller->id) {
            abort(403);
        }

        $carriers = ShippingCarrier::where('is_active', true)->get();
        $trackings = $order->trackings()->with('carrier')->orderBy('created_at', 'desc')->get();

        return view('seller.orders.tracking', compact('order', 'carriers', 'trackings'));
    }

    public function storeTracking(Request $request, Order $order)
    {
        $seller = seller();
        if ($order->seller_id !== $seller->id) {
            abort(403);
        }

        $data = $request->validate([
            'tracking_number' => 'required|string|max:255',
            'carrier_id' => 'required|exists:shipping_carriers,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $data['order_id'] = $order->id;
        $data['seller_id'] = $seller->id;
        $data['status_id'] = $order->status->value;

        OrderTracking::create($data);

        if ($order->status->value < 3) {
            $order->update(['status' => 3]);
            $order->statusLogs()->create([
                'old_status' => $order->status->value - 1,
                'new_status' => 3,
                'changed_by' => 'seller',
            ]);
        }

        return redirect()->route('seller.orders.details', $order->invoice_id)
            ->with('success', 'Tracking information added.');
    }
}
