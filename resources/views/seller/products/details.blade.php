@extends('seller.layouts.app')
@section('title', $product->name)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

<?php $variantCount = $product->variants->count(); ?>

<div class="d-none">
    <input type="text" name="sku" value="{{ $product->sku }}">
    <input type="text" name="buying_price" value="{{ $product->buying_price }}">
    <input type="text" name="selling_price" value="{{ $product->selling_price }}">
    <input type="text" name="discount_value" value="{{ $product->discount_value }}">
    <select name="discount_type">
        @foreach (\App\Enums\DiscountType::cases() as $type)
        <option value="{{ $type->value }}" @selected($type->value == $product->discount_type)>{{ $type->label() }}</option>
        @endforeach
    </select>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 mb-4 px-3 pt-3 pb-2">
            <div class="d-flex flex-wrap align-items-start gap-4">
                <div style="width:180px; flex-shrink:0;">
                    <div class="border rounded position-relative overflow-hidden bg-light">
                        <img
                            src="{{ $product->imageUrl }}"
                            alt="{{ $product->name }}"
                            class="img-fluid"
                            style="width:100%; height:180px; object-fit:contain;" />
                    </div>
                    @if($product->images->count() > 0)
                    <div class="d-flex flex-wrap gap-1 mt-2">
                        @foreach($product->images as $image)
                        <img
                            src="{{ storage_url($image->image) }}"
                            class="rounded border"
                            style="width:42px; height:42px; object-fit:cover;"
                            alt="Gallery image" />
                        @endforeach
                    </div>
                    @endif
                </div>
                <div class="flex-grow-1">
                    <div class="row g-0 small lh-sm">
                        <div class="col-md-6">
                            <div class="mb-2">
                                <h2 class="mb-0 fw-semibold">{{ $product->name }}</h2>
                            </div>
                            <div class="mb-1"><span class="text-muted">Added:</span> {{ $product->created_at->format('d M, Y') }}</div>
                            <div class="mb-1"><span class="text-muted">SKU:</span> {{ $product->sku }}</div>
                            <div class="mb-1"><span class="text-muted">Brand:</span> {{ $product->brand->name ?? '—' }}</div>
                            <div class="mb-1">
                                <span class="text-muted">Category:</span>
                                {{ $product->category->name ?? '—' }}
                                @if($product->subcategory)
                                › {{ $product->subcategory->name }}
                                @endif
                            </div>
                            <div class="mb-1">
                                <span class="text-muted">Unit:</span>
                                {{ $product->unit_value ?? '' }} {{ $product->unit->short_name ?? '' }}
                            </div>
                            <div><span class="text-muted">Low Stock Qty:</span> {{ $product->low_stock_quantity ?? 0 }}</div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-1"><span class="text-muted">Buying Price:</span> {{ money($product->buying_price) }}</div>
                            <div class="mb-1"><span class="text-muted">Selling Price:</span> {{ money($product->selling_price) }}</div>
                            <div class="mb-1"><span class="text-muted">VAT:</span> {{ $product->vat_percent }}%</div>
                            <div class="mb-1">
                                <span class="text-muted">Discount:</span>
                                @if($product->discount_type)
                                {{ ucfirst($product->discount_type) }}
                                {{ $product->discount_type === 'percentage' ? $product->discount_value.'%' : money($product->discount_value) }} @else None @endif
                            </div>
                            <div class="mb-1"><span class="text-muted">Payment Type:</span> {{ ucfirst($product->payment_type->title()) }}</div>
                            <div><span class="text-muted">Status:</span>
                                @if($product->is_active)
                                <span class="badge bg-success-subtle text-success">Active</span>
                                @else
                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 mt-3 border-top pt-3">
                <a href="{{ route('seller.products.edit', $product->slug) }}" class="btn btn-primary btn-sm d-flex align-items-center gap-1">
                    <i data-feather="edit" class="icon-xs"></i> Edit Product
                </a>

                <button
                    type="button"
                    class="btn btn-outline-danger btn-sm d-flex align-items-center gap-1"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteModal-{{ $product->id }}">
                    <i data-feather="trash-2" class="icon-xs"></i> Delete
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="mb-4 shadow-sm card">
            <div class="bg-white card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 card-title">Stock</h5>
                <div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                        data-bs-target="#stockUpdateModal">
                        <i class="fas fa-plus-circle me-1"></i> Update
                    </button>
                </div>
            </div>
            <div class="card-body" style="max-height: 220px; overflow-y:scroll;">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered align-middle">
                        <thead class="table-light text-center">
                            <tr>
                                <th>Date</th>
                                @if ($variantCount > 0)
                                <th>Variant</th>
                                @endif
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($product->stock_history as $history)
                            <tr>
                                <td class="text-nowrap small">
                                    {{ $history->created_at->format('d/m/y h:i A') }}
                                </td>
                                @if ($variantCount > 0)
                                <td class="text-nowrap small">
                                    {{ $history->variant?->fullName === null ? 'Default' : $history->variant->fullName }}
                                </td>
                                @endif
                                <td class="text-center small">
                                    {{ abs($history->quantity ?? 0) }}
                                </td>
                                <td class="text-center small">
                                    @switch($history->type)
                                    @case(\App\Enums\StockType::ADD_STOCK)
                                    <span class="badge bg-success">Added</span>
                                    @break

                                    @case(\App\Enums\StockType::REMOVE_STOCK)
                                    <span class="badge bg-danger">Removed</span>
                                    @break

                                    @case(\App\Enums\StockType::SET_EXACT_STOCK)
                                    <span class="badge bg-warning text-dark">Set Exact</span>
                                    @break
                                    @endswitch
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No stock history available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center border-bottom">
        <h5 class="mb-0">@if($variantCount > 0) $variantCount @endif Variants</h5>
        <button class="btn btn-light btn-sm border" data-bs-toggle="modal" data-bs-target="#addVariantModal">+ Add Variants</button>
    </div>
    @if($variantCount > 0)
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Image</th>
                    <th>SKU</th>
                    <th>Options</th>
                    <th>Buying Price</th>
                    <th>Selling Price</th>
                    <th>Discount</th>
                    <th>Discounted Price</th>
                    <th>Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->variants as $variant)
                <tr>
                    <td>
                        <img src="{{ $variant->imageUrl }}" class="img-thumbnail" style="width:45px; height:45px; object-fit:cover;" alt="variant-img">
                    </td>
                    <td class="text-monospace small">
                        {{ $variant->sku }}
                        @if($variant->is_default)
                        <span class="badge bg-primary">Default</span>
                        @endif
                    </td>
                    <td><span class="badge bg-light text-dark me-1">{{ $variant->fullName }}</span></td>
                    <td>{{ money($variant->buying_price) }}</td>
                    <td>{{ money($variant->selling_price) }}</td>
                    <td>
                        @if($variant->discount_type)
                        {{ ucfirst($variant->discount_type) }} –
                        {{ $variant->discount_type === 'percentage'
                                    ? $variant->discount_value . '%'
                                    : money($variant->discount_value) }}
                        @else
                        —
                        @endif
                    </td>

                    <td>{{ money($variant->discounted_price ?? $variant->selling_price) }}</td>
                    <td>{{ $variant->availableStock }}</td>
                    <td>
                        <div class="d-flex mt-2">
                            <button class="btn btn-light border btn-sm me-1" data-bs-toggle="modal"
                                data-bs-target="#editVariantModal{{ $variant->id }}">
                                <i data-feather="edit" class="icon-xs"></i>
                            </button>

                            @if ($variant->stock_out <= 0)
                                <button class="btn btn-danger border btn-sm " data-bs-toggle="modal"
                                data-bs-target="#deleteVariantModal{{ $variant->id }}">
                                <i data-feather="trash" class="icon-xs"></i>
                                </button>
                                @endif
                        </div>
                    </td>
                </tr>
                <div class="modal fade" id="editVariantModal{{ $variant->id }}" tabindex="-1" aria-labelledby="editVariantModalLabel{{ $variant->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editVariantModalLabel{{ $variant->id }}">
                                    Edit Variant ({{ $variant->fullName }})
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('seller.productVariants.update', $variant->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="mb-3 col-6">
                                            <label class="form-label">Buying Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ currency() }}</span>
                                                <input type="number" class="form-control" name="buying_price" step="0.01" value="{{ $variant->buying_price }}" required>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-6">
                                            <label class="form-label">Selling Price</label>
                                            <div class="input-group">
                                                <span class="input-group-text">{{ currency() }}</span>
                                                <input type="number" class="form-control" name="selling_price" step="0.01" value="{{ $variant->selling_price }}" required>
                                            </div>
                                        </div>

                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Discount Type</label>
                                            <select name="discount_type" class="form-select">
                                                <option value="" selected>--Choose--</option>
                                                <option value="{{ \App\Enums\DiscountType::FLAT->value }}"
                                                    {{ $variant->discount_type == \App\Enums\DiscountType::FLAT->value ? 'selected' : '' }}>
                                                    {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}
                                                </option>
                                                <option
                                                    value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}"
                                                    {{ $variant->discount_type == \App\Enums\DiscountType::PERCENTAGE->value ? 'selected' : '' }}>
                                                    {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}
                                                </option>
                                            </select>
                                        </div>
                                        <div class="mb-3 col-md-6">
                                            <label class="form-label">Discount Value</label>
                                            <input name="discount_value" type="number" value="{{ $variant->discount_value }}" class="form-control">
                                        </div>
                                        <div class="mb-3 col-md-12">
                                            <label class="form-label">Low Stock Quantity</label>
                                            <input name="low_stock_quantity" type="number" value="{{ $variant->low_stock_quantity }}" class="form-control">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <x-image-input name="image" :image="$variant->imageUrl" />
                                        </div>
                                        <div class="mb-3 col-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="is_default_{{ $variant->id }}" name="is_default" value="1"{{ $variant->is_default ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_default_{{ $variant->id }}">Set as default variant</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-success">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Delete Variant Modal -->
                <div class="modal fade" id="deleteVariantModal{{ $variant->id }}" tabindex="-1"
                    aria-labelledby="deleteModalLabel-{{ $variant->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Confirm Delete</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="text-center modal-body">
                                <div class="alert alert-warning d-flex align-items-center justify-content-center"
                                    role="alert">
                                    <i class="bi bi-exclamation-circle-fill me-2 text-danger"
                                        style="font-size: 1.5rem;"></i>
                                    <p class="mb-0 text-secondary">
                                        Are you sure you want to delete this variant
                                        ({{ $variant->sku }})?
                                    </p>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-center">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Cancel</button>
                                <form action="{{ route('seller.productVariants.delete', $variant->id) }}"
                                    method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="card-body text-center text-muted">
        <p class="mb-0">No variants found for this product.</p>
    </div>
    @endif
