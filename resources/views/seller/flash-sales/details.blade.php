@extends('seller.layouts.app')
@section('title', 'Flash Sale Show')

@section('content')

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h4 class="fw-bold mb-1">{{ $flashSale->title }}</h4>
                    <p class="text-muted mb-0 small">{{ $flashSale->start_time->format('d M Y, h:i A') }} to
                        {{ $flashSale->end_time->format('d M Y, h:i A') }}</p>
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-light border d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#guidelineModal">
                        <i data-feather="info" class="icon-xs"></i> See Guidelines
                    </button>
                    <button class="btn btn-primary d-inline-flex align-items-center gap-1" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i data-feather="plus" class="icon-xs"></i> Add Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <h4 class="fw-semibold mb-3">My Products</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white align-middle">
            <thead class="table-light">
                <tr>
                    <th scope="col" class="small fw-semibold text-muted">Product</th>
                    <th scope="col" class="small fw-semibold text-muted">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($submitted as $s)
                    <tr>
                        <td>{{ $s->product->name }}</td>
                        <td>
                            @if ($s->status == 0)
                                <span class="badge badge-soft-warning">Pending</span>
                            @elseif($s->status == 1)
                                <span class="badge badge-soft-success">Approved</span>
                            @else
                                <span class="badge badge-soft-danger">Rejected</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <form class="modal-content border-0" method="POST" action="{{ route('seller.flash-sales.submit', $flashSale->id) }}">
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
                                <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->totalStock }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary d-inline-flex align-items-center gap-1">Submit Product</button>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="guidelineModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0">
                <div class="modal-header">
                    <h5 class="modal-title">Guidelines</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    {!! $flashSale->description !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
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
