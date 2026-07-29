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
<div class="flex flex-wrap justify-between items-start gap-2 mb-3">
    <div class="flex items-start gap-2">
        <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-light btn-sm mt-1" title="Back to Details">
            <i data-lucide="arrow-left" style="width:16px;height:16px;"></i>
        </a>
        <div>
            <div class="flex items-center gap-2 mb-1">
                <h4 class="font-bold mb-0 text-ink">Edit Product</h4>
                @if ($product->status == $product::STATUS_ACTIVE)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-feedback-success border border-emerald-200">Active</span>
                @elseif ($product->status == $product::STATUS_PENDING_APPROVAL)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-feedback-warning border border-amber-200 text-ink">Pending</span>
                @elseif ($product->status == $product::STATUS_INACTIVE)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-surface-muted text-ink-secondary border border-border">Inactive</span>
                @elseif ($product->status == $product::STATUS_DRAFT)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-feedback-info border border-blue-200 text-ink">Draft</span>
                @endif
            </div>
            <div class="text-sm text-ink-tertiary flex items-center gap-3">
                <span>SKU: <strong>{{ $product->sku }}</strong></span>
                <span>Added: {{ $product->created_at->format('d M, Y') }}</span>
            </div>
        </div>
    </div>
    <button type="submit" form="productUpdateForm" id="updateBtn" class="btn btn-primary">
        <i data-lucide="save" style="width:16px;height:16px;"></i> Update Product
    </button>
</div>

<form id="productUpdateForm" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5><i data-lucide="circle-info" class="me-2 text-brand"></i>Basic Information</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="col-span-full">
                            <label class="form-label-sm">Product Name</label>
                            <input type="text" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->name }}" name="name" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Brand</label>
                            <select name="brand" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors brand-select">
                                <option value="">—</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Category</label>
                            <select name="category_id" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="categorySelect" required>
                                <option value="" disabled>—</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($category->id == $product->category_id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Subcategory</label>
                            <select name="subcategory_id" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" id="subcategorySelect" {{ $product->subcategory_id ? '' : 'disabled' }}>
                                <option value="" disabled>—</option>
                                @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}" @selected($subcategory->id == $product->subcategory_id)>{{ $subcategory->name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-6">
                            <label class="form-label-sm">Unit</label>
                            <div class="flex">
                                <input type="number" step="0.01" name="unit_value" value="{{ $product->unit_value }}" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Value" required>
                                <select name="unit_id" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                    <option value="" disabled {{ $product->unit_id === null ? 'selected' : '' }}>—</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="md:col-span-6">
                            <label class="form-label-sm">Tags <span class="text-ink-tertiary">(comma sep.)</span></label>
                            <input type="text" name="tags" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->tags->pluck('name')->implode(', ') }}" placeholder="e.g. cotton, summer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5><i data-lucide="tags" class="me-2 text-brand"></i>Pricing & Inventory</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Cost Price</label>
                            <input type="number" name="cost_price" step="0.01" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->cost_price }}" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Selling Price</label>
                            <input type="number" name="price" step="0.01" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->price }}" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Compare Price <span class="text-ink-tertiary">(sale)</span></label>
                            <input name="compare_price" type="number" step="0.01" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->compare_price }}" placeholder="Optional">
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Low Stock Qty</label>
                            <input name="low_stock_quantity" type="number" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->low_stock_quantity }}" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Payment Type</label>
                            <select name="payment_type" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors">
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}" @selected($paymentType->value == $product->payment_type->value)>{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5><i data-lucide="align-left" class="me-2 text-brand"></i>Description &amp; Specifications</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="col-span-full">
                            <label class="form-label-sm">Short Description</label>
                            <textarea name="short_description" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="2">{{ $product->short_description }}</textarea>
                        </div>
                        <div class="col-span-full">
                            <label class="form-label-sm">Full Description</label>
                            <textarea name="description" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="5">{{ $product->description }}</textarea>
                        </div>
                        <div class="col-span-full">
                            <label class="form-label-sm">Specifications <span class="text-ink-tertiary">(key:value per line)</span></label>
                            <textarea name="specifications" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3">@if($product->specifications)@foreach($product->specifications as $key => $value){{ $key }}: {{ $value }}
