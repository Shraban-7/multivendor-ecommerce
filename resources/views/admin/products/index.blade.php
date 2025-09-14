@extends('admin.layouts.app')
@section('title', 'Products')
@section('content')

<div class="mb-3 d-flex justify-content-between align-items-end">
    <h4 class="mb-0">Products</h4>
</div>

<div class="table-responsive">
    <table id="product-table" class="table mb-3 bg-white table-bordered">
        <thead>
            <tr>
                <th scope="col">Product</th>
                <th scope="col">Price</th>
                <th scope="col">Stock</th>
                <th scope="col">Date</th>
                <th scope="col">Status</th>
                <th scope="col">Seller</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
            @php
            $totalStockIn = $product->variants->sum('stock_in');
            $totalStockOut = $product->variants->sum('stock_out');
            $totalStock = $totalStockIn = $totalStockOut;
            @endphp
            <tr>
                <td>
                    <div class="d-flex">
                        <img src="{{ storage_url($product->thumbnail) }}" class="border rounded-circle" alt="Image" style="height:64px; width:64px">
                        <div class="ms-3">
                            <div class="fw-bold">{{ $product->name }}</div>
                            <div class="small">
                                Category: {{ $product->category->name }}
                                @if($product->brand)
                                <br> Brand: {{ $product->brand->name }}
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
                <td>
                    {{ money($product->selling_price) }}
                </td>
                <td>
                    {{ $totalStock }} {{ $product->unit->short_name }}
                </td>
                <td>{{ $product->created_at->format('d/m/y h:i A') }} </td>
                <td>
                    @if ($product->is_approved)
                    <span class="badge text-bg-success">Active</span>
                    @else
                    <span class="badge text-bg-warning">Inactive</span>
                    @endif
                </td>
                <td class="d-flex">
                    <x-seller :seller="$product->seller" />
                </td>
                <td>
                    <button class="btn btn-primary btn-sm border d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#statusModal-{{ $product->id }}">
                        <i data-feather="edit" class="icon-xs"></i>
                        <span>Edit</span>
                    </button>

                    <div class="modal fade" id="statusModal-{{ $product->id }}" tabindex="-1"
                        aria-labelledby="statusModalLabel-{{ $product->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form action="{{ route('admin.products.updateStatus', $product->id) }}"
                                    method="POST">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="statusModalLabel-{{ $product->id }}">
                                            Update Product Status
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="status-{{ $product->id }}" class="form-label">Select
                                                Status</label>
                                            <select class="form-select" id="status-{{ $product->id }}"
                                                name="is_approved">
                                                <option value="1"
                                                    {{ $product->is_approved ? 'selected' : '' }}>Active</option>
                                                <option value="0"
                                                    {{ !$product->is_approved ? 'selected' : '' }}>Inactive
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="post">
                @CSRF
                <div class="modal-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Category</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Subcategory</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>

                                <option value=""></option>

                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Brand</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3 col-md-6">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Buying Price</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Selling Price</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Quantity</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label class="form-label">Stock in</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>

                    </div>
                    <button type="submit" class="btn btn-theme">Save Contest</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    new DataTable('#product-table');
</script>
@endpush

@endsection