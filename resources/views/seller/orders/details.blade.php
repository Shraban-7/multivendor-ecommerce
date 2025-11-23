@extends('seller.layouts.app')
@section('title', 'Order Details | ' . $order->invoice_id)
@section('content')

    <?php
    $isPos = is_null($order->user_id) ? true : false;
    ?>

    <div class="mb-2">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Order Details</h4>
            @if ($isPos)
                <a href="{{ route('seller.pos.index', ['order_id' => $order->invoice_id]) }}"
                    class="btn btn-primary border btn-sm me-1" title="Details">
                    <i data-feather="edit" class="icon-xs"></i> Edit Order
                </a>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Summary</h5>
                    <div class="d-flex">
                        @if ($isPos)
                            <button type="button" class="btn btn-light border btn-sm me-1"
                                onclick="printReceipt('{{ route('receipt', $order->invoice_id) }}')">
                                <i data-feather="printer" class="icon-xs"></i> Receipt
                            </button>
                        @endif
                        <button type="button" class="btn btn-light border btn-sm me-1"
                            onclick="printReceipt('{{ route('invoice', $order->invoice_id) }}')">
                            <i data-feather="download" class="icon-xs"></i>Invoice
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Invoice ID:</span>
                            <span class="fw-medium">{{ $order->invoice_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Date:</span>
                            <div class="text-end">
                                <span class="block">{{ $order->created_at->format('d/m/Y h:i A') }}</span>

                                @if ($order->created_at != $order->updated_at)
                                    <span class="small text-gray-500">
                                        Updated: {{ $order->updated_at->format('d/m/Y h:i A') }}
                                    </span>
                                @endif
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                            <span>Status:</span>
                            <div class="d-flex align-items-center gap-2">
                                @if ($order->status->label() === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif ($order->status->label() === 'accepted')
                                    <span class="badge bg-secondary">Accepted</span>
                                @elseif ($order->status->label() === 'shipped')
                                    <span class="badge bg-primary">Shipped</span>
                                @elseif ($order->status->label() === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @elseif ($order->status->label() === 'delivered')
                                    <span class="badge bg-success">Delivered</span>
                                @elseif ($order->status->label() === 'returned')
                                    <span class="badge bg-dark">Returned</span>
                                @elseif ($order->status->label() === 'refunded')
                                    <span class="badge bg-info text-dark">Refunded</span>
                                @elseif ($order->status->label() === 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @endif

                                @if ($order->user_id != null)
                                    <button class="btn btn-sm btn-light border d-flex align-items-center gap-1"
                                        data-bs-toggle="modal" data-bs-target="#changeStatusModal">
                                        <i class="bi bi-arrow-repeat text-secondary"></i>
                                        Update
                                    </button>
                                @endif
                            </div>
                        </li>

                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Method:</span>
                            <span class="fw-medium">{{ $order?->payment_method }}</span>
                        </li>
                        <li class="list-group-item d-flex align-items-center justify-content-between px-0">
                            <span>Payment Status:</span>
                            @if ($order->due == $order->payable)
                                <span class="badge bg-danger">Unpaid</span>
                            @elseif ($order->due > 0)
                                <span class="badge bg-warning">Partially Paid</span>
                            @else
                                <span class="badge bg-success">Paid</span>
                            @endif
                        </li>

                    </ul>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Customer Information</h5>
                </div>
                <div class="card-body">

                    @if ($order->user || $order->customer)
                        @php
                            $createdAt = null;

                            if ($order->user) {
                                $userName = $order->user->name;
                                $userEmail = $order->user->email;
                                $userPhone = $order->user->phone;

                                $createdAt = \Carbon\Carbon::parse($order->user->created_at)->format('M Y');
                            } elseif ($order->customer) {
                                $createdAt = \Carbon\Carbon::parse($order->customer->created_at)->format('M Y');
                                $userName = $order->customer->name;
                                $userEmail = '';
                                $userPhone = $order->customer->phone;
                            }
                        @endphp
                        <h6 class="fw-bold">{{ $userName }}</h6>
                        <p class="mb-1"><i data-feather="mail" class="icon-xs me-1"></i>
                            {{ $userEmail }}
                        </p>
                        <p class="mb-1"><i data-feather="phone" class="icon-xs me-1"></i>
                            {{ $userPhone }}
                        </p>
                        <p class="mb-0">
                            <i data-feather="user" class="icon-xs me-1"></i>
                            Customer since {{ $createdAt }}
                        </p>
                    @endif

                </div>
            </div>

            @if (!$isPos)
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Shipping Details</h5>
                    </div>
                    <div class="card-body">
                        <address class="mb-0">
                            <h6 class="fw-bold">{{ $order->customer_name }}</h6>
                            <p class="mb-1"><i data-feather="mail" class="icon-xs me-1"></i>
                                {{ $order->customer_email }}
                            </p>
                            <p class="mb-1"><i data-feather="phone" class="icon-xs me-1"></i>
                                {{ $order->customer_phone }}
                            </p>
                            <p class="mb-1"><i data-feather="home" class="icon-xs me-1"></i>
                                {{ $order->customer_address }}
                            </p>
                        </address>
                    </div>
                </div>
            @endif

            @if ($isPos)
                <div class="card-body">
                    <button class="btn btn-danger w-100 delete-cart-item-btn" data-id="{{ $order->id }}"
                        data-bs-toggle="modal" data-bs-target="#deleteConfirmModal">
                        Delete This Order
                    </button>
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
            @endif
        </div>

        <!-- Customer and Order Details -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-white">
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col" class="text-center">Price</th>
                                    <th scope="col" class="text-center">Discount</th>
                                    <th scope="col" class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $imageUrl = null;

                                                    if ($item->variant && $item->variant->image) {
                                                        $imageUrl = storage_url($item->variant->image);
                                                    } elseif (isset($item->product->thumbnail)) {
                                                        $imageUrl = storage_url($item->product->thumbnail);
                                                    }
                                                @endphp

                                                @if ($imageUrl)
                                                    <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}"
                                                        class="rounded me-3" width="60" height="60">
                                                @else
                                                    <div class="bg-white rounded me-3" style="width: 50px; height: 50px;">
                                                    </div>
                                                @endif

                                                <div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <h6 class="mb-0 me-2">{{ $item->product->name }}</h6>
                                                        <span class="badge bg-primary rounded-pill">x
                                                            {{ $item->quantity }}</span>
                                                    </div>

                                                    @if ($item->product_variant_id && $item->variant && $item->variant->option_values)
                                                        <div class="mt-1 small text-muted">
                                                            @foreach ($item->variant->option_values as $value)
                                                                <span class="me-2">
                                                                    <strong>{{ $value->option->name ?? '' }}:</strong>
                                                                    {{ $value->value }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if (isset($item->variant))
                                                        <small class="text-muted d-block">SKU:
                                                            {{ $item->variant->sku }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ money($item->selling_price) }}</td>
                                        <td class="text-center">{{ money($item->discount) }}</td>
                                        <td class="text-end">{{ money($item->total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-white">
                                <tr>
                                    <th colspan="3" class="text-end">Subtotal:</th>
                                    <td class="text-end"><span class="fw-bold">{{ money($order->sub_total) }}</span></td>
                                </tr>
                                @if (isset($order->discount) && $order->discount > 0)
                                    <tr>
                                        <th colspan="3" class="text-end">Discount:</th>
                                        <td class="text-end">-{{ money($order->discount) }}</td>
                                    </tr>
                                @endif
                                @if ($order->shipping_fee)
                                    <tr>
                                        <th colspan="3" class="text-end">Shipping:</th>
                                        <td class="text-end">{{ money($order->shipping_fee) }}</td>
                                    </tr>
                                @endif
                                @if (isset($order->vat_amount) && $order->vat_amount > 0)
                                    <tr>
                                        <th colspan="3" class="text-end">VAT:</th>
                                        <td class="text-end">{{ money($order->vat_amount) }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th colspan="3" class="text-end">Total:</th>
                                    <td class="text-end fw-bold">{{ money($order->total) }}</td>
                                </tr>
                                @if ($order->due > 0)
                                    <tr>
                                        <th colspan="3" class="text-end">Paid:</th>
                                        <td class="text-end">{{ money($order->paid) }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">Due:</th>
                                        <td class="text-end text-danger fw-bold">{{ money($order->due) }}</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            @if (!$isPos)
                @if ($order->review)
                    <div class="card border-0 shadow-sm mt-4">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Order Review</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span class="fw-medium">Rating:</span>
                                <span class="text-warning">
                                    @for ($i = 1; $i <= $order->review->rating; $i++)
                                        <i data-feather="star" class="text-warning"></i>
                                    @endfor
                                    @for ($i = $order->review->rating + 1; $i <= 5; $i++)
                                        <i data-feather="star" class="text-muted"></i>
                                    @endfor
                                </span>
                            </div>
                            <div class="mt-3">
                                <p class="mb-0 mt-2"><span
                                        class="fw-medium me-2">Review:</span>{{ $order->review->description }}</p>
                            </div>
                            <div class="text-muted mt-3">
                                <span>Reviewed on:
                                    {{ \Carbon\Carbon::parse($order->review->created_at)->format('d-m-Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-muted mb-0">No review provided.</p>
                @endif
            @endif
        </div>

        <!-- Change Status Modal -->
        <div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="changeStatusModalLabel">Update Order Status</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Status Change -->
                            <div class="mb-3">
                                <label class="form-label">Change Order Status</label>
                                <div class="input-group">
                                    <!-- Old Status -->
                                    <span class="input-group-text bg-light">
                                        {{ ucfirst($order->status->title()) }}
                                    </span>
                                    <select name="new_status" class="form-select" required>
                                        <option value="">-- Select Status --</option>
                                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                                            <option value="{{ $status->value }}"
                                                {{ $order->status->value === $status->value ? 'selected' : '' }}>
                                                {{ ucfirst($status->title()) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="mb-3">
                                <label class="form-label">Remarks (optional)</label>
                                <textarea name="remarks" class="form-control" rows="3"></textarea>
                            </div>

                            <input type="hidden" name="changed_by" value="{{ auth()->user()->role ?? 'admin' }}">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



    @endsection

    @push('scripts')
        <script>
            function printReceipt(url) {
                let printWindow = window.open(url, '_blank', 'width=800,height=600');

                printWindow.onload = function() {
                    printWindow.focus();
                    printWindow.print();
                    printWindow.onafterprint = function() {
                        printWindow.close();
                    };
                };
            }

            let deleteOrderId = null;

            $(document).on('click', '.delete-cart-item-btn', function() {
                deleteOrderId = $(this).data('id');
            });

            $('#confirmDeleteBtn').on('click', function() {
                if (!deleteOrderId) return;

                $.ajax({
                    url: "{{ route('seller.pos.sales.delete', ':id') }}".replace(':id', deleteOrderId),
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        if (response.status) {
                            toastr.success(response.message || "Order deleted successfully!");
                            $('#deleteConfirmModal').modal('hide');

                            setTimeout(() => {
                                window.location.href = "{{ route('seller.pos.sales.index') }}";
                            }, 800);

                            deleteOrderId = null;
                        } else {
                            toastr.error(response.message || "Failed to delete order!");
                        }
                    },
                    error: function(xhr) {
                        toastr.error("Something went wrong!");
                    }
                });
            });
        </script>
    @endpush
