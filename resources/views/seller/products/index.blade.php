@extends('seller.layouts.app')
@section('title', 'Products')
@section('content')

    <div class="mb-3 d-flex justify-content-between align-items-end">
        <h4 class="mb-0">Products</h4>
        <a href="{{ route('seller.products.add') }}" class="btn btn-theme">
            <i data-feather="plus" class="icon-xs"></i> Add Product
        </a>
    </div>

    <div class="table-responsive">
        <table class="table mb-3 bg-white table-bordered">
            <thead>
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Price</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Date</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="{{ storage_url($product->thumbnail) }}" class="border rounded-circle"
                                    alt="Image" style="height:80px; width:80px">
                                <div class="mt-2 ms-3">
                                    <div>{{ $product->name }}</div>
                                    <div class="mt-2">
                                        <small>Category: <strong>{{ $product->category->name }}</strong></small><br>
                                        <small>Brand: <strong>{{ $product->brand?->name }}</strong></small><br>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            Buy: {{ $product->buying_price }} <br>
                            Sell: {{ $product->selling_price }}
                        </td>
                        <td>
                            <div class="mb-2">
                                @if ($product->stock_status == 'in_stock')
                                    <span class="text-white badge text-bg-success">In Stock</span>
                                @else
                                    <span class="text-white badge text-bg-danger">Stock Out</span>
                                @endif
                            </div>
                            Qty: <strong> {{ $product->quantity }}</strong> <br>

                            <span> In: {{ $product->stock_in }} | Out: <span
                                    class="text-danger">{{ $product->stock_out }}</span></span>

                        </td>
                        <td> </td>
                        <td class="d-flex">
                            <a href="{{ route('seller.products.details', $product->id) }}"
                                class="border btn btn-light btn-sm me-1" title="Details">
                                <i data-feather="file-text" class="icon-xs"></i> Details
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div>
        {{ $products->links() }}
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Add Product</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('seller.products.store') }}" method="post">
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

@endsection
