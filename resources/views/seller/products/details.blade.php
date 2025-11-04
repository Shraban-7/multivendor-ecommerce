@extends('seller.layouts.app')
@section('title', $product->name)
@section('content')
    <!-- <div class="content-header mb-3">
            <div class="container-fluid">
                <div class="row gy-3 align-items-start align-items-lg-center justify-content-between">
                    <div class="col-12 col-lg-6">
                        <h4 class="m-0 text-dark">Product Details</h4>
                    </div>
                    <div class="col-12 col-lg-6 text-lg-end">
                        <a href="{{ route('seller.products.edit', $product->slug) }}"
                            class="btn btn-primary btn-sm w-100 w-lg-auto">
                            <i data-feather="edit" class="icon-xs me-1"></i> Edit Product
                        </a>
                    </div>
                </div>
            </div>
        </div> -->

    <div id="alertBox"></div>

    <div class="row">
        <!-- Product Overview Card -->
        <div class="col-lg-8">
            <div class="mb-4 shadow-sm card">
                <!-- <div class="bg-white card-header d-flex justify-content-between">
                        <h5 class="mb-0 card-title">Product Overview</h5>
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2">
                            <button class="btn btn-danger btn-sm" title="Delete" data-bs-toggle="modal"
                                data-bs-target="#deleteModal-{{ $product->id }}">
                                <i data-feather="trash-2" class="icon-xs"></i> Delete Product
                            </button>
                        </div>
                    </div> -->
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img src="{{ storage_url($product->thumbnail) }}" alt="{{ $product->name }}"
                                class="img-fluid w-100" style="height: 250px; object-fit: contain;">
                        </div>

                        <div class="col-12 col-md-8">
                            <h3>{{ $product->name }}</h3>

                            <div class="mb-2 d-flex flex-wrap gap-2">
                                <span class="badge border bg-light text-dark small">{{ $product->category->name }}</span>
                                @if ($product->subcategory)
                                    <span
                                        class="badge border bg-light text-dark small">{{ $product->subcategory->name }}</span>
                                @endif

                                @if ($product->brand)
                                    <span class="badge border bg-light text-dark small">{{ $product->brand->name }}</span>
                                @endif
                            </div>

                            @if (!empty($product->description))
                                @php
                                    $plainDescription = strip_tags($product->description);
                                    $wordCount = str_word_count($plainDescription);
                                @endphp
                                <strong class="mt-2">Description:</strong>

                                @if ($wordCount > 500)
                                    <div class="collapse" id="desc-{{ $product->id }}">
                                        <div class="product-description">
                                            {!! $product->description !!}
                                        </div>
                                    </div>
                                    <a class="btn btn-link p-0 mt-1 toggle-desc" data-bs-toggle="collapse"
                                        href="#desc-{{ $product->id }}" role="button" aria-expanded="false"
                                        aria-controls="desc-{{ $product->id }}">
                                        Read more
                                    </a>
                                @else
                                    <div class="product-description">
                                        {!! $product->description !!}
                                    </div>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-between">
                        <button class="btn btn-danger btn-sm" data-bs-toggle="modal"
                            data-bs-target="#deleteModal-{{ $product->id }}">
                            <i data-feather="trash-2" class="icon-xs"></i> Delete Product
                        </button>
                        <a href="{{ route('seller.products.edit', $product->slug) }}" class="btn btn-primary btn-sm">
                            <i data-feather="edit" class="icon-xs"></i> Edit Product
                        </a>
                    </div>
                </div>
            </div>

            <!-- Product Variants -->
            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0">Product Variants ({{ $product->variants->count() }})</h5>
                    <div>
                        <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addOptionModal">
                            <i data-feather="plus" class="icon-xs"></i> Add Option
                        </button>
                        {{-- <button type="button" class="btn btn-sm btn-outline-success btn-sm" data-bs-toggle="modal"
                            data-bs-target="#addVariantModal">
                            <i data-feather="plus" class="icon-xs"></i> Add Variant
                        </button> --}}
                    </div>
                </div>

                @if ($product->variants->isEmpty())
                    <div class="alert alert-info text-center">No variants.</div>
                @endif

                <div class="row g-3">
                    @foreach ($product->variants as $variant)
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <h6 class="card-title mb-0">SKU: {{ $variant->sku }}</h6>
                                    <div class="small text-muted">
                                        @if ($variant->options && count($variant->options))
                                            <p class="mb-1">
                                                @foreach ($variant->options as $key => $option)
                                                    <strong>{{ $option->option_value->option->name }}:</strong>
                                                    {{ $option->option_value->value }}
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </p>
                                        @endif
                                        <hr class="my-2">
                                        <p class="mb-1">Stock: <strong>{{ $variant->stock }}</strong></p>
                                        <p class="mb-1">Price:
                                            <strong>{{ money($variant->selling_price) }}</strong>
                                        </p>
                                        <p class="mb-0">Discounted Price:
                                            <strong>{{ money($variant->discounted_price) }}</strong>
                                        </p>
                                    </div>

                                    @if ($variant->is_default)
                                        <span class="badge bg-success">Default Variant</span>
                                    @endif

                                    <div class="d-flex mt-2">
                                        <button class="btn btn-light border btn-sm me-1" data-bs-toggle="modal"
                                            data-bs-target="#editVariantModal{{ $variant->id }}">
                                            <i data-feather="edit" class="icon-xs"></i> Edit
                                        </button>

                                        @if ($variant->stock_out <= 0)
                                            <button class="btn btn-danger border btn-sm " data-bs-toggle="modal"
                                                data-bs-target="#deleteVariantModal{{ $variant->id }}">
                                                <i data-feather="trash" class="icon-xs"></i> Delete
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Variant Modal -->
                            <div class="modal fade" id="editVariantModal{{ $variant->id }}" tabindex="-1"
                                aria-labelledby="editVariantModalLabel{{ $variant->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editVariantModalLabel{{ $variant->id }}">
                                                Edit Variant
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form
                                            action="{{ route('seller.productVariants.update', [$product->id, $variant->id]) }}"
                                            method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row">
                                                    <div class="mb-3 col-6">
                                                        <label class="form-label">Buying Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">{{ currency() }}</span>
                                                            <input type="number" class="form-control"
                                                                name="buying_price" step="0.01"
                                                                value="{{ $variant->buying_price }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3 col-6">
                                                        <label class="form-label">Selling Price</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">{{ currency() }}</span>
                                                            <input type="number" class="form-control"
                                                                name="selling_price" step="0.01"
                                                                value="{{ $variant->selling_price }}" required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3 col-md-6">
                                                        <label class="form-label">Discount Type</label>
                                                        <select name="discount_type" class="form-select">
                                                            <option value="" selected>
                                                                --Choose--</option>
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
                                                        <input name="discount_value" type="number"
                                                            value="{{ $variant->discount_value }}" class="form-control">
                                                    </div>
                                                    <div class="mb-3 col-md-12">
                                                        <label class="form-label">Low Stock
                                                            Quantity</label>
                                                        <input name="low_stock_quantity" type="number"
                                                            value="{{ $variant->low_stock_quantity }}"
                                                            class="form-control">
                                                    </div>
                                                    <div class="col-12 mb-3">
                                                        <x-image-input name="image" :image="storage_url($variant?->image)" />
                                                    </div>
                                                    <div class="mb-3 col-12">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="is_default_{{ $variant->id }}" name="is_default"
                                                                value="1"
                                                                {{ $variant->is_default ? 'checked' : '' }}>
                                                            <label class="form-check-label"
                                                                for="is_default_{{ $variant->id }}">
                                                                Set as default variant
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light border"
                                                    data-bs-dismiss="modal">Cancel</button>
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
                        </div>
                    @endforeach
                </div>
            </div>

            @if (count($product->images))
                <div class="card card-body mb-3">
                    <div class="product-gallery">
                        <h6 class="mb-2 text-muted fw-bold small">Gallery Images</h6>
                        <div class="row g-2">
                            @foreach ($product->images as $image)
                                <div class="col-4 col-sm-3 col-md-2">
                                    <img src="{{ storage_url($image->image) }}" alt="Gallery image"
                                        class="img-fluid border rounded"
                                        style="object-fit: cover; width: 100%; height: 100px;">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h5 class="mb-0">SEO & Social Share Settings</h5>
                </div>

                <div class="card-body">
                    <form id="productSeoForm" enctype="multipart/form-data">
                        @csrf
                        <h5 class="mb-3">Meta Information (Search Engines)</h5>

                        <div class="mb-3">
                            <label class="form-label">Meta Title
                                <small class="text-muted">(max 70 characters)</small>
                            </label>
                            <input type="text" name="meta_title" maxlength="70" class="form-control"
                                placeholder="e.g. Red Cotton T-Shirt – Buy Online" value="{{ $seo?->meta_title }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Description
                                <small class="text-muted">(recommended up to 160 characters)</small>
                            </label>
                            <textarea name="meta_description" maxlength="160" rows="3" class="form-control"
                                placeholder="Short, keyword-rich description shown in Google results.">{{ $seo?->meta_description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Meta Keywords
                                <small class="text-muted">(comma separated)</small>
                            </label>
                            <input type="text" name="meta_keywords" maxlength="255" class="form-control"
                                placeholder="e.g. t-shirt, red cotton shirt, mens fashion"
                                value="{{ $seo?->meta_keywords }}">
                            <small class="text-muted d-block mt-1">
                                *Keywords are optional; modern search engines rely more on content.
                            </small>
                        </div>

                        <hr class="my-4">

                        <!-- Open Graph Section -->
                        <h5 class="mb-3">Open Graph (Social Media Preview)</h5>
                        <p class="small text-muted">
                            These fields control how the product appears when shared on Facebook, WhatsApp,
                            LinkedIn, etc. If left blank, the Meta Title/Description will be used.
                        </p>

                        <div class="mb-3">
                            <label class="form-label">OG Title
                                <small class="text-muted">(max 70 characters)</small>
                            </label>
                            <input type="text" name="og_title" maxlength="70" class="form-control"
                                placeholder="Catchy title for social sharing" value="{{ $seo?->og_title }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">OG Description
                                <small class="text-muted">(recommended up to 160 characters)</small>
                            </label>
                            <textarea name="og_description" maxlength="160" rows="3" class="form-control"
                                placeholder="Appears below the title when shared on social media.">{{ $seo?->og_description }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">OG Image</label>
                            <input type="file" name="og_image" class="form-control">

                            @if (!empty($seo->og_image))
                                <div class="mt-2">
                                    <p class="mb-1">Current OG Image:</p>
                                    <img src="{{ storage_url($seo->og_image) }}" alt="OG Image" class="img-thumbnail"
                                        style="max-width: 200px;">
                                </div>
                            @endif

                            <small class="text-muted d-block mt-1">
                                Recommended size: <strong>1200 × 630 px</strong>, JPG/PNG/WebP, max 2 MB.
                                This image will be shown as the preview when the link is shared.
                            </small>
                        </div>
                        <div class="text-end">
                            <button type="button" id="seoUpdateBtn" class="btn btn-primary">
                                Save SEO Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="mb-4 shadow-sm card">
                <div class="bg-white card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 card-title">Inventory Status</h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                            data-bs-target="#stockUpdateModal">
                            <i class="fas fa-plus-circle me-1"></i> Update Stock
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Header -->
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start mb-3">
                                <h6 class="text-muted fw-bold small mb-2 mb-md-0">
                                    <i class="fas fa-boxes-stacked me-1 text-primary"></i> Stock History
                                </h6>

                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-sm table-hover table-bordered align-middle">
                                    <thead class="table-light text-center">
                                        <tr>
                                            <th>Date</th>
                                            <th>Variant</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($product->stock_history as $history)
                                            <tr>
                                                <td class="text-nowrap small">
                                                    {{ $history->created_at?->format('d/m/y h:i A') ?? '-' }}
                                                </td>
                                                <td class="text-nowrap small">
                                                    {{ $history->variant?->fullName === null ? 'Default' : $history->variant->fullName }}
                                                </td>
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
                                                    <td colspan="4" class="text-center text-muted">No stock history
                                                        available</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- product delete modal -->
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

        <!-- Stock Update Modal -->
        <div class="modal fade" id="stockUpdateModal2" tabindex="-1" aria-hidden="true" data-id="{{ $product->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                        <div class="modal-header">
                            <h5 class="modal-title">Update Inventory</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Variants</label>
                                <select class="form-select" id="variant" name="product_variant_id">
                                    <option value="">--Select Variant--</option>
                                    @foreach ($product->variants as $variant)
                                        <option value="{{ $variant->id }}">
                                            {{ $variant?->fullName == null ? 'Default' : $variant->fullName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Action</label>
                                <select class="form-select" id="stockAction" name="stock_action">
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

                            <div class="mb-3">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="stockQuantity" name="stock_quantity"
                                    min="1" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note (Optional)</label>
                                <textarea class="form-control" id="stockNote" name="stock_note" rows="2"
                                    placeholder="Reason for this inventory change"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save
                                Changes</button>
                        </div>
                    </form>
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

                            @foreach ($product->variants as $variant)
                                <h5>{{ $variant->fullName == null ? 'Default' : $variant->fullName }}</h5>
                                <div class="row">
                                    <div class="col-md-4 mb-2">
                                        <select class="form-select form-select-sm" name="stock_action[{{ $variant->id }}]">
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
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Stocks</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Variant Add Modal -->
        <div class="modal fade" id="addVariantModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Variant</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div id="variantAlert"></div>
                        <form id="variantForm" action="{{ route('seller.productVariants.store', $product->id) }}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label class="form-label">Buying Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ currency() }}</span>
                                        <input type="number" class="form-control" name="buying_price" required>
                                    </div>
                                </div>
                                <div class="mb-3 col-6">
                                    <label class="form-label">Selling Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">{{ currency() }}</span>
                                        <input type="number" class="form-control" name="selling_price" required>
                                    </div>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Discount Type</label>
                                    <select name="discount_type" class="form-select w-100" id="" required>
                                        <option value="" selected>--Choose--</option>
                                        <option value="{{ \App\Enums\DiscountType::FLAT->value }}"
                                            {{ $product->discount_type == \App\Enums\DiscountType::FLAT->value ? 'selected' : '' }}>
                                            {{ ucfirst(\App\Enums\DiscountType::FLAT->label()) }}
                                        </option>
                                        <option value="{{ \App\Enums\DiscountType::PERCENTAGE->value }}"
                                            {{ $product->discount_type == \App\Enums\DiscountType::PERCENTAGE->value ? 'selected' : '' }}>
                                            {{ ucfirst(\App\Enums\DiscountType::PERCENTAGE->label()) }}
                                        </option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label class="form-label">Discount Value</label>
                                    <input name="discount_value" type="number" class="form-control" required>
                                </div>
                                <div>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold">Select Options</label>
                                            <select id="mainOptionSelect" class="form-select" multiple>
                                                @foreach ($product_options as $option)
                                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        @foreach ($product_options as $option)
                                            <div class="col-md-12 mb-3 option-values" id="option-{{ $option->id }}"
                                                style="display:none;">
                                                <label class="form-label fw-bold">{{ $option->name }}</label>
                                                <select name="option_values[{{ $option->id }}][]" class="form-select"
                                                    multiple>
                                                    @foreach ($option->options as $item)
                                                        <option value="{{ $item->id }}">{{ $item->value }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveVariant">Save Variant</button>
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

        @push('scripts')
            <script>
                $(document).ready(function() {
                    $('.toggle-desc').on('click', function() {
                        let expanded = $(this).attr('aria-expanded') === 'true';
                        $(this).text(expanded ? 'Read less' : 'Read more');
                    });

                    $('#mainOptionSelect').select2({
                        theme: "bootstrap-5",
                        placeholder: "Choose options",
                        allowClear: true,
                        selectionCssClass: "select2",
                        dropdownCssClass: "select2"
                    });

                    $('.option-values select').select2({
                        theme: "bootstrap-5",
                        placeholder: "Choose option values",
                        allowClear: true
                    });

                    $('#mainOptionSelect').on('change', function() {
                        $('.option-values').hide();
                        let selected = $(this).val() || [];

                        selected.forEach(function(id) {
                            $('#option-' + id).show();
                        });
                    });

                    $('#mainOptionSelect').trigger('change');

                    $('#attributeSelect').on('change', function() {
                        let attributeId = $(this).val();

                        if (attributeId) {
                            $.ajax({
                                url: `/seller/products/get-options/${attributeId}`,
                                type: 'GET',
                                success: function(data) {
                                    $('#optionSelect').empty();
                                    $('#optionSelect').append(
                                        '<option disabled selected>Select Attribute Option</option>'
                                    );

                                    $.each(data, function(key, option) {
                                        $('#optionSelect').append(
                                            `<option value="${option.id}">${option.value}</option>`
                                        );
                                    });
                                }
                            });
                        } else {
                            $('#optionSelect').empty();
                            $('#optionSelect').append('<option disabled selected>Select Attribute Option</option>');
                        }
                    });
                });

                $(".multiple-select-clear-field").select2({
                    theme: "bootstrap-5",
                    placeholder: "Choose options",
                    allowClear: true,
                    selectionCssClass: "select2",
                    dropdownCssClass: "select2"
                });

                $('#saveVariant').click(function(e) {
                    e.preventDefault();

                    let form = $('#variantForm')[0];
                    let formData = new FormData(form);

                    $('#variantAlert').html('');
                    $('#saveVariant').attr('disabled', true).text('Saving...');

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
                            $('#saveVariant').attr('disabled', false).text('Save Variant');

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
    @endsection
