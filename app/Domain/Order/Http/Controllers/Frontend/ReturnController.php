<?php

namespace App\Domain\Order\Http\Controllers\Frontend;

use App\Domain\Order\Enums\OrderStatus;
use App\Domain\Order\Enums\ReturnType;
use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\OrderItem;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Models\ReturnRequestItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReturnController extends Controller
{
    public function index()
    {
        $returns = ReturnRequest::where('user_id', Auth::id())
            ->with('order', 'items.orderItem')
            ->latest()
            ->paginate(10);

        return view('frontend.returns.index', compact('returns'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'type' => 'required|in:full,partial,exchange',
            'reason' => 'required|string|max:2000',
            'exchange_note' => 'nullable|string|max:2000',
            'items' => 'nullable|array',
            'items.*.id' => 'required_with:items|exists:order_items,id',
            'items.*.quantity' => 'required_with:items|integer|min:1',
        ]);

        if (in_array($validated['type'], ['partial', 'exchange']) && empty($validated['items'])) {
            return back()->with('error', 'Please select at least one item to return.')->withInput();
        }

        if ($validated['type'] === 'exchange' && empty($validated['exchange_note'])) {
            return back()->with('error', 'Please describe what you want in exchange.')->withInput();
        }

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

        DB::transaction(function () use ($order, $validated) {
            $type = ReturnType::from($validated['type']);

            $data = [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'type' => $type->value,
                'reason' => $validated['reason'],
                'status' => 'pending',
            ];

            if ($type === ReturnType::EXCHANGE) {
                $data['exchange_note'] = $validated['exchange_note'];
            }

            $returnRequest = ReturnRequest::create($data);

            if ($type === ReturnType::FULL) {
                foreach ($order->items as $item) {
                    ReturnRequestItem::create([
                        'return_request_id' => $returnRequest->id,
                        'order_item_id' => $item->id,
                        'quantity' => $item->quantity,
                        'refund_amount' => $item->total,
                    ]);
                }
            } else {
                foreach ($validated['items'] as $raw) {
                    $item = OrderItem::where('id', $raw['id'])
                        ->where('order_id', $order->id)
                        ->firstOrFail();

                    $qty = min((int) $raw['quantity'], $item->quantity);
                    $refundAmount = ($item->total / $item->quantity) * $qty;

                    ReturnRequestItem::create([
                        'return_request_id' => $returnRequest->id,
                        'order_item_id' => $item->id,
                        'quantity' => $qty,
                        'refund_amount' => $refundAmount,
                    ]);
                }
            }

            $order->update(['status' => OrderStatus::RETURN_REQUESTED->value]);
        });

        return redirect()->route('returns.index')->with('success', 'Return request submitted successfully.');
    }
}

