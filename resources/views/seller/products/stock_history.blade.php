@extends('seller.layouts.app')
@section('title', 'Stock History')

@section('content')
<div class="mb-2 d-flex justify-content-between align-items-end">
    <h4 class="mb-0">Stock History</h4>
    <a href="{{ route('seller.products.index') }}" class="btn btn-secondary btn-sm">
        <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Products
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-hover align-middle bg-white" id="stock-history-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Note</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stockHistories as $history)
            <tr>
                <td>
                    <p class="fw-bold mb-0">{{ $history->product->name }}</p>
                    <span class="small text-muted">{{ $history->variant->full_name ?? 'default' }}</span>
                </td>
                <td>
                    @switch($history->type)
                    @case(\App\Enums\StockType::ADD_STOCK)
                    <span class="badge bg-success">+{{ $history->quantity }} {{ $history->product->unit->short_name }}</span>
                    @break

                    @case(\App\Enums\StockType::REMOVE_STOCK)
                    <span class="badge bg-danger">-{{ $history->quantity }} {{ $history->product->unit->short_name }}</span>
                    @break

                    @case(\App\Enums\StockType::SET_EXACT_STOCK)
                    <span class="badge bg-warning text-dark">Adjusted: {{ $history->quantity }} {{ $history->product->unit->short_name }}</span>
                    @break
                    @endswitch
                </td>
                <td>{{ $history->note ?? '-' }}</td>
                <td>{{ $history->created_at->format('d/m/y, h:i A') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $stockHistories->links() }}

@endsection