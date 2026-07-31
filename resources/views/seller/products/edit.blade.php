@extends('seller.layouts.app')
@section('title', 'Edit Product')

@push('styles')
<link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .sticky-sidebar { position: sticky; top: 1rem; }
    .cropper-preview { width: 180px; height: 180px; margin: 0 auto; cursor: pointer; overflow: hidden; border-radius: 8px; border: 2px dashed #dee2e6; transition: border-color .2s; background: #f8f9fa; display: flex; align-items: center; justify-content: center; }
    .cropper-preview:hover { border-color: #0d6efd; }
    .collapsible-header { cursor: pointer; user-select: none; }
    .collapsible-header.collapsed .collapse-icon-open { display: none; }
    .collapsible-header:not(.collapsed) .collapse-icon-closed { display: none; }
</style>
@endpush

@section('content')
<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="package" class="text-brand-deep" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.products.index') }}" class="hover:text-ink transition-colors">Products</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Edit Product</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Edit Product</h1>
                    @php
                        $statusKey = (string) $product->status;
                        $pillMap = [
                            $product::STATUS_ACTIVE => ['bg-emerald-50 text-emerald-700', 'bg-emerald-400'],
                            $product::STATUS_PENDING_APPROVAL => ['bg-amber-50 text-amber-700', 'bg-amber-400'],
                            $product::STATUS_INACTIVE => ['bg-neutral-100 text-neutral-600', 'bg-neutral-400'],
                            $product::STATUS_DRAFT => ['bg-sky-50 text-sky-700', 'bg-sky-400'],
                        ];
                        [$pillBg, $dotBg] = $pillMap[$statusKey] ?? ['bg-neutral-100 text-neutral-600', 'bg-neutral-400'];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5" style="background: {{ $dotBg }};"></span>{{ $product->statusName }}
                    </span>
                </div>
                <div class="text-sm text-ink-tertiary flex items-center gap-3 flex-wrap mt-1">
                    <span>SKU: <strong>{{ $product->sku }}</strong></span>
                    <span>Barcode: <strong class="font-mono">{{ $product->barcode }}</strong>
                        <button type="button" class="copy-btn text-ink-secondary hover:text-brand ms-1" data-copy="{{ $product->barcode }}" title="Copy">
                            <i data-lucide="copy" style="width:12px;height:12px;"></i>
                        </button>
                        <button type="button" class="text-ink-secondary hover:text-brand ms-1 regen-barcode-btn" data-url="{{ route('seller.products.regenerate-barcode', $product) }}" data-csrf="{{ csrf_token() }}" title="Generate new barcode">
                            <i data-lucide="refresh-cw" style="width:12px;height:12px;"></i>
                        </button>
                    </span>
                    <a href="{{ route('seller.products.printBarcode') }}?sku={{ $product->sku }}" target="_blank" class="text-brand hover:text-brand-deep no-underline">
                        <i data-lucide="barcode" style="width:14px;height:14px;"></i> Print labels
                    </a>
                    <span>Added: {{ $product->created_at->format('d M, Y') }}</span>
                </div>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.products.show', $product->slug) }}" class="btn btn-light btn-sm" title="Back to Details">
                    <i data-lucide="arrow-left" style="width:14px;height:14px;"></i> Details
                </a>
                <button type="submit" form="productUpdateForm" id="updateBtn" class="btn btn-primary btn-sm">
                    <i data-lucide="save" style="width:14px;height:14px;"></i> Update Product
                </button>
            </div>
        </div>
    </div>
</section>

<form id="productUpdateForm" enctype="multipart/form-data" method="POST">
    @csrf
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="circle-info" class="me-2 text-brand" style="width:16px;height:16px;"></i>Basic Information</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Product Name</label>
                            <input type="text" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->name }}" name="name" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Brand</label>
                            <select name="brand" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors brand-select">
                                <option value="">—</option>
                                @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Category</label>
                            <select name="category_id" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" id="categorySelect" required>
                                <option value="" disabled>—</option>
                                @foreach ($categories as $category)
                                <option value="{{ $category->id }}" @selected($category->id == $product->category_id)>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Subcategory</label>
                            <select name="subcategory_id" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" id="subcategorySelect" {{ $product->subcategory_id ? '' : 'disabled' }}>
                                <option value="" disabled>—</option>
                                @foreach ($categories as $category)
                                @foreach ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" data-category="{{ $category->id }}" @selected($subcategory->id == $product->subcategory_id)>{{ $subcategory->name }}</option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Unit</label>
                            <div class="flex gap-2">
                                <input type="number" step="0.01" name="unit_value" value="{{ $product->unit_value }}" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Value" style="max-width:120px;" required>
                                <select name="unit_id" class="w-full px-3 py-2 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" required>
                                    <option value="" disabled {{ $product->unit_id === null ? 'selected' : '' }}>—</option>
                                    @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ $product->unit_id == $unit->id ? 'selected' : '' }}>{{ $unit->short_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="md:col-span-6">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Tags <span class="text-ink-tertiary">(comma sep.)</span></label>
                            <input type="text" name="tags" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->tags->pluck('name')->implode(', ') }}" placeholder="e.g. cotton, summer">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="tags" class="me-2 text-brand" style="width:16px;height:16px;"></i>Pricing & Inventory</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Cost Price</label>
                            <input type="number" name="cost_price" step="0.01" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->cost_price }}" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Selling Price</label>
                            <input type="number" name="price" step="0.01" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->price }}" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Compare Price <span class="text-ink-tertiary">(sale)</span></label>
                            <input name="compare_price" type="number" step="0.01" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->compare_price }}" placeholder="Optional">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Low Stock Qty</label>
                            <input name="low_stock_quantity" type="number" min="0" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->low_stock_quantity }}" required>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Payment Type</label>
                            <select name="payment_type" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors">
                                @foreach (App\Enums\PaymentType::cases() as $paymentType)
                                <option value="{{ $paymentType->value }}" @selected($paymentType->value == $product->payment_type->value)>{{ $paymentType->title() }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="align-left" class="me-2 text-brand" style="width:16px;height:16px;"></i>Description &amp; Specifications</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Short Description</label>
                            <x-textarea-input name="short_description" :value="$product->short_description" />
                        </div>
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Full Description</label>
                            <x-textarea-input name="description" :value="$product->description" />
                        </div>
                        <div class="col-span-full">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Specifications <span class="text-ink-tertiary">(key:value per line)</span></label>
                            <textarea name="specifications" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" rows="3">@if($product->specifications)@foreach($product->specifications as $key => $value){{ $key }}: {{ $value }}
@endforeach @endif</textarea>
                        </div>
                    </div>
                </div>
            </div>

            @include('seller.products.partials.upload-images')

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="truck" class="me-2 text-brand" style="width:16px;height:16px;"></i>Shipping &amp; Manufacturer</h5>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->weight }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->height }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->width }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->length }}" placeholder="0.00">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Country of Origin</label>
                            <input type="text" name="country_of_origin" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->country_of_origin }}" placeholder="e.g. Bangladesh">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Manufacturer</label>
                            <input type="text" name="manufacturer_name" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->manufacturer_name }}" placeholder="Name">
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-xs font-medium text-ink-secondary mb-1">Manufacturer Details</label>
                            <input type="text" name="manufacturer_details" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $product->manufacturer_details }}" placeholder="Address / contact">
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                    <h5 class="font-bold mb-0 text-sm"><i data-lucide="eye" class="me-2 text-brand" style="width:16px;height:16px;"></i>Visibility</h5>
                </div>
                <div class="p-5">
                    <div class="flex gap-3">
                        <div class="flex items-center gap-2">
                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_featured" {{ $product->is_featured ? 'checked' : '' }}>
                            <label class="text-sm text-ink">Featured</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="best_selling" {{ $product->best_selling ? 'checked' : '' }}>
                            <label class="text-sm text-ink">Best Selling</label>
                        </div>
                        <div class="flex items-center gap-2">
                            <input class="h-4 w-4 rounded border-border text-brand focus:ring-brand" type="checkbox" name="is_visible" {{ $product->is_visible ? 'checked' : '' }}>
                            <label class="text-sm text-ink">Visible on Storefront</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                <div class="bg-surface-muted px-4 py-2.5 border-b border-border collapsible-header collapsed" data-bs-toggle="collapse" data-bs-target="#seoCollapse" aria-expanded="false" role="button">
                    <h5 class="font-bold mb-0 text-sm flex items-center">
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
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Meta Title <span class="text-ink-tertiary">(max 70)</span></label>
                                <input type="text" name="meta_title" maxlength="70" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $seo?->meta_title }}" placeholder="e.g. Red Cotton T-Shirt – Buy Online">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Meta Keywords <span class="text-ink-tertiary">(comma sep.)</span></label>
                                <input type="text" name="meta_keywords" maxlength="255" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $seo?->meta_keywords }}" placeholder="e.g. t-shirt, cotton">
                            </div>
                            <div class="col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">Meta Description <span class="text-ink-tertiary">(max 160)</span></label>
                                <textarea name="meta_description" maxlength="160" rows="2" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Shown in search results.">{{ $seo?->meta_description }}</textarea>
                            </div>
                            <hr class="my-1">
                            <h6 class="text-sm font-semibold">Open Graph</h6>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">OG Title</label>
                                <input type="text" name="og_title" maxlength="70" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" value="{{ $seo?->og_title }}" placeholder="Social sharing title">
                            </div>
                            <div class="md:col-span-6">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">OG Image</label>
                                <input type="file" name="og_image" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors">
                                @if (!empty($seo->og_image))
                                <div class="mt-1"><img src="{{ storage_url($seo->og_image) }}" alt="OG" class="img-thumbnail" style="max-width:100px;"></div>
                                @endif
                            </div>
                            <div class="col-span-full">
                                <label class="block text-xs font-medium text-ink-secondary mb-1">OG Description</label>
                                <textarea name="og_description" maxlength="160" rows="2" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" placeholder="Appears below the title when shared.">{{ $seo?->og_description }}</textarea>
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
            <div class="sticky-sidebar space-y-4">
                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                        <h5 class="font-bold mb-0 text-sm"><i data-lucide="camera" class="me-2 text-brand" style="width:16px;height:16px;"></i>Thumbnail</h5>
                    </div>
                    <div class="p-5 text-center">
                        <div class="cropper-preview" id="thumbnailPreview" data-bs-toggle="modal" data-bs-target="#thumbnailCropperModal">
                            <img src="{{ $product->imageUrl }}" alt="Thumbnail" class="img-fluid" style="max-width:100%;max-height:100%;object-fit:cover;">
                        </div>
                        <span class="text-ink-tertiary text-sm mt-2 block">Click to crop &amp; change. 3:4 ratio</span>
                        <input type="file" name="thumbnail" class="hidden" accept="image/*">
                    </div>
                </div>

                <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
                    <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                        <h5 class="font-bold mb-0 text-sm"><i data-lucide="layers" class="me-2 text-brand" style="width:16px;height:16px;"></i>Product Stats</h5>
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
                <input type="file" id="thumbnailUploadInput" accept="image/*" class="w-full px-3 py-1.5 text-sm text-ink bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors mb-3">
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

    // Barcode copy-to-clipboard + regenerate handler.
    $(document).on('click', '.copy-btn', function() {
        const text = $(this).data('copy');
        if (!text) return;
        navigator.clipboard?.writeText(text);
        const $icon = $(this).find('svg');
        const original = $icon.attr('data-lucide');
        $icon.attr('data-lucide', 'check');
        window.renderIcons && window.renderIcons();
        showSuccessToast?.('Copied ' + text);
        setTimeout(() => {
            $icon.attr('data-lucide', original);
            window.renderIcons && window.renderIcons();
        }, 1500);
    });

    $(document).on('click', '.regen-barcode-btn', function() {
        const btn = $(this);
        const url = btn.data('url');
        const $container = btn.parent();
        if (!url) return;
        if (!confirm('Generate a new barcode for this product? The old one will stop working.')) return;
        btn.prop('disabled', true).find('svg').css('animation', 'spin 0.6s linear infinite');
        $.ajax({
            url: url,
            method: 'POST',
            data: { _token: btn.data('csrf') },
            success: function(resp) {
                if (resp && resp.barcode) {
                    $container.find('strong.font-mono').text(resp.barcode);
                    btn.parent().find('.copy-btn').attr('data-copy', resp.barcode);
                    showSuccessToast?.(resp.message || 'Barcode regenerated.');
                } else {
                    showErrorToast?.('Unexpected response.');
                }
            },
            error: function(xhr) {
                showErrorToast?.(xhr.responseJSON?.message || 'Failed to regenerate barcode.');
            },
            complete: function() {
                btn.prop('disabled', false).find('svg').css('animation', '');
            }
        });
    });

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