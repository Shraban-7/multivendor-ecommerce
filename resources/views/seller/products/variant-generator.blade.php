<div class="card shadow-sm border-0 mb-4" id="variantGenerator">
    <div class="card-header bg-white">
        <h5 class="mb-0">Product Variant Generator</h5>
    </div>
    <div class="card-body">
        <div id="attributeRows" class="mb-3">
            <div class="row">
                @foreach ($categoryAttributes as $categoryId => $categoryAttributeOptions)
                @foreach ($categoryAttributeOptions as $categoryAttributeOption)
                <div
                    class="col-md-6 mb-3 attributeColumn d-none"
                    data-category="{{ $categoryId }}">
                    <label class="mb-1">Add {{ $categoryAttributeOption['name'] }}(s)</label>
                    <select
                        name="option_values"
                        class="option_values w-100"
                        multiple
                        data-option="{{ $categoryAttributeOption['name'] }}">
                        @foreach ($categoryAttributeOption['values'] as $optionValue)
                        <option value="{{ $optionValue['value'] }}">
                            {{ $optionValue['value'] }}
                        </option>
                        @endforeach
                    </select>
                </div>
                @endforeach
                @endforeach
            </div>

            <div class="d-flex align-items-center gap-4 mb-3 flex-wrap">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="useMainPrices" checked />
                    <label class="form-check-label fw-semibold" for="useMainPrices">
                        Use main product's Buying &amp; Selling Price for all variants
                    </label>
                </div>
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" role="switch" id="useMainDiscount" checked />
                    <label class="form-check-label fw-semibold" for="useMainDiscount">
                        Use main product's Discount Type &amp; Value for all variants
                    </label>
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
        const useMainPricesSwitch = document.getElementById("useMainPrices");
        const useMainDiscountSwitch = document.getElementById("useMainDiscount");
        const generateVariantsBtn = document.getElementById("generateVariantsBtn");
        const variantsTableContainer = document.getElementById("variantsTableContainer");
        const clearVariantsBtn = document.getElementById("clearVariantsBtn");

        function applyMainPricesToVariants() {
            const mainBuying = document.querySelector('input[name="buying_price"]')?.value.trim() || "";
            const mainSelling = document.querySelector('input[name="selling_price"]')?.value.trim() || "";

            document.querySelectorAll("#variantsTableBody tr").forEach((row) => {
                const bp = row.querySelector('input[placeholder="Buying Price"]');
                const sp = row.querySelector('input[placeholder="Selling Price"]');
                if (bp && mainBuying) bp.value = mainBuying;
                if (sp && mainSelling) sp.value = mainSelling;
            });
        }

        // Function to apply main product discount type & value
        function applyMainDiscountToVariants() {
            const mainType = document.querySelector('select[name="discount_type"]')?.value || "none";
            const mainValue = document.querySelector('input[name="discount_value"]')?.value.trim() || "";

            document.querySelectorAll("#variantsTableBody tr").forEach((row) => {
                const typeSelect = row.querySelector(".variant-discount-type");
                const valueInput = row.querySelector(".variant-discount-value");

                if (typeSelect) {
                    typeSelect.value = mainType || "none";
                    // enable/disable value input based on type
                    if (mainType === "" || mainType === "none") {
                        valueInput.disabled = true;
                        valueInput.value = "";
                    } else {
                        valueInput.disabled = false;
                        valueInput.value = mainValue;
                    }
                }
            });
        }

        useMainPricesSwitch.addEventListener("change", function() {
            if (this.checked) applyMainPricesToVariants();
        });

        useMainDiscountSwitch.addEventListener("change", function() {
            if (this.checked) applyMainDiscountToVariants();
        });

        //live sync when main product inputs change
        ["buying_price", "selling_price"].forEach((name) => {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) {
                input.addEventListener("input", () => {
                    if (useMainPricesSwitch.checked) applyMainPricesToVariants();
                });
            }
        });

        ["discount_type", "discount_value"].forEach((name) => {
            const input = document.querySelector(`[name="${name}"]`);
            if (input) {
                input.addEventListener("input", () => {
                    if (useMainDiscountSwitch.checked) applyMainDiscountToVariants();
                });
            }
        });

        // Re-apply automatically after variants are generated
        document.addEventListener("variantsGenerated", () => {
            if (useMainPricesSwitch.checked) applyMainPricesToVariants();
            if (useMainDiscountSwitch.checked) applyMainDiscountToVariants();
        });

        function toggleDiscountValue(discountTypeSelect, discountValueInput) {
            if (discountTypeSelect.value === "none") {
                discountValueInput.setAttribute("disabled", "disabled");
                discountValueInput.value = "";
            } else {
                discountValueInput.removeAttribute("disabled");
            }
        }

        function generateCombinations(attributes) {
            if (attributes.length === 0) return [];

            const combinations = [];

            function combine(index, currentCombination) {
                if (index === attributes.length) {
                    combinations.push(currentCombination);
                    return;
                }

                const attributeName = attributes[index].name;
                const values = attributes[index].values;

                for (let i = 0; i < values.length; i++) {
                    const newCombination = {
                        ...currentCombination,
                        [attributeName]: values[i],
                    };
                    combine(index + 1, newCombination);
                }
            }

            combine(0, {});
            return combinations;
        }

        generateVariantsBtn.addEventListener("click", function() {
            const optionSelects = document.querySelectorAll(".option_values");
            const attributes = [];

            optionSelects.forEach((select) => {
                const attributeName = select.getAttribute("data-option");
                const selectedOptions = Array.from(select.selectedOptions).map((opt) =>
                    opt.value.trim()
                );

                if (attributeName && selectedOptions.length > 0) {
                    attributes.push({
                        name: attributeName,
                        values: selectedOptions,
                    });
                }
            });

            if (attributes.length === 0) {
                variantsTableContainer.innerHTML = '<p class="text-muted mb-0">Please select at least one option to generate variants.</p>';
                return;
            }

            const combinations = generateCombinations(attributes);

            if (combinations.length === 0) {
                variantsTableContainer.innerHTML = '<p class="text-muted mb-0">No valid combinations could be generated. Check your selections.</p>';
                return;
            }

            // ---- Build HTML Table ----
            let tableHtml = `
                <table class="table table-sm table-bordered mb-0">
                <thead class="bg-light">
                    <tr>
                    <th>#</th>
                    <th>SKU</th>`;

            attributes.forEach((attr) => {
                tableHtml += `<th>${attr.name}</th>`;
            });

            tableHtml += `
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
                const sku = productSku + `-${attributes
                .map((a) => comb[a.name])
                .join("-")
                .replace(/\s+/g, "")
                .toUpperCase()}`;

                tableHtml += `
                <tr data-variant-row-id="${variantId}">
                <td>${variantId}</td>
                <td>
                <input type="text" class="form-control form-control-sm" value="${sku}" />
                </td>`;

                attributes.forEach((attr) => {
                    tableHtml += `<td><input type="text" class="form-control form-control-sm" value="${comb[attr.name]}" readonly /></td>`;
                });

                tableHtml += `
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

            document.dispatchEvent(new Event("variantsGenerated"));

            // Discount logic
            variantsTableContainer.querySelectorAll(".variant-discount-type").forEach((select) => {
                const input = select.closest("td").nextElementSibling.querySelector(".variant-discount-value");
                toggleDiscountValue(select, input);
                select.addEventListener("change", () =>
                    toggleDiscountValue(select, input)
                );
            });
        });

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