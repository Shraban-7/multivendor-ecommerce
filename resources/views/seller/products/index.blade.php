@extends('seller.layouts.app')
@section('title', 'Products')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Products</h4>
    <a href="{{ route('seller.products.add') }}" class="btn btn-theme">
        <i data-feather="plus" class="icon-xs"></i> Add Product 
    </a>
</div>

<div class="table-responsive">
    <table class="table table-bordered bg-white mb-3">
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
                        <img src="{{ storage_url($product->thumbnail) }}" 
                        class="rounded-circle border" alt="Image" style="height:80px; width:80px" >
                        
                        <div class="ms-3 mt-2">
                            <div>{{ $product->name }}</div>
                            <div class="mt-2">
                                <small>Category: <strong>{{ $product->category->name }}</strong></small><br>
                                <small>Brand: <strong>{{ $product->brand->name }}</strong></small><br>
                                
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
                            <span class="badge text-bg-success text-white">In Stock</span>
                        @else
                            <span class="badge text-bg-danger text-white">Stock Out</span>
                        @endif
                    </div>  
                    Qty: <strong> {{ $product->quantity }}</strong> <br>              

                    <span> In: {{ $product->stock_in }} | Out: <span class="text-danger">{{ $product->stock_out }}</span></span>

                </td>
                <td>  </td>
                <td class="d-flex">
                    <a href="{{ route('seller.products.edit', $product->id ) }}" class="btn btn-light border btn-sm me-1" title="Edit">
                        <i data-feather="edit" class="icon-xs"></i> Edit
                    </a>
                    <button class="btn btn-light border btn-sm me-1" title="Details">
                        <i data-feather="file-text" class="icon-xs"></i> Details
                    </button>
                    <form action="" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger border btn-sm" title="Delete" type="submit">
                            <i data-feather="trash-2" class="icon-xs"></i> Delete
                        </button>
                    </form>

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
    <div class="modal-dialog  modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Add Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('seller.products.store') }}" method="post">
                @CSRF
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Subcategory</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                               
                                <option value=""></option>
                                
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Brand</label>
                            <select name="game_id" class="form-select w-100" id="gameSelect" required>
                                <option value="" selected disabled>--Choose--</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Buying Price</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Selling Price</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Quantity</label>
                            <input name="name" type="text" value="" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
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