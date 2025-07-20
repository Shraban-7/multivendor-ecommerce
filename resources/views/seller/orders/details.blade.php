@extends('seller.layouts.app')
@section('title', 'Order Details')
@section('content')

    <div class="mb-3">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Order Details</h4>
            <a href="{{ route('seller.orders.' . $order->status->label()) }}" class="btn btn-outline-secondary btn-sm">
                <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to {{ ucfirst($order->status->label()) }} Orders
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Order Summary -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Order Summary</h5>
                    <a href="{{ route('invoice', $order->invoice_id) }}" title="Details" target="__blank"
                        class="btn btn-light border btn-sm me-1">
                        <i data-feather="download" class="icon-xs"></i>Invoice
                    </a>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Invoice ID:</span>
                            <span class="fw-medium">{{ $order->invoice_id }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Date:</span>
                            <span class="fw-medium">{{ $order->created_at->format('d-m-Y h:i A') }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Status:</span>
                            <span>
                                @if ($order->status->label() === 'pending')
                                    <span class="badge bg-warning">Pending</span>
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
                                @endif

                            </span>
                        </li>
                        {{-- <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Delivery Status:</span>
                            <span>
                                @if ($order->delivery_status === \App\Enums\OrderStatus::ORDER_PLACED->value)
                                    <span class="badge bg-warning">Order Placed</span>
                                @elseif ($order->delivery_status === \App\Enums\OrderStatus::PACKAGING->value)
                                    <span class="badge bg-primary">Packaging</span>
                                @elseif ($order->delivery_status === \App\Enums\OrderStatus::ON_THE_ROAD->value)
                                    <span class="badge bg-dark">On The Road</span>
                                @elseif ($order->delivery_status === \App\Enums\OrderStatus::DELIVERED->value)
                                    <span class="badge bg-success">Delivered</span>
                                @endif
                            </span>
                        </li> --}}
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Method:</span>
                            <span class="fw-medium">{{ $order->payment_method ?? 'N/A' }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between px-0">
                            <span>Payment Status:</span>
                            @if ($order->due == $order->payable)
                                <span class="badge bg-warning">Unpaid</span>
                            @elseif ($order->due != $order->payable && $order->due > 0)
                                <span class="badge bg-danger">Partially Paid</span>
                            @else
                                <span class="badge bg-success">Paid</span>
                            @endif
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Update Order Status -->
            <form action="{{ route('seller.orders.updateStatus', $order->id) }}" method="POST">
                @csrf
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Status</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <select class="form-select" id="status" name="status">
                                <option value="{{ \App\Enums\OrderStatus::PENDING->value }}"
                                    {{ $order->status->label() === 'pending' ? 'selected' : '' }}>Pending</option>

                                <option value="{{ \App\Enums\OrderStatus::SHIPPED->value }}"
                                    {{ $order->status->label() === 'shipped' ? 'selected' : '' }}>Shipped</option>

                                <option value="{{ \App\Enums\OrderStatus::DELIVERED->value }}"
                                    {{ $order->status->label() === 'delivered' ? 'selected' : '' }}>Delivered</option>

                                <option value="{{ \App\Enums\OrderStatus::CANCELLED->value }}"
                                    {{ $order->status->label() === 'cancelled' ? 'selected' : '' }}>Cancelled</option>

                                <option value="{{ \App\Enums\OrderStatus::RETURNED->value }}"
                                    {{ $order->status->label() === 'returned' ? 'selected' : '' }}>Returned</option>

                                <option value="{{ \App\Enums\OrderStatus::REFUNDED->value }}"
                                    {{ $order->status->label() === 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div>

                <!-- Update Order Delivery Status -->
                {{-- <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Delivery Status</h5>
                    </div>
                    <div class="card-body">

                        <div class="mb-3">
                            <select class="form-select" id="delivery_status" name="delivery_status">
                                <option value="{{ \App\Enums\OrderStatus::ORDER_PLACED->value }}"
                                    {{ $order->status->label() === 'order_placed' ? 'selected' : '' }}>Order Placed
                                </option>
                                <option value="{{ \App\Enums\OrderStatus::PACKAGING->value }}"
                                    {{ $order->status->label() === 'packaging' ? 'selected' : '' }}>Packaging</option>
                                <option value="{{ \App\Enums\OrderStatus::ON_THE_ROAD->value }}"
                                    {{ $order->status->label() === 'on_the_road' ? 'selected' : '' }}>On The Road</option>
                                <option value="{{ \App\Enums\OrderStatus::DELIVERED->value }}"
                                    {{ $order->status->label() === 'delivered' ? 'selected' : '' }}>Delivered</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </div> --}}
            </form>
        </div>

        <!-- Customer and Order Details -->
        <div class="col-lg-8">
            <div class="row">
                <!-- Customer Information -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-header bg-white">
                            <h5 class="mb-0">Customer Information</h5>
                        </div>
                        <div class="card-body">
                            <h6 class="fw-bold">{{ $order->user->name }}</h6>
                            <p class="mb-1"><i data-feather="mail" class="icon-xs me-1"></i> {{ $order->user->email }}
                            </p>
                            <p class="mb-1"><i data-feather="phone" class="icon-xs me-1"></i> {{ $order->user->phone }}
                            </p>
                            <p class="mb-0"><i data-feather="user" class="icon-xs me-1"></i> Customer since
                                {{ \Carbon\Carbon::parse($order->user->created_at)->format('M Y') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Shipping Details -->
                <div class="col-md-6 mb-4">
                    <div class="card border-0 shadow-sm h-100">
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
                </div>
            </div>

            <!-- Order Items -->
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
                                    <th scope="col" class="text-center">Discounted Price</th>
                                    <th scope="col" class="text-center">Quantity</th>
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
                                                        class="rounded me-3" width="50">
                                                @else
                                                    <div class="bg-white rounded me-3" style="width: 50px; height: 50px;">
                                                    </div>
                                                @endif

                                                <div>
                                                    <h6 class="mb-0">{{ $item->product->name }}</h6>
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
                                                        <small class="text-muted d-block">
                                                            Variant SKU: {{ $item->variant->sku }}
                                                        </small>
                                                        <small class="text-muted d-block">
                                                            Stock:
                                                            {{ $item->variant->stock_in - $item->variant->stock_out }}
                                                        </small>
                                                        <small class="text-muted d-block">
                                                            Price: {{ money($item->variant->selling_price) }}
                                                            (Without Discount)
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ money($item->unit_price + $item->product->discount) }}
                                        </td>
                                        <td class="text-center">{{ money($item->unit_price) }}</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            {{ money(($item->unit_price + $item->product->discount) * $item->quantity) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-white">
                                <tr>
                                    <th colspan="4" class="text-end">Subtotal:</th>
                                    <td class="text-end">{{ $order->sub_total }}</td>
                                </tr>
                                @if (isset($order->discount) && $order->discount > 0)
                                    <tr>
                                        <th colspan="4" class="text-end">Discount:</th>
                                        <td class="text-end">-{{ $order->discount }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th colspan="4" class="text-end">Shipping:</th>
                                    <td class="text-end">{{ $order->shipping_fee }}</td>
                                </tr>
                                @if (isset($order->tax) && $order->tax > 0)
                                    <tr>
                                        <th colspan="4" class="text-end">Tax:</th>
                                        <td class="text-end">{{ $order->tax }}</td>
                                    </tr>
                                @endif
                                <tr>
                                    <th colspan="4" class="text-end">Total:</th>
                                    <td class="text-end fw-bold">{{ $order->total }}</td>
                                </tr>
                                @if ($order->due > 0)
                                    <tr>
                                        <th colspan="4" class="text-end">Paid:</th>
                                        <td class="text-end">{{ $order->payable - $order->due }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="4" class="text-end">Due:</th>
                                        <td class="text-end text-danger fw-bold">{{ $order->due }}</td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Order Review -->
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
        </div>

    @endsection
