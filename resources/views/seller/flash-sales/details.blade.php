@extends('seller.layouts.app')
@section('title', 'Flash Sale Show')

@section('content')

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="p-5">
            <div class="flex justify-between items-center flex-wrap gap-2">
                <div>
                    <h4 class="font-bold mb-1">{{ $flashSale->title }}</h4>
                    <p class="text-ink-tertiary mb-0 text-sm">{{ $flashSale->start_time->format('d M Y, h:i A') }} to
                        {{ $flashSale->end_time->format('d M Y, h:i A') }}</p>
                </div>

                <div class="flex gap-2">
                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#guidelineModal">
                        <i data-lucide="info" class="icon-xs"></i> See Guidelines
                    </button>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                        <i data-lucide="plus" class="icon-xs"></i> Add Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <h4 class="font-semibold mb-3">My Products</h4>

    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-ink border-collapse table-bordered table-hover bg-white align-middle">
            <thead class="bg-surface-muted">
                <tr>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Product</th>
                    <th scope="col" class="text-sm font-semibold text-ink-tertiary">Status</th>
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
                        <label class="block text-xs font-medium text-ink-secondary mb-1">Select Product</label>
                        <select name="product_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors product-select">
                            @foreach ($myProducts as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Stock: {{ $p->totalStock }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary">Submit Product</button>
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
