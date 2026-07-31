<div class="bg-white rounded-sm shadow-sm overflow-hidden mb-4 hidden" id="variantGenerator">
    <div class="px-5 py-3 bg-surface-muted flex items-center gap-2 border-b border-border">
        <i data-lucide="layers" style="width:14px;height:14px;" class="text-ink-tertiary"></i>
        <h5 class="text-sm font-bold text-ink-emphasis mb-0">Product Variant Generator</h5>
        <div class="grow"></div>
        <button type="button" class="btn btn-light btn-sm" id="clearVariantsBtn">
            <i data-lucide="x" style="width:13px;height:13px;"></i> Clear
        </button>
        <button type="button" class="btn btn-primary btn-sm" id="generateVariantsBtn">
            <i data-lucide="layers" style="width:13px;height:13px;"></i> Generate Variants
        </button>
    </div>
    <div class="p-5">
        <div id="attributeRows" class="mb-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <div>
                    <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Color(s)</label>
                    <select name="color_ids" class="color-select w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" multiple>
                        @foreach ($colors as $color)
                            <option value="{{ $color->id }}" data-hex="{{ $color->hex_code }}">{{ $color->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-[11px] font-semibold text-ink-tertiary mb-1 uppercase tracking-wider">Size(s)</label>
                    <select name="size_ids" class="size-select w-full px-3 py-2 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" multiple>
                        @foreach ($sizes as $size)
                            <option value="{{ $size->id }}">{{ $size->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div id="variantsTableContainer" class="overflow-x-auto"></div>
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

            const combinations = [];
            if (selectedColors.length === 0 && selectedSizes.length === 0) {
                combinations.push({ color: null, size: null });
            }
            if (selectedColors.length === 0 && selectedSizes.length > 0) {
                selectedSizes.forEach(s => combinations.push({ color: null, size: s }));
            }
            if (selectedColors.length > 0 && selectedSizes.length === 0) {
                selectedColors.forEach(c => combinations.push({ color: c, size: null }));
            }
            if (selectedColors.length > 0 && selectedSizes.length > 0) {
                selectedColors.forEach(c =>
                    selectedSizes.forEach(s => combinations.push({ color: c, size: s }))
                );
            }

            const htmlRows = combinations.map((combo, index) => {
                const label = [combo.color?.name, combo.size?.name].filter(Boolean).join(' / ') || 'Default';
                const hex = combo.color?.hex || '#F5F5F5';
                const colorId = combo.color?.id ?? '';
                const sizeId = combo.size?.id ?? '';
                return `
                <div class="border-t border-border">
                    <span class="inline-block w-6 h-6 rounded-full align-middle me-2" style="background-color: ${hex};" title="${combo.color?.name ?? 'no color'}"></span>
                    <strong>${label}</strong>
                    <input type="hidden" name="variants[${index}][color_id]" value="${colorId}" />
                    <input type="hidden" name="variants[${index}][size_id]" value="${sizeId}" />
                </div>`;
            }).join('');

            variantsTableContainer.innerHTML = `
                <table class="w-full text-left text-sm text-ink border-collapse">
                    <thead class="bg-surface-muted">
                        <tr>
                            <th class="px-4 py-2.5">#</th>
                            <th class="px-4 py-2.5">Variant</th>
                            <th class="px-4 py-2.5">SKU</th>
                            <th class="px-4 py-2.5">Price</th>
                            <th class="px-4 py-2.5">Stock</th>
                            <th class="px-4 py-2.5">Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${combinations.map((combo, index) => {
                            const label = [combo.color?.name, combo.size?.name].filter(Boolean).join(' / ') || 'Default';
                            const hex = combo.color?.hex || '#F5F5F5';
                            const colorId = combo.color?.id ?? '';
                            const sizeId = combo.size?.id ?? '';
                            return `
                            <tr class="border-t border-border">
                                <td class="px-4 py-2.5">${index + 1}</td>
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2">
                                        <span class="inline-block w-5 h-5 rounded-full" style="background-color: ${hex};"></span>
                                        <span class="font-semibold">${label}</span>
                                    </div>
                                    <input type="hidden" name="variants[${index}][color_id]" value="${colorId}" />
                                    <input type="hidden" name="variants[${index}][size_id]" value="${sizeId}" />
                                </td>
                                <td class="px-4 py-2.5">
                                    <input type="text" name="variants[${index}][sku]" class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" />
                                </td>
                                <td class="px-4 py-2.5">
                                    <input type="number" name="variants[${index}][price]" step="0.01" class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" value="${document.querySelector('[name=price]')?.value ?? 0}" />
                                </td>
                                <td class="px-4 py-2.5">
                                    <input type="number" name="variants[${index}][stock]" class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" value="0" />
                                </td>
                                <td class="px-4 py-2.5">
                                    <input type="file" name="variants[${index}][image]" accept="image/*" class="w-full text-sm" />
                                </td>
                            </tr>`;
                        }).join('')}
                    </tbody>
                </table>
                <div class="mt-3 flex justify-end">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-brand-tint text-brand-deep me-3">
                        ${combinations.length} variants queued
                    </span>
                    <button type="button" id="appendMoreVariantsBtn" class="btn btn-light btn-sm">
                        <i data-lucide="plus" style="width:13px;height:13px;"></i> Add More Rows
                    </button>
                </div>
            `;
            if (window.renderIcons) window.renderIcons(variantsTableContainer);
        });

        clearVariantsBtn.addEventListener('click', function() {
            variantsTableContainer.innerHTML = '';
        });
    });

    document.addEventListener("click", function(e) {
        if (e.target && e.target.id === "appendMoreVariantsBtn") {
            const tbody = document.querySelector("#variantsTableContainer tbody");
            const i = tbody.querySelectorAll("tr").length;
            const row = document.createElement("tr");
            row.className = "border-t border-border";
            row.innerHTML = `
                <td class="px-4 py-2.5">${i + 1}</td>
                <td class="px-4 py-2.5"><input type="text" name="variants[${i}][label]" class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" /></td>
                <td class="px-4 py-2.5"><input type="text"   name="variants[${i}][sku]"     class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" /></td>
                <td class="px-4 py-2.5"><input type="number" name="variants[${i}][price]"  step="0.01" class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" /></td>
                <td class="px-4 py-2.5"><input type="number" name="variants[${i}][stock]"  class="w-full px-2 py-1 text-sm text-ink-emphasis bg-surface-muted rounded-xs focus:outline-none focus:ring-1 focus:ring-brand-deep transition-colors" /></td>
                <td class="px-4 py-2.5"><input type="file"    name="variants[${i}][image]"  accept="image/*" class="w-full text-sm" /></td>
            `;
            tbody.appendChild(row);
        }
    });
</script>
