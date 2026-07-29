@extends('admin.layouts.app')
@section('title', $seller->business_name . ' | Seller Profile')

@section('content')
    @php
        $deleted = \App\Domain\Vendor\Models\Seller::DELETED;
    @endphp
    <div>
        <div class="flex justify-between items-center mb-4">
            <div>
                <h4 class="font-semibold mb-0">Seller Profile</h4>
                <p class="text-sm text-ink-secondary mt-1">{{ $seller->business_name }}</p>
            </div>
            <div>
                <a href="{{ route('admin.sellers.index') }}" class="btn btn-light btn-sm">
                    <i data-lucide="arrow-left" class="icon-xs me-1"></i> Back to Sellers
                </a>
                <a href="{{ route('admin.sellers.edit', $seller->username) }}" class="btn btn-light btn-sm">
                    <i data-lucide="edit" class="icon-xs me-1"></i> Edit
                </a>
</div>
            </div>

            <!-- Right: Analytics -->

        <!-- Profile and Analytics Row -->
        <div class="grid grid-cols-1 mb-5">

            <!-- Left: Seller + Business Info -->
            <div class="md:col-span-1">
                <div class="bg-white border border-border rounded-sm shadow-sm p-4 mb-4 relative">

                    <!-- Seller Info -->
                    <div class="flex items-start mb-3">
                        <img src="{{ storage_url($seller->image) }}" alt="{{ $seller->name }}" class="border rounded-lg me-3"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: .5rem;">
                        <div>
                            <div class="flex items-center mb-2">
                                <h5 class="font-bold mb-0">{{ $seller->name }}</h5>

                                @if ($seller->status == $deleted)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-50 text-red-700 ms-2">Deleted</span>
                                @endif
                            </div>

                            <p class="text-ink-tertiary text-sm mb-1">
                                <i data-lucide="mail" class="icon-xs me-1"></i>{{ $seller->email }}
                            </p>
                            <p class="text-ink-tertiary text-sm mb-1">
                                <i data-lucide="phone" class="icon-xs me-1"></i>{{ $seller->phone }}
                            </p>
                            <p class="text-ink-tertiary text-sm mb-1">
                                <i data-lucide="map-pin" class="icon-xs me-1"></i>{{ $seller->business_address ?? '' }}
                            </p>
                            <p class="text-ink-tertiary text-sm mb-1">
                                <i data-lucide="calendar" class="icon-xs me-1"></i>
                                {{ optional($seller->created_at)->format('d M Y') }}
                            </p>
                        </div>
                    </div>
                    @if ($seller->status == $deleted)
<div class="flex justify-end items-center mb-3 gap-2">
                            <!-- Restore Button -->
                            <button type="button" class="btn btn-light btn-sm"
                                onclick="restoreItem({{ $seller->id }})">
                                Restore Account
                            </button>

                            <!-- Permanently Delete Button -->
                            <button type="button" class="btn btn-danger btn-sm"
                                data-bs-toggle="modal" data-bs-target="#permanentDeleteModal{{ $seller->id }}">
                                <i data-lucide="triangle-alert"></i>
                                Delete Permanently
                            </button>
                        </div>
                @endif
                <hr class="my-3">

                    <!-- Business Info -->
                    <div class="flex items-start {{ $seller->status == $deleted ? '' : 'mb-3' }} ">
                        <img src="{{ storage_url($seller->business_logo) }}" alt="{{ $seller->business_name }}"
                            class="border rounded-lg me-3"
                            style="width: 100px; height: 100px; object-fit: cover; border-radius: .5rem;">
                        <div>
                            <h6 class="font-semibold mb-1">
                                <i data-lucide="briefcase" class="icon-xs me-1"></i>{{ $seller->business_name }}
                            </h6>
                            <p class="text-ink-tertiary text-sm mb-1">
                                <i data-lucide="mail" class="icon-xs me-1"></i>{{ $seller->business_email }}
                            </p>
                            <p class="text-ink-tertiary text-sm mb-1">
                                <i data-lucide="map-pin" class="icon-xs me-1"></i>{{ $seller->business_address }}
                            </p>
                            <p class="text-ink-tertiary text-sm">
                                <i data-lucide="file-text" class="icon-xs me-1"></i>
                                Trade License: {{ $seller->trade_license_no ?? '' }}
                            </p>
                        </div>
                    </div>

                    <!-- Block/Delete Button triggers modal -->
                    @if ($seller->status != $deleted)
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                            data-bs-target="#blockSellerModal{{ $seller->id }}">
                            <i data-lucide="trash" class="icon-xs me-1"></i> Delete
                        </button>
