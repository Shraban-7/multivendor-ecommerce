@extends('admin.layouts.app')
@section('title', 'Orders')
@section('content')

    <h4 class="mb-3">Orders </h4>

    <div class="table-responsive">
        <table id="order-table" class="table table-bordered bg-white mb-3 text-nowrap">
            <thead>
                <tr>
                    <th scope="col">InvoiceId</th>
                    <th scope="col">Seller</th>
                    <th scope="col">Sale Amount</th>
                    <th scope="col">Commission </th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td> {{ $order->invoice_id }}</td>
                        <td> <x-seller :seller="$order->seller" /></td>
                        <td> {{ money($order->payable) }} </td>
                        <td>
                            @if ($order->total_commission != null)
                                {{  money( $order->total_commission ) }}
                                @if($order->commission_type == \App\Enums\CommissionType::PERCENTAGE->value)
                                    ({{ $order->commission_amount }} %)
                                @endif
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @push('scripts')
        <script>
            new DataTable('#order-table', {
                order: [
                    [0, 'desc']
                ]
            });
        </script>
    @endpush

@endsection
