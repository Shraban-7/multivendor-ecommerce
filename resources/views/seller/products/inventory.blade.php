@extends('seller.layouts.app')
@section('title', 'Product Inventory')

@push('styles')
<style>
    :root { --row-height: 36px; --compact-padding: 6px 8px; --border-color: #e0e0e0; }
    .inventory-page { font-size: 13px; }
    .header-controls { display: flex; gap: 12px; margin-bottom: 10px; align-items: center; padding: 4px 0; flex-wrap: wrap; }
    .search-box { flex: 1; max-width: 320px; }
    .toggle-btn { background: white; border: 1px solid var(--border-color); padding: 6px 12px; border-radius: 4px; cursor: pointer; user-select: none; display: flex; align-items: center; gap: 4px; }
    .toggle-btn.active { background: #f0f0f0; }
    .compact-table { width: 100%; border-collapse: separate; border-spacing: 0; table-layout: auto; }
    .compact-table th { position: sticky; top: 0; background: white; z-index: 10; padding: var(--compact-padding); border-bottom: 2px solid var(--border-color); font-weight: 600; color: #444; white-space: nowrap; }
    .compact-table th.sortable { cursor: pointer; }
    .compact-table th.sortable:hover { background-color: #f8f8f8; }
    .compact-table td { padding: var(--compact-padding); border-bottom: 1px solid var(--border-color); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .compact-table tbody tr:nth-child(odd) { background-color: #fcfcfc; }
    .compact-table tbody tr:hover { background-color: #eef7ff !important; }
    .thumbnail { width: 32px; height: 32px; object-fit: cover; border-radius: 2px; display: none; }
    .qty-cell { text-align: right; width: 80px; }
    .price-cell { text-align: right; width: 100px; }
    .hidden-column { display: none; }
    #resizeable { position: relative; user-select: none; }
    #handle { position: absolute; top: 0; right: 0; width: 8px; height: 100%; cursor: ew-resize; background-color: transparent; display: flex; flex-direction: column; justify-content: center; align-items: center; padding: 0 2px; transition: background-color 0.2s ease; user-select: none; }
    #handle:hover { background-color: #ddd; }
    #handle span { display: block; width: 4px; height: 2px; margin: 2px 0; background-color: #666; border-radius: 1px; }
    #handle:hover span { background-color: #333; }
    @media (max-width: 768px) { .search-box { max-width: 100%; order: 1; } .toggle-group { order: 2; } .compact-table { min-width: 700px; } }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-end mb-3">
    <h4 class="fw-bold mb-0 text-dark">Product Inventory</h4>
</div>

<div class="inventory-page">
    <div class="row">
        <div class="col-lg-12" id="resizeable">
            <div class="header-controls">
                <input type="text" class="form-control form-control-sm search-box" placeholder="Search products / SKU / variant…" id="searchInput">

                <div class="toggle-group d-flex gap-2">
                    <span class="toggle-btn" id="toggleImages" data-active="false" style="display: none;">
                        <i data-feather="image" style="width:16px;height:16px;"></i>
                        Show Images
                    </span>

                    <div class="dropdown">
                        <span class="toggle-btn" id="toggleContextMenu" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-feather="columns" style="width:14px;height:14px;"></i> Columns <i data-feather="chevron-down" style="width:14px;height:14px;"></i>
                        </span>
                        <div class="dropdown-menu p-2" aria-labelledby="toggleContextMenu" style="min-width:200px;">
                            <div class="row gx-2 gy-1">
                                <div class="col-6"><label class="d-flex align-items-center gap-1 small py-1 px-1 rounded cursor-pointer user-select-none" style="cursor:pointer;"><input type="checkbox" id="toggleId" class="column-toggle form-check-input m-0" data-column="id" style="cursor:pointer;"> ID</label></div>
                                <div class="col-6"><label class="d-flex align-items-center gap-1 small py-1 px-1 rounded cursor-pointer user-select-none" style="cursor:pointer;"><input type="checkbox" id="toggleVariant" checked class="column-toggle form-check-input m-0" data-column="variant" style="cursor:pointer;"> Variant</label></div>
                                <div class="col-6"><label class="d-flex align-items-center gap-1 small py-1 px-1 rounded cursor-pointer user-select-none" style="cursor:pointer;"><input type="checkbox" id="toggleQuantity" checked class="column-toggle form-check-input m-0" data-column="quantity" style="cursor:pointer;"> Stock</label></div>
                                <div class="col-6"><label class="d-flex align-items-center gap-1 small py-1 px-1 rounded cursor-pointer user-select-none" style="cursor:pointer;"><input type="checkbox" id="togglePrice" checked class="column-toggle form-check-input m-0" data-column="price" style="cursor:pointer;"> Price</label></div>
                                <div class="col-6"><label class="d-flex align-items-center gap-1 small py-1 px-1 rounded cursor-pointer user-select-none" style="cursor:pointer;"><input type="checkbox" id="toggleImageColumn" class="column-toggle form-check-input m-0" data-column="image" style="cursor:pointer;"> Image</label></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-container" style="overflow: auto; max-height: calc(100vh - 220px);">
                <table class="compact-table">
                    <thead>
                        <tr>
                            <th class="col-id hidden-column">ID</th>
                            <th class="sortable" data-sort="name">Product</th>
                            <th class="sortable qty-cell col-quantity text-end" data-sort="quantity">Stock</th>
                            <th class="sortable price-cell col-price text-end" data-sort="price">Price</th>
                            <th class="sortable price-cell col-compare_price text-start" data-sort="compare_price">Compare Price</th>
                            <th class="text-end">SKU</th>
                            <th class="col-image hidden-column">Img</th>
                        </tr>
                    </thead>
                    <tbody id="productList"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => feather.replace());

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
            <td class="qty-cell col-quantity text-end">${variant.quantity}</td>
            <td class="price-cell col-price text-end">${variant.price}</td>
            <td class="price-cell col-compare_price text-start">${variant.compare_price}</td>
            <td class="text-end">${variant.sku}</td>
            <td class="col-image hidden-column">
                <img src="${variant.image}" class="thumbnail" alt="${variant.name}" onerror="this.outerHTML='<div class=\\'thumbnail\\'>Missing</div>'">
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
