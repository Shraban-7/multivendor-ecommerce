<?php

namespace App\Domain\Order\Http\Controllers\Api;

use App\Domain\Order\Models\Order;
use App\Domain\Order\Models\ReturnRequest;
use App\Domain\Order\Services\DisputeService;
use App\Domain\Order\Services\ReturnService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function __construct(
        private readonly ReturnService $returnService,
        private readonly DisputeService $disputeService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $returns = ReturnRequest::where('user_id', $request->user()->id)
            ->with(['order', 'items.orderItem', 'dispute', 'refundTransactions'])
            ->latest('id')
            ->paginate($request->integer('per_page', 15));

        return response()->json($returns);
    }

    public function show(Request $request, ReturnRequest $return): JsonResponse
    {
        if ($return->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $return->load(['order', 'items.orderItem.product', 'events', 'refundTransactions', 'shipments', 'dispute']);

        return response()->json($return);
    }

    public function store(Request $request): JsonResponse
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

        $order = Order::where('id', $validated['order_id'])
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        try {
            $return = $this->returnService->createReturnRequest(
                $order,
                (int) $request->user()->id,
                $validated['type'],
                $validated['reason'],
                $validated['exchange_note'] ?? null,
                $validated['items'] ?? [],
            );
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        $return->load(['items.orderItem', 'order']);

        return response()->json([
            'message' => 'Return submitted',
            'rma' => $return->rma_number,
            'return' => $return,
        ], 201);
    }

    public function cancel(Request $request, ReturnRequest $return): JsonResponse
    {
        if ($return->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        try {
            $this->returnService->cancel($return, 'customer', (int) $request->user()->id);
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Return cancelled']);
    }

    public function recordShipment(Request $request, ReturnRequest $return): JsonResponse
    {
        if ($return->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'carrier' => 'required|string|max:80',
            'tracking_number' => 'nullable|string|max:120',
            'notes' => 'nullable|string|max:1000',
        ]);

        $this->returnService->recordShipment(
            $return,
            $validated['carrier'],
            $validated['tracking_number'] ?? null,
            'customer',
            (int) $request->user()->id,
            $validated['notes'] ?? null,
        );

        return response()->json(['message' => 'Shipment recorded']);
    }

    public function dispute(Request $request, ReturnRequest $return): JsonResponse
    {
        if ($return->user_id !== $request->user()->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'reason' => 'required|string|max:2000',
            'description' => 'nullable|string|max:5000',
        ]);

        try {
            $this->disputeService->openDispute(
                $return,
                (int) $request->user()->id,
                $validated['reason'],
                $validated['description'] ?? null,
            );
        } catch (\Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return response()->json(['message' => 'Dispute submitted']);
    }
}