</div>

<div id="alertBox"></div>

<div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1"
    aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Confirm
                    Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center modal-body">
                <div class="alert alert-warning d-flex" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2 text-danger" style="font-size: 1.5rem;"></i>
                    <p class="mt-1 text-secondary">
                        Are you sure you want to delete this Product?
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('seller.products.delete', $product->id) }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="stockUpdateModal" tabindex="-1" aria-hidden="true" data-id="{{ $product->id }}">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title">Update Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-outline-info btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#stockInstruction" aria-expanded="false"
                            aria-controls="stockInstruction">
                            ℹ️ স্টক আপডেট নির্দেশনা দেখুন
                        </button>
                        <div class="collapse mt-2" id="stockInstruction">
                            <div class="alert alert-info mb-0" role="alert">
                                <h5 class="alert-heading">📦 স্টক আপডেট করার নিয়ম</h5>
                                <ul class="mb-0">
                                    <li><strong>স্টক অ্যাকশন</strong> অপশন থেকে নির্বাচন করুন:</li>
                                    <ul>
                                        <li><strong>স্টক যুক্ত করুন (Add Stock)</strong> – নতুন পণ্য যোগ করতে।</li>
                                        <li><strong>স্টক বাদ দিন (Remove Stock)</strong> – স্টক কমাতে।</li>
                                        <li><strong>স্টক নির্ধারণ করুন (Set Exact Stock)</strong> – স্টক ঠিক করে
                                            দিতে।</li>
                                    </ul>
                                    <li><strong>Qty</strong> ঘরে সংখ্যাটি দিন (যেমন: 5, 10)।</li>
                                    <li><strong>Note</strong> ঘরে চাইলে মন্তব্য দিন (ঐচ্ছিক)।</li>
                                    <li>বর্তমান স্টক পরিমাণ ব্র্যাকেটের ভিতরে দেখানো হয়েছে।</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if ($variantCount > 0)
                    @foreach ($product->variants as $variant)
                    <h5>{{ $variant->fullName == null ? 'Default' : $variant->fullName }}</h5>
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <select class="form-select form-select-sm"
                                name="stock_action[{{ $variant->id }}]">
                                <option value="{{ \App\Enums\StockType::ADD_STOCK->value }}">
                                    {{ \App\Enums\StockType::ADD_STOCK->label() }}
                                </option>
                                <option value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                    {{ \App\Enums\StockType::REMOVE_STOCK->label() }}
                                </option>
                                <option value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                    {{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Qty
                                    ({{ $variant->stock_in - $variant->stock_out }})
                                </span>
                                <input type="number" class="form-control"
                                    name="stock_quantity[{{ $variant->id }}]" min="1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Note</span>
                                <input type="text" class="form-control"
                                    name="stock_note[{{ $variant->id }}]">
                            </div>
                        </div>
                    </div>
                    <hr>
                    @endforeach
                    @else
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <select class="form-select form-select-sm" name="stock_action_product">
                                <option value="{{ \App\Enums\StockType::ADD_STOCK->value }}">
                                    {{ \App\Enums\StockType::ADD_STOCK->label() }}
                                </option>
                                <option value="{{ \App\Enums\StockType::REMOVE_STOCK->value }}">
                                    {{ \App\Enums\StockType::REMOVE_STOCK->label() }}
                                </option>
                                <option value="{{ \App\Enums\StockType::SET_EXACT_STOCK->value }}">
                                    {{ \App\Enums\StockType::SET_EXACT_STOCK->label() }}
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Qty
                                    ({{ $product->stock_in - $product->stock_out }})
                                </span>
                                <input type="number" class="form-control" name="stock_quantity_product"
                                    min="1">
                            </div>
                        </div>
                        <div class="col-md-4 mb-2">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text">Note</span>
                                <input type="text" class="form-control" name="stock_note_product">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stocks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Variant</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="variantAlert"></div>
                <form id="variantForm">
                    @include('seller.products.variant-generator')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="saveVariantsBtn">Save Variants</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addOptionModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="POST" action="{{ route('seller.options.store', $product->id) }}">
                @csrf
                <div class="modal-header bg-white text-dark">
                    <h5 class="modal-title" id="addOptionModalLabel">Add Product Option</h5>
                    <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-4">
                        <label for="attribute_name" class="form-label fw-bold">Select Existing Option</label>
                        <select class="form-select" id="attribute_name" name="option_id">
                            <option value="" disabled selected>Select an option</option>
                            @foreach ($product_options as $option)
                            <option value="{{ $option->id }}">{{ $option->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="text-center mb-3 fw-semibold text-muted">— or create new —</div>
                    <div class="mb-3">
                        <label for="new_attribute_name" class="form-label fw-bold">New Option Name</label>
                        <input type="text" class="form-control" id="new_attribute_name" name="name"
                            placeholder="Enter new attribute name">
                    </div>
                    <div class="mb-3">
                        <label for="attribute_value" class="form-label fw-bold">Value <span
                                class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="attribute_value" name="value"
                            placeholder="e.g., Red, XL" required>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        const categoryId = "{{ $product->category_id }}";
        showVariantOptions(categoryId);

        function showVariantOptions(categoryId) {
            $('.attributeColumn').addClass('d-none');
            const $visibleColumns = $('.attributeColumn[data-category="' + categoryId + '"]').removeClass('d-none');
            if ($visibleColumns.length > 0) {
                $('#variantGenerator').removeClass('d-none');
            } else {
                $('#variantGenerator').addClass('d-none');
            }
        }

        $('.option_values').select2({
            tags: true,
            placeholder: 'Select or type a value',
            dropdownParent: '#addVariantModal',
            allowClear: true,
            width: '100%',
            closeOnSelect: false
        });

        $('.toggle-desc').on('click', function() {
            let expanded = $(this).attr('aria-expanded') === 'true';
            $(this).text(expanded ? 'Read less' : 'Read more');
        });
    });

    $(".multiple-select-clear-field").select2({
        theme: "bootstrap-5",
        placeholder: "Choose options",
        allowClear: true,
        selectionCssClass: "select2",
        dropdownCssClass: "select2"
    });

    $('#saveVariantsBtn').click(function(e) {
        const variants = collectVariantsData();

        let formData = new FormData();
        formData.append('variants', JSON.stringify(variants));
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('product_id', "{{ $product->id }}");

        $('#variantAlert').html('');
        $('#saveVariantsBtn').attr('disabled', true).text('Saving...');

        $.ajax({
            url: "{{ route('seller.productVariants.store', $product->id) }}",
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                $('#variantAlert').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            Variant added successfully!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);

                setTimeout(function() {
                    $('#addVariantModal').modal('hide');
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                $('#saveVariantsBtn').attr('disabled', false).text('Save Variants');
                $('#variantAlert').html(`
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        `+xhr.responseJSON.message+`
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = Object.values(errors).map(item => `<div>${item[0]}</div>`).join(
                        '');
                    $('#variantAlert').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                ${messages}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                } else {
                    $('#variantAlert').html(`
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                Something went wrong. Please try again.
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        `);
                }
            }
        });
    });

    function collectVariantsData() {
        const variantBody = document.getElementById("variantsTableBody");
        if (!variantBody) return [];

        const variantRows = variantBody.querySelectorAll("tr");
        const variants = [];

        // Get headers only from THIS table
        const table = variantBody.closest("table");
        const headerCells = table.querySelectorAll("thead th");

        // Extract only attribute columns (skip fixed ones)
        const skipColumns = [
            "#",
            "SKU",
            "Buying Price",
            "Selling Price",
            "Discount Type",
            "Discount Value",
            "Image",
            "Actions",
        ];

        const attributeHeaders = Array.from(headerCells)
            .map((cell) => cell.textContent.trim())
            .filter((title) => !skipColumns.includes(title) && title !== "");

        variantRows.forEach((row) => {
            const variant = {};
            variant.sku =
                row.querySelector('td:nth-child(2) input')?.value.trim() || "";

            // Collect attribute values
            variant.attributes = {};

            attributeHeaders.forEach((title, i) => {
                // +3 because the 1st column is #, 2nd is SKU
                const cellInput = row.querySelector(`td:nth-child(${i + 3}) input`);
                variant.attributes[title] = cellInput?.value?.trim() || "";
            });

            // Prices
            const colStart = 3 + attributeHeaders.length;
            const buyingPriceInput = row.querySelector(
                `td:nth-child(${colStart}) input`
            );
            const sellingPriceInput = row.querySelector(
                `td:nth-child(${colStart + 1}) input`
            );
            const discountTypeSelect = row.querySelector(".variant-discount-type");
            const discountValueInput = row.querySelector(".variant-discount-value");
            const imageInput = row.querySelector('input[type="file"]');

            variant.buying_price = buyingPriceInput?.value || "";
            variant.selling_price = sellingPriceInput?.value || "";
            variant.discount_type = discountTypeSelect?.value || "none";
            variant.discount_value = discountValueInput?.value || "";
            variant.image = imageInput?.files?.[0] || null;

            variants.push(variant);
        });

        return variants;
    }

    $('#generateSkuBtn').on('click', function() {
        let skuParts = [];

        let productId = $('input[name="product_id"]').val();
        if (productId) {
            skuParts.push('PID' + productId);
        }

        $('select[name^="attributes"]').each(function() {
            let name = $(this).attr('name').match(/\[(.*?)\]/)[1];
            let value = $(this).val();
            if (value) {
                let formatted = name.toUpperCase().replace(/\s+/g, '') + '-' + value.toUpperCase()
                    .replace(/\s+/g, '');
                skuParts.push(formatted);
            }
        });

        let timestamp = Date.now();
        skuParts.push('TS' + timestamp);

        let generatedSku = skuParts.join('_');

        $('#skuInput').val(generatedSku);
    });

    $('#seoUpdateBtn').click(function(e) {
        let form = $('#productSeoForm')[0];
        let formData = new FormData(form);

        $('#alertBox').html('');

        $.ajax({
            url: "{{ route('seller.products.updateSeo', $product->slug) }}",
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#seoUpdateBtn').attr('disabled', true).text('Updating...');
            },
            success: function(response) {
                $('#seoUpdateBtn').attr('disabled', false).text('Save SEO Settings');
                $('#alertBox').html(`
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        Product updated successfully!
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `);
                setTimeout(function() {
                    location.reload();
                }, 1500);
            },
            error: function(xhr) {
                $('#seoUpdateBtn').attr('disabled', false).text('Save SEO Settings');

                if (xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    let messages = Object.values(errors).map(item =>
                        `<div>${item[0]}</div>`).join('');
                    $('#alertBox').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${messages}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                } else {
                    let errorMessage = "Something went wrong. Please try again.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseText) {
                        errorMessage = xhr.responseText;
                    }
                    $('#alertBox').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            ${errorMessage}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            }
        });
    });
</script>
@endpush