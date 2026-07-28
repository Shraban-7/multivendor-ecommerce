@extends('seller.layouts.app')
@section('title', 'Payment Listener Payments')

@section('content')

<h4 class="fw-bold mb-3 text-dark">Payments</h4>

<div class="table-responsive">
    <table class="table table-bordered table-hover bg-white align-middle">
        <thead class="table-light">
            <tr>
                <th scope="col" class="small fw-semibold text-muted">Date</th>
                <th scope="col" class="small fw-semibold text-muted">Sender</th>
                <th scope="col" class="small fw-semibold text-muted">SMS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                <td class="fw-semibold">{{ $payment->sender }}</td>
                <td>{{ $payment->full_sms }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $payments->links() }}

@endsection
