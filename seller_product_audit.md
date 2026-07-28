# Seller Product Management — Production Readiness Audit

**Date:** 2026-07-29
**Scope:** Full product lifecycle — create, read, update, delete, publish, search, inventory, pricing, tax, media, multi-seller isolation

---

## 1. Existing Implementation (What Works)

### Product Lifecycle
| Phase | Status | Details |
|-------|--------|---------|
| **Create** | ✅ | Seller form with category cascading, brand Select2+tagger, CropperJS thumbnail, variant generator, auto SKU |
| **Read/List** | ✅ | Paginated table with price range, stock badges, status badges, variants modal |
| **Details** | ✅ | Full detail view with profit margin, stock history, variant colors/sizes |
| **Update** | ✅ | Inline edit with SEO/OG fields, image gallery upload, variant management, main price cascade to variants |
| **Delete** | ✅ | Soft-delete (via `SoftDeletes` trait), cleans up images/variants/seo/stock_history |
| **Duplicate** | ❌ | Not implemented |
| **Search** | ✅ | Frontend listing with category/brand/price/color/size filters, sorting |

### Product Information
| Field | Status | Storage |
|-------|--------|---------|
| Name | ✅ | `products.name` |
| Slug | ✅ | `products.slug` (unique, auto-generated with collision avoidance) |
| Short description | ✅ | `products.short_description` |
| Full description | ✅ | `products.description` |
| Specifications | ❌ | Not stored |
| Brand | ✅ | `products.brand_id` → `brands` |
| Category | ✅ | `products.category_id` → `categories` |
| Subcategory | ✅ | `products.subcategory_id` → `categories` |
| Tags | ❌ | Not implemented |
| Attributes | ⚠️ | `options`/`option_values` tables exist; not surfaced in seller product form |
| Variants | ✅ | `product_variants` with color/size FK, individual pricing/stock |
| SKU | ✅ | Auto-generated from seller code + atomic counter |
| Barcode | ⚠️ | Uses SKU as barcode (JsBarcode on print labels); no dedicated barcode field |
| Unit of measure | ✅ | `product_units` table linked via `products.unit_id` |
| Weight/Dimensions | ✅ | `products.weight/height/width/length` |
| Country of origin | ❌ | Not stored |
| Manufacturer info | ❌ | Not stored |

### Architecture
| Layer | Status | Notes |
|-------|--------|-------|
| **Controllers** | ✅ | Seller/Admin/Frontend/Api — 20 controller files across domains |
| **Services** | ✅ | `ProductService`, `StockManagerService`, `FlashSaleService`, `CatalogCacheService` |
| **Repositories** | ✅ | Interface + Eloquent implementation for Product/Category/Brand/FlashSale/Option |
| **Form Requests** | ✅ | `StoreProductRequest`, `UpdateProductRequest` (just added) |
| **Policies** | ✅ | `ProductPolicy` (just added) |
| **Observers** | ⚠️ | `ProductObserver` — only clears 2 cache keys; no business logic hooks |
| **Events** | ❌ | No custom events dispatched anywhere in the product domain |
| **Routes** | ✅ | Well-organized in `app/Domain/Product/routes.php` with admin/seller/frontend/api groups |

---

## 2. Missing Features

### 2.1 Draft Products
**Severity: Medium** | **Effort: Small**

No `STATUS_DRAFT` constant exists. Products are either `PENDING_APPROVAL` (0), `ACTIVE` (1), `INACTIVE` (2), or `DELETED` (3). Sellers cannot save incomplete products as drafts and return later.

### 2.2 Product Visibility
**Severity: Medium** | **Effort: Small**

No `is_visible` or `visibility` field. The only visibility control is `status` (`ACTIVE` = visible, `INACTIVE`/`PENDING_APPROVAL` = hidden). Sellers cannot make a product visible/invisible from the list view without going through admin approval.

### 2.3 Product Duplication
**Severity: Medium** | **Effort: Small**

No clone/duplicate functionality. Sellers must re-enter all product data from scratch for similar products.

### 2.4 Product Specifications
**Severity: Low** | **Effort: Small**

No dedicated `specifications` JSON/text field. Sellers must put specs in the description textarea.

### 2.5 Tags
**Severity: Low** | **Effort: Medium**

No tagging system. Affects product discoverability and search relevance.

### 2.6 Country of Origin
**Severity: Low** | **Effort: Small**

No `country_of_origin` field. Important for cross-border ecommerce compliance.

### 2.7 Manufacturer Information
**Severity: Low** | **Effort: Small**

No `manufacturer_name`, `manufacturer_address`, `manufacturer_contact` fields. Needed for regulatory compliance in many jurisdictions.

### 2.8 Image Ordering
**Severity: Low** | **Effort: Small**

