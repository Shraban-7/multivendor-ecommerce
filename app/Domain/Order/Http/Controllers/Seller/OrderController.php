<?php

namespace App\Domain\Order\Http\Controllers\Seller;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Repositories\Contracts\OrderRepositoryInterface;
use App\Domain\Order\Services\OrderService;
use App\Domain\Product\Models\Product;
use App\Domain\Product\Models\ProductVariant;
use App\Domain\Product\Services\StockManagerService;
use App\Domain\Vendor\Models\Seller;
use App\Domain\Vendor\Models\VendorTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    private const VALID_TRANSITIONS = [
        0 => [1, 5],       // PENDING → ACCEPTED, CANCELLED
        1 => [2, 5],       // ACCEPTED → SHIPPED, CANCELLED
        2 => [3, 5],       // SHIPPED → DELIVERED, CANCELLED
        3 => [4, 6],       // DELIVERED → COMPLETED, RETURN_REQUESTED
        5 => [],           // CANCELLED → terminal
        6 => [7],          // RETURN_REQUESTED → RETURN_APPROVED
        7 => [8],          // RETURN_APPROVED → RETURNED
        8 => [9],          // RETURNED → REFUNDED
        9 => [],           // REFUNDED → terminal
    ];

    public function __construct(
        private readonly OrderRepositoryInterface $orderRepo,
        private readonly OrderService $orderService,
        private readonly StockManagerService $stockManager,
    ) {}

    public function index(Request $request)
    {
        $type = $request->segment(3);
        $statusValue = $type ? OrderStatus::valueFromLabel($type) : null;

        if ($type && $statusValue === null) {
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

        $order->load(['review', 'items', 'trackings.carrier', 'billing_address', 'payment']);

        return view('seller.orders.details', compact('order'));
    }

    public function updateStatus(Order $order, Request $request)
    {
        if ($order->seller_id !== get_seller_id()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        if ($order->status->value == OrderStatus::COMPLETED->value) {
            return redirect()->back()->with('error', 'Completed orders cannot be updated.');
        }

        $request->validate([
            'new_status' => 'required|integer|in:0,1,2,3,4,5,6,7,8,9',
            'remarks' => 'nullable|string',
        ]);

        $oldStatusValue = $order->status->value;
        $newStatusValue = (int) $request->new_status;

        if ($oldStatusValue == $newStatusValue) {
            return redirect()->back()->with('success', 'Order status already '.$order->status->title());
        }

        $allowed = self::VALID_TRANSITIONS[$oldStatusValue] ?? [];
        if (! in_array($newStatusValue, $allowed)) {
            return redirect()->back()->with('error', 'Invalid status transition from '.$order->status->title().'.');
        }

        $isCancelling = $newStatusValue === OrderStatus::CANCELLED->value;

        $this->orderRepo->update($order, ['status' => $newStatusValue]);
        $this->orderRepo->createStatusLog($order, [
            'old_status' => $oldStatusValue,
            'new_status' => $newStatusValue,
            'changed_by' => 'seller',
            'remarks' => $request->remarks,
        ]);

        $order->refresh();

        if ($isCancelling) {
            $this->restoreOrderStock($order);
        }

        $order->addSellerEarningToBalance();

        if ($order->status->value == OrderStatus::COMPLETED->value) {
            $this->orderService->approveAffiliateCommission($order);
        }

        if ($order->status->value == OrderStatus::RETURN_APPROVED->value) {
            ReturnRequest::where('order_id', $order->id)
                ->where('status', 'pending')
                ->update(['status' => 'approved', 'approved_at' => now()]);
        }

        if (in_array($order->status->value, [OrderStatus::RETURNED->value, OrderStatus::REFUNDED->value])) {
            $returnRequest = ReturnRequest::where('order_id', $order->id)->first();
            if ($returnRequest && $returnRequest->status !== 'refunded') {
                $refundAmount = $returnRequest->items()->sum('refund_amount');
                $refundAmount = $refundAmount ?: $order->payable;
                $returnRequest->update(['status' => 'refunded', 'refunded_at' => now()]);
                $order->update(['refund_amount' => $refundAmount]);

                if ($order->seller_earning_added) {
                    $seller = Seller::find($order->seller_id);
                    if ($seller) {
                        $balanceBefore = (float) $seller->balance;
                        $seller->decrement('balance', (float) $order->seller_earnings);

                        VendorTransaction::record(
                            $seller,
                            VendorTransaction::TYPE_REFUND,
                            -(float) $order->seller_earnings,
                            $balanceBefore,
                            $order,
                            "Refund for order #{$order->invoice_id} — deducted {$order->seller_earnings}",
                        );
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Order status updated to '.$order->status->title());
    }

    private function restoreOrderStock(Order $order): void
    {
        $order->loadMissing('items');

        foreach ($order->items as $item) {
            $quantity = (int) $item->quantity;
            if ($quantity <= 0) {
                continue;
            }

            $variant = null;
            $product = null;

            if (! empty($item->product_variant_id)) {
                $variant = ProductVariant::find($item->product_variant_id);
                $product = $variant?->product ?? Product::find($item->product_id);
            } else {
                $product = Product::find($item->product_id);
            }

            if (! $product) {
                continue;
            }

            $note = 'Restored: Order cancelled #'.($order->invoice_id ?? $order->id);
            $this->stockManager->restoreStock($product, $variant, $quantity, $note);
        }
    }
}
