<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Inventory - Minimal Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --row-height: 36px;
            --compact-padding: 6px 8px;
            --border-color: #e0e0e0;
        }

        body {
            font-family: 'Segoe UI', 'Roboto', -apple-system, sans-serif;
            font-size: 13px;
            background-color: #fafafa;
            padding: 15px;
            color: #333;
        }

        .header-controls {
            display: flex;
            gap: 12px;
            margin-bottom: 10px;
            align-items: center;
            padding: 4px 0;
            flex-wrap: wrap;
        }

        .search-box {
            flex: 1;
            max-width: 320px;
        }

        .toggle-btn {
            background: white;
            border: 1px solid var(--border-color);
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            user-select: none;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .toggle-btn.active {
            background: #f0f0f0;
        }

        .compact-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            table-layout: auto;
        }

        .compact-table th {
            position: sticky;
            top: 0;
            background: white;
            z-index: 10;
            padding: var(--compact-padding);
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: #444;
            white-space: nowrap;
        }

        .compact-table th.sortable {
            cursor: pointer;
        }

        .compact-table th.sortable:hover {
            background-color: #f8f8f8;
        }

        .compact-table td {
            padding: var(--compact-padding);
            border-bottom: 1px solid var(--border-color);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .compact-table tbody tr:nth-child(odd) {
            background-color: #fcfcfc;
        }

        .compact-table tbody tr:hover {
            background-color: #eef7ff !important;
        }

        .thumbnail {
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 2px;
            display: none;
            /* Hidden by default */
        }

        .qty-cell {
            text-align: right;
            width: 80px;
        }

        .price-cell {
            text-align: right;
            width: 100px;
        }

        .dropdown-menu {
            font-size: 13px;
            padding: 8px;
            min-width: 140px;
        }

        .dropdown-item {
            padding: 6px 8px;
            display: flex;
            align-items: center;
        }

        .dropdown-item input {
            margin-right: 8px;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .header-controls {
                gap: 8px;
            }

            .search-box {
                max-width: 100%;
                order: 1;
            }

            .toggle-group {
                order: 2;
            }

            .compact-table {
                min-width: 700px;
                /* Allows horizontal scrolling on small screens */
            }
        }

        .hidden-column {
            display: none;
        }

        #resizeable {
            position: relative;
            user-select: none;
        }

        #handle {
            position: absolute;
            top: 0;
            right: 0;
            width: 8px;
            height: 100%;
            cursor: ew-resize;
            background-color: transparent;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 2px;
            transition: background-color 0.2s ease;
            user-select: none;
        }

        #handle:hover {
            background-color: #ddd;
        }

        #handle span {
            display: block;
            width: 4px;
            height: 2px;
            margin: 2px 0;
            background-color: #666;
            border-radius: 1px;
        }

        #handle:hover span {
            background-color: #333;
        }
    </style>
</head>

