@extends('seller.layout')

@section('content')
<div class="container py-4">

    <h3>{{ $flashSale->title }}</h3>
    <p class="text-muted">{{ $flashSale->start_time }} to {{ $flashSale->end_time }}</p>

    <!-- Guidelines -->
    <div class="alert alert-info">
        <h5>Flash Sale Guidelines</h5>
        {!! $flashSale->guidelines !!}
    </div>

    <hr>

    <!-- Seller Submitted Products -->
    <h4 class="mb-3">My Products in This Flash Sale</h4>

    <table class="table table-bordered">
        <thead class="table-light">
            <tr>
                <th>Product</th>
                <th>Discount</th>
                <th>Qty</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach($submitted as $s)
            <tr>
                <td>{{ $s->product->name }}</td>
                <td>
                    {{ $s->discount_value }}
                    {{ $s->discount_type == 'percentage' ? '%' : 'BDT' }}
                </td>
                <td>{{ $s->qty }}</td>
                <td>
                    @if($s->status == 0)
                        <span class="badge bg-warning">Pending</span>
                    @elseif($s->status == 1)
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <button class="btn btn-primary mt-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
        Add Product to {{ $flashSale->title }}
    </button>

</div>

<div class="modal fade" id="addProductModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content" method="POST"
              action="{{ route('seller.flash-sales.submit', $flashSale->id) }}">

            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Add Product to {{ $flashSale->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label">Select Product</label>
                    <select name="product_id" class="form-select">
                        @foreach($myProducts as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->stock }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <label>Discount Type</label>
                        <select name="discount_type" class="form-select">
                            <option value="percentage">Percentage (%)</option>
                            <option value="fixed">Fixed Amount</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Discount Value</label>
                        <input type="number" name="discount_value" class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Qty</label>
                        <input type="number" name="qty" class="form-control">
                    </div>

                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Submit Product</button>
            </div>

        </form>
    </div>
</div>


@endsection
