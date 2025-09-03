@extends('admin.layouts.app')
@section('title', 'Payments')

@section('content')
    <?php use App\Models\Payment; ?>
    <div class="d-flex justify-content-between align-items-end mb-3">
        <h4 class="mb-4">Payments</h4>
        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="offcanvas" data-bs-target="#userFilterCanvas">
            <i class="bi bi-funnel"></i> Filter
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
                    <label for="user_name" class="form-label">Customer Name</label>
                    <input type="text" name="user_name" id="user_name" value="{{ request('user_name') }}"
                        class="form-control" placeholder="Enter customer name">
                </div>

                <div class="mb-3">
                    <label for="user_phone" class="form-label">Customer Phone</label>
                    <input type="text" name="user_phone" id="user_phone" value="{{ request('user_phone') }}"
                        class="form-control" placeholder="Enter customer phone">
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table id="payment-table" class="table table-hover table-bordered align-middle text-nowrap bg-white">
            <thead class="table-white">
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
            <tbody class="table-white">
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
                                <span class="badge bg-success">Paid</span>
                            @elseif ($payment->status === Payment::PENDING)
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif ($payment->status === Payment::FAILED)
                                <span class="badge bg-danger">Failed</span>
                            @endif
                        </td>
                        <td>{{ ucfirst($payment->gateway) }}</td>
                        <td>{{ $payment->created_at->format('d-m-Y h:i A') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {!! $payments->links() !!}
    </div>
@endsection
