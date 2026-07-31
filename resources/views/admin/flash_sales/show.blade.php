@extends('admin.layouts.app')
@section('title', 'Flash Sale Details')

@section('content')
    <div class="flex justify-between items-start mb-4">
        <div>
            <h1 class="text-xl font-semibold text-ink">Flash Sale Details</h1>
            <p class="text-sm text-ink-secondary mt-1">{{ $sale->title }}</p>
        </div>
        <a href="{{ route('admin.flash-sales.index') }}" class="btn btn-light btn-sm">
            <i data-lucide="arrow-left" class="icon-xs"></i> Back
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="md:col-span-1">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden p-5">
                @if ($sale->image)
                    <img src="{{ storage_url($sale->image) }}" class="w-full border rounded-xs mb-4" style="max-height: 200px; object-fit: cover;">
                @endif

                <div class="space-y-3">
                    <div>
                        <span class="text-xs font-medium text-ink-secondary uppercase tracking-wider">Title</span>
                        <p class="text-sm font-semibold text-ink mt-0.5">{{ $sale->title }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-ink-secondary uppercase tracking-wider">Status</span>
                        <p class="mt-0.5">
                            @if ($sale->is_active)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Active</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink-tertiary bg-surface-muted rounded-full">Inactive</span>
                            @endif
                        </p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-ink-secondary uppercase tracking-wider">Start Time</span>
                        <p class="text-sm text-ink mt-0.5">{{ $sale->start_time }}</p>
                    </div>
                    <div>
                        <span class="text-xs font-medium text-ink-secondary uppercase tracking-wider">End Time</span>
                        <p class="text-sm text-ink mt-0.5">{{ $sale->end_time }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="md:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden mb-4">
                <div class="px-4 py-3 border-b border-border bg-surface-muted">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Description</h6>
                </div>
                <div class="p-4 text-sm text-ink-secondary">
                    {!! $sale->description !!}
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="px-4 py-3 border-b border-border bg-surface-muted flex items-center justify-between">
                    <h6 class="text-xs font-semibold text-ink uppercase tracking-wider">Flash Sale Products</h6>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-ink border-collapse">
                        <thead>
                            <tr>
                                <th>Vendor</th>
                                <th>Product</th>
                                <th>Sold</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sale->products as $item)
                                <tr id="row-{{ $item->id }}">
                                    <td>{{ $item->seller->name }}</td>
                                    <td class="font-medium text-ink">{{ $item->product->name }}</td>
                                    <td>{{ $item->sold }}</td>
                                    <td id="status-badge-{{ $item->id }}">
                                        @if ($item->status == 1)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Approved</span>
                                        @elseif ($item->status == 2)
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded-full">Rejected</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink bg-yellow-400 rounded-full">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#reviewModal-{{ $item->id }}">
                                            Review
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-8 text-ink-tertiary">No products in this flash sale</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @foreach ($sale->products as $item)
        <div class="modal fade" id="reviewModal-{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-b border-border">
                        <h5 class="modal-title text-sm font-semibold text-ink">Review Flash Sale Product</h5>
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
                                <select name="status" class="w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                    <option value="0" {{ $item->status == 0 ? 'selected' : '' }}>Pending</option>
                                    <option value="1" {{ $item->status == 1 ? 'selected' : '' }}>Approved</option>
                                    <option value="2" {{ $item->status == 2 ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div class="flex justify-end gap-2 pt-3 border-t border-border">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary saveBtn">Save Changes</button>
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
                            badge = '<span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-green-500 rounded-full">Approved</span>';
                        } else if (status == 2) {
                            badge = '<span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-white bg-red-500 rounded-full">Rejected</span>';
                        } else {
                            badge = '<span class="inline-flex items-center px-2 py-0.5 text-xs font-medium text-ink bg-yellow-400 rounded-full">Pending</span>';
                        }

                        $("#status-badge-" + productId).html(badge);
                        modal.modal('hide');
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                    }
                });
            });
        </script>
    @endpush

@endsection