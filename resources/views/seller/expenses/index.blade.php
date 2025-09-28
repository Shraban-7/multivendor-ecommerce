@extends('seller.layouts.app')
@section('title', 'Expenses')
@section('content')

    <div class="mb-2 rounded d-flex justify-content-between align-items-center">
        <h4 class="mb-0"> Expenses </h4>
    </div>

    <div class="table-responsive">
        <table id="order-table" class="table table-bordered bg-white mb-3 text-nowrap">
            <thead>
                <tr>
                    <th scope="col"># Order ID</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Due</th>
                    <th scope="col">Commission</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" target="__blank">#
                                {{ $order->invoice_id }}
                            </a>
                        </td>
                        <td>
                            {{ $order->created_at->format('d/m/Y h:i A') }}
                            @if ($order->created_at != $order->updated_at)
                                <p class="small text-muted mb-0">Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}</p>
                            @endif
                        </td>
                        <td> {{ $order->user->name }} </td>
                        <td> <span class="text-dark">{{ money($order->payable) }}</span> </td>
                        <td> <span class="text-danger"> {{ money($order->due) }}</span> </td>
                        <td>
                            @if ($order->total_commission != null)
                                {{ money($order->total_commission) }}
                                @if ($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    ({{ $order->commission_amount }} %)
                                @endif
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                            <a href="{{ route('invoice', $order->invoice_id) }}" title="Details" target="__blank"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="download" class="icon-xs"></i> Invoice
                            </a>
                            {{-- <a href="{{ route('receipt', $order->invoice_id) }}" title="Details" target="__blank"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="download" class="icon-xs"></i> Receipt
                            </a> --}}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="d-flex justify-content-end">
            {{ $orders->links() }}
        </div>
    </div>

@endsection
