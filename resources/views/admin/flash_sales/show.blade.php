@extends('admin.layouts.app')
@section('title','flash sale')

@section('content')

<div class="container mt-4">
    <h4>Flash Sale Details</h4>

    <div class="card mt-3">
        <div class="card-body">

            <h5>{{ $sale->title }}</h5>

            @if($sale->image)
            <img src="{{ asset('uploads/flash_sale/'.$sale->image) }}" class="img-fluid mb-3" style="max-height: 250px;">
            @endif

            <p>{{ $sale->description }}</p>

            <p><strong>Start:</strong> {{ $sale->start_time }}</p>
            <p><strong>End:</strong> {{ $sale->end_time }}</p>

            <p>
                <strong>Status: </strong>
                @if($sale->is_active)
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-secondary">Inactive</span>
                @endif
            </p>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <h6>Flash Sale Products</h6>
        </div>
        <div class="card-body p-0">
            <table class="table mb-0">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Product</th>
                        <th>Flash Price</th>
                        <th>Stock</th>
                        <th>Sold</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->flashSaleProducts as $item)
                    <tr>
                        <td>{{ $item->vendor->name }}</td>
                        <td>{{ $item->product->name }}</td>
                        <td>{{ $item->flash_price }}</td>
                        <td>{{ $item->flash_stock }}</td>
                        <td>{{ $item->sold }}</td>
                        <td>
                            @if($item->status == 1)
                            <span class="badge bg-success">Approved</span>
                            @elseif($item->status == 2)
                            <span class="badge bg-danger">Rejected</span>
                            @else
                            <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.flash-sale-products.edit', $item->id) }}"
                                class="btn btn-sm btn-warning">Review</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection