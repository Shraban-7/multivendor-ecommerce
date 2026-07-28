<?php

namespace App\Domain\Vendor\Http\Controllers\Seller;

use App\Domain\Vendor\Models\SellerPayout;
use App\Domain\Vendor\Models\SellerPayoutMethod;
use App\Domain\Vendor\Services\PayoutService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SellerPayoutController extends Controller
{
    public function __construct(
        private readonly PayoutService $payoutService,
    ) {}

    public function index(Request $request)
    {
        $seller = seller();
        $payouts = SellerPayout::where('seller_id', $seller->id)
            ->with('payoutMethod')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $availableBalance = $this->payoutService->getAvailableBalance($seller);
        $pendingBalance = $this->payoutService->getPendingBalance($seller);
        $totalWithdrawn = $this->payoutService->getTotalWithdrawn($seller);
        $pendingEarnings = $this->payoutService->getPendingEarnings($seller);

        $statusFilter = $request->get('status');

        if ($statusFilter !== null) {
            $payouts = SellerPayout::where('seller_id', $seller->id)
                ->where('status', (int) $statusFilter)
                ->with('payoutMethod')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('seller.payouts.index', compact(
            'payouts',
            'availableBalance',
            'pendingBalance',
            'totalWithdrawn',
            'pendingEarnings',
            'statusFilter'
        ));
    }

    public function create()
    {
        $seller = seller();
        $availableBalance = $this->payoutService->getAvailableBalance($seller);
        $methods = SellerPayoutMethod::where('seller_id', $seller->id)->get();

        return view('seller.payouts.create', compact('availableBalance', 'methods'));
    }

    public function store(Request $request)
    {
        $seller = seller();

        $data = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:'.$seller->balance,
            ],
            'payout_method_id' => 'required|exists:seller_payout_methods,id,seller_id,'.$seller->id,
            'seller_note' => 'nullable|string|max:500',
        ]);

        try {
            $payout = $this->payoutService->requestPayout($seller, $data);

            return redirect()->route('seller.payouts.index')
                ->with('success', 'Payout request submitted successfully.');
        } catch (\InvalidArgumentException $e) {
            return back()->withErrors(['amount' => $e->getMessage()])->withInput();
        }
    }

    public function show(SellerPayout $payout)
    {
        $seller = seller();
        if ($payout->seller_id !== $seller->id) {
            abort(403);
        }

        $payout->load('payoutMethod');

        return view('seller.payouts.show', compact('payout'));
    }

    public function methods()
    {
        $seller = seller();
        $methods = SellerPayoutMethod::where('seller_id', $seller->id)->get();

        return view('seller.payouts.methods', compact('methods'));
    }

    public function storeMethod(Request $request)
    {
        $seller = seller();

        $data = $request->validate([
            'method_type' => 'required|in:bank,mobile_banking,cash',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'routing_number' => 'nullable|string|max:50',
            'mobile_provider' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        $data['seller_id'] = $seller->id;

        if (! empty($data['is_default'])) {
            SellerPayoutMethod::where('seller_id', $seller->id)->update(['is_default' => false]);
        }

        SellerPayoutMethod::create($data);

        return redirect()->route('seller.payouts.methods.index')
            ->with('success', 'Payout method added successfully.');
    }

    public function updateMethod(Request $request, SellerPayoutMethod $method)
    {
        $seller = seller();
        if ($method->seller_id !== $seller->id) {
            abort(403);
        }

        $data = $request->validate([
            'method_type' => 'required|in:bank,mobile_banking,cash',
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:100',
            'bank_name' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
            'routing_number' => 'nullable|string|max:50',
            'mobile_provider' => 'nullable|string|max:50',
            'is_default' => 'nullable|boolean',
        ]);

        if (! empty($data['is_default'])) {
            SellerPayoutMethod::where('seller_id', $seller->id)->where('id', '!=', $method->id)->update(['is_default' => false]);
        }

        $method->update($data);

        return redirect()->route('seller.payouts.methods.index')
            ->with('success', 'Payout method updated successfully.');
    }

    public function destroyMethod(SellerPayoutMethod $method)
    {
        $seller = seller();
        if ($method->seller_id !== $seller->id) {
            abort(403);
        }

        $method->delete();

        return redirect()->route('seller.payouts.methods.index')
            ->with('success', 'Payout method deleted.');
    }

    public function setDefaultMethod(SellerPayoutMethod $method)
    {
        $seller = seller();
        if ($method->seller_id !== $seller->id) {
            abort(403);
        }

        SellerPayoutMethod::where('seller_id', $seller->id)->update(['is_default' => false]);
        $method->update(['is_default' => true]);

        return redirect()->route('seller.payouts.methods.index')
            ->with('success', 'Default payout method updated.');
    }
}
