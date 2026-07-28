# Product Variant Module Audit

**Date:** 2026-07-29
**Score:** 6/10

---

## Executive Summary

The Product Variant module has a solid foundation with color/size specialization, stock source-of-truth via `product_stocks`, and frontend selection. However, it suffers from incomplete admin UI (no views for Colors or Sizes), missing admin routes, lack of variant lifecycle events, and no per-product SKU enforcement after dropping global uniqueness. The multivendor architecture is correctly respected — variants belong to products which belong to sellers — but no explicit variant-level policies or gates exist.

---

## Database Schema

### `product_variants` (Core table)

| Column | Type | Notes |
|--------|------|-------|
| id | bigint (PK) | Auto-increment |
| product_id | bigint (FK→products) | Nullable; defines seller ownership implicitly |
| color_id | bigint (FK→colors) | Nullable, nullOnDelete |
| size_id | bigint (FK→sizes) | Nullable, nullOnDelete |
| sku | string | **No longer globally unique** (dropped in migration `2026_07_27_000003`) |
| image | string (nullable) | Single image stored as path string; **no relation table** |
| cost_price | decimal(10,2) | |
| price | decimal(10,2) | |
| compare_price | decimal(10,2) | Nullable |
| stock_in | int (default 0) | Denormalized; SoT is `product_stocks` |
| stock_out | int (default 0) | Denormalized |
| low_stock_quantity | int (default 0) | |
| timestamps | | |

### `product_variant_options` (Pivot table)

Connects variants to generic `option_values` for arbitrary attributes beyond color/size.

### `colors` (Lookup table)

| Column | Type |
|--------|------|
| id | bigint (PK) |
| name | string |
| slug | string |
| hex_code | string(7) |
| image | string (nullable) |

### `sizes` (Lookup table)

| Column | Type |
|--------|------|
| id | bigint (PK) |
| name | string |
| slug | string |
| sort_order | int |

---

## Audit Checklist

| # | Criteria | Status | Score | Notes |
|---|----------|--------|-------|-------|
| 1 | **Variant Creation** | ✅ Present | 1/1 | `ProductVariantController::store()` + `ProductService::createVariants()` — batch create via JSON array |
| 2 | **Attributes (Generic)** | ✅ Present | 1/1 | `Option`/`OptionValue` models; `options`/`option_values` tables; admin CRUD via `Admin\OptionController` |
| 3 | **Attribute Values** | ✅ Present | 1/1 | OptionValues CRUD; linked via `product_variant_options` pivot |
| 4 | **Color** | 🟡 Partial | 0.5/1 | `Color` model + table + `Admin\ColorController` exists; **no admin view, no admin routes, no sidebar link** |
| 5 | **Size** | 🟡 Partial | 0.5/1 | Same as Color — model + table + controller exist; **no admin view, no admin routes, no sidebar link** |
| 6 | **Variant Images** | 🟡 Partial | 0.3/1 | Stored as single string column on `product_variants.image`; **no `product_variant_images` table** — only one image per variant supported |
| 7 | **Variant SKU** | 🟡 Partial | 0.5/1 | Auto-generated from product slug + color + size slugs; **global unique constraint dropped** but no per-product unique enforcement added |
| 8 | **Variant Pricing** | ✅ Present | 1/1 | cost_price, price, compare_price per variant; calculatedPrice/calculatedDiscount accessors; discount display on frontend |
| 9 | **Variant Stock** | ✅ Present | 0.8/1 | `product_stocks` as SoT with row-level locking; `StockManagerService` variant-aware; stock history with variant FK; denormalized stock_in/stock_out on variants table for quick reads |
| 10 | **Multivendor Compatibility** | ✅ Present | 0.9/1 | Variants belong to products which belong to sellers; ownership verified via product seller in controller; **no explicit `VariantPolicy`** or gates |

**Total Score: 6/10**

---

## Detailed Findings

### Strengths (Score = 1)

1. **Variant Creation** — Batch variant generation from color × size cartesian product. Variants can be created during product creation (`ProductService::createVariants`) or added later (`ProductVariantController::store`). Frontend JS generates the grid in `variant-generator.blade.php`.

2. **Generic Attributes** — `Option`/`OptionValue` models with a pivot `product_variant_options` allow attaching arbitrary attribute values to variants beyond color/size. Admin manages these via `Admin\OptionController`.

3. **Attribute Values** — Full CRUD for option values with inline editing via modals.

4. **Variant Pricing** — Each variant has its own cost_price, price, compare_price. The `calculatedPrice()` accessor returns `compare_price ?? price` for effective display. Discount calculations in `calculatedDiscount()`.

5. **Variant Stock** — Robust architecture: `product_stocks` table is the source of truth (with `product_variant_id` nullable FK), adjusted via `StockManagerService` with atomic transactions and row-level locking. Denormalized `stock_in`/`stock_out` on variants for fast reads. Stock history with variant FK.

6. **Multivendor** — All variants cascade from `product → seller`. Seller controllers validate ownership by checking `$variant->product->seller_id === get_seller_id()`. Resources include seller data.

### Weaknesses (Score < 1)

1. **Colors — Missing Admin UI** — `Color` model, table (`colors`), and controller (`Admin\ColorController`) exist with full CRUD methods, but:
   - **No routes** registered in `app/Domain/Product/routes.php`
   - **No admin view** at `resources/views/admin/colors/index.blade.php`
   - **No sidebar link** in admin sidebar

2. **Sizes — Missing Admin UI** — Same as Colors.
   - **No routes** registered in routes.php
   - **No admin view** at `resources/views/admin/sizes/index.blade.php`
   - **No sidebar link** in admin sidebar

