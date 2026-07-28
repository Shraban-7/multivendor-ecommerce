<?php

namespace App\Domain\Vendor\Http\Controllers\Admin;

use App\Domain\Vendor\Models\SellerPayout;
use App\Domain\Vendor\Services\PayoutService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminPayoutController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService,
    ) {}

    public function index(Request $request)
    {
        $query = SellerPayout::with('seller', 'payoutMethod');

        if ($request->filled('status')) {
            $query->where('status', (int) $request->status);
        }

        if ($request->filled('seller_id')) {
            $query->where('seller_id', (int) $request->seller_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(30);

        $stats = [
            'pending' => SellerPayout::where('status', SellerPayout::STATUS_PENDING)->sum('amount'),
            'processing' => SellerPayout::where('status', SellerPayout::STATUS_PROCESSING)->sum('amount'),
            'completed' => SellerPayout::where('status', SellerPayout::STATUS_COMPLETED)->sum('amount'),
            'total_pending_count' => SellerPayout::where('status', SellerPayout::STATUS_PENDING)->count(),
        ];

        return view('admin.payouts.index', compact('payouts', 'stats'));
    }

    public function show(SellerPayout $payout)
    {
        $payout->load('seller', 'payoutMethod', 'processedBy');
        return view('admin.payouts.show', compact('payout'));
    }

    public function approve(SellerPayout $payout)
    {
        if (!$payout->isPending()) {
            return back()->with('error', 'Only pending payouts can be approved.');
        }

        $this->payoutService->approve($payout, admin()->id);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout approved and moved to processing.');
    }

    public function complete(Request $request, SellerPayout $payout)
    {
        if (!$payout->isProcessing()) {
            return back()->with('error', 'Only processing payouts can be completed.');
        }

        $data = $request->validate([
            'transaction_id' => 'nullable|string|max:255',
        ]);

        $this->payoutService->complete($payout, admin()->id, $data['transaction_id'] ?? null);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout marked as completed.');
    }

    public function cancel(Request $request, SellerPayout $payout)
    {
        if ($payout->isCompleted()) {
            return back()->with('error', 'Completed payouts cannot be cancelled.');
        }

        $data = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $this->payoutService->cancel($payout, admin()->id, $data['admin_note'] ?? null);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout cancelled and amount returned to seller.');
    }

    public function markFailed(Request $request, SellerPayout $payout)
    {
        if ($payout->isCompleted()) {
            return back()->with('error', 'Completed payouts cannot be marked as failed.');
        }

        $data = $request->validate([
            'admin_note' => 'nullable|string|max:1000',
        ]);

        $this->payoutService->markFailed($payout, admin()->id, $data['admin_note'] ?? null);

        return redirect()->route('admin.payouts.show', $payout)
            ->with('success', 'Payout marked as failed and amount returned to seller.');
    }
}
