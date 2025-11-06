@extends('seller.layouts.app')
@section('title', 'Payment Listener Payments')

@section('content')

<h4 class="mb-2">Payments</h4>

<div class="table-responsive">
    <table class="table table-sm align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Gateway</th>
                <th>Amount</th>
                <th>Sender</th>
                <th>TrxID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                <td>{{ $payment->sender }}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $payments->links() }}

@endsection