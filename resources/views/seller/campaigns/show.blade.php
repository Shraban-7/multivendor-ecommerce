@extends('seller.layouts.app')
@section('title', 'Campaign Details')

@section('content')

    <div class="row">
        <div class="col-md-6">
            <div class="card position-relative overflow-hidden">
                @if ($campaign->image)
                    <img src="{{ storage_url($campaign->image) }}"
                         class="w-100"
                         style="object-fit: cover; height: 300px;"
                         alt="Campaign Image">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 300px;">
                        <em>No Image</em>
                    </div>
                @endif
                <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                    <a href="{{ route('seller.campaigns.edit', $campaign->id) }}"
                       class="btn btn-sm btn-light border shadow-sm">
                        <i data-feather="edit" class="icon-xs"></i> Edit
                    </a>
                    <a href="{{ route('seller.campaigns.index') }}"
                       class="btn btn-sm btn-light border">
                        <i data-feather="arrow-left" class="icon-xs"></i> Back
                    </a>
                </div>

                <div class="p-4">
                    <h4 class="fw-bold mb-1">{{ $campaign->title ?? '' }}</h4>
                    <p class="text-muted small mb-3">
                        {{ $campaign->start_date->format('d M Y, h:i A') }} —
                        {{ $campaign->end_date->format('d M Y, h:i A') }}
                    </p>
                    <h6 class="mb-2">Description</h6>
                    <p class="text-muted">{!! nl2br(e($campaign->description)) !!}</p>
                </div>
            </div>

        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Products in this Campaign</h5>
                <a href="javascript:void(0)" class="btn btn-sm btn-success" data-bs-toggle="modal"
                    data-bs-target="#addProductModal">
                    <i data-feather="plus" class="icon-xs"></i> Add Products
                </a>
            </div>
            @if ($campaign_products && $campaign_products->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle bg-white">
                        <thead class="table-white">
                            <tr>
                                <th>#</th>
                                <th style="width: 100px;">Image</th>
                                <th>Name</th>
                                <th>Selling Price</th>
                                <th>Discounted Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($campaign_products as $index => $product)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($product->thumbnail)
                                            <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                                class="img-fluid rounded"
                                                style="height: 60px; width: 60px; object-fit: cover;">
                                        @else
                                            <span class="text-muted"></span>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ money($product->selling_price) }}</td>
                                    <td>{{ money($product->discounted_price) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <em>No products have been added to this campaign yet.</em>
                </div>
            @endif
        </div>
    </div>




    <!-- Add Products Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('seller.campaigns.add_products', $campaign->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Select Products to Add</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        @if ($products->count())
                            <div class="row">
                                @foreach ($products as $product)
                                    <div class="col-md-4 mb-3">
                                        <label for="product_{{ $product->id }}" class="position-relative d-block">
                                            <input type="checkbox"
                                                class="form-check-input position-absolute top-0 end-0 m-2 z-3"
                                                name="product_ids[]" value="{{ $product->id }}"
                                                id="product_{{ $product->id }}">
                                            <div class="border rounded shadow-sm p-3 h-100">
                                                <strong class="d-block text-truncate mb-1">{{ $product->name }}</strong>
                                                <small
                                                    class="text-muted text-decoration-line-through me-2">{{ money($product->selling_price) }}</small>
                                                <small class="text-muted">{{ money($product->discounted_price) }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted">No available products to add.</p>
                        @endif
                    </div>


                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Add Selected</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



@endsection