`product_images` table has no `sort_order` column. Gallery images display in arbitrary order.

### 2.9 Per-Product Scheduled Discounts
**Severity: Low** | **Effort: Large**

Flash sales exist (admin-created, seller-submission) but there is no per-product scheduled discount. Only `compare_price` is available for simple "original vs. sale" pricing.

---

## 3. Database Gaps

| Gap | Table | Missing Column/Table | Impact |
|-----|-------|---------------------|--------|
| No specifications | `products` | `specifications` (JSON) | No structured spec data |
| No tags | `products` | No `product_tags` pivot table | Poor discoverability |
| No country of origin | `products` | `country_of_origin` (varchar) | Compliance gap |
| No manufacturer info | `products` | `manufacturer_name`, `manufacturer_details` | Compliance gap |
| No image ordering | `product_images` | `sort_order` (int, default 0) | Unpredictable gallery order |
| No visibility toggle | `products` | `is_visible` (boolean) | Cannot independently control visibility vs. approval status |
| No tax infrastructure | — | No `tax_classes`, `tax_rates`, `seller_tax_configs` tables | No tax support |
| No purchase/reservation tracking | — | No `stock_reservations` table | No cart stock reservation |
| Missing foreign keys | `products`, `product_images`, `product_stocks`, `stock_histories` | No explicit FK constraints on seller_id, product_id, etc. | Referential integrity relies on app logic |

---

## 4. Security Issues

### 4.1 No Explicit Authorization in Seller Controllers
**Severity: High**

The `Seller\ProductController` does not call `$this->authorize()` or use `Gate` in any method. Authorization depends entirely on:
- `seller` middleware on the route group (authenticates as seller)
- The `getForSeller()` repository method filtering by `seller_id`
- Route model binding for some endpoints

While the `seller` middleware prevents non-sellers from accessing the routes, **cross-seller access is not prevented within the controller**. For example:
- `show()` uses route model binding `Product $product` — any seller could access any product if the route binds by ID
- `edit()`/`update()` fetch by slug without verifying seller ownership
- `delete()` uses route model binding without ownership check

The `ProductPolicy` exists but is **never invoked** in the controller.

### 4.2 No XSS Sanitization for Description Fields
**Severity: Medium**

Description fields accept user input without sanitization. If rendered with `{!! $product->description !!}` (unescaped), this is an XSS vector.

### 4.3 No Rate Limiting on Seller Product API
**Severity: Low**

The seller API product routes (in `routes/seller-api.php`) have no `throttle` middleware. An attacker could flood the API with create/update requests.

### 4.4 Variant SKU Uniqueness Dropped
**Severity: Medium**

Migration `2026_07_27_000003` dropped the UNIQUE constraint from `product_variants.sku`. Variant SKUs can now collide, potentially causing confusion in inventory and order fulfillment.

---

## 5. Performance Issues

### 5.1 N+1 in Frontend Product Listing
**Severity: Medium**

`Frontend\ProductController::index()` (line 40) loads ALL variants:
```php
$allVariants = ProductVariant::with(['color', 'size'])->get();
```
This loads every variant across all products into memory just to build filter options. On a marketplace with 10K+ products, this is a severe performance issue.

### 5.2 No Cache for Frontend Product Queries
**Severity: Medium**

The frontend product listing queries the database on every request. No caching layer is used despite having a `CatalogCacheService` available.

### 5.3 Frontend Product Listing Loads All Relations on Paginated Results
**Severity: Medium**

`Product::active()->withDefaultRelations()` loads 9+ relations (brand, images, category, subcategory, variants.color, variants.size, seller, reviews.user, unit) for every product in a paginated listing. This is expensive for listing pages that display 25 products with minimal information.

### 5.4 No Database Indexes on Frequently Queried Columns
**Severity: Low**

While composite indexes exist on `(status, category_id)` and `(status, seller_id)`, there are no indexes on `brand_id`, `created_at` (for sorting), or `price` (for price range filtering).

---

## 6. Tax/VAT Architecture Review

### Current State
The system **originally had** a simple `tax` column (decimal 5,2) on both `products` and `orders` tables. This was **removed** by migration `2025_08_19_150007` (misleadingly named "add vat column") and replaced with `payment_type`. There is:

- No `Tax` domain
- No `TaxClass` model
- No `TaxRate` table
- No `SellerTaxConfig`
- No tax calculation service
- No global tax configuration

### Assessment
**The removal of per-product `tax` was the correct decision.** Per-product tax columns are an anti-pattern for marketplaces because:

1. **Tax rates change by jurisdiction, not by product** — Products don't have inherent tax rates; jurisdictions do
2. **Marketplaces operate across regions** — A single product sold to customers in different states/countries requires different tax treatment
3. **Seller-specific tax configuration** — Sellers may have different tax obligations (e.g., registered vs. non-registered sellers)
4. **Tax calculation is a cross-cutting concern** — It applies at checkout, not at product creation

