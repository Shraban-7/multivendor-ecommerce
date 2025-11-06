@extends('seller.layouts.app')
@section('title', 'Payment Listener Payments')

@section('content')

<h4 class="mb-2">Payments</h4>

<div class="table-responsive">
    <table class="table bg-white table-sm align-middle">
        <thead>
            <tr>
                <th>Date</th>
                <th>Sender</th>
                <th>SMS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                <td>{{ $payment->sender }}</td>
                <td>{{ $payment->full_sms }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $payments->links() }}

@endsection