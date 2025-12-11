@extends('seller.layouts.app')
@section('title', 'Flash Sale Show')

@section('content')
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

    <table class="table bg-white table-bordered">
        <thead>
            <tr>
                <th>Product</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($submitted as $s)
                <tr>
                    <td>{{ $s->product->name }}</td>
                    <td>
                        @if ($s->status == 0)
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
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content" method="POST" action="{{ route('seller.flash-sales.submit', $flashSale->id) }}">

                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add Product to {{ $flashSale->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Select Product</label>
                        <select name="product_id" class="form-select product-select">
                            @foreach ($myProducts as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->totalStock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Submit Product</button>
                </div>

            </form>
        </div>

    @endsection

    @push('scripts')
        <script>
            $('.product-select').select2({
                theme: 'bootstrap-5',
                dropdownParent: $('#addProductModal')
            });
        </script>
    @endpush
