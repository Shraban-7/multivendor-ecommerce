<?php

namespace App\Domain\Order\Http\Controllers\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Services\OrderService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly OrderService $orderService,
    ) {}

    public function index(Request $request)
    {
        $type = $request->segment(3);
        $statusValue = $type ? OrderStatus::valueFromLabel($type) : null;

        if ($type && $statusValue === null && $type != 'pos') {
            return redirect()->route('seller.dashboard');
        }

        $orders = $this->orderRepo->searchSellerOrders(
            get_seller_id(),
            [
                'status' => $statusValue,
                'invoice_id' => $request->invoice_id,
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
            ['billing_address', 'user', 'items'],
        );

        return view('seller.orders.index', compact('orders', 'type'));
    }

    public function details($invoice_id)
    {
        $order = $this->orderRepo->findByInvoiceId($invoice_id);
        if (! $order || get_seller_id() != $order->seller_id) {
            return redirect()->back();
        }

        $order->load(['review', 'items']);

        return view('seller.orders.details', compact('order'));
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

        $oldStatus = $order->status;
        if ($oldStatus->value == $request->new_status) {
            return redirect()->back()->with('success', 'Order status already '.$oldStatus->title());
        }

        $this->orderRepo->update($order, ['status' => $request->new_status]);
        $this->orderRepo->createStatusLog($order, [
            'old_status' => $oldStatus->value,
            'new_status' => $request->new_status,
            'changed_by' => 'seller',
            'remarks' => $request->remarks,
        ]);

        $order->addSellerEarningToBalance();

        if ($order->status->value == OrderStatus::COMPLETED->value) {
            $this->orderService->approveAffiliateCommission($order);
        }

        return redirect()->back()->with('success', 'Order updated successfully');
    }

    public function posInvoice($invoice_id)
    {
        $order = $this->orderRepo->findByInvoiceId($invoice_id);

        if ($order && get_seller_id() == $order->seller_id) {
            return view('seller.orders.pos_invoice', compact('order'));
        }

        return redirect()->back();
    }
}
