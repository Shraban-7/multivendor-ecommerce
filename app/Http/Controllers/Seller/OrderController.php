<?php

namespace App\Http\Controllers\Seller;

use App\Models\User;
use App\Models\Order;
use App\Models\Seller;
use App\Enums\OrderStatus;
use Illuminate\Http\Request;
use App\Models\OrderStatusLog;
use App\Models\AffiliateCommission;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->segment(3);

        $statusValue = $type ? OrderStatus::valueFromLabel($type) : null;

        if ($type && $statusValue === null && $type != 'pos') {
            return redirect()->route('seller.dashboard');
        }

        $orders = Order::where('seller_id', get_seller_id())
            ->when($statusValue !== null, fn($q) => $q->where('status', $statusValue))
            ->whereNotNull('user_id')
            ->latest('id');

        if ($request->filled('invoice_id')) {
            $orders->where('invoice_id', 'like', '%' . $request->invoice_id . '%');
        }
        if ($request->filled('customer_name')) {
            $orders->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->customer_name . '%'));
        }
        if ($request->filled('customer_phone')) {
            $orders->whereHas('user', fn($q) => $q->where('phone', 'like', '%' . $request->customer_phone . '%'));
        }
        if ($request->filled('date_from')) {
            $orders->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $orders->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $orders->paginate(25);

        return view('seller.orders.index', compact('orders', 'type'));
    }

    public function details($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();
        if (get_seller_id() == $order->seller_id) {
            $order->load(['review', 'items']);
            return view('seller.orders.details', compact('order'));
        }
        return redirect()->back();
    }

    public function updateStatus(Order $order, Request $request)
    {
        if ($order->status->value == OrderStatus::COMPLETED->value) {
            return redirect()->back()->with('error', 'Completed orders cannot be updated.');
        }

        $request->validate([
            'new_status' => 'required|integer',
            'remarks' => 'nullable|string',
        ]);

        $old_status = $order->status;

        if ($old_status->value == $request->new_status) {
            return  redirect()->back()->with('success', 'Order status already ' . $old_status->title());
        }

        $order->status = $request->new_status;
        $order->save();

        OrderStatusLog::create([
            'order_id' => $order->id,
            'old_status' => $old_status->value,
            'new_status' => $request->new_status,
            'changed_by' => 'seller',
            'remarks' => $request->remarks,
        ]);

        $order->addSellerEarningToBalance();
        if ($order->status->value == OrderStatus::COMPLETED->value) {

            $affiliate_commission = AffiliateCommission::where('order_id', $order->id)->first();
            if ($affiliate_commission && $affiliate_commission->status != AffiliateCommission::APPROVED) {
                $affiliate_commission->status = AffiliateCommission::APPROVED;
                $affiliate_commission->save();

                $user = User::find($affiliate_commission->affiliate_id);
                if ($user) {
                    $user->balance += $affiliate_commission->commission_amount;
                    $user->save();
                }
            }
        }

        return redirect()->back()->with('success', 'Order updated successfully');
    }


    public function posInvoice($invoice_id)
    {
        $order = Order::where('invoice_id', $invoice_id)->first();

        if (get_seller_id() == $order->seller_id) {
            return view('seller.orders.pos_invoice', compact('order'));
        }
        return redirect()->back();
    }
}
