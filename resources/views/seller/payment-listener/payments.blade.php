@extends('seller.layouts.app')
@section('title', 'Payment Listener Payments')

@section('content')

<h4 class="font-bold mb-3 text-ink">Payments</h4>

<div class="overflow-x-auto">
    <table class="w-full text-left text-sm text-ink border-collapse">
        <thead class="bg-surface-muted">
            <tr>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">Date</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">Sender</th>
                <th scope="col" class="text-sm font-semibold text-ink-tertiary">SMS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $payment)
            <tr>
                <td>{{ $payment->created_at->format('Y-m-d h:i A') }}</td>
                <td class="font-semibold">{{ $payment->sender }}</td>
                <td>{{ $payment->full_sms }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{ $payments->links() }}

@endsection