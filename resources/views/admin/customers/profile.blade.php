@extends('admin.layouts.app')
@section('title', $customer->name .' | Client Profile')

@section('content')

    <div class="container-fluid">
        <div class="flex justify-between items-center mb-2">
            <h4 class="mb-0">User Profile</h4>
            <a href="{{ route('admin.customers.index') }}" class="btn btn-light btn-sm">
                <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Customers
            </a>
        </div>

        <div class="grid grid-cols-1">
            <div class="col-span-full md:col-span-1">
                <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col md:flex-row items-start">
                    <!-- Profile Image -->
                    <div class="text-center me-md-4 mb-3 mb-md-0">
                        <img src="{{ storage_url($customer->image) }}" alt="{{ $customer->username }}"
                             class="img-thumbnail"
                             style="width: 120px; height: 120px; object-fit: cover; border-radius: .5rem;">
                        <p class="mt-2 font-medium mb-0">{{ $customer->name }}</p>
                    </div>

                    <!-- Profile Info -->
                    <div class="grow w-full">
                        <div class="grid grid-cols-1">
                            <div class="col-span-full sm:col-span-1 mb-3">
                                <label class="text-ink-tertiary text-sm">
                                    <i data-feather="mail" class="me-1"></i>
                                </label>
                                <span class="font-semibold">{{ $customer->email }}</span>
                            </div>
                            <div class="col-span-full sm:col-span-1 mb-3">
                                <label class="text-ink-tertiary text-sm">
                                    <i data-feather="phone" class="me-1"></i>
                                </label>
                                <span class="font-semibold">{{ $customer->phone }}</span>
                            </div>
                            <div class="col-span-full sm:col-span-1 mb-3">
                                <label class="text-ink-tertiary text-sm">
                                    <i data-feather="globe" class="me-1"></i>
                                </label>
                                <span class="font-semibold">{{ $customer->country->name ?? '' }}</span>
                            </div>
                            <div class="col-span-full sm:col-span-1 mb-3">
                                <label class="text-ink-tertiary text-sm">
                                    <i data-feather="map-pin" class="me-1"></i>
                                </label>
                                <span class="font-semibold">{{ $customer->address ?? '' }}</span>
                            </div>
                            <div class="col-span-full mb-2">
                                <label class="text-ink-tertiary text-sm">
                                    <i data-feather="calendar" class="me-1"></i>
                                </label>
                                <span class="font-semibold">{{ optional($customer->created_at)->format('d M Y h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Analytics Cards -->
        <h4 class="mb-2">Analytics</h4>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2 lg:gap-4">
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Spent</span>
                            <i data-feather="dollar-sign" class="text-feedback-success"></i>
                        </div>
                        <h4 class="font-bold mt-3 mb-1">{{ money($total_spent) }}</h4>
                        <small class="text-ink-tertiary">Lifetime value</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full card-lift">
                    <div class="p-5">
                        <div class="flex justify-between items-center">
                            <span class="font-semibold">Total Orders</span>
                            <i data-feather="shopping-cart" class="text-brand"></i>
                        </div>
                        <h4 class="font-bold mt-3 mb-1">{{ $total_orders }}</h4>
                        <small class="text-ink-tertiary">All orders placed</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                    <div class="p-5">
                        <div class="flex justify-between">
                            <span class="font-semibold">Pending</span>
                            <i data-feather="clock" class="text-feedback-warning"></i>
                        </div>
                        <h4 class="font-bold my-2">{{ $pending_orders }}</h4>
                        <small>Pending Orders</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                    <div class="p-5">
                        <div class="flex justify-between">
                            <span class="font-semibold">Delivered</span>
                            <i data-feather="check-circle" class="text-feedback-success"></i>
                        </div>
                        <h4 class="font-bold my-2">{{ $delivered_orders }}</h4>
                        <small>Delivered Orders</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                    <div class="p-5">
                        <div class="flex justify-between">
                            <span class="font-semibold">Shipped</span>
                            <i data-feather="truck" class="text-brand"></i>
                        </div>
                        <h4 class="font-bold my-2">{{ $shipped_orders }}</h4>
                        <small>Shipped Orders</small>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                    <div class="p-5">
                        <div class="flex justify-between">
                            <span class="font-semibold">Cancelled</span>
                            <i data-feather="x-circle" class="text-feedback-danger"></i>
                        </div>
                        <h4 class="font-bold my-2">{{ $cancelled_orders }}</h4>
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