@endif

                </div>
            <div class="md:col-span-2">
                <div class="grid grid-cols-1 sm:grid-cols-2 2xl:grid-cols-4 gap-3">
                    <div class="col">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                            <div class="p-5">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Pending</span>
                                    <i data-lucide="clock" class="text-feedback-warning"></i>
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
                                    <i data-lucide="check-circle" class="text-feedback-success"></i>
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
                                    <span class="font-semibold">Cancelled</span>
                                    <i data-lucide="x-circle" class="text-feedback-danger"></i>
                                </div>
                                <h4 class="font-bold my-2">{{ $cancelled_orders }}</h4>
                                <small>Cancelled Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                            <div class="p-5">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Products</span>
                                    <i data-lucide="box" class="text-feedback-info"></i>
                                </div>
                                <h4 class="font-bold my-2">{{ $total_products }}</h4>
                                <small>Total Products</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                            <div class="p-5">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Orders</span>
                                    <i data-lucide="shopping-cart" class="text-feedback-success"></i>
                                </div>
                                <h4 class="font-bold my-2">{{ $total_orders }}</h4>
                                <small>Total Orders</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                            <div class="p-5">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Revenue</span>
                                    <span class="text-emerald-600 text-lg font-semibold">{{ currency() }}</span>
                                </div>
                                <h4 class="font-bold my-2">{{ money($total_revenue) }}</h4>
                                <small>Total Revenue</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                            <div class="p-5">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Commission</span>
                                    <span class="text-emerald-600 text-lg font-semibold">{{ currency() }}</span>
                                </div>
                                <h4 class="font-bold my-2">{{ money($total_commission) }}</h4>
                                <small>Total Commission</small>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden card-lift h-full">
                            <div class="p-5">
                                <div class="flex justify-between">
                                    <span class="font-semibold">Customers</span>
                                    <i data-lucide="users" class="text-brand"></i>
                                </div>
                                <h4 class="font-bold my-2">{{ $total_customers }}</h4>
                                <small>Total Customers</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <h4 class="mb-2">Products</h4>
        <div class="grid grid-cols-1 mb-5">
            @forelse ($products as $product)
                <div class="md:col-span-1 mb-4">
                    <a href="{{ route('products.details', $product->slug) }}" target="__blank"
                        class="no-underline text-ink">
                        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden h-full">
                            <img src="{{ storage_url($product->thumbnail) }}"
                                alt="{{ $product->name }}" style="height: 150px; width: 100%; object-fit: cover;">
                            <div class="p-2">
                                <h6 class="font-semibold mb-1 truncate">{{ $product->name }}</h6>
                                <p class="text-ink-tertiary text-sm mb-0">{{ money($product->compare_price) }}</p>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-span-full">
                    <p class="text-center text-ink-tertiary">No products found.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="flex justify-center">
            {{ $products->links() }}
        </div>


    </div>

    @push('modals')
        <div class="modal fade" id="permanentDeleteModal{{ $seller->id }}" tabindex="-1"
            aria-labelledby="permanentDeleteModalLabel{{ $seller->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="permanentDeleteModalLabel{{ $seller->id }}">Confirm Permanent Deletion</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to permanently delete this seller? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="permanentlyDeleteSeller({{ $seller->id }})">
                            Yes, Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="blockSellerModal{{ $seller->id }}" tabindex="-1"
            aria-labelledby="blockSellerModalLabel{{ $seller->id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="blockSellerModalLabel{{ $seller->id }}">Confirm Action</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this seller? This action cannot be undone.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <form action="{{ route('admin.sellers.delete', $seller->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="btn btn-danger">Yes, Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endpush

    @push('scripts')
        <script>
            window.renderIcons && window.renderIcons();

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
