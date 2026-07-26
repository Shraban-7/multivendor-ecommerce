<div class="card shadow-sm border-0 mb-4 d-none" id="variantGenerator">
    <div class="card-header bg-white">
        <h5 class="mb-0">Product Variant Generator</h5>
    </div>
    <div class="card-body">
        <div id="attributeRows" class="mb-3">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="mb-1">Color(s)</label>
                    <select name="color_ids" class="color-select w-100" multiple>
                        @foreach ($colors as $color)
                        <option value="{{ $color->id }}" data-hex="{{ $color->hex_code }}">
                            {{ $color->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="mb-1">Size(s)</label>
                    <select name="size_ids" class="size-select w-100" multiple>
                        @foreach ($sizes as $size)
                        <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-sm" id="generateVariantsBtn">Generate Variants</button>
            <button type="button" class="btn btn-secondary btn-sm ms-2" id="clearVariantsBtn">Clear Variants</button>
        </div>

        <div id="variantsTableContainer" class="table-responsive"></div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const generateVariantsBtn = document.getElementById("generateVariantsBtn");
        const variantsTableContainer = document.getElementById("variantsTableContainer");
        const clearVariantsBtn = document.getElementById("clearVariantsBtn");

        generateVariantsBtn.addEventListener("click", function() {
            const colorSelect = document.querySelector(".color-select");
            const sizeSelect = document.querySelector(".size-select");

            const selectedColors = Array.from(colorSelect.selectedOptions).map(opt => ({
                id: parseInt(opt.value),
                name: opt.text.trim(),
                hex: opt.dataset.hex || '#CCCCCC',
            }));

            const selectedSizes = Array.from(sizeSelect.selectedOptions).map(opt => ({
                id: parseInt(opt.value),
                name: opt.text.trim(),
            }));

            if (selectedColors.length === 0 && selectedSizes.length === 0) {
                variantsTableContainer.innerHTML = '<p class="text-muted mb-0">Please select at least one color or size to generate variants.</p>';
                return;
            }

            const combinations = [];
            if (selectedColors.length > 0 && selectedSizes.length > 0) {
                selectedColors.forEach(color => {
                    selectedSizes.forEach(size => {
                        combinations.push({ color_id: color.id, color_name: color.name, color_hex: color.hex, size_id: size.id, size_name: size.name });
                    });
                });
            } else if (selectedColors.length > 0) {
                selectedColors.forEach(color => {
                    combinations.push({ color_id: color.id, color_name: color.name, color_hex: color.hex, size_id: null, size_name: '' });
                });
            } else {
                selectedSizes.forEach(size => {
                    combinations.push({ color_id: null, color_name: '', color_hex: '', size_id: size.id, size_name: size.name });
                });
            }

            if (combinations.length === 0) {
                variantsTableContainer.innerHTML = '<p class="text-muted mb-0">No valid combinations could be generated.</p>';
                return;
            }

            let tableHtml = `
                <table class="table table-sm table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                    <th>#</th>
                    <th>SKU</th>
                    <th>Color</th>
                    <th>Size</th>
                    <th>Buying Price</th>
                    <th>Selling Price</th>
                    <th>Discount Type</th>
                    <th>Discount Value</th>
                    <th>Image</th>
                    <th>Actions</th>
                    </tr>
                </thead>
            <tbody id="variantsTableBody">
            `;

            const productSku = document.querySelector('input[name="sku"]')?.value.trim() || "PROD";

            combinations.forEach((comb, index) => {
                const variantId = index + 1;
                const skuParts = [productSku];
                if (comb.color_name) skuParts.push(comb.color_name.replace(/\s+/g, ''));
                if (comb.size_name) skuParts.push(comb.size_name.replace(/\s+/g, ''));
                const sku = skuParts.join('-').toUpperCase();

                tableHtml += `
                <tr data-variant-row-id="${variantId}" data-color-id="${comb.color_id || ''}" data-size-id="${comb.size_id || ''}">
                <td>${variantId}</td>
                <td>
                <input type="text" class="form-control form-control-sm" value="${sku}" />
                </td>
                <td>
                <input type="hidden" class="variant-color-id" value="${comb.color_id || ''}" />
                ${comb.color_name ? `<span style="display:inline-block;width:16px;height:16px;border-radius:50%;background:${comb.color_hex};border:1px solid #ddd;vertical-align:middle;margin-right:4px;"></span> ${comb.color_name}` : '-'}
                </td>
                <td>
                <input type="hidden" class="variant-size-id" value="${comb.size_id || ''}" />
                ${comb.size_name || '-'}
                </td>
                <td><input type="number" class="form-control form-control-sm" placeholder="Buying Price" /></td>
                <td><input type="number" class="form-control form-control-sm" placeholder="Selling Price" /></td>
                <td>
                <select class="form-select form-select-sm variant-discount-type">
                    <option value="none" selected>No Discount</option>
                    <option value="percentage">Percentage (%)</option>
                    <option value="flat">Flat Amount</option>
                </select>
                </td>
                <td>
                <input type="number"
                    class="form-control form-control-sm variant-discount-value"
                    disabled placeholder="Discount Value" />
                </td>
                <td><input type="file" class="form-control form-control-sm" accept="image/*" /></td>
                <td>
                <button type="button" class="btn btn-outline-danger btn-sm remove-variant-row-btn">Remove</button>
                </td>
                </tr>`;
            });

            tableHtml += `</tbody></table>`;
            variantsTableContainer.innerHTML = tableHtml;

            variantsTableContainer.querySelectorAll(".variant-discount-type").forEach((select) => {
                const input = select.closest("td").nextElementSibling.querySelector(".variant-discount-value");
                toggleDiscountValue(select, input);
                select.addEventListener("change", () => toggleDiscountValue(select, input));
            });
        });

        function toggleDiscountValue(discountTypeSelect, discountValueInput) {
            if (discountTypeSelect.value === "none") {
                discountValueInput.setAttribute("disabled", "disabled");
                discountValueInput.value = "";
            } else {
                discountValueInput.removeAttribute("disabled");
            }
        }

        clearVariantsBtn.addEventListener("click", function() {
            variantsTableContainer.innerHTML = '<p class="text-muted mb-0">Click "Generate Variants" to see combinations.</p>';
        });

        variantsTableContainer.addEventListener("click", function(event) {
            if (event.target.classList.contains("remove-variant-row-btn")) {
                event.target.closest("tr").remove();
                const tbody = document.getElementById("variantsTableBody");
                if (tbody) {
                    const rows = tbody.querySelectorAll("tr");
                    rows.forEach((row, index) => {
                        row.querySelector("td:first-child").textContent = index + 1;
                    });
                }
            }
        });
    });
</script>