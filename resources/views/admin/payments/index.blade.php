@extends('admin.layouts.app')
@section('title', 'Payments')

@section('content')
    <?php use App\Domain\Payment\Models\Payment; ?>
    <div class="flex justify-between items-end mb-3">
        <h4 class="mb-4">Payments</h4>
        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#userFilterCanvas">
            <i data-lucide="funnel"></i> Filter
        </button>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="userFilterCanvas" aria-labelledby="userFilterCanvasLabel">
        <div class="offcanvas-header">
            <h5 id="userFilterCanvasLabel">Filter Payments</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form method="GET" action="{{ route('admin.payments.index') }}">
                <div class="mb-3">
                    <label for="user_name" class="block text-xs font-medium text-ink-secondary mb-1">Customer Name</label>
                    <input type="text" name="user_name" id="user_name" value="{{ request('user_name') }}"
                        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Enter customer name">
                </div>

                <div class="mb-3">
                    <label for="user_phone" class="block text-xs font-medium text-ink-secondary mb-1">Customer Phone</label>
                    <input type="text" name="user_phone" id="user_phone" value="{{ request('user_phone') }}"
                        class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Enter customer phone">
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-light">Reset</a>
                    <button type="submit" class="btn btn-primary">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table id="payment-table" class="w-full text-left text-sm text-ink border-collapse">
            <thead class="bg-white">
                <tr>
                    <th scope="col">Transaction ID</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Details</th>
                    <th scope="col">Amount</th>
                    <th scope="col">Status</th>
                    <th scope="col">Gateway</th>
                    <th scope="col">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($payments as $payment)
                    <tr>
                        <td>{{ $payment->transaction_id }}</td>
                        <td>
                            @if ($payment->user)
                                <x-user :user="$payment->user" />
                            @endif
                        </td>
                        <td>
                            @if ($payment->customer_name)
                                <div><strong>Name:</strong> {{ $payment->customer_name }}</div>
                            @endif

                            @if ($payment->customer_email)
                                <div><strong>Email:</strong> {{ $payment->customer_email }}</div>
                            @endif

                            @if ($payment->customer_phone)
                                <div><strong>Phone:</strong> {{ $payment->customer_phone }}</div>
                            @endif
                        </td>

                        <td>{{ money($payment->amount, $payment->currency) }}</td>
                        <td>
                            @if ($payment->status === Payment::SUCCESSFUL)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-feedback-success text-white">Paid</span>
                            @elseif ($payment->status === Payment::PENDING)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-feedback-warning text-white">Pending</span>
                            @elseif ($payment->status === Payment::FAILED)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-feedback-danger text-white">Failed</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($payment->gateway) }}</td>
                        <td>{{ $payment->created_at->format('d-m-Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="flex justify-end mt-3">
        {!! $payments->links() !!}
    </div>
@endsection
