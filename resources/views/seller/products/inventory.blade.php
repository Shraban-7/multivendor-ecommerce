@php
    use Illuminate\Support\Facades\Storage;
    $totalProducts = count($products);
    $totalStock = 0;
    $totalValue = 0;
    $lowStock = 0;
    foreach ($products as $p) {
        foreach ($p['variants'] ?? [] as $v) {
            $totalStock += (int) ($v['quantity'] ?? 0);
            $totalValue += (float) ($v['price'] ?? 0) * (int) ($v['quantity'] ?? 0);
            if (((int) ($v['quantity'] ?? 0)) <= 5) { $lowStock++; }
        }
        if (empty($p['variants'])) {
            $totalStock += (int) ($p['quantity'] ?? 0);
            $totalValue += (float) ($p['price'] ?? 0) * (int) ($p['quantity'] ?? 0);
            if (((int) ($p['quantity'] ?? 0)) <= 5) { $lowStock++; }
        }
    }
@endphp
@extends('seller.layouts.app')
@section('title', 'Product Inventory')

@push('styles')
<style>
    :root { --row-height: 36px; --compact-padding: 6px 8px; --border-color: #E5E5E5; }
    .inventory-page { font-size: 13px; }
    .header-controls { display: flex; gap: 12px; margin-bottom: 10px; align-items: center; padding: 4px 0; flex-wrap: wrap; }
    .search-box { flex: 1; max-width: 320px; }
    .toggle-btn { background: white; padding: 6px 12px; border-radius: 6px; cursor: pointer; user-select: none; display: flex; align-items: center; gap: 4px; }
    .toggle-btn.active { background: var(--bs-surface-muted, #F5F5F5); }
    .compact-table { width: 100%; border-collapse: separate; border-spacing: 0; table-layout: auto; }
    .compact-table th { position: sticky; top: 0; background: white; z-index: 10; padding: var(--compact-padding); border-bottom: 2px solid var(--border-color); font-weight: 600; color: #444; white-space: nowrap; }
    .compact-table th.sortable { cursor: pointer; }
    .compact-table th.sortable:hover { background-color: #FAFAFA; }
    .compact-table td { padding: var(--compact-padding); border-bottom: 1px solid var(--border-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .compact-table tbody tr:nth-child(odd) { background-color: #FCFCFC; }
    .compact-table tbody tr:hover { background-color: #FFF1EA !important; }
    .thumbnail { width: 32px; height: 32px; object-fit: cover; border-radius: 4px; display: none; }
    .qty-cell { text-align: right; width: 80px; }
    .price-cell { text-align: right; width: 100px; }
    .hidden-column { display: none; }
    #resizeable { position: relative; user-select: none; }
    #handle { position: absolute; top: 0; right: 0; width: 8px; height: 100%; cursor: ew-resize; background-color: transparent; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 0 2px; transition: background-color 0.2s ease; user-select: none; }
    #handle:hover { background-color: #E5E5E5; }
    #handle span { display: block; width: 4px; height: 2px; margin: 2px 0; background-color: #767676; border-radius: 1px; }
    #handle:hover span { background-color: #2D3748; }
    @media (max-width: 768px) { .search-box { max-width: 100%; order: 1; } .toggle-group { order: 2; } .compact-table { min-width: 700px; } }
</style>
@endpush

@section('content')

<section class="bg-white rounded-sm shadow-sm overflow-hidden mb-3 relative">
    <div class="absolute top-0 left-0 right-0 h-1" style="background: linear-gradient(90deg, #F85606, #fb923c, #fbbf24);"></div>
    <div class="p-5 lg:p-6 pt-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="min-w-0">
                <nav class="flex items-center gap-1 mb-2 text-xs text-ink-tertiary">
                    <i data-lucide="package" class="text-feedback-info" style="width:12px;height:12px;"></i>
                    <span>Catalog</span>
                    <i data-lucide="chevron-right" style="width:12px;height:12px;"></i>
                    <span class="text-ink-soft font-semibold">Inventory</span>
                </nav>
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <h1 class="text-xl font-bold text-ink-emphasis mb-0">Product Inventory</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider bg-feedback-info/15 text-feedback-info">
                        <i data-lucide="boxes" style="width:11px;height:11px;" class="me-1"></i> Stock Cockpit
                    </span>
                </div>
                <p class="text-sm text-ink-secondary mb-0">Live spreadsheet of every variant across your catalog.</p>
            </div>
        </div>
    </div>
</section>

@php
    $tiles = [
        ['label' => 'Products',     'value' => $totalProducts, 'top' => '#F85606', 'text' => 'text-brand-deep',        'icon' => 'package'],
        ['label' => 'Units in Stock','value' => $totalStock,    'top' => '#0ea5e9', 'text' => 'text-feedback-info',     'icon' => 'cubes'],
        ['label' => 'Inventory Value', 'value' => money($totalValue), 'top' => '#10b981', 'text' => 'text-feedback-success',  'icon' => 'dollar-sign', 'is_money' => true],
        ['label' => 'Low Stock',     'value' => $lowStock,     'top' => '#ef4444', 'text' => 'text-feedback-danger',   'icon' => 'triangle-alert'],
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
                <h3 class="text-2xl font-bold {{ $tile['text'] }} mb-0">
                    @if($tile['is_money'] ?? false)
                        {{ $tile['value'] }}
                    @else
                        {{ number_format($tile['value']) }}
                    @endif
                </h3>
            </div>
        </article>
    @endforeach
</section>

<section class="bg-white rounded-sm shadow-sm overflow-hidden">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2 flex-wrap">
        <i data-lucide="sliders-horizontal" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h3 class="text-sm font-bold text-ink-emphasis mb-0">Inventory Cockpit</h3>
        <div class="grow"></div>
        <span class="toggle-btn" id="toggleImages" data-active="false" style="display: none;">
            <i data-lucide="image" style="width:16px;height:16px;"></i>
            Show Images
        </span>
        <div class="dropdown">
            <span class="toggle-btn" id="toggleContextMenu" data-bs-toggle="dropdown" aria-expanded="false">
                <i data-lucide="columns" style="width:14px;height:14px;"></i> Columns <i data-lucide="chevron-down" style="width:14px;height:14px;"></i>
            </span>
            <div class="dropdown-menu p-2" aria-labelledby="toggleContextMenu" style="min-width:200px;">
                <div class="grid grid-cols-2 gap-x-2 gap-y-1">
                    <div class="col-span-1"><label class="flex items-center gap-1 text-sm py-1 px-1 rounded-xs cursor-pointer user-select-none"><input type="checkbox" id="toggleId" class="column-toggle h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep m-0" data-column="id"> ID</label></div>
                    <div class="col-span-1"><label class="flex items-center gap-1 text-sm py-1 px-1 rounded-xs cursor-pointer user-select-none"><input type="checkbox" id="toggleVariant" checked class="column-toggle h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep m-0" data-column="variant"> Variant</label></div>
                    <div class="col-span-1"><label class="flex items-center gap-1 text-sm py-1 px-1 rounded-xs cursor-pointer user-select-none"><input type="checkbox" id="toggleQuantity" checked class="column-toggle h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep m-0" data-column="quantity"> Stock</label></div>
                    <div class="col-span-1"><label class="flex items-center gap-1 text-sm py-1 px-1 rounded-xs cursor-pointer user-select-none"><input type="checkbox" id="togglePrice" checked class="column-toggle h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep m-0" data-column="price"> Price</label></div>
                    <div class="col-span-1"><label class="flex items-center gap-1 text-sm py-1 px-1 rounded-xs cursor-pointer user-select-none"><input type="checkbox" id="toggleImageColumn" class="column-toggle h-4 w-4 rounded text-brand focus:outline-none focus:ring-1 focus:ring-brand-deep m-0" data-column="image"> Image</label></div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 border-t border-border">
        <div class="header-controls">
            <input type="text"
                   class="search-box w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep placeholder:text-ink-tertiary transition-colors"
                   placeholder="Search products / SKU / variant…"
                   id="searchInput">
        </div>

        <div id="resizeable">
            <div class="table-container" style="overflow: auto; max-height: calc(100vh - 280px);">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th class="col-id hidden-column">ID</th>
                            <th class="sortable" data-sort="name">Product</th>
                            <th class="sortable qty-cell col-quantity text-right" data-sort="quantity">Stock</th>
                            <th class="sortable price-cell col-price text-right" data-sort="price">Price</th>
                            <th class="sortable price-cell col-compare_price text-left" data-sort="compare_price">Compare Price</th>
                            <th class="text-right">SKU</th>
                            <th class="col-image hidden-column">Img</th>
                        </tr>
                    </thead>
                    <tbody id="productList"></tbody>
                </table>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => window.renderIcons && window.renderIcons());

const products = @json($products);
const productList = document.getElementById('productList');
const searchInput = document.getElementById('searchInput');
const toggleImages = document.getElementById('toggleImages');
const columnToggles = document.querySelectorAll('.column-toggle');

let filteredVariants = flattenVariants(products);
let sortConfig = { key: null, direction: 'asc' };

function flattenVariants(products) {
    const variants = [];
    products.forEach(product => {
        if (product.variants && product.variants.length > 0) {
            product.variants.forEach(variant => {
                variants.push({
                    id: variant.id,
                    sku: variant.sku ?? product.sku,
                    name: product.name,
                    quantity: parseInt(variant.quantity ?? product.quantity),
                    price: variant.price ?? product.price,
                    compare_price: variant.compare_price ?? product.compare_price,
                    image: variant.image || product.image,
                    productImage: product.image,
                    label: (variant.label ?? "").trim()
                });
            });
        } else {
            variants.push({
                id: product.id,
                sku: product.sku,
                name: product.name,
                quantity: parseInt(product.quantity),
                price: product.price,
                compare_price: product.compare_price,
                image: product.image,
                productImage: product.image,
                label: "",
            });
        }
    });
    return variants;
}

function renderProducts() {
    productList.innerHTML = '';
    filteredVariants.forEach(variant => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="col-id hidden-column">${variant.id}</td>
            <td><b>${variant.name}</b> <br> <i>${variant.label}</i></td>
            <td class="qty-cell col-quantity text-right">${variant.quantity}</td>
            <td class="price-cell col-price text-right">${variant.price}</td>
            <td class="price-cell col-compare_price text-left">${variant.compare_price}</td>
            <td class="text-right">${variant.sku}</td>
            <td class="col-image hidden-column">
                <img src="${variant.image}" class="thumbnail" alt="${variant.name}" onerror="this.outerHTML='<div class=&quot;thumbnail&quot;>Missing</div>'">
            </td>
        `;
        productList.appendChild(row);
    });
    updateImageVisibility();
}

function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    filteredVariants = flattenVariants(products).filter(v =>
        v.name.toLowerCase().includes(searchTerm) ||
        v.label.toLowerCase().includes(searchTerm) ||
        v.sku.toLowerCase().includes(searchTerm)
    );
    renderProducts();
}

function sortProducts(key) {
    let direction = 'asc';
    if (sortConfig.key === key && sortConfig.direction === 'asc') direction = 'desc';
    sortConfig = { key, direction };
    filteredVariants.sort((a, b) => {
        let aVal = ['quantity','price','compare_price'].includes(key) ? parseFloat(a[key]) : (a[key]?.toString().toLowerCase() ?? '');
        let bVal = ['quantity','price','compare_price'].includes(key) ? parseFloat(b[key]) : (b[key]?.toString().toLowerCase() ?? '');
        if (aVal < bVal) return direction === 'asc' ? -1 : 1;
        if (aVal > bVal) return direction === 'asc' ? 1 : -1;
        return 0;
    });
    renderProducts();
}

function updateImageVisibility() {
    const show = toggleImages.getAttribute('data-active') === 'true';
    document.querySelectorAll('.thumbnail').forEach(img => { img.style.display = show ? 'block' : 'none'; });
    toggleImages.classList.toggle('active', show);
}

function toggleColumn(columnClass, show) {
    document.querySelectorAll('.' + columnClass).forEach(el => el.classList.toggle('hidden-column', !show));
}

function initColumns() {
    columnToggles.forEach(toggle => toggleColumn('col-' + toggle.dataset.column, toggle.checked));
}

searchInput.addEventListener('input', filterProducts);
toggleImages.addEventListener('click', () => {
    toggleImages.setAttribute('data-active', toggleImages.getAttribute('data-active') !== 'true');
    updateImageVisibility();
});
document.querySelectorAll('.sortable').forEach(h => h.addEventListener('click', () => sortProducts(h.dataset.sort)));
columnToggles.forEach(t => t.addEventListener('change', () => toggleColumn('col-' + t.dataset.column, t.checked)));

renderProducts();
initColumns();

const resizeable = document.getElementById('resizeable');
const handle = document.getElementById('handle');
let isResizing = false;
handle.addEventListener('mousedown', () => { isResizing = true; document.body.style.cursor = 'ew-resize'; });
document.addEventListener('mouseup', () => { isResizing = false; document.body.style.cursor = 'default'; });
document.addEventListener('mousemove', e => {
    if (!isResizing) return;
    resizeable.style.width = e.clientX - resizeable.getBoundingClientRect().left + 'px';
});
</script>
@endpush

@endsection
