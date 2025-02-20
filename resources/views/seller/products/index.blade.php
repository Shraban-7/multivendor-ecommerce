@extends('seller.layouts.app')
@section('title', 'Products')
@section('content')

<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="mb-0">Products</h4>
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
                        <img src="{{ asset('assets/'. $product->thumbnail) }}" 
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
                    <button class="btn btn-light border btn-sm me-1" title="Edit">
                        <i data-feather="edit" class="icon-xs"></i> Edit
                    </button>
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
@endsection