@extends('seller.layouts.app')
@section('title', 'Customers')
@section('content')

    @php
        $activeTab = request()->get('tab', 'pos');
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <h4 class="fw-bold mb-0 text-dark">Customers</h4>
        <button class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="offcanvas" data-bs-target="#filterCanvas"
            aria-controls="filterCanvas">
            <i data-feather="filter" class="icon-xs"></i> Filter
        </button>
    </div>

    <ul class="nav nav-tabs mb-3 bg-white" id="customerTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a href="{{ route('seller.customers') }}"
                class="nav-link {{ $activeTab == 'pos' ? 'active' : '' }}">
                POS Customers
            </a>
        </li>

        <li class="nav-item" role="presentation">
            <a href="{{ route('seller.customers', ['tab' => 'website']) }}"
                class="nav-link {{ $activeTab == 'website' ? 'active' : '' }}">
                Website Customers
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade {{ $activeTab == 'pos' ? 'show active' : '' }}">
            <div class="table-responsive ">
                <table class="table table-bordered table-hover bg-white mb-3 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="small fw-semibold text-muted">Customer</th>
                            <th scope="col" class="small fw-semibold text-muted">Phone</th>
                            <th scope="col" class="small fw-semibold text-muted">Email</th>
                            <th scope="col" class="small fw-semibold text-muted">Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->phone }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->created_at->format('d/m/Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $customers->links() }}
        </div>

        <div class="tab-pane fade {{ $activeTab == 'website' ? 'show active' : '' }}">
            <div class="table-responsive">
                <table class="table table-bordered table-hover bg-white mb-3 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" class="small fw-semibold text-muted">Customer</th>
                            <th scope="col" class="small fw-semibold text-muted">Phone</th>
                            <th scope="col" class="small fw-semibold text-muted">Email</th>
                            <th scope="col" class="small fw-semibold text-muted">Registration Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td><x-user :user="$user" /></td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d/m/Y h:i A') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->links() }}
        </div>
    </div>

    <div class="offcanvas offcanvas-end" tabindex="-1" id="filterCanvas" aria-labelledby="filterCanvasLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="filterCanvasLabel">Filter</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>

        <div class="offcanvas-body">
            <form action="{{ route('seller.customers') }}" method="GET">
                <input type="hidden" name="tab" value="{{ $activeTab }}">

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

                <div class="d-flex gap-2">
                    <a href="{{ route('seller.customers', ['tab' => $activeTab]) }}" class="btn btn-outline-secondary w-100">Reset</a>
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div>

@endsection
