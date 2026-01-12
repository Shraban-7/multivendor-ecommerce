@extends('seller.layouts.app')
@section('title', 'Sales')
@section('content')

    <div class="mb-2 rounded d-flex justify-content-between align-items-center">
        <h4 class="mb-0">POS Orders</h4>
        <button class="btn btn-sm btn-primary" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
            <i data-feather="filter" class="icon-xs"></i> Filter
        </button>
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
                    {{-- <th scope="col">Action</th> --}}
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
                                <p class="small text-muted mb-0">Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}
                                </p>
                            @endif
                        </td>
                        <td>
                            @if ($order->customer)
                                {{ $order->customer->name }} ({{ $order->customer->phone }})
                            @endif
                        </td>
                        <td> <span class="text-dark">{{ money($order->payable) }}</span> </td>
                        <td>
                            @if ($order->due > 0)
                                <span class="text-danger"> {{ money($order->due) }}</span>
                                <button class="btn btn-sm btn-light border pay-now-btn ms-1" data-id="{{ $order->id }}"
                                    data-due="{{ $order->due }}">
                                    Pay Due
                                </button>
                            @endif
                        </td>

                        <!-- <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                        </td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="d-flex justify-content-end">
            {{ $orders->links() }}
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Filter Orders</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <form action="{{ route('seller.pos.sales.index') }}" method="GET">
                <div class="mb-3">
                    <label for="invoice_id" class="form-label">Invoice ID</label>
                    <input type="text" class="form-control" id="invoice_id" name="invoice_id"
                        value="{{ request('invoice_id') }}">
                </div>
                <div class="mb-3">
                    <label for="customer_name" class="form-label">Customer Name</label>
                    <input type="text" class="form-control" id="customer_name" name="customer_name"
                        value="{{ request('customer_name') }}">
                </div>
                <div class="mb-3">
                    <label for="customer_phone" class="form-label">Customer Phone</label>
                    <input type="text" class="form-control" id="customer_phone" name="customer_phone"
                        value="{{ request('customer_phone') }}">
                </div>
                <div class="mb-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from"
                        value="{{ request('date_from') }}">
                </div>
                <div class="mb-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to"
                        value="{{ request('date_to') }}">
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('seller.pos.sales.index') }}" class="btn btn-outline-secondary w-100">Reset</a>
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>


    <div class="modal fade" id="payNowModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay Due</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="pay-amount" class="form-label">Enter Amount</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="pay-amount">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmPayBtn">Pay</button>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
        <script>
            let payOrderId = null;

            $(document).on('click', '.pay-now-btn', function() {
                payOrderId = $(this).data('id');
                let dueAmount = $(this).data('due');

                const modalEl = document.getElementById('payNowModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });

            $('#confirmPayBtn').on('click', function() {
                let amount = parseFloat($('#pay-amount').val());
                if (!amount || amount <= 0) {
                    showErrorToast("Please enter a valid amount");
                    return;
                }
                if (!payOrderId) return;

                let button = $(this);
                let originalText = button.text();
                button.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "{{ route('seller.pos.sales.pay', ':id') }}".replace(':id', payOrderId),
                    method: 'POST',
                    data: {
                        amount: amount,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        button.prop('disabled', false).text(originalText);

                        if (response.status) {
                            showSuccessToast(response.message || "Payment successful!");

                            const modalEl = document.getElementById('payNowModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();

                            $('#pay-amount').val('');

                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        } else {
                            showErrorToast(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON?.message || "Something went wrong";
                        showErrorToast(message);
                        button.prop('disabled', false).text(originalText);
                    }
                });
            });
        </script>
    @endpush

@endsection