@endforeach @endif</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5><i data-lucide="image" class="me-2 text-brand"></i>Gallery Images</h5>
                </div>
                <div class="p-5">
                    @include('seller.products.partials.upload-images')
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5><i data-lucide="truck" class="me-2 text-brand"></i>Shipping &amp; Manufacturer</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->weight }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->height }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->width }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="form-label-sm">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->length }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->country_of_origin }}" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Manufacturer</label>
                            <input type="text" name="manufacturer_name" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->manufacturer_name }}" placeholder="Name">
                        </div>
                        <div class="md:col-span-4">
                            <label class="form-label-sm">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->manufacturer_details }}" placeholder="Address / contact">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                    <h5><i data-lucide="eye" class="me-2 text-brand"></i>Visibility</h5>
                </div>
                <div class="p-5">
                    <div class="flex gap-3">
                        <div class="flex items-center gap-2 form-switch">
                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="text-sm text-ink">Featured</label>
                        </div>
                        <div class="flex items-center gap-2 form-switch">
                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="best_selling" {{ $product->best_selling ? 'checked' : '' }}>
                            <label class="text-sm text-ink">Best Selling</label>
                        </div>
                        <div class="flex items-center gap-2 form-switch">
                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_visible" {{ $product->is_visible ? 'checked' : '' }}>
                            <label class="text-sm text-ink">Visible on Storefront</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between collapsible-header collapsed" data-bs-toggle="collapse" data-bs-target="#seoCollapse" aria-expanded="false" role="button">
                    <h5 class="flex items-center">
                        <i data-lucide="chevron-down" class="collapse-icon-closed me-2 text-ink-tertiary" style="width:14px;height:14px;"></i>
                        <i data-lucide="chevron-up" class="collapse-icon-open me-2 text-ink-tertiary" style="width:14px;height:14px;"></i>
                        <i data-lucide="search" class="me-2 text-brand" style="width:16px;height:16px;"></i>SEO &amp; Social Share
                    </h5>
                </div>
                <div class="collapse" id="seoCollapse">
                    @php $seo = $product->seo; @endphp
                    <div class="p-5">
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                            <div class="md:col-span-6">
                                <label class="form-label-sm">Meta Title <span class="text-ink-tertiary">(max 70)</span></label>
                                <input type="text" name="meta_title" maxlength="70" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $seo?->meta_title }}" placeholder="e.g. Red Cotton T-Shirt – Buy Online">
                            </div>
                            <div class="md:col-span-6">
                                <label class="form-label-sm">Meta Keywords <span class="text-ink-tertiary">(comma sep.)</span></label>
                                <input type="text" name="meta_keywords" maxlength="255" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $seo?->meta_keywords }}" placeholder="e.g. t-shirt, cotton">
                            </div>
                            <div class="col-span-full">
                                <label class="form-label-sm">Meta Description <span class="text-ink-tertiary">(max 160)</span></label>
                                <textarea name="meta_description" maxlength="160" rows="2" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Shown in search results.">{{ $seo?->meta_description }}</textarea>
                            </div>
                            <hr class="my-1">
                            <h6 class="text-sm font-semibold">Open Graph</h6>
                            <div class="md:col-span-6">
                                <label class="form-label-sm">OG Title</label>
                                <input type="text" name="og_title" maxlength="70" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $seo?->og_title }}" placeholder="Social sharing title">
                            </div>
                            <div class="md:col-span-6">
                                <label class="form-label-sm">OG Image</label>
                                <input type="file" name="og_image" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                @if (!empty($seo->og_image))
                                <div class="mt-1"><img src="{{ storage_url($seo->og_image) }}" alt="OG" class="img-thumbnail" style="max-width:100px;"></div>
                                @endif
                            </div>
                            <div class="col-span-full">
                                <label class="form-label-sm">OG Description</label>
                                <textarea name="og_description" maxlength="160" rows="2" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Appears below the title when shared.">{{ $seo?->og_description }}</textarea>
                            </div>
                            <div class="col-span-full">
                                <button type="button" id="seoUpdateBtn" class="btn btn-outline-primary btn-sm"><i data-lucide="save"></i> Save SEO</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="alertBox"></div>
        </div>

        <div class="lg:col-span-1">
            <div class="sticky-sidebar">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                        <h5><i data-lucide="camera" class="me-2 text-brand"></i>Thumbnail</h5>
                    </div>
                    <div class="p-5 text-center">
                        <div class="cropper-preview" id="thumbnailPreview" data-bs-toggle="modal" data-bs-target="#thumbnailCropperModal">
                            <img src="{{ $product->imageUrl }}" alt="Thumbnail" class="img-fluid" style="max-width:100%;max-height:100%;object-fit:cover;">
                        </div>
                        <span class="text-ink-tertiary text-sm mt-2 block">Click to crop &amp; change. 3:4 ratio</span>
                        <input type="file" name="thumbnail" class="hidden" accept="image/*">
                    </div>
                </div>

                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden section-card">
                    <div class="px-5 py-4 border-b border-border bg-white flex items-center justify-between">
                        <h5><i data-lucide="layers" class="me-2 text-brand"></i>Product Stats</h5>
                    </div>
                    <div class="p-5">
                        @php
                            $vc = $product->variants->count();
                            $totalStock = $product->totalStock;
                            $margin = $product->price - $product->cost_price;
                            $marginPct = $product->cost_price > 0 ? round(($margin / $product->cost_price) * 100, 1) : 0;
                        @endphp
                        <div class="flex justify-around text-center mb-3">
                            <div>
                                <div class="text-base font-bold text-brand">{{ $vc }}</div>
                                <div class="text-sm text-ink-tertiary">Variants</div>
                            </div>
                            <div>
                                <div class="text-base font-bold {{ $totalStock <= $product->low_stock_quantity ? 'text-feedback-danger' : 'text-feedback-success' }}">{{ $totalStock }}</div>
                                <div class="text-sm text-ink-tertiary">Stock</div>
                            </div>
                            <div>
                                <div class="text-base font-bold {{ $margin > 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">{{ $marginPct }}%</div>
                                <div class="text-sm text-ink-tertiary">Margin</div>
                            </div>
                        </div>
                        <div class="text-sm text-ink-tertiary mb-2">
                            <span>Created: {{ $product->created_at->format('d M, Y') }}</span><br>
                            <span>Updated: {{ $product->updated_at->format('d M, Y h:ia') }}</span>
                        </div>
                        <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-light btn-sm w-full" target="__blank">
                            <i data-lucide="external-link" class="icon-xs"></i> View Details
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
                <input type="file" id="thumbnailUploadInput" accept="image/*" class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors mb-3">
                <img id="thumbnailCropperImage" src="#" class="d-none img-fluid" style="max-height:400px;">
            </div>
            <div class="modal-footer"><button type="button" class="btn btn-success btn-sm" id="cropThumbnailBtn"><i data-lucide="check" class="me-1"></i>Crop &amp; Insert</button></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script>
    window.renderIcons && window.renderIcons();

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
                $('#updateBtn').attr('disabled', false).html('<i data-lucide="save" class="me-1"></i> Update Product');
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
            success: (res) => { showSuccessToast(res.message); btn.prop('disabled', false).html('<i data-lucide="save" class="me-1"></i> Save SEO'); },
            error: (xhr) => { btn.prop('disabled', false).html('<i data-lucide="save" class="me-1"></i> Save SEO'); showErrorToast(xhr.responseJSON?.message || 'Error saving SEO.'); }
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