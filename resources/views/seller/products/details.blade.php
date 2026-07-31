@extends('seller.layouts.app')
@section('title', $product->name)

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .thumbnail-gallery-img { width: 52px; height: 52px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: border-color .15s; }
    .thumbnail-gallery-img:hover, .thumbnail-gallery-img.active { border-color: #0d6efd; }
</style>
@endpush

@section('content')
<?php
    $variantCount = $product->variants->count();
    $totalStock = $product->variants->sum('stock_in') - $product->variants->sum('stock_out');
    $margin = $product->price - $product->cost_price;
    $marginPercent = $product->cost_price > 0 ? round(($margin / $product->cost_price) * 100, 1) : 0;
    $seo = $product->seo;
?>

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="package" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <a href="{{ route('seller.products.index') }}" class="hover:text-ink-emphasis">Catalog</a>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold truncate">{{ $product->name }}</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0 truncate max-w-[420px]">{{ $product->name }}</h1>
                    @php
                        $pillBg = match ((int) $product->status) {
                            (int) $product::STATUS_ACTIVE            => 'bg-feedback-success/15 text-feedback-success',
                            (int) $product::STATUS_PENDING_APPROVAL  => 'bg-feedback-warning/15 text-feedback-warning',
                            (int) $product::STATUS_INACTIVE          => 'bg-surface-muted text-ink-tertiary',
                            (int) $product::STATUS_DELETED           => 'bg-feedback-danger/15 text-feedback-danger',
                            default                                  => 'bg-surface-muted text-ink-tertiary',
                        };
                        $pillLabel = match ((int) $product->status) {
                            (int) $product::STATUS_ACTIVE            => 'Active',
                            (int) $product::STATUS_PENDING_APPROVAL  => 'Pending',
                            (int) $product::STATUS_INACTIVE          => 'Inactive',
                            (int) $product::STATUS_DELETED           => 'Deleted',
                            default                                  => ucfirst((string) $product->status),
                        };
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider {{ $pillBg }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current opacity-70 me-1.5"></span>
                        {{ $pillLabel }}
                    </span>
                    @if ($product->is_visible)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info"><i data-lucide="eye" style="width:11px;height:11px;" class="me-1"></i> Visible</span>
                    @else
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-surface-muted text-ink-tertiary"><i data-lucide="eye-off" style="width:11px;height:11px;" class="me-1"></i> Hidden</span>
                    @endif
                </div>
                <p class="text-sm text-ink-secondary mb-0 inline-flex flex-wrap items-center gap-2">
                    <span>SKU: <strong class="text-ink-emphasis">{{ $product->sku }}</strong></span>
                    <span class="text-ink-tertiary">·</span>
                    <span><i data-lucide="calendar" style="width:11px;height:11px;" class="me-1 align-text-bottom text-ink-tertiary"></i>Added {{ $product->created_at->format('d M, Y h:i A') }}</span>
                </p>
            </div>
            <div class="flex flex-wrap gap-2 shrink-0">
                <a href="{{ route('seller.products.edit', $product->slug) }}" class="btn btn-light">
                    <i data-lucide="pencil" style="width:14px;height:14px;"></i> Edit
                </a>
                <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="btn btn-light">
                    <i data-lucide="layers" style="width:14px;height:14px;"></i> Variants
                </a>
                <button type="button" class="btn btn-light text-feedback-danger" style="color:#dc2625;"
                        data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $product->id }}">
                    <i data-lucide="trash-2" style="width:14px;height:14px;"></i> Delete
                </button>
            </div>
        </div>
    </div>
</section>

@php
    $variantCount   = $product->variants->count();
    $totalStock     = $product->variants->sum('stock_in') - $product->variants->sum('stock_out');
    $margin         = $product->price - $product->cost_price;
    $marginPercent  = $product->cost_price > 0 ? round(($margin / $product->cost_price) * 100, 1) : 0;
    $tiles = [
        ['label' => 'Variants',  'value' => $variantCount,                                      'icon' => 'layers',       'top' => '#F85606', 'text' => 'text-brand-deep',        'display' => 'number'],
        ['label' => 'Stock',     'value' => $totalStock,                                        'icon' => 'cubes',        'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'display' => 'number'],
        ['label' => 'Selling',   'value' => money($product->price),                           'icon' => 'dollar-sign',  'top' => '#10b981', 'text' => 'text-feedback-success',  'display' => 'text'],
        ['label' => 'Margin',    'value' => money($margin).' ('.$marginPercent.'%)',         'icon' => 'percent',      'top' => '#fb923c', 'text' => 'text-feedback-warning',  'display' => 'text'],
    ];
@endphp
<section class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
    @foreach ($tiles as $tile)
        <article class="bg-white rounded-sm shadow-sm overflow-hidden relative">
            <div class="absolute top-0 left-0 right-0 h-1" style="background-color: {{ $tile['top'] }};"></div>
            <div class="p-4 pt-5">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-[11px] font-semibold text-ink-tertiary uppercase tracking-wider">{{ $tile['label'] }}</span>
                    <i data-lucide="{{ $tile['icon'] }}" class="text-ink-tertiary" style="width:14px;height:14px;"></i>
                </div>
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">{{ $tile['value'] }}</h3>
            </div>
        </article>
    @endforeach
</section>

<div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
    <div class="lg:col-span-7 space-y-4">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    <div class="md:col-span-5 text-center">
                        <div class="border rounded-xs bg-surface-muted flex items-center justify-center overflow-hidden"
                            style="height:300px;">
                            <img id="mainProductImage"
                                src="{{ $product->imageUrl }}"
                                alt="{{ $product->name }}"
                                class="img-fluid"
                                style="max-height:100%; object-fit:contain;" />
                        </div>
                        @if($product->images->count() > 0)
                        <div class="flex flex-wrap gap-1 mt-2 justify-center">
                            <img src="{{ $product->imageUrl }}"
                                class="thumbnail-gallery-img active"
                                onclick="switchImage(this, '{{ $product->imageUrl }}')"
                                alt="Thumbnail">
                            @foreach($product->images as $image)
                            <img src="{{ storage_url($image->image) }}"
                                class="thumbnail-gallery-img"
                                onclick="switchImage(this, '{{ storage_url($image->image) }}')"
                                alt="Gallery image">
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="md:col-span-7">
                        <table class="w-full text-left text-sm text-ink border-collapse">
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5" style="width:110px;">Category</td>
                                <td class="py-1.5">{{ $product->category->name ?? '—' }}
                                    @if($product->subcategory) › {{ $product->subcategory->name }} @endif
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Brand</td>
                                <td class="py-1.5">{{ $product->brand->name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Unit</td>
                                <td class="py-1.5">{{ $product->unit_value ?? '' }} {{ $product->unit->short_name ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Payment</td>
                                <td class="py-1.5">{{ ucfirst($product->payment_type->title()) }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Tags</td>
                                <td class="py-1.5">
                                    @forelse($product->tags as $tag)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-secondary border border-border me-1">{{ $tag->name }}</span>
                                    @empty
                                    <span class="text-ink-tertiary">—</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Variants</td>
                                <td class="py-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-brand-tint text-brand border border-brand/20">{{ $variantCount }}</span>
                                    <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="text-sm ms-2">Manage</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Total Stock</td>
                                <td class="py-1.5">
                                    <span class="font-semibold {{ $totalStock <= $product->low_stock_quantity ? 'text-feedback-danger' : '' }}">
                                        {{ $totalStock }} {{ $product->unit->short_name ?? 'pcs' }}
                                    </span>
                                    @if($totalStock <= $product->low_stock_quantity)
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-500 text-white ms-1">Low Stock</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-5 space-y-4">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                <h5 class="font-bold mb-0 text-sm"><i data-lucide="chart-column" class="me-2 text-brand" style="width:16px;height:16px;"></i>Pricing Summary</h5>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3 text-center">
                    <div class="md:col-span-4">
                        <div class="p-3 rounded-md bg-surface-muted">
                            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Cost Price</div>
                            <div class="text-base font-bold text-ink-secondary">{{ money($product->cost_price) }}</div>
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <div class="p-3 rounded-md bg-surface-muted">
                            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Selling Price</div>
                            <div class="text-base font-bold text-brand">{{ money($product->price) }}</div>
                        </div>
                    </div>
                    <div class="md:col-span-4">
                        <div class="p-3 rounded-md bg-surface-muted">
                            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Compare Price</div>
                            <div class="text-base font-bold {{ $product->compare_price ? 'text-feedback-success' : 'text-ink-tertiary' }}">
                                {{ $product->compare_price ? money($product->compare_price) : '—' }}
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-6 mt-3">
                        <div class="p-3 rounded-md border border-border">
                            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Profit Margin</div>
                            <div class="text-base font-bold {{ $margin > 0 ? 'text-feedback-success' : 'text-feedback-danger' }}">
                                {{ money($margin) }} ({{ $marginPercent }}%)
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-6 mt-3">
                        <div class="p-3 rounded-md border border-border">
                            <div class="text-ink-tertiary text-xs uppercase tracking-wider font-semibold mb-1">Low Stock Threshold</div>
                            <div class="text-base font-bold">{{ $product->low_stock_quantity }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants" type="button" role="tab">
            <i data-lucide="layers" class="me-1"></i>Variants ({{ $variantCount }})
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="stock-tab" data-bs-toggle="tab" data-bs-target="#stock" type="button" role="tab">
            <i data-lucide="boxes" class="me-1"></i>Stock History
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="description-tab" data-bs-toggle="tab" data-bs-target="#description" type="button" role="tab">
            <i data-lucide="align-left" class="me-1"></i>Description
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="shipping-tab" data-bs-toggle="tab" data-bs-target="#shipping" type="button" role="tab">
            <i data-lucide="truck" class="me-1"></i>Shipping
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="seo-tab" data-bs-toggle="tab" data-bs-target="#seo" type="button" role="tab">
            <i data-lucide="search" class="me-1"></i>SEO
        </button>
    </li>
</ul>

<div class="tab-content" id="productTabsContent">
    <div class="tab-pane fade show active" id="variants" role="tabpanel">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
                <h5 class="font-bold mb-0 text-sm"><i data-lucide="layers" class="me-2 text-brand" style="width:16px;height:16px;"></i>All Variants</h5>
                <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="btn btn-primary btn-sm">
                    <i data-lucide="plus"></i> Add Variant
                </a>
            </div>
            <div class="overflow-x-auto">
                @if($variantCount > 0)
                <table class="w-full text-left text-sm text-ink border-collapse">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="px-4 py-2.5">Image</th>
                            <th class="px-4 py-2.5">SKU</th>
                            <th class="px-4 py-2.5">Barcode</th>
                            <th class="px-4 py-2.5">Options</th>
                            <th class="px-4 py-2.5 text-right">Cost</th>
                            <th class="px-4 py-2.5 text-right">Price</th>
                            <th class="px-4 py-2.5 text-right">Compare</th>
                            <th class="px-4 py-2.5 text-right">Weight</th>
                            <th class="px-4 py-2.5 text-center">Stock</th>
                            <th class="px-4 py-2.5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @foreach($product->variants as $variant)
                        <tr>
                            <td class="px-4 py-3"><img src="{{ $variant->imageUrl }}" class="rounded-xs" style="width:36px;height:36px;object-fit:cover;"></td>
                            <td class="px-4 py-3 font-mono text-sm">{{ $variant->sku }}</td>
                            <td class="px-4 py-3 text-sm text-ink-tertiary">{{ $variant->barcode ?? '—' }}</td>
                            <td class="px-4 py-3"><span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-surface-muted text-ink-secondary">{{ $variant->label }}</span></td>
                            <td class="px-4 py-3 text-sm text-right">{{ money($variant->cost_price) }}</td>
                            <td class="px-4 py-3 text-right">{{ money($variant->price) }}</td>
                            <td class="px-4 py-3 text-right">{{ $variant->compare_price ? money($variant->compare_price) : '—' }}</td>
                            <td class="px-4 py-3 text-sm text-right">{{ $variant->weight ? $variant->weight.' kg' : '—' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $variant->availableStock > 0 ? 'bg-brand-tint text-brand border border-brand/20' : 'bg-surface-muted text-ink-secondary border border-border' }}">
                                    {{ $variant->availableStock }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($variant->status)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">Active</span>
                                @else
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-gray-500 text-white">Disabled</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center text-ink-tertiary py-8">
                    <p class="mb-2">No variants configured for this product.</p>
                    <a href="{{ route('seller.products.edit', $product->slug) }}#variantSection" class="btn btn-primary btn-sm">
                        <i data-lucide="plus"></i> Add Variants
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="stock" role="tabpanel">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border flex items-center justify-between">
                <h5 class="font-bold mb-0 text-sm"><i data-lucide="boxes" class="me-2 text-brand" style="width:16px;height:16px;"></i>Stock History</h5>
                <button type="button" class="btn btn-outline-primary btn-sm"
                    data-bs-toggle="modal" data-bs-target="#stockUpdateModal">
                    <i data-lucide="circle-plus"></i> Update Stock
                </button>
            </div>
            <div class="overflow-x-auto" style="max-height:400px; overflow-y:auto;">
                <table class="w-full text-left text-sm text-ink border-collapse">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="px-4 py-2.5">Date</th>
                            @if($variantCount > 0)
                            <th class="px-4 py-2.5">Variant</th>
                            @endif
                            <th class="px-4 py-2.5 text-center">Qty</th>
                            <th class="px-4 py-2.5 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border">
                        @forelse($product->stock_history as $history)
                        <tr>
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $history->created_at->format('d/m/y h:i A') }}</td>
                            @if($variantCount > 0)
                            <td class="px-4 py-3 whitespace-nowrap text-sm">{{ $history->variant?->label ?? 'Default' }}</td>
                            @endif
                            <td class="px-4 py-3 text-center text-sm">{{ abs($history->quantity ?? 0) }}</td>
                            <td class="px-4 py-3 text-center">
                                @switch($history->type)
                                @case(\App\Domain\Product\Enums\StockType::ADD_STOCK)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-500 text-white">Added</span>
                                @break
                                @case(\App\Domain\Product\Enums\StockType::REMOVE_STOCK)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-red-500 text-white">Removed</span>
                                @break
                                @case(\App\Domain\Product\Enums\StockType::SET_EXACT_STOCK)
                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-amber-500 text-white">Set Exact</span>
                                @break
                                @endswitch
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $variantCount > 0 ? 4 : 3 }}" class="text-center text-ink-tertiary py-8">No stock history</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="description" role="tabpanel">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                <h5 class="font-bold mb-0 text-sm"><i data-lucide="align-left" class="me-2 text-brand" style="width:16px;height:16px;"></i>Description & Specifications</h5>
            </div>
            <div class="p-5">
                <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider mb-1">Short Description</h6>
                <p class="mb-4">{{ $product->short_description ?? '—' }}</p>

                <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider mb-1">Full Description</h6>
                <div class="mb-4">{!! nl2br(e($product->description ?? '—')) !!}</div>

                @if($product->specifications)
                <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider mb-1">Specifications</h6>
                <table class="w-full text-left text-sm text-ink border-collapse" style="max-width:400px;">
                    @foreach($product->specifications as $key => $value)
                    <tr>
                        <td class="font-semibold text-ink-tertiary py-1" style="width:140px;">{{ $key }}</td>
                        <td class="py-1">{{ $value }}</td>
                    </tr>
                    @endforeach
                </table>
                @endif
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="shipping" role="tabpanel">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                <h5 class="font-bold mb-0 text-sm"><i data-lucide="truck" class="me-2 text-brand" style="width:16px;height:16px;"></i>Shipping & Manufacturer</h5>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-6">
                        <table class="w-full text-left text-sm text-ink border-collapse">
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5" style="width:140px;">Weight</td>
                                <td class="py-1.5">{{ $product->weight ? $product->weight.' kg' : '—' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Dimensions</td>
                                <td class="py-1.5">
                                    {{ $product->height ? $product->height.' × '.$product->width.' × '.$product->length.' cm' : '—' }}
                                </td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Country</td>
                                <td class="py-1.5">{{ $product->country_of_origin ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="md:col-span-6">
                        <table class="w-full text-left text-sm text-ink border-collapse">
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5" style="width:140px;">Manufacturer</td>
                                <td class="py-1.5">{{ $product->manufacturer_name ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider py-1.5">Details</td>
                                <td class="py-1.5">{{ $product->manufacturer_details ?? '—' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="tab-pane fade" id="seo" role="tabpanel">
        <div class="bg-white border border-border rounded-sm shadow-sm overflow-hidden">
            <div class="bg-surface-muted px-4 py-2.5 border-b border-border">
                <h5 class="font-bold mb-0 text-sm"><i data-lucide="search" class="me-2 text-brand" style="width:16px;height:16px;"></i>SEO & Social Share</h5>
            </div>
            <div class="p-5">
                @if($seo)
                <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                    <div class="md:col-span-6">
                        <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider">Meta Title</h6>
                        <p>{{ $seo->meta_title ?? '—' }}</p>
                    </div>
                    <div class="md:col-span-6">
                        <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider">Meta Keywords</h6>
                        <p>{{ $seo->meta_keywords ?? '—' }}</p>
                    </div>
                    <div class="col-span-full">
                        <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider">Meta Description</h6>
                        <p>{{ $seo->meta_description ?? '—' }}</p>
                    </div>
                    <hr class="my-2">
                    <h6 class="font-semibold">Open Graph</h6>
                    <div class="md:col-span-6">
                        <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider">OG Title</h6>
                        <p>{{ $seo->og_title ?? '—' }}</p>
                    </div>
                    <div class="md:col-span-6">
                        <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider">OG Image</h6>
                        @if(!empty($seo->og_image))
                        <img src="{{ storage_url($seo->og_image) }}" class="img-thumbnail" style="max-width:150px;">
                        @else
                        <p class="text-ink-tertiary">—</p>
                        @endif
                    </div>
                    <div class="col-span-full">
                        <h6 class="font-semibold text-ink-tertiary text-xs uppercase tracking-wider">OG Description</h6>
                        <p>{{ $seo->og_description ?? '—' }}</p>
                    </div>
                </div>
                @else
                <p class="text-ink-tertiary mb-0">No SEO data configured.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal-{{ $product->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel-{{ $product->id }}">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="text-center modal-body">
                <div class="p-4 rounded-sm bg-amber-50 border border-amber-200 text-feedback-warning text-sm flex items-start gap-3" role="alert">
                    <i data-lucide="circle-alert" class="me-2 text-feedback-danger" style="font-size: 1.5rem;"></i>
                    <p class="mt-1 text-ink-secondary">Are you sure you want to delete this Product?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
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
        <div class="modal-content border-0">
            <form action="{{ route('seller.products.stockUpdate', $product->id) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h4 class="modal-title font-bold">Update Inventory</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <button class="btn btn-outline-info btn-sm" type="button" data-bs-toggle="collapse"
                            data-bs-target="#stockInstruction" aria-expanded="false" aria-controls="stockInstruction">
                            ℹ️ Stock update instructions
                        </button>
                        <div class="collapse mt-2" id="stockInstruction">
                            <div class="p-4 rounded-sm bg-blue-50 border border-blue-200 text-feedback-info text-sm mb-0">
                                <ul class="mb-0 text-sm">
                                    <li><strong>Add Stock</strong> – Increase stock quantity.</li>
                                    <li><strong>Remove Stock</strong> – Decrease stock.</li>
                                    <li><strong>Set Exact Stock</strong> – Set to a precise number.</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if($variantCount > 0)
                    @foreach($product->variants as $variant)
                    <h6 class="font-semibold">{{ $variant->label ?? 'Default' }}</h6>
                    <div class="grid grid-cols-1 md:grid-cols-12 mb-3 gap-2">
                        <div class="md:col-span-4 mb-2">
                            <select class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" name="stock_action[{{ $variant->id }}]">
                                <option value="{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->label() }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 mb-2">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">Qty ({{ $variant->availableStock }})</span>
                                <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="stock_quantity[{{ $variant->id }}]" min="1">
                            </div>
                        </div>
                        <div class="md:col-span-4 mb-2">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">Note</span>
                                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="stock_note[{{ $variant->id }}]">
                            </div>
                        </div>
                    </div>
                    @if(!$loop->last)<hr>@endif
                    @endforeach
                    @else
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-2">
                        <div class="md:col-span-4 mb-2">
                            <select class="w-full px-3 py-1.5 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep transition-colors" name="stock_action_product">
                                <option value="{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::ADD_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::REMOVE_STOCK->label() }}</option>
                                <option value="{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->value }}">{{ \App\Domain\Product\Enums\StockType::SET_EXACT_STOCK->label() }}</option>
                            </select>
                        </div>
                        <div class="md:col-span-4 mb-2">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">Qty ({{ $product->stock_in - $product->stock_out }})</span>
                                <input type="number" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="stock_quantity_product" min="1">
                            </div>
                        </div>
                        <div class="md:col-span-4 mb-2">
                            <div class="flex">
                                <span class="inline-flex items-center px-3 py-2 text-sm text-ink-tertiary bg-surface-muted border border-border">Note</span>
                                <input type="text" class="w-full px-3 py-2 text-sm text-ink bg-white border border-border rounded-xs focus:outline-none focus:border-brand-deep focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors" name="stock_note_product">
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Stocks</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="alertBox"></div>
@endsection

@push('scripts')
<script>
    function switchImage(el, url) {
        document.querySelectorAll('.thumbnail-gallery-img').forEach(i => i.classList.remove('active'));
        el.classList.add('active');
        document.getElementById('mainProductImage').src = url;
    }
</script>
@endpush