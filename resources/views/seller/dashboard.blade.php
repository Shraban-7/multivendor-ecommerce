@extends('seller.layouts.app')
@section('title', 'Seller Dashboard')
@section('content')
    <div class="row">
        <div class="col-12 mb-5">
            <div class="row row-cols-lg-4 row-cols-2 g-lg-5 g-2">
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Pending Orders</span>
                                <i data-feather="clock" class="text-warning"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $pending_orders }}</h3>
                            </div>
                            <a href="{{ route('seller.orders.cancelled') }}"><small>View Orders</small> </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Delivered Orders</span>
                                <i data-feather="check-circle" class="text-success"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $delivered_orders }}</h3>
                            </div>
                            <a href="{{ route('seller.orders.delivered') }}"><small>View Orders</small> </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Shipped Orders</span>
                                <i data-feather="truck" class="text-primary"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $shipped_orders }}</h3>
                            </div>
                            <a href="{{ route('seller.orders.shipped') }}"><small>View Orders</small> </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Cancelled Orders</span>
                                <i data-feather="x-circle" class="text-danger"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $cancelled_orders }}</h3>
                            </div>
                            <a href="{{ route('seller.orders.cancelled') }}"><small>View Orders</small> </a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Total Products</span>
                                <i data-feather="box" class="text-info"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $total_products }}</h3>
                            </div>
                            <a href="{{ route('seller.products.index') }}"><small>Total products listed</small></a>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Total Orders</span>
                                <i data-feather="shopping-cart" class="text-success"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $total_orders }}</h3>
                            </div>
                            <small>Orders received</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Total Revenue</span>
                                <i data-feather="dollar-sign" class="text-success"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ money($total_revenue) }}</h3>
                            </div>
                            <small>Total earnings from sales</small>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card h-100 card-lift">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-semi-bold">Total Customers</span>
                                <i data-feather="users" class="text-primary"></i>
                            </div>
                            <div class="mt-2 mb-2">
                                <h3 class="fw-bold mb-0">{{ $total_customers }}</h3>
                            </div>
                            <small>Registered customers</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
