@extends('seller.layouts.app')
@section('title', 'Orders')
@section('content')

<div class="mb-2 rounded">
    <h4 class="mb-0">{{ ucfirst($type) }} Orders </h4>
</div>

<div class="table-responsive">
    <table class="table table-bordered bg-white mb-3 text-nowrap">
        <thead>
            <tr>
                <th scope="col">OrderId</th>
                <th scope="col">Date</th>
                <th scope="col">Customer</th>
                <th scope="col">Subtotal</th>
                <th scope="col">Due</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
            <tr>
                <td> {{ $order->id }}</td>
                <td></td>
                <td> {{ $order->user->fullname }} </td>
                <td> <span class="text-dark">{{ $order->payable }}</span> </td>
                <td> <span class="text-danger"> {{ $order->due }}</span> </td>

                <td>
                    @if ( $order->status->label() === 'pending' )
                    <span class="badge bg-warning">
                        Pending
                    </span>
                    @elseif ($order->status->label() === 'shipped')
                    <span class="badge bg-primary">
                        Shipped
                    </span>
                    @elseif ($order->status->label() === 'cancelled')
                    <span class="badge bg-danger">
                        Cancelled
                    </span>
                    @elseif ($order->status->label() === 'delivered')
                    <span class="badge bg-success">
                        Delivered
                    </span>
                    @endif
                </td>
                <td>
                    <a href="" title="Details" class="btn btn-light border btn-sm me-1">
                        <i data-feather="clipboard" class="icon-xs"></i> Details
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>


@endsection