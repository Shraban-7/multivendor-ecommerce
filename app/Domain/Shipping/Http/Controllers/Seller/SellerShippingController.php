<?php

namespace App\Domain\Shipping\Http\Controllers\Seller;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderTracking;
use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Shipping\Models\SellerShippingZone;
use App\Domain\Shipping\Models\Shipment;
use App\Domain\Shipping\Models\ShippingCarrier;
use App\Domain\Shipping\Models\District;
use App\Domain\Shipping\Repositories\ShippingRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerShippingController extends Controller
{
    public function __construct(
        private readonly ShippingRepositoryInterface $shippingRepo,
    ) {}

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

        if ($order->status->value < OrderStatus::DELIVERED->value) {
            $oldStatus = $order->status->value;
            $order->update(['status' => OrderStatus::DELIVERED->value]);
            $order->statusLogs()->create([
                'old_status' => $oldStatus,
                'new_status' => OrderStatus::DELIVERED->value,
                'changed_by' => 'seller',
            ]);
        }

        return redirect()->route('seller.orders.details', $order->invoice_id)
            ->with('success', 'Tracking information added.');
    }

    public function shipments(Request $request)
    {
        $seller = seller();
        $filters = array_filter([
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'order_id' => $request->order_id,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
        ]);

        $shipments = $this->shippingRepo->getShipmentsBySeller($seller->id, $filters);

        return view('seller.shipping.shipments', compact('shipments'));
    }

    public function shipmentShow($id)
    {
        $seller = seller();
        $shipment = $this->shippingRepo->findShipmentById((int) $id, $seller->id);

        if (! $shipment) {
            return redirect()->route('seller.shipping.shipments')
                ->with('error', 'Shipment not found.');
        }

        return view('seller.shipping.shipment_show', compact('shipment'));
    }

    public function shipmentCreate(Order $order)
    {
        $seller = seller();
        if ($order->seller_id !== $seller->id) {
            abort(403);
        }

        $carriers = ShippingCarrier::where('is_active', true)->get();

        return view('seller.shipping.shipment_create', compact('order', 'carriers'));
    }

    public function shipmentStore(Request $request)
    {
        $seller = seller();

        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'shipping_carrier_id' => 'required|exists:shipping_carriers,id',
            'tracking_number' => 'required|string|max:255',
            'weight' => 'nullable|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'cod_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $order = Order::findOrFail($data['order_id']);
        if ($order->seller_id !== $seller->id) {
            abort(403);
        }

        $data['seller_id'] = $seller->id;
        $data['status'] = Shipment::STATUS_PENDING;
        $data['pickup_address'] = $seller->address ?? $seller->business_address;
        $data['delivery_address'] = $order->billing_address?->address;

        $shipment = $this->shippingRepo->createShipment($data);

        $this->shippingRepo->createTrackingLog(
            $shipment->id,
            Shipment::STATUS_PENDING,
            null,
            'Shipment created and ready for pickup.'
        );

        $carrierName = $shipment->carrier?->name ?? 'Carrier';
        OrderTracking::create([
            'order_id' => $order->id,
            'seller_id' => $seller->id,
            'status_id' => $order->status->value,
            'tracking_number' => $data['tracking_number'],
            'courier_name' => $carrierName,
            'notes' => 'Shipment created',
        ]);

        return redirect()->route('seller.shipping.shipments')
            ->with('success', 'Shipment created successfully.');
    }

    public function shipmentUpdateStatus(Request $request, $id)
    {
        $seller = seller();
        $shipment = $this->shippingRepo->findShipmentById((int) $id, $seller->id);

        if (! $shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }

        $data = $request->validate([
            'status' => 'required|string|in:'.implode(',', array_keys(Shipment::statuses())),
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $shipment->status;
        $this->shippingRepo->updateShipment($shipment, ['status' => $data['status']]);

        if ($data['status'] === Shipment::STATUS_DELIVERED) {
            $shipment->update(['delivered_at' => now()]);
        }
        if ($data['status'] === Shipment::STATUS_PICKED_UP && ! $shipment->shipped_at) {
            $shipment->update(['shipped_at' => now()]);
        }

        $this->shippingRepo->createTrackingLog(
            $shipment->id,
            $data['status'],
            $data['location'] ?? null,
            $data['description'] ?? null,
        );

        if ($data['status'] === Shipment::STATUS_DELIVERED && $oldStatus !== Shipment::STATUS_DELIVERED) {
            $order = $shipment->order;
            if ($order && $order->seller_id === $seller->id) {
                $oldOrderStatus = $order->status->value;
                $order->update(['status' => OrderStatus::DELIVERED->value]);
                $order->statusLogs()->create([
                    'old_status' => $oldOrderStatus,
                    'new_status' => OrderStatus::DELIVERED->value,
                    'changed_by' => 'seller',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Shipment status updated to '.Shipment::statuses()[$data['status']]);
    }
}
