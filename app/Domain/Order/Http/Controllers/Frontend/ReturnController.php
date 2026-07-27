<?php

namespace App\Domain\Order\Http\Controllers\Frontend;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::where('user_id', Auth::id())
            ->with('order')
            ->latest()
            ->paginate(10);

        return view('frontend.returns.index', compact('returns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'reason' => 'required|string|max:2000',
        ]);

        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($order->status->value !== OrderStatus::DELIVERED->value) {
            return back()->with('error', 'You can only request a return for delivered orders.');
        }

        $existing = ReturnRequest::where('order_id', $order->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'A return request for this order is already pending or approved.');
        }

        ReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        $order->update(['status' => OrderStatus::RETURN_REQUESTED->value]);

        return redirect()->route('returns.index')->with('success', 'Return request submitted successfully.');
    }
}