<body>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-lg-8" id="resizeable">
                <div id="handle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <div class="header-controls">
                    <input type="text"
                        class="form-control form-control-sm search-box"
                        placeholder="Search products / SKU / variant…"
                        id="searchInput">

                    <div class="toggle-group d-flex gap-2">
                        <span class="toggle-btn" id="toggleImages" data-active="false" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                <path d="M2.002 1a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V3a2 2 0 0 0-2-2h-12zm12 1a1 1 0 0 1 1 1v6.5l-3.777-1.947a.5.5 0 0 0-.577.093l-3.71 3.71-2.66-1.772a.5.5 0 0 0-.63.062L1.002 12V3a1 1 0 0 1 1-1h12z" />
                            </svg>
                            Show Images
                        </span>

                        <div class="dropdown">
                            <span class="toggle-btn dropdown-toggle"
                                id="toggleContextMenu"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Columns
                            </span>

                            <div class="dropdown-menu" aria-labelledby="toggleContextMenu">
                                <div class="dropdown-item">
                                    <input type="checkbox" id="toggleId" class="column-toggle" data-column="id">
                                    <label for="toggleId">ID</label>
                                </div>
                                <div class="dropdown-item">
                                    <input type="checkbox" id="toggleVariant" checked class="column-toggle" data-column="variant">
                                    <label for="toggleVariant">Variant/SKU</label>
                                </div>
                                <div class="dropdown-item">
                                    <input type="checkbox" id="toggleQuantity" checked class="column-toggle" data-column="quantity">
                                    <label for="toggleQuantity">Stock</label>
                                </div>
                                <div class="dropdown-item">
                                    <input type="checkbox" id="togglePrice" checked class="column-toggle" data-column="price">
                                    <label for="togglePrice">Price</label>
                                </div>
                                <div class="dropdown-item">
                                    <input type="checkbox" id="toggleImageColumn" class="column-toggle" data-column="image">
                                    <label for="toggleImageColumn">Image Column</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="table-container" style="overflow: auto; max-height: calc(100vh - 120px);">
                    <table class="compact-table">
                        <thead>
                            <tr>
                                <th class="col-id hidden-column">ID</th>
                                <th class="sortable" data-sort="name">Product</th>
                                <th class="sortable qty-cell col-quantity text-end" data-sort="quantity">Stock</th>
                                <th class="sortable price-cell col-price text-end" data-sort="price">Price</th>
                                <th class="sortable price-cell col-discounted_price text-start" data-sort="discounted_price">Discounted Price</th>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const products = @json($products);
        const productList = document.getElementById('productList');
        const searchInput = document.getElementById('searchInput');
        const toggleImages = document.getElementById('toggleImages');
        const columnToggles = document.querySelectorAll('.column-toggle');

        // State
        let filteredVariants = flattenVariants(products);
        let sortConfig = {
            key: null,
            direction: 'asc'
        };

        // function flattenVariants(products) {
        //     const variants = [];

        //     products.forEach(product => {
        //         product.variants.forEach(variant => {
        //             variants.push({
        //                 id: variant.id,
        //                 sku: variant.sku,
        //                 name: product.name,
        //                 quantity: parseInt(variant.quantity),
        //                 price: variant.price,
        //                 discounted_price: variant.discounted_price || 0,
        //                 image: variant.image || product.image,
        //                 productImage: product.image,
        //                 fullName: `${variant.fullName}`.trim()
        //             });
        //         });
        //     });

        //     return variants;
        // }

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
                            discounted_price: variant.discounted_price ?? product.discounted_price,
                            image: variant.image || product.image,
                            productImage: product.image,
                            fullName: (variant.fullName ?? "").trim()
                        });
                    });
                } else {
                    variants.push({
                        id: product.id,
                        sku: product.sku,
                        name: product.name,
                        quantity: parseInt(product.quantity),
                        price: product.price,
                        discounted_price: product.discounted_price,
                        image: product.image,
                        productImage: product.image,
                        fullName: ""
                    });
                }
            });

            return variants;
        }

        function renderProducts() {
            productList.innerHTML = '';

            filteredVariants.forEach((variant) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="col-id hidden-column">${variant.id}</td>
                    <td><b>${variant.name}</b> <br> <i>${variant.fullName}</i></td>
                    <td class="qty-cell col-quantity text-end">${variant.quantity}</td>
                    <td class="price-cell col-price text-end">${variant.price}</td>
                    <td class="price-cell col-discounted_price text-start">${variant.discounted_price}</td>
                    <td class="text-end">${variant.sku}</td>
                    <td class="col-image hidden-column">
                        <img src="${variant.image}" class="thumbnail" alt="${variant.name}" onerror="handleImageError(this)">
                    </td>
                `;
                productList.appendChild(row);
            });

            updateImageVisibility();
        }

        function handleImageError(img) {
            img.classList.add('error');
            img.outerHTML = '<div class="thumbnail error">Image<br>Missing</div>';
        }

        function filterProducts() {
            const searchTerm = searchInput.value.toLowerCase().trim();

            filteredVariants = flattenVariants(products).filter(variant =>
                variant.name.toLowerCase().includes(searchTerm) ||
                variant.fullName.toLowerCase().includes(searchTerm) ||
                variant.sku.toLowerCase().includes(searchTerm)
            );

            renderProducts();
        }

        function sortProducts(key) {
            let direction = 'asc';
            if (sortConfig.key === key && sortConfig.direction === 'asc') {
                direction = 'desc';
            }

            sortConfig = {
                key,
                direction
            };

            filteredVariants.sort((a, b) => {
                let aVal = a[key];
                let bVal = b[key];

                if (key === 'quantity' || key === 'price' || key == 'discounted_price') {
                    aVal = parseFloat(aVal);
                    bVal = parseFloat(bVal);
                } else {
                    aVal = aVal?.toString().toLowerCase();
                    bVal = bVal?.toString().toLowerCase();
                }

                if (aVal < bVal) return direction === 'asc' ? -1 : 1;
                if (aVal > bVal) return direction === 'asc' ? 1 : -1;
                return 0;
            });

            renderProducts();
        }

        function updateImageVisibility() {
            const thumbnails = document.querySelectorAll('.thumbnail');
            const showImages = toggleImages.getAttribute('data-active') === 'true';

            thumbnails.forEach(img => {
                img.style.display = showImages ? 'block' : 'none';
            });

            toggleImages.classList.toggle('active', showImages);
        }

        function toggleColumn(columnClass, show) {
            const elements = document.querySelectorAll(`.${columnClass}`);
            elements.forEach(el => {
                el.classList.toggle('hidden-column', !show);
            });
        }

        function initColumns() {
            columnToggles.forEach(toggle => {
                const columnClass = `col-${toggle.dataset.column}`;
                const show = toggle.checked;
                toggleColumn(columnClass, show);
            });
        }

        searchInput.addEventListener('input', filterProducts);

        toggleImages.addEventListener('click', () => {
            const currentState = toggleImages.getAttribute('data-active') === 'true';
            toggleImages.setAttribute('data-active', !currentState);
            updateImageVisibility();
        });

        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => {
                const key = header.dataset.sort;
                sortProducts(key);
            });
        });

        columnToggles.forEach(toggle => {
            toggle.addEventListener('change', () => {
                const columnClass = `col-${toggle.dataset.column}`;
                toggleColumn(columnClass, toggle.checked);
            });
        });

        renderProducts();
        initColumns();
    </script>

    <script>
        const resizeable = document.getElementById('resizeable');
        const handle = document.getElementById('handle');

        let isResizing = false;

        handle.addEventListener('mousedown', (e) => {
            isResizing = true;
            document.body.style.cursor = 'ew-resize';
        });

        document.addEventListener('mouseup', (e) => {
            isResizing = false;
            document.body.style.cursor = 'default';
        });

        document.addEventListener('mousemove', (e) => {
            if (!isResizing) return;

            const newWidth = e.clientX - resizeable.getBoundingClientRect().left;

            // if (newWidth > 100 && newWidth < 800) {
            //     resizeable.style.width = newWidth + 'px';
            // }
            resizeable.style.width = newWidth + 'px';
        });
    </script>
</body>

</html>