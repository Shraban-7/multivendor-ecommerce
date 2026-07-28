# Product Variant Module Audit

**Date:** 2026-07-29  
**Auditor:** Senior Laravel Architect  
**Scope:** Database schema, CRUD flows, Multivendor security, Inventory, Validation  
**Score:** 4.5/10

---

## Executive Summary

The existing variant system has a functional foundation but was built incrementally — the addition of dedicated `colors`/`sizes` tables was retrofitted on top of a generic `options`/`option_values` system, leaving technical debt. Several critical features from the spec (barcode, weight, variant status/disable, variant images gallery, SKU per-product uniqueness, duplicate combination prevention, inventory transactions) are entirely missing.

---

## Architecture Mapping

| Spec Term | Existing Term | Status |
|-----------|---------------|--------|
| `attributes` | `options` | ✅ Exists (renamed) |
| `attribute_values` | `option_values` | ✅ Exists (renamed) |
| `variant_attribute_values` | `product_variant_options` (pivot) | ✅ Exists |
| `product_variants.sku` | `product_variants.sku` | ⚠️ Global unique dropped, no per-product enforcement |
| `product_variants.barcode` | — | ❌ Missing |
| `product_variants.discount_price` | `compare_price` (inverse convention) | ⚠️ Concept exists with opposite naming |
| `product_variants.cost_price` | `cost_price` | ✅ Exists |
| `product_variants.weight` | — | ❌ Missing |
| `product_variants.status` | — | ❌ Missing (no enable/disable) |
| `product_variant_images` | — | ❌ Missing (single image column only) |
| `inventory_transactions` | — | ❌ Missing (no stock journal) |
| Variant combination uniqueness | — | ❌ Missing |

---

## Detailed Audit

### 1. Database Tables

#### Existing Tables

| Table | Purpose | Status |
|-------|---------|--------|
| `product_variants` | Core variant data | ⚠️ Missing barcode, weight, status, discount_price |
| `product_variant_options` | Variant ↔ option values pivot | ✅ Exists |
| `options` | Attributes (Color, Size, Storage...) | ✅ Exists (missing `status` column) |
| `option_values` | Attribute values (Black, M, 128GB...) | ✅ Exists |
| `category_options` | Category ↔ attribute binding | ✅ Exists |
| `colors` | Specialized color lookup | ✅ Exists (retrofit, overlaps with options) |
| `sizes` | Specialized size lookup | ✅ Exists (retrofit, overlaps with options) |

#### Missing Tables

| Table | Spec Requirement | Impact |
|-------|-----------------|--------|
| `product_variant_images` | Multiple images per variant, gallery support | Cannot upload separate images for each variant |
| `inventory_transactions` | Stock journal (purchase, sale, adjustment) | No audit trail for variant stock changes |

### 2. Missing Columns on `product_variants`

| Column | Spec | Current State |
|--------|------|---------------|
| `barcode` | Unique product identifier | ❌ Not present |
| `weight` | Per-variant shipping weight | ❌ Not present |
| `status` | Enable/disable individual variant (boolean) | ❌ Not present |
| `discount_price` | Sale/discounted price | ⚠️ `compare_price` used instead (inverse convention: compare > price = discounted) |

### 3. Validation Gaps

| Validation | Spec Requirement | Current State |
|------------|-----------------|---------------|
| SKU unique per product | `TSH-BLK-M` cannot exist twice for same product | ❌ Global unique dropped (`2026_07_27_000003`), no per-product replacement |
| Barcode unique | `890123456789` cannot be duplicated | ❌ Entirely missing |
| Duplicate combination | `Black + M` + `Black + M` not allowed | ❌ No check before insertion |
| Variant min fields | Must have at least one attribute value | ❌ Not enforced |

### 4. Security Issues

| Issue | Severity | Details |
|-------|----------|---------|
| No variant-specific policy | Medium | Ownership check is inline in controllers, no reusable `VariantPolicy` |
| No FormRequest validation | Low | Validation is done inline with `$request->validate()` |
| Color/Size delete cascade | Low | Colors/sizes use `nullOnDelete` — safe |

### 5. Performance Issues

| Issue | Impact | Details |
|-------|--------|---------|
| `$with = ['color', 'size']` on ProductVariant | Always eager-loads even when not needed | Forces 2 extra queries on every variant load |
| No variant count cache on product | Listing queries N+1 risk | `$product->variants->count()` hits DB every time |
| Stock denormalization drift | `stock_in`/`stock_out` can desync from `product_stocks` SoT | No scheduled reconciliation |

### 6. Features vs Spec

| Feature | Spec | Status |
|---------|------|--------|
| Enable variants toggle on product | Seller enables/disables variant mode | ❌ Missing |
| Select attributes per product | Checkboxes for Color, Size, Storage... | ⚠️ Partial (color/size only, no generic attribute selection) |
| Generate combinations | Cartesian product of selected values | ✅ Exists (color × size only) |
| Edit variant price | Per-variant pricing | ✅ Exists |
| Edit variant SKU | Per-variant SKU override | ⚠️ SKU is auto-generated, not easily editable |
| Edit variant barcode | Per-variant barcode | ❌ Missing |
| Upload variant image | Single variant image | ✅ Exists (single image only) |
| Multiple variant images | Gallery per variant | ❌ Missing |
| Variant enable/disable | Status toggle per variant | ❌ Missing |
| Variant weight | Per-variant shipping weight | ❌ Missing |
| Delete variant | Remove variant | ✅ Exists |
| Stock deduction on order | Real-time stock sync | ⚠️ Partial (uses stock_in/stock_out directly, no inventory transactions journal) |
| Inventory transactions | Purchase/sale/adjustment history | ❌ Missing |

---

## Implementation Roadmap

### P0 — Critical (Implement Now)

| # | Task | Files |
|---|------|-------|
| 1 | **Migration: Add barcode, weight, status** to `product_variants` | New migration file |
| 2 | **Migration: Create `product_variant_images`** table | New migration file |
| 3 | **Update ProductVariant model**: Add new fields to casts, add `discountPrice` accessor, `variantImages()` hasMany, `optionValues()` belongsToMany, `scopeActive()`, `scopeForSeller()` | `ProductVariant.php` |
| 4 | **Create ProductVariantImage model** | New model file |
| 5 | **Add per-product SKU uniqueness validation** | `ProductVariantController::store()` |
| 6 | **Add barcode uniqueness validation** | `ProductVariantController::store()` |
| 7 | **Add duplicate combination prevention** | `ProductVariantController::store()` |
| 8 | **Update ProductVariantController::update**: Handle barcode, weight, status fields | `ProductVariantController.php` |
| 9 | **Update ProductService::createVariants**: Handle barcode, weight, status | `ProductService.php` |

### P1 — High Priority

| # | Task | Files |
|---|------|-------|
| 10 | **Update variant generator UI**: Add barcode, weight fields | `variant-generator.blade.php` |
| 11 | **Update variant detail view**: Show status toggle, image gallery | `seller/products/details.blade.php` |
| 12 | **Update product index**: Show variant status badge | `seller/products/index.blade.php` |
| 13 | **Add status toggle to ProductVariantController** | `ProductVariantController.php` + routes |

### P2 — Medium Priority

| # | Task | Files |
|---|------|-------|
| 14 | Create `VariantPolicy` for reusable authorization | New policy file |
| 15 | Create `StoreVariantRequest`/`UpdateVariantRequest` FormRequests | New form request files |
| 16 | Add `is_variant` toggle on product create form | `create.blade.php` + `ProductService.php` |
| 17 | Fire `VariantCreated`/`VariantUpdated`/`VariantDeleted` events | New event + listener files |
| 18 | Add `options` table `status` column (enable/disable attributes) | Migration |

### P3 — Low Priority

| # | Task | Files |
|---|------|-------|
| 19 | Create `inventory_transactions` table + model + service | New module |
| 20 | Add scheduled reconciliation between `product_variants.stock_in/out` and `product_stocks` | Console command |
| 21 | Remove `$with = ['color', 'size']` from ProductVariant and use explicit eager loading | `ProductVariant.php` |
| 22 | Add variant count cache column to `products` table | Migration |

---

## Recommended Final Schema

```sql
-- Attributes (renamed from options)
attributes: id, name, status, created_at, updated_at

-- Attribute values (renamed from option_values)
attribute_values: id, attribute_id, value, created_at, updated_at

-- Product variants (enhanced)
product_variants:
  id, product_id, sku, barcode, price, discount_price, cost_price,
  stock_in, stock_out, low_stock_quantity, weight, image, status,
  color_id (nullable FK→colors), size_id (nullable FK→sizes),
  created_at, updated_at

-- Variant attribute mapping (exists as product_variant_options)
variant_attribute_values: variant_id, attribute_value_id

-- Variant images (NEW)
product_variant_images:
  id, product_variant_id, image_path, is_primary, sort_order, created_at, updated_at

-- Inventory transactions (NEW)
inventory_transactions:
  id, product_variant_id, type (purchase/sale/adjustment/return),
  quantity, reference_type, reference_id, notes, created_at
```