### Recommended Architecture
```sql
-- Tax classes (e.g., "Standard Rate", "Reduced Rate", "Zero Rate", "Exempt")
tax_classes: id, name, slug, description

-- Tax rates per jurisdiction
tax_rates: id, tax_class_id, country_id, state_id, rate (decimal 5,2), 
           name (e.g., "VAT 15%"), is_compound, priority, 
           applies_to_shipping (bool), valid_from, valid_until

-- Product-tax class assignment (many-to-many)
product_tax_class: product_id, tax_class_id

-- Seller tax configuration
seller_tax_configs: seller_id, tax_id, registration_number, 
                    is_tax_exempt, tax_behavior (inclusive/exclusive)

-- Global defaults
system_settings: key = 'default_tax_behavior' → 'inclusive' or 'exclusive'
                 key = 'default_tax_class_id' → 1
```

**Implementation priority:** Low (not blocking production launch for a domestic BDT marketplace)

---

## 7. Recommended Improvements

### Priority 1: Security (Must Fix)
| # | Item | File(s) |
|---|------|---------|
| 1.1 | Enforce `ProductPolicy` in `Seller\ProductController` via `$this->authorize()` | `ProductController.php` |
| 1.2 | Add seller ownership check in `edit()`/`update()`/`delete()`/`show()` | `ProductController.php` |
| 1.3 | Re-add UNIQUE constraint on `product_variants.sku` or enforce in service | Migration + `ProductVariant` model |

### Priority 2: Feature Gaps (Should Have)
| # | Item | Approach |
|---|------|----------|
| 2.1 | Draft products | Add `STATUS_DRAFT = 4` constant; seller can save as draft; controller allows draft visibility |
| 2.2 | Product duplication | Add `duplicate()` to `ProductService` + `POST /seller/products/{product}/duplicate` route + button in index view |
| 2.3 | Gallery image ordering | Add `sort_order` column to `product_images`; implement drag-and-drop reorder in edit view |
| 2.4 | Product visibility toggle | Add `is_visible` column to `products`; toggle button in seller index view |
| 2.5 | Tags | Create `product_tags`/`tags` tables; tag input in create/edit forms |
| 2.6 | Specifications | Add `specifications` JSON column; key-value editor in create/edit forms |
| 2.7 | Country of origin | Add `country_of_origin` varchar column; select/dropdown in forms |
| 2.8 | Manufacturer info | Add `manufacturer_name`, `manufacturer_details` text columns |

### Priority 3: Performance (Should Fix)
| # | Item | Approach |
|---|------|----------|
| 3.1 | Fix N+1 in frontend listing | Load filter options from dedicated pre-computed cache or a lightweight query without loading all variants |
| 3.2 | Cache frontend product queries | Use `CatalogCacheService` for listing/filter queries with tag-based cache invalidation |
| 3.3 | Optimize eager loading | Create a `scopeForListing()` on Product that loads only necessary relations (category, thumbnail, price — not reviews, seller, full variants) |
| 3.4 | Add missing DB indexes | Index `brand_id`, `price`, `created_at` on `products` table |

### Priority 4: Tax Architecture (Plan for Future)
| # | Item | Approach |
|---|------|----------|
| 4.1 | Create Tax domain | `php artisan make:domain Tax` with migrations for `tax_classes`, `tax_rates`, `product_tax_class`, `seller_tax_configs` |
| 4.2 | Tax calculation service | `TaxCalculator` service that resolves applicable rate based on customer address, product tax class, seller config |
| 4.3 | Integrate with checkout | Apply tax calculation in OrderService during checkout |

---

## 8. Production Readiness Score

| Category | Score (0-10) |
|----------|:------------:|
| **Product CRUD** | 8/10 |
| **Pricing** | 7/10 |
| **Inventory** | 8/10 |
| **Images & Media** | 7/10 |
| **Seller Isolation** | 6/10 |
| **Authorization** | 4/10 |
| **Validation** | 7/10 |
| **Database Design** | 7/10 |
| **Performance** | 5/10 |
| **Tax/VAT** | 0/10 |
| **Security (XSS, CSRF, Rate Limiting)** | 6/10 |
| **Observability (Events, Logging)** | 2/10 |
| **Overall** | **5.6/10** |

### Verdict
The module is **functional but not production-ready** in a multi-seller marketplace context. The core CRUD operations work, but **authorization enforcement is the critical blocker** — the `ProductPolicy` exists but is never called, meaning any authenticated seller could potentially access another seller's products.

**Minimum to reach 7/10:** Security fixes (Priority 1) + Draft products + Product duplication
**Target for launch:** All Priority 1 + 2 items completed
