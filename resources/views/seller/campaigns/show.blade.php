@extends('seller.layouts.app')
@section('title', 'Campaign Details')

@section('content')
    <!-- Back Button (outside the card) -->
    <div class="mb-3 d-flex justify-content-end">
        <a href="{{ route('seller.campaigns.index') }}" class="btn btn-sm btn-outline-secondary">
            <i data-feather="arrow-left" class="icon-xs"></i> Back
        </a>
    </div>

    <!-- Campaign Card -->
    <div class="card shadow-sm border-0 overflow-hidden col-md-8 mx-auto">
        @if ($campaign->image)
            <div class="position-relative">
                <!-- Campaign Cover Image -->
                <img src="{{ storage_url($campaign->image) }}" class="w-100" style="height: 300px; object-fit: cover;"
                    alt="Campaign Cover">

                <!-- Edit Button Top Right -->
                <a href="{{ route('seller.campaigns.edit', $campaign->id) }}"
                    class="btn btn-sm btn-light border position-absolute top-0 end-0 m-3 shadow">
                    <i data-feather="edit" class="icon-xs"></i> Edit
                </a>

                <!-- Campaign Title & Dates at Bottom -->
                <div class="position-absolute bottom-0 start-0 p-4 w-100 bg-white bg-opacity-75 text-dark">
                    <h4 class="mb-1 fw-bold">{{ $campaign->title ?? '' }}</h4>
                    <p class="mb-0 small">
                        {{ $campaign->start_date->format('d M Y, h:i A') }} —
                        {{ $campaign->end_date->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
        @endif

        <div class="p-4">
            <!-- Description -->
            <h5 class="mb-2">Description</h5>
            <p class="text-muted">{!! nl2br(e($campaign->description)) !!}</p>
        </div>
    </div>

    <!-- Products in Campaign Section -->
    <div class="card mt-5 shadow-sm col-md-8 mx-auto">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Products in this Campaign</h5>
            <a href="#" class="btn btn-sm btn-success">
                <i data-feather="plus" class="icon-xs"></i> Add Products
            </a>
        </div>

        <div class="card-body">
            @if ($campaign->products && $campaign->products->count())
                <div class="row g-3">
                    @foreach ($campaign->products as $product)
                        <div class="col-md-4">
                            <div class="card h-100 border rounded shadow-sm">
                                @if ($product->thumbnail)
                                    <img src="{{ storage_url($product->thumbnail) }}" class="card-img-top"
                                        style="height: 120px; object-fit: cover;" alt="{{ $product->name }}">
                                @endif
                                <div class="card-body p-2">
                                    <h6 class="card-title text-truncate mb-1" title="{{ $product->name }}">
                                        {{ $product->name }}</h6>
                                    <p class="mb-1 small text-muted">{{ number_format($product->price, 2) }} ৳</p>
                                    <span class="badge {{ $product->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-4">
                    <em>No products have been added to this campaign yet.</em>
                </div>
            @endif
        </div>
    </div>


@endsection
