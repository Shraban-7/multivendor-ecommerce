@if ($orders->isEmpty())
    <p class="text-center text-muted">No sales today.</p>
@else
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th>Invoice Id</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Time</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $index => $order)
                <tr>
                    <td>{{ $order->invoice_id }}</td>
                    <td>{{ $order->customer->name ?? '' }}</td>
                    <td>{{ money($order->total) }}</td>
                    <td>{{ $order->created_at->format('h:i A') }}</td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}"
                                class="btn btn-light border btn-sm">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                            <a href="{{ route('seller.pos.index', ['order_id' => $order->id]) }}"
                                class="btn btn-light border btn-sm">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </a>
                            <a href="{{ route('invoice', $order->invoice_id) }}" target="_blank"
                                class="btn btn-light border btn-sm">
                                <i data-feather="download" class="icon-xs"></i> Invoice
                            </a>
                            <a href="{{ route('receipt', $order->invoice_id) }}" target="_blank"
                                class="btn btn-light border btn-sm">
                                <i data-feather="printer" class="icon-xs"></i> Receipt
                            </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
