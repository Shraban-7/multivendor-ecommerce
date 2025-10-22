@extends('admin.layouts.app')
@section('title', $seller->business_name . ' | Seller Profile')

@section('content')
    @php
        $deleted = \App\Models\Seller::DELETED;
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">Seller Profile</h4>
            <div>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-outline-secondary btn-sm">
                    <i data-feather="arrow-left" class="icon-xs me-1"></i> Back to Sellers
                </a>
                <a href="{{ route('admin.sellers.edit', $seller->username) }}" class="btn btn-white border btn-sm">
                    <i data-feather="edit" class="icon-xs me-1"></i> Edit
                </a>
            </div>
        </div>

        <!-- Profile and Analytics Row -->
        <div class="row mb-5">

            <!-- Left: Seller + Business Info -->
            <div class="col-md-4">
                <div class="bg-white rounded-lg shadow-sm p-4 mb-4 position-relative">

                    <!-- Seller Info -->
                    <div class="d-flex align-items-start mb-3">
                        <img src="{{ storage_url($seller->image) }}" alt="{{ $seller->name }}" class="img-thumbnail me-3"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: .5rem;">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <h5 class="fw-bold mb-0">{{ $seller->name }}</h5>

                                @if ($seller->status == $deleted)
                                    <span class="badge bg-danger ms-2">Deleted</span>
                                @endif
                            </div>

                            <p class="text-muted small mb-1">
                                <i data-feather="mail" class="icon-xs me-1"></i>{{ $seller->email }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i data-feather="phone" class="icon-xs me-1"></i>{{ $seller->phone }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i data-feather="map-pin" class="icon-xs me-1"></i>{{ $seller->address ?? '' }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i data-feather="calendar" class="icon-xs me-1"></i>
                                {{ optional($seller->created_at)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    @if ($seller->status == $deleted)
                        <div class="d-flex justify-content-end align-items-center mb-3 gap-2">
                            <!-- Restore Button -->
                            <button type="button" class="btn btn-sm btn-light border"
                                onclick="restoreItem({{ $seller->id }})">
                                Restore Account
                            </button>

                            <!-- Permanently Delete Button -->
                            <button type="button" class="btn btn-sm btn-danger d-flex align-items-center gap-1"
                                data-bs-toggle="modal" data-bs-target="#permanentDeleteModal{{ $seller->id }}">
                                <i class="bi bi-exclamation-triangle-fill"></i>
                                Delete Permanently
                            </button>
                        </div>

                        <!-- Confirmation Modal -->
                        <div class="modal fade" id="permanentDeleteModal{{ $seller->id }}" tabindex="-1"
                            aria-labelledby="permanentDeleteModalLabel{{ $seller->id }}" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="permanentDeleteModalLabel{{ $seller->id }}">Confirm
                                            Permanent Deletion</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Are you sure you want to permanently delete this seller? This action cannot be
                                        undone.
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="button" class="btn btn-danger"
                                            onclick="permanentlyDeleteSeller({{ $seller->id }})">
                                            Yes, Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <hr class="my-3">

                    <!-- Business Info -->
                    <div class="d-flex align-items-start {{ $seller->status == $deleted ? '' : 'mb-3' }} ">
                        <img src="{{ storage_url($seller->business_logo) }}" alt="{{ $seller->business_name }}"
                            class="img-thumbnail me-3"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: .5rem;">
                        <div>
                            <h6 class="fw-semibold mb-1">
                                <i data-feather="briefcase" class="icon-xs me-1"></i>{{ $seller->business_name }}
                            </h6>
                            <p class="text-muted small mb-1">
                                <i data-feather="mail" class="icon-xs me-1"></i>{{ $seller->business_email }}
                            </p>
                            <p class="text-muted small mb-1">
                                <i data-feather="map-pin" class="icon-xs me-1"></i>{{ $seller->business_address }}
                            </p>
                            <p class="text-muted small">
                                <i data-feather="file-text" class="icon-xs me-1"></i>
                                Trade License: {{ $seller->trade_license_no ?? '' }}
                            </p>
                        </div>
                    </div>

                    <!-- Block/Delete Button triggers modal -->
                    @if ($seller->status != $deleted)
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal"
                            data-bs-target="#blockSellerModal{{ $seller->id }}">
                            <i data-feather="trash" class="icon-xs me-1"></i> Delete
                        </button>
                    @endif

                    <!-- Confirmation Modal -->
                    <div class="modal fade" id="blockSellerModal{{ $seller->id }}" tabindex="-1"
                        aria-labelledby="blockSellerModalLabel{{ $seller->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="blockSellerModalLabel{{ $seller->id }}">Confirm Action
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Are you sure you want to delete this seller? This action cannot be undone.
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.sellers.delete', $seller->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Analytics -->
            <div class="col-md-8">
                <div class="row row-cols-2 row-cols-md-2 row-cols-xxl-4 g-3">
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
                                    <span class="fw-semi-bold">Cancelled</span>
                                    <i data-feather="x-circle" class="text-danger"></i>
                                </div>
                                <h4 class="fw-bold my-2">{{ $cancelled_orders }}</h4>
                                <small>Cancelled Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-lift h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semi-bold">Products</span>
                                    <i data-feather="box" class="text-info"></i>
                                </div>
                                <h4 class="fw-bold my-2">{{ $total_products }}</h4>
                                <small>Total Products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-lift h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semi-bold">Orders</span>
                                    <i data-feather="shopping-cart" class="text-success"></i>
                                </div>
                                <h4 class="fw-bold my-2">{{ $total_orders }}</h4>
                                <small>Total Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-lift h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semi-bold">Revenue</span>
                                    <span class="text-success text-xxl font-semibold">{{ currency() }}</span>
                                </div>
                                <h4 class="fw-bold my-2">{{ money($total_revenue) }}</h4>
                                <small>Total Revenue</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-lift h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semi-bold">Commission</span>
                                    <span class="text-success text-xxl font-semibold">{{ currency() }}</span>
                                </div>
                                <h4 class="fw-bold my-2">{{ money($total_commission) }}</h4>
                                <small>Total Commission</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card card-lift h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semi-bold">Customers</span>
                                    <i data-feather="users" class="text-primary"></i>
                                </div>
                                <h4 class="fw-bold my-2">{{ $total_customers }}</h4>
                                <small>Total Customers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <h4 class="mb-2">Products</h4>
        <div class="row mb-5">
            @forelse ($products as $product)
                <div class="col-md-2 mb-4">
                    <a href="{{ route('products.details', $product->slug) }}" target="__blank"
                        class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="{{ storage_url($product->thumbnail) }}" class="card-img-top"
                                alt="{{ $product->name }}" style="height: 150px; object-fit: cover;">
                            <div class="card-body p-2">
                                <h6 class="fw-semibold mb-1 text-truncate">{{ $product->name }}</h6>
                                <p class="text-muted small mb-0">{{ money($product->discounted_price) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">No products found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $products->links() }}
        </div>


    </div>

    @push('scripts')
        <script>
            feather.replace();

            function restoreItem(sellerId) {
                if (!sellerId) return;

                $.ajax({
                    url: "{{ route('admin.sellers.restore', ':id') }}".replace(':id', sellerId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Seller restored successfully!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // console.error(error);
                        alert('Failed to restore seller.');
                    }
                });
            }

            function permanentlyDeleteSeller(sellerId) {
                $.ajax({
                    url: "{{ route('admin.sellers.permanent-delete', ':id') }}".replace(':id', sellerId),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Seller permanently deleted!');
                        window.location.href = "{{ route('admin.sellers.index') }}";
                    },
                    error: function(xhr) {
                        alert('Failed to permanently delete seller.');
                    }
                });
            }
        </script>
    @endpush

@endsection
