<?php

namespace App\Domain\Order\Http\Controllers\Admin;

use App\Domain\Order\Enums\DisputeResolution;
use App\Domain\Order\Enums\ReturnStatus;
use App\Domain\Order\Models\Dispute;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Services\DisputeService;
use App\Domain\Order\Services\ReturnService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturnManageController extends Controller
{
    public function __construct(
        private readonly ReturnService $returnService,
        private readonly DisputeService $disputeService,
    ) {}

    public function index(Request $request)
    {
        $query = ReturnRequest::with(['order', 'user', 'items.orderItem', 'dispute']);

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

        $returns = $query->latest('id')->paginate(20)->withQueryString();

        $counts = [
            'total' => ReturnRequest::count(),
            'pending' => ReturnRequest::where('status', ReturnStatus::PENDING->value)->count(),
            'awaiting_shipment' => ReturnRequest::where('status', ReturnStatus::AWAITING_SHIPMENT->value)->count(),
            'approved' => ReturnRequest::where('status', ReturnStatus::APPROVED->value)->count(),
            'rejected' => ReturnRequest::where('status', ReturnStatus::REJECTED->value)->count(),
            'refunded' => ReturnRequest::where('status', ReturnStatus::REFUNDED->value)->count(),
            'disputed' => ReturnRequest::disputed()->count(),
        ];

        return view('admin.returns.index', compact('returns', 'counts'));
    }

    public function show(ReturnRequest $return)
    {
        $return->load([
            'order.seller',
            'user',
            'items.orderItem.product',
            'dispute.raisedBy',
            'events',
            'refundTransactions',
        ]);

        return view('admin.returns.show', compact('return'));
    }

    public function approve(Request $request, ReturnRequest $return)
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:2000',
        ]);

        try {
            $this->returnService->approve($return, 'admin', auth()->id(), $validated['note'] ?? null);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Return approved.');
    }

    public function reject(Request $request, ReturnRequest $return)
    {
        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:2000',
        ]);

        try {
            $this->returnService->reject($return, 'admin', auth()->id(), $validated['rejection_reason']);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Return rejected.');
    }

    public function resolveDispute(Request $request, Dispute $dispute)
    {
        $validated = $request->validate([
            'resolution' => 'required|in:approved,rejected,partial_refund,wallet_credit',
            'admin_note' => 'nullable|string|max:2000',
            'resolution_amount' => 'nullable|numeric|min:0',
        ]);

        try {
            $this->disputeService->resolve(
                $dispute,
                DisputeResolution::from($validated['resolution']),
                auth()->id(),
                $validated['admin_note'] ?? null,
                $validated['resolution_amount'] ?? null,
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Dispute resolved.');
    }

    public function markReceived(Request $request, ReturnRequest $return)
    {
        $validated = $request->validate([
            'note' => 'nullable|string|max:2000',
        ]);

        try {
            $this->returnService->markItemReceived($return, 'admin', auth()->id(), $validated['note'] ?? null);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Return marked as received.');
    }

    public function cancel(Request $request, ReturnRequest $return)
    {
        $validated = $request->validate([
            'reason' => 'nullable|string|max:2000',
        ]);

        try {
            $this->returnService->cancel($return, 'admin', auth()->id(), $validated['reason'] ?? null);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Return cancelled.');
    }
}
