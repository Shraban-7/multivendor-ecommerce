@extends('seller.layouts.app')
@section('title', 'Orders')
@section('content')

    <div class="mb-2 rounded">
        <h4 class="mb-0">Pos Orders </h4>
    </div>

    <div class="table-responsive">
        <table id="order-table" class="table table-bordered bg-white mb-3 text-nowrap">
            <thead>
                <tr>
                    <th scope="col">OrderId</th>
                    <th scope="col">Date</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Subtotal</th>
                    <th scope="col">Due</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td> {{ $order->invoice_id }}</td>
                        <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d-m-Y h:i A') }}</td>
                        <td> {{ $order->user->name ?? '' }} </td>
                        <td> <span class="text-dark">{{ money($order->payable) }}</span> </td>
                        <td>
                            <span class="text-danger"> {{ money($order->due) }}</span>

                            <button class="btn btn-sm btn-light border pay-now-btn" data-id="{{ $order->id }}"
                                data-due="{{ $order->due }}">
                                Pay Now
                            </button>
                        </td>

                        <td>
                            <a href="{{ route('seller.orders.details', $order->invoice_id) }}" title="Details"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="clipboard" class="icon-xs"></i> Details
                            </a>
                            <a href="{{ route('seller.pos.index', ['order_id' => $order->id]) }}"
                                class="btn btn-light border btn-sm me-1" title="Details">
                                <i data-feather="edit" class="icon-xs"></i> Edit
                            </a>
                            <a href="{{ route('invoice', $order->invoice_id) }}" title="Details" target="__blank"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="download" class="icon-xs"></i> Invoice
                            </a>
                            <a href="{{ route('receipt', $order->invoice_id) }}" title="Details" target="__blank"
                                class="btn btn-light border btn-sm me-1">
                                <i data-feather="printer" class="icon-xs"></i> Receipt
                            </a>
                            <button class="btn btn-sm text-danger border delete-cart-item-btn"
                                data-id="{{ $order->id }}" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                                <i data-feather="trash-2" class="icon-xs"></i> Delete
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to remove this order?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="payNowModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pay Now</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label for="pay-amount" class="form-label">Enter Amount</label>
                        <input type="number" min="0" step="0.01" class="form-control" id="pay-amount"
                            placeholder="Enter amount">
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
            new DataTable('#order-table', {
                order: [
                    [0, 'desc']
                ]
            });

            let deleteOrderId = null;

            $(document).on('click', '.delete-cart-item-btn', function() {
                deleteOrderId = $(this).data('id');
            });

            let payOrderId = null;

            $(document).on('click', '.pay-now-btn', function() {
                payOrderId = $(this).data('id');
                let dueAmount = $(this).data('due');

                $('#pay-amount').val(dueAmount);

                const modalEl = document.getElementById('payNowModal');
                const modal = new bootstrap.Modal(modalEl);
                modal.show();
            });

            $('#confirmPayBtn').on('click', function() {
                let amount = parseFloat($('#pay-amount').val());
                if (!amount || amount <= 0) {
                    toastr.error("Please enter a valid amount");
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
                            toastr.success(response.message || "Payment successful!");

                            const modalEl = document.getElementById('payNowModal');
                            const modal = bootstrap.Modal.getInstance(modalEl);
                            modal.hide();

                            $('#pay-amount').val('');

                            setTimeout(() => {
                                location.reload();
                            }, 500);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        var message = xhr.responseJSON?.message || "Something went wrong";
                        toastr.error(message);
                        button.prop('disabled', false).text(originalText);
                    }
                });
            });
        </script>
    @endpush

@endsection
