@extends('admin.layouts.app')
@section('title', $customer->fullname .' | Client Profile')

@section('content')

    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">User Profile</h4>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-outline-secondary btn-sm">
                <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Customers
            </a>
        </div>

        <div class="row">
            <div class="col-12 col-md-6">
                <div class="bg-white rounded-lg shadow-sm p-4 d-flex flex-column flex-md-row align-items-start">
                    <!-- Profile Image -->
                    <div class="text-center me-md-4 mb-3 mb-md-0">
                        <img src="{{ storage_url($customer->image) }}" alt="{{ $customer->username }}"
                             class="img-thumbnail"
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: .5rem;">
                        <p class="mt-2 fw-medium mb-0">{{ $customer->fullname }}</p>
                    </div>

                    <!-- Profile Info -->
                    <div class="flex-grow-1 w-100">
                        <div class="row">
                            <div class="col-12 col-sm-6 mb-3">
                                <label class="text-muted small">
                                    <i data-feather="mail" class="me-1"></i>
                                </label>
                                <span class="fw-semibold">{{ $customer->email }}</span>
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label class="text-muted small">
                                    <i data-feather="phone" class="me-1"></i>
                                </label>
                                <span class="fw-semibold">{{ $customer->phone }}</span>
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label class="text-muted small">
                                    <i data-feather="globe" class="me-1"></i>
                                </label>
                                <span class="fw-semibold">{{ $customer->country->name ?? '' }}</span>
                            </div>
                            <div class="col-12 col-sm-6 mb-3">
                                <label class="text-muted small">
                                    <i data-feather="map-pin" class="me-1"></i>
                                </label>
                                <span class="fw-semibold">{{ $customer->address ?? '' }}</span>
                            </div>
                            <div class="col-12 mb-2">
                                <label class="text-muted small">
                                    <i data-feather="calendar" class="me-1"></i>
                                </label>
                                <span class="fw-semibold">{{ optional($customer->created_at)->format('d M Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Analytics Cards -->
        <h4 class="mb-2">Analytics</h4>
        <div class="row row-cols-lg-3 row-cols-2 g-lg-4 g-2">
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total Spent</span>
                            <i data-feather="dollar-sign" class="text-success"></i>
                        </div>
                        <h4 class="fw-bold mt-3 mb-1">{{ money($total_spent) }}</h4>
                        <small class="text-muted">Lifetime value</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100 card-lift">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">Total Orders</span>
                            <i data-feather="shopping-cart" class="text-primary"></i>
                        </div>
                        <h4 class="fw-bold mt-3 mb-1">{{ $total_orders }}</h4>
                        <small class="text-muted">All orders placed</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-lift h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semi-bold">Pending</span>
                            <i data-feather="clock" class="text-warning"></i>
                        </div>
                        <h4 class="fw-bold my-2">{{ $pending_orders }}</h4>
                        <small>Pending Orders</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-lift h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semi-bold">Delivered</span>
                            <i data-feather="check-circle" class="text-success"></i>
                        </div>
                        <h4 class="fw-bold my-2">{{ $delivered_orders }}</h4>
                        <small>Delivered Orders</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-lift h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semi-bold">Shipped</span>
                            <i data-feather="truck" class="text-primary"></i>
                        </div>
                        <h4 class="fw-bold my-2">{{ $shipped_orders }}</h4>
                        <small>Shipped Orders</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card card-lift h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semi-bold">Cancelled</span>
                            <i data-feather="x-circle" class="text-danger"></i>
                        </div>
                        <h4 class="fw-bold my-2">{{ $cancelled_orders }}</h4>
                        <small>Cancelled Orders</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            feather.replace();
        </script>
    @endpush

@endsection
