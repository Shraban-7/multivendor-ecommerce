@extends('seller.layouts.app')
@section('title', 'Edit Product')

@push('styles')
<link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .section-card { border-radius: 12px; border: 0; box-shadow: 0 1px 3px rgba(0,0,0,.08); margin-bottom: 1rem; }
    .section-card .card-header { background: #fff; border-bottom: 1px solid #e9ecef; padding: .75rem 1.1rem; }
    .section-card .card-header h5 { font-size: .9rem; font-weight: 600; margin: 0; }
    .section-card .card-body { padding: 1.1rem; }
    .sticky-sidebar { position: sticky; top: 1rem; }
    .form-label-sm { font-size: .82rem; margin-bottom: .25rem; font-weight: 500; }
    .cropper-preview { width: 180px; height: 180px; margin: 0 auto; cursor: pointer; overflow: hidden; border-radius: 8px; border: 2px dashed #dee2e6; transition: border-color .2s; background: #f8f9fa; display: flex; align-items: center; justify-content: center; }
    .cropper-preview:hover { border-color: #0d6efd; }
    .collapsible-header { cursor: pointer; user-select: none; }
    .collapsible-header.collapsed .collapse-icon-open { display: none; }
    .collapsible-header:not(.collapsed) .collapse-icon-closed { display: none; }
</style>
@endpush

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-start gap-2 mb-3">
    <div class="d-flex align-items-start gap-2">
        <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-light border btn-sm d-inline-flex align-items-center gap-1 mt-1" title="Back to Details">
            <i data-feather="arrow-left" style="width:16px;height:16px;"></i>
        </a>
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h4 class="fw-bold mb-0 text-dark">Edit Product</h4>
                @if ($product->status == $product::STATUS_ACTIVE)
                <span class="badge bg-success">Active</span>
                @elseif ($product->status == $product::STATUS_PENDING_APPROVAL)
                <span class="badge bg-warning text-dark">Pending</span>
                @elseif ($product->status == $product::STATUS_INACTIVE)
                <span class="badge bg-secondary">Inactive</span>
                @elseif ($product->status == $product::STATUS_DRAFT)
                <span class="badge bg-info text-dark">Draft</span>
                @endif
            </div>
            <div class="small text-muted d-flex align-items-center gap-3">
                <span>SKU: <strong>{{ $product->sku }}</strong></span>
                <span>Added: {{ $product->created_at->format('d M, Y') }}</span>
            </div>
        </div>
    </div>
    <button type="submit" form="productUpdateForm" id="updateBtn" class="btn btn-primary d-inline-flex align-items-center gap-1">
        <i data-feather="save" style="width:16px;height:16px;"></i> Update Product
    </button>
</div>

<form id="productUpdateForm" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="row">
        <div class="col-lg-8">
            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-info-circle me-2 text-primary"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-sm">Product Name</label>
                            <input type="text" class="form-control form-control-sm" value="{{ $product->name }}" name="name" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Brand</label>
                            <select name="brand" class="form-select form-select-sm brand-select">
                                <option value="">—</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Category</label>
                            <select name="category_id" class="form-select form-select-sm" id="categorySelect" required>
                                <option value="" disabled>—</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($category->id == $product->category_id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Subcategory</label>
                            <select name="subcategory_id" class="form-select form-select-sm" id="subcategorySelect" {{ $product->subcategory_id ? '' : 'disabled' }}>
                                <option value="" disabled>—</option>
                                @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}" @selected($subcategory->id == $product->subcategory_id)>{{ $subcategory->name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-sm">Unit</label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.01" name="unit_value" value="{{ $product->unit_value }}" class="form-control" placeholder="Value" required>
                                <select name="unit_id" class="form-select" required>
                                    <option value="" disabled {{ $product->unit_id === null ? 'selected' : '' }}>—</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-sm">Tags <span class="text-muted">(comma sep.)</span></label>
                            <input type="text" name="tags" class="form-control form-control-sm" value="{{ $product->tags->pluck('name')->implode(', ') }}" placeholder="e.g. cotton, summer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-tags me-2 text-primary"></i>Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label-sm">Cost Price</label>
                            <input type="number" name="cost_price" step="0.01" min="0" class="form-control form-control-sm" value="{{ $product->cost_price }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-sm">Selling Price</label>
                            <input type="number" name="price" step="0.01" min="0" class="form-control form-control-sm" value="{{ $product->price }}" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-sm">Compare Price <span class="text-muted">(sale)</span></label>
                            <input name="compare_price" type="number" step="0.01" min="0" class="form-control form-control-sm" value="{{ $product->compare_price }}" placeholder="Optional">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-sm">Low Stock Qty</label>
                            <input name="low_stock_quantity" type="number" min="0" class="form-control form-control-sm" value="{{ $product->low_stock_quantity }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Payment Type</label>
                            <select name="payment_type" class="form-select form-select-sm">
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}" @selected($paymentType->value == $product->payment_type->value)>{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-align-left me-2 text-primary"></i>Description &amp; Specifications</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label-sm">Short Description</label>
                            <textarea name="short_description" class="form-control form-control-sm" rows="2">{{ $product->short_description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-sm">Full Description</label>
                            <textarea name="description" class="form-control form-control-sm" rows="5">{{ $product->description }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label-sm">Specifications <span class="text-muted">(key:value per line)</span></label>
                            <textarea name="specifications" class="form-control form-control-sm" rows="3">@if($product->specifications)@foreach($product->specifications as $key => $value){{ $key }}: {{ $value }}
@endforeach @endif</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-image me-2 text-primary"></i>Gallery Images</h5>
                </div>
                <div class="card-body">
                    @include('seller.products.partials.upload-images')
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-truck me-2 text-primary"></i>Shipping &amp; Manufacturer</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label-sm">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control form-control-sm" value="{{ $product->weight }}" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-sm">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control form-control-sm" value="{{ $product->height }}" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-sm">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="form-control form-control-sm" value="{{ $product->width }}" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-sm">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="form-control form-control-sm" value="{{ $product->length }}" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="form-control form-control-sm" value="{{ $product->country_of_origin }}" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Manufacturer</label>
                            <input type="text" name="manufacturer_name" class="form-control form-control-sm" value="{{ $product->manufacturer_name }}" placeholder="Name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-sm">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="form-control form-control-sm" value="{{ $product->manufacturer_details }}" placeholder="Address / contact">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header">
                    <h5><i class="fas fa-eye me-2 text-primary"></i>Visibility</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="form-check-label small">Featured</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="best_selling" {{ $product->best_selling ? 'checked' : '' }}>
                            <label class="form-check-label small">Best Selling</label>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_visible" {{ $product->is_visible ? 'checked' : '' }}>
                            <label class="form-check-label small">Visible on Storefront</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card section-card">
                <div class="card-header collapsible-header collapsed" data-bs-toggle="collapse" data-bs-target="#seoCollapse" aria-expanded="false" role="button">
                    <h5 class="d-flex align-items-center">
                        <i data-feather="chevron-down" class="collapse-icon-closed me-2 text-muted" style="width:14px;height:14px;"></i>
                        <i data-feather="chevron-up" class="collapse-icon-open me-2 text-muted" style="width:14px;height:14px;"></i>
                        <i data-feather="search" class="me-2 text-primary" style="width:16px;height:16px;"></i>SEO &amp; Social Share
                    </h5>
                </div>
                <div class="collapse" id="seoCollapse">
                    @php $seo = $product->seo; @endphp
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label-sm">Meta Title <span class="text-muted">(max 70)</span></label>
                                <input type="text" name="meta_title" maxlength="70" class="form-control form-control-sm" value="{{ $seo?->meta_title }}" placeholder="e.g. Red Cotton T-Shirt – Buy Online">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">Meta Keywords <span class="text-muted">(comma sep.)</span></label>
                                <input type="text" name="meta_keywords" maxlength="255" class="form-control form-control-sm" value="{{ $seo?->meta_keywords }}" placeholder="e.g. t-shirt, cotton">
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">Meta Description <span class="text-muted">(max 160)</span></label>
                                <textarea name="meta_description" maxlength="160" rows="2" class="form-control form-control-sm" placeholder="Shown in search results.">{{ $seo?->meta_description }}</textarea>
                            </div>
                            <hr class="my-1">
                            <h6 class="small fw-semibold">Open Graph</h6>
                            <div class="col-md-6">
                                <label class="form-label-sm">OG Title</label>
                                <input type="text" name="og_title" maxlength="70" class="form-control form-control-sm" value="{{ $seo?->og_title }}" placeholder="Social sharing title">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label-sm">OG Image</label>
                                <input type="file" name="og_image" class="form-control form-control-sm">
                                @if (!empty($seo->og_image))
                                <div class="mt-1"><img src="{{ storage_url($seo->og_image) }}" alt="OG" class="img-thumbnail" style="max-width:100px;"></div>
                                @endif
                            </div>
                            <div class="col-12">
                                <label class="form-label-sm">OG Description</label>
                                <textarea name="og_description" maxlength="160" rows="2" class="form-control form-control-sm" placeholder="Appears below the title when shared.">{{ $seo?->og_description }}</textarea>
                            </div>
                            <div class="col-12">
                                <button type="button" id="seoUpdateBtn" class="btn btn-outline-primary btn-sm d-inline-flex align-items-center gap-1"><i class="fas fa-save"></i> Save SEO</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="alertBox"></div>
        </div>

        <div class="col-lg-4">
            <div class="sticky-sidebar">
                <div class="card section-card">
                    <div class="card-header">
                        <h5><i class="fas fa-camera me-2 text-primary"></i>Thumbnail</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="cropper-preview" id="thumbnailPreview" data-bs-toggle="modal" data-bs-target="#thumbnailCropperModal">
                            <img src="{{ $product->imageUrl }}" alt="Thumbnail" class="img-fluid" style="max-width:100%;max-height:100%;object-fit:cover;">
                        </div>
                        <span class="text-muted small mt-2 d-block">Click to crop &amp; change. 3:4 ratio</span>
                        <input type="file" name="thumbnail" class="d-none" accept="image/*">
                    </div>
                </div>

                <div class="card section-card">
                    <div class="card-header">
                        <h5><i class="fas fa-layer-group me-2 text-primary"></i>Product Stats</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $vc = $product->variants->count();
                            $totalStock = $product->totalStock;
                            $margin = $product->price - $product->cost_price;
                            $marginPct = $product->cost_price > 0 ? round(($margin / $product->cost_price) * 100, 1) : 0;
                        @endphp
                        <div class="d-flex justify-content-around text-center mb-3">
                            <div>
                                <div class="fs-5 fw-bold text-primary">{{ $vc }}</div>
                                <div class="small text-muted">Variants</div>
                            </div>
                            <div>
                                <div class="fs-5 fw-bold {{ $totalStock <= $product->low_stock_quantity ? 'text-danger' : 'text-success' }}">{{ $totalStock }}</div>
                                <div class="small text-muted">Stock</div>
                            </div>
                            <div>
                                <div class="fs-5 fw-bold {{ $margin > 0 ? 'text-success' : 'text-danger' }}">{{ $marginPct }}%</div>
                                <div class="small text-muted">Margin</div>
                            </div>
                        </div>
                        <div class="small text-muted mb-2">
                            <span>Created: {{ $product->created_at->format('d M, Y') }}</span><br>
                            <span>Updated: {{ $product->updated_at->format('d M, Y h:ia') }}</span>
                        </div>
                        <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-outline-secondary btn-sm w-100 d-inline-flex align-items-center justify-content-center gap-1" target="__blank">
                            <i data-feather="external-link" class="icon-xs"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<div class="modal fade" id="thumbnailCropperModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header"><h5 class="modal-title">Crop Thumbnail</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="closeCropperModalBtn"></button></div>
            <div class="modal-body text-center">
                <input type="file" id="thumbnailUploadInput" accept="image/*" class="form-control form-control-sm mb-3">
                <img id="thumbnailCropperImage" src="#" class="d-none img-fluid" style="max-height:400px;">
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-success btn-sm" id="cropThumbnailBtn"><i class="fas fa-check me-1"></i>Crop &amp; Insert</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
    feather.replace();

    $(".brand-select").select2({ tags: true, theme: "bootstrap-5" });

    $('#categorySelect').change(function() {
        let catId = $(this).val(), hasOpts = false;
        $('#subcategorySelect').val('').trigger('change');
        $('#subcategorySelect option').each(function() {
            if (catId == $(this).data('category')) { $(this).show(); hasOpts = true; }
            else { $(this).hide(); }
        });
        $('#subcategorySelect').attr('disabled', !hasOpts);
    });

    $('#updateBtn').on('click', function(e) {
        e.preventDefault();
        let formData = new FormData($('#productUpdateForm')[0]);
        $.ajax({
            url: "{{ route('seller.products.update', $product->slug) }}",
            type: 'POST', data: formData, processData: false, contentType: false,
            beforeSend: () => { $('#updateBtn').attr('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Updating...'); },
            success: (res) => { showSuccessToast('Product updated!'); setTimeout(() => window.location.href = res.redirect, 1500); },
            error: (xhr) => {
                $('#updateBtn').attr('disabled', false).html('<i class="fas fa-save me-1"></i> Update Product');
                if (xhr.status === 422) showErrorToast(Object.values(xhr.responseJSON.errors).map(i => i[0]).join('<br>'));
                else showErrorToast(xhr.responseJSON?.message || 'Something went wrong.');
            }
        });
    });

    $('#seoUpdateBtn').on('click', function(e) {
        e.preventDefault();
        let btn = $(this);
        let formData = new FormData($('#productUpdateForm')[0]);
        $.ajax({
            url: "{{ route('seller.products.updateSeo', $product->slug) }}",
            type: 'POST', data: formData, processData: false, contentType: false,
            beforeSend: () => btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Saving...'),
            success: (res) => { showSuccessToast(res.message); btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save SEO'); },
            error: (xhr) => { btn.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Save SEO'); showErrorToast(xhr.responseJSON?.message || 'Error saving SEO.'); }
        });
    });

    let cropper;
    const cm = new bootstrap.Modal(document.getElementById('thumbnailCropperModal'));
    document.getElementById('thumbnailUploadInput').addEventListener('change', function() {
        const file = this.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.getElementById('thumbnailCropperImage');
            img.src = e.target.result; img.classList.remove('d-none');
            if (cropper) cropper.destroy();
            cropper = new Cropper(img, { aspectRatio: 3/4, viewMode: 1, autoCropArea: 1 });
        };
        reader.readAsDataURL(file);
    });
    document.getElementById('cropThumbnailBtn').addEventListener('click', function() {
        if (!cropper) return;
        cropper.getCroppedCanvas({ width: 900, height: 1200, imageSmoothingEnabled: true, imageSmoothingQuality: 'high' }).toBlob(function(blob) {
            document.getElementById('thumbnailPreview').innerHTML = `<img src="${URL.createObjectURL(blob)}" class="img-fluid" style="max-width:100%;max-height:100%;object-fit:cover;">`;
            const dt = new DataTransfer();
            dt.items.add(new File([blob], "thumbnail.png", { type: 'image/png' }));
            document.querySelector('input[name="thumbnail"]').files = dt.files;
            cm.hide();
            document.getElementById('thumbnailUploadInput').value = '';
            document.getElementById('thumbnailCropperImage').classList.add('d-none');
            cropper.destroy(); cropper = null;
        }, 'image/png');
    });
    document.getElementById('closeCropperModalBtn').addEventListener('click', () => {
        if (cropper) { cropper.destroy(); cropper = null; }
        document.getElementById('thumbnailUploadInput').value = '';
        document.getElementById('thumbnailCropperImage').classList.add('d-none');
    });
</script>
@endpush