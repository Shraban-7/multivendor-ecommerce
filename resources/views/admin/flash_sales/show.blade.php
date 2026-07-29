@extends('admin.layouts.app')
@section('title', 'flash sale')

@section('content')
    <h4>Flash Sale Details</h4>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mt-3">
        <div class="p-5">

            <h5>{{ $sale->title }}</h5>

            @if ($sale->image)
                <img src="{{ storage_url($sale->image) }}" class="img-fluid mb-3" style="max-height: 250px;">
            @endif

            <p>{!! $sale->description !!}</p>

            <p><strong>Start:</strong> {{ $sale->start_time }}</p>
            <p><strong>End:</strong> {{ $sale->end_time }}</p>

            <p>
                <strong>Status: </strong>
                @if ($sale->is_active)
                    <span class="badge bg-feedback-success">Active</span>
                @else
                    <span class="badge bg-surface-muted">Inactive</span>
                @endif
            </p>
        </div>
    </div>

    <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mt-4">
        <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
            <h6>Flash Sale Products</h6>
        </div>
        <div class="p-5 p-0">
            <table class="w-full text-left text-sm text-ink border-collapse">
                <thead>
                    <tr>
                        <th>Vendor</th>
                        <th>Product</th>
                        <th>Sold</th>
                        <th>Status</th>
                        <th width="120">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sale->products as $item)
                        <tr id="row-{{ $item->id }}">
                            <td>{{ $item->seller->name }}</td>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->sold }}</td>
                            <td id="status-badge-{{ $item->id }}">
                                @if ($item->status == 1)
                                    <span class="badge bg-feedback-success">Approved</span>
                                @elseif ($item->status == 2)
                                    <span class="badge bg-feedback-danger">Rejected</span>
                                @else
                                    <span class="badge bg-feedback-warning text-ink">Pending</span>
                                @endif
                            </td>

                            <td>
                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#reviewModal-{{ $item->id }}">
                                    Review
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    @foreach ($sale->products as $item)
        <div class="modal fade" id="reviewModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">Review Flash Sale Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <form class="flashSaleForm" data-id="{{ $item->id }}"
                            data-product-id="{{ $item->product->id }}"
                            action="{{ route('admin.flash-sales.product.review', ['id' => $item->id, 'productId' => $item->product->id]) }}"
                            method="POST">

                            @csrf

                            <div class="mb-3">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Status</label>
                                <select name="status" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                    <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Approved</option>
                                    <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="modal-footer px-0">
                                <button type="submit" class="btn btn-primary saveBtn">Save changes</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
        <script>
            $(document).on('submit', '.flashSaleForm', function(e) {
                e.preventDefault();

                let form = $(this);
                let productId = form.data('id'); 
                let actionUrl = form.attr('action');
                let formData = form.serialize();
                let modal = form.closest('.modal');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,

                    success: function(res) {
                        
                        let status = form.find('select[name="status"]').val();
                        let badge;

                        if (status == 1) {
                            badge = '<span class="badge bg-feedback-success">Approved</span>';
                        } else if (status == 2) {
                            badge = '<span class="badge bg-feedback-danger">Rejected</span>';
                        } else {
                            badge = '<span class="badge bg-feedback-warning text-ink">Pending</span>';
                        }

                        $("#status-badge-" + productId).html(badge);
                        modal.modal('hide');

                        // showSuccessToast(res.message);
                    },

                    error: function(xhr) {
                        console.log(xhr.responseText);
                        // showErrorToast("Something went wrong");
                    }
                });
            });
        </script>
    @endpush

@endsection