3. **Variant Images** — Only one image per variant (string column). No `product_variant_images` table for multiple images per variant. The `imageUrl()` accessor provides a default fallback.

4. **Variant SKU** — The migration `2026_07_27_000003` dropped the global unique constraint on `product_variants.sku` to allow same SKU across different products (multi-vendor concern). However, **no per-product unique constraint** was added, so a seller could accidentally create two variants with the same SKU for the same product.

5. **Stock Denormalization** — `stock_in`/`stock_out` on `product_variants` is a cached/denormalized mirror of `product_stocks`. While this enables fast listing queries, it creates a risk of drift if the `StockManagerService` isn't the only code path that modifies stock.

### Missing Entirely

- **ProductVariantPolicy** (no gates/policies)
- **Variant lifecycle events** (no `VariantCreated`/`VariantUpdated`/`VariantDeleted` events)
- **FormRequest classes** for variant validation
- **Bulk price sync** when parent product price changes
- **ProductVariant repository** (access is direct via Eloquent)
- **Attribute validation per category** (CategoryOption links exist but no enforcement)

---

## Recommendations

### Critical (Must Fix)

| Priority | Issue | Fix |
|----------|-------|-----|
| P0 | Missing admin routes for Colors/Sizes | Add to `routes.php` |
| P0 | Missing admin views for Colors/Sizes | Create `index.blade.php` following admin layouts |
| P1 | Missing sidebar links for Colors/Sizes | Add under "Manage Catalogs" in admin sidebar |

### High Priority (Should Fix)

| Priority | Issue | Fix |
|----------|-------|-----|
| P1 | No per-product unique SKU | Add validation in `ProductVariantController::store` to check SKU uniqueness per `product_id` |
| P2 | Variant image limited to 1 | Create `product_variant_images` table with sort_order, or allow multiple image uploads |

### Medium Priority (Nice to Have)

| Priority | Issue | Fix |
|----------|-------|-----|
| P2 | No FormRequest validation | Create `StoreVariantRequest`/`UpdateVariantRequest` |
| P2 | No variant policies | Create `VariantPolicy` with `before()` for seller check |
| P3 | No lifecycle events | Fire events on create/update/delete for hookable side effects |
| P3 | No bulk price sync | Add "Apply product prices to all variants" button in seller UI |

---

## Migration History (Variant-Related)

| File | Purpose | Status |
|------|---------|--------|
| `2025_02_17_055937_create_product_variants_table.php` | Core variants table | ✅ Migrated |
| `2025_06_30_094812_create_product_variant_options_table.php` | Variant ↔ option values pivot | ✅ Migrated |
| `2026_07_27_000001_create_colors_table.php` | Colors lookup | ✅ Migrated |
| `2026_07_27_000002_create_sizes_table.php` | Sizes lookup | ✅ Migrated |
| `2026_07_27_000003_add_color_size_to_product_variants_table.php` | Added color_id/size_id FK; dropped global unique SKU | ✅ Migrated |
| `2026_07_27_030000_drop_is_default_from_product_variants_table.php` | Dropped deprecated is_default column | ✅ Migrated |
| `2026_07_25_000001_add_variant_id_to_product_stocks_table.php` | Added product_variant_id to stock SoT | ✅ Migrated |
| `2026_07_25_200000_add_additional_indexes.php` | Added variant indexes across tables | ✅ Migrated |

---

## File Map

### Existing Files
- `app/Domain/Product/Models/ProductVariant.php` — Variant model with color/size relations, accessors
- `app/Domain/Product/Models/ProductVariantOption.php` — Pivot model
- `app/Domain/Product/Models/Option.php` — Generic attribute model
- `app/Domain/Product/Models/OptionValue.php` — Attribute value model
- `app/Domain/Product/Models/Color.php` — Color lookup model
- `app/Domain/Product/Models/Size.php` — Size lookup model
- `app/Domain/Product/Models/CategoryOption.php` — Category-option pivot
- `app/Domain/Product/Models/Product.php` — Has `variants()`, `groupedOptions()`, `toDetailsArray()`
- `app/Domain/Product/Http/Controllers/Seller/ProductVariantController.php` — Batch store, update, destroy
- `app/Domain/Product/Http/Controllers/Seller/ProductController.php` — Variant operations in product context
- `app/Domain/Product/Http/Controllers/Seller/ProductStockController.php` — Variant stock adjustments
- `app/Domain/Product/Http/Controllers/Admin/OptionController.php` — Option/attribute CRUD
- `app/Domain/Product/Http/Controllers/Admin/ColorController.php` — **Missing routes + view**
- `app/Domain/Product/Http/Controllers/Admin/SizeController.php` — **Missing routes + view**
- `app/Domain/Product/Http/Controllers/Frontend/ProductController.php` — Variant lookup, filtering
- `app/Domain/Product/Http/Resources/ProductVariantResource.php` — API transformer
- `app/Domain/Product/Services/ProductService.php` — `createVariants()`, `duplicate()` with variants
- `app/Domain/Product/Services/StockManagerService.php` — Variant-aware stock management
- `resources/views/seller/products/variant-generator.blade.php` — JS variant grid generator
- `resources/views/components/frontend/variant-selection-card.blade.php` — Frontend color/size selector
- `resources/views/seller/products/details.blade.php` — Variant management UI
- `resources/views/seller/products/inventory.blade.php` — Flat variants inventory view
- `resources/views/admin/options/index.blade.php` — Admin option management

### Missing Files (To Create)
- `resources/views/admin/colors/index.blade.php`
- `resources/views/admin/sizes/index.blade.php`
