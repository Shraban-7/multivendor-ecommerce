<?php

namespace App\Domain\Order\Http\Controllers\Seller;

use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Models\Dispute;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Services\DisputeService;
use App\Domain\Order\Services\ReturnService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function __construct(
        private readonly ReturnService $returnService,
        private readonly DisputeService $disputeService,
    ) {}

    public function index(Request $request)
    {
        $sellerId = get_seller_id();

        $query = ReturnRequest::query()
            ->with(['order', 'user', 'items.orderItem.product', 'dispute'])
            ->forSeller($sellerId)
            ->latest('id');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('rma_number', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($o) => $o->where('invoice_id', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }
        if ($request->boolean('disputed')) {
            $query->disputed();
        }

        $returns = $query->paginate(20)->withQueryString();

        $counts = [
            'total' => ReturnRequest::forSeller($sellerId)->count(),
            'pending' => ReturnRequest::forSeller($sellerId)->where('status', ReturnStatus::PENDING->value)->count(),
            'awaiting_shipment' => ReturnRequest::forSeller($sellerId)->where('status', ReturnStatus::AWAITING_SHIPMENT->value)->count(),
            'approved' => ReturnRequest::forSeller($sellerId)->where('status', ReturnStatus::APPROVED->value)->count(),
            'received' => ReturnRequest::forSeller($sellerId)->where('status', ReturnStatus::ITEM_RECEIVED->value)->count(),
            'refunded' => ReturnRequest::forSeller($sellerId)->where('status', ReturnStatus::REFUNDED->value)->count(),
            'rejected' => ReturnRequest::forSeller($sellerId)->where('status', ReturnStatus::REJECTED->value)->count(),
            'disputed' => ReturnRequest::forSeller($sellerId)->disputed()->count(),
        ];

        return view('seller.returns.index', compact('returns', 'counts'));
    }

    public function show(ReturnRequest $return)
    {
        if ($return->order->seller_id !== get_seller_id()) {
            abort(403);
        }

        $return->load([
            'order.items',
            'user',
            'items.orderItem.product',
            'dispute.raisedBy',
            'events',
            'refundTransactions',
            'shipments',
        ]);

        return view('seller.returns.show', compact('return'));
    }

    public function approve(Request $request, ReturnRequest $return)
    {
        if ($return->order->seller_id !== get_seller_id()) {
            abort(403);
        }

        $validated = $request->validate([
            'note' => 'nullable|string|max:2000',
        ]);

        try {
            $this->returnService->approve($return, 'seller', get_seller_id(), $validated['note'] ?? null);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Return approved.');
    }

    public function reject(Request $request, ReturnRequest $return)
    {
        if ($return->order->seller_id !== get_seller_id()) {
            abort(403);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        try {
            $this->returnService->reject($return, 'seller', get_seller_id(), $validated['rejection_reason']);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Return rejected.');
    }

    public function recordShipment(Request $request, ReturnRequest $return)
    {
        if ($return->order->seller_id !== get_seller_id()) {
            abort(403);
        }

        $validated = $request->validate([
            'carrier' => 'required|string|max:80',
            'tracking_number' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // Tracking info supplied by seller for the exchange leg (return receipt tracking also allowed)
            $shipment = $this->returnService->recordShipment(
                $return,
                $validated['carrier'],
                $validated['tracking_number'] ?? null,
                'seller',
                get_seller_id(),
                $validated['notes'] ?? null,
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Shipment recorded.');
    }

    public function markReceived(Request $request, ReturnRequest $return)
    {
        if ($return->order->seller_id !== get_seller_id()) {
            abort(403);
        }

        $validated = $request->validate([
            'note' => 'nullable|string|max:2000',
        ]);

        try {
            $this->returnService->markItemReceived($return, 'seller', get_seller_id(), $validated['note'] ?? null);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Item marked as received. Stock restored.');
    }

    public function disputeRespond(Request $request, Dispute $dispute)
    {
        $return = $dispute->returnRequest;
        if ($return->order->seller_id !== get_seller_id()) {
            abort(403);
        }

        $validated = $request->validate([
            'response' => 'required|string|max:5000',
        ]);

        try {
            $this->disputeService->sellerRespond($dispute, $validated['response'], get_seller_id());
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Response submitted to admin.');
    }
}
