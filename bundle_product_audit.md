# Bundle Product Module — Audit Report

**Date:** 2026-07-29
**Scope:** Full audit of bundle/combo product functionality for seller product management

---

## 1. Existing Implementation

**There is zero existing bundle/combo functionality.** The codebase has:

| Feature | Status |
|---------|--------|
| Bundle creation | ❌ Not implemented |
| Bundle editing | ❌ Not implemented |
| Bundle deletion | ❌ Not implemented |
| Bundle visibility | ❌ Not implemented |
| Bundle approval workflow | ❌ Not implemented |
| Bundle pricing (auto/manual) | ❌ Not implemented |
| Bundle discount | ❌ Not implemented |
| Bundle inventory | ❌ Not implemented |
| Bundle images (thumbnail/gallery) | ❌ Not implemented |
| Bundle SKU/Barcode | ❌ Not implemented |
| Bundle order processing | ❌ Not implemented |
| Bundle stock deduction | ❌ Not implemented |
| Bundle cart support | ❌ Not implemented |
| Bundle validation | ❌ Not implemented |
| Authorization/ownership | ❌ Not implemented |

**No bundle-related tables, models, controllers, routes, or views exist.**

---

## 2. Database Design

### Required Tables

#### `bundles`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| seller_id | bigint FK→sellers | Cascade delete |
| name | varchar(255) | |
| slug | varchar(255) | Unique, indexed |
| sku | varchar(255) | Unique per seller |
| barcode | varchar(255) | Nullable, unique |
| type | varchar(20) | `fixed` or `mix_match` |
| price_type | varchar(20) | `auto` or `manual` |
| price | decimal(12,2) | Manual price if set |
| compare_price | decimal(12,2) | Nullable |
| discount_type | varchar(20) | `percentage` or `fixed` |
| discount_value | decimal(12,2) | |
| total_stock | int | Calculated min of child stocks |
| status | tinyint | 0=pending, 1=active, 2=inactive, 3=draft |
| is_visible | bool | |
| thumbnail | varchar(255) | |
| short_description | text | |
| description | longtext | |
| deleted_at | timestamp | Soft deletes |
| created_at/updated_at | timestamps | |

#### `bundle_items`
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| bundle_id | bigint FK→bundles | Cascade delete |
| product_id | bigint FK→products | |
| quantity | int | ≥ 1 |
| is_optional | bool | |
| sort_order | int | |

#### `bundle_images`
| Column | Type |
|--------|------|
| id | bigint PK |
| bundle_id | bigint FK→bundles |
| image | varchar(255) |
| sort_order | int |

#### `bundle_pricing_rules` (for mix-and-match)
| Column | Type | Notes |
|--------|------|-------|
| id | bigint PK | |
| bundle_id | bigint FK→bundles | |
| min_items | int | |
| max_items | int | |
| discount_percent | decimal(5,2) | |
| label | varchar(255) | e.g., "Buy 3, save 10%" |

---

## 3. Pricing Architecture

### Automatic Price Calculation
```
bundle_price = SUM(item.price * item.quantity for all items)
```
- If discount_type=percentage: `final_price = bundle_price - (bundle_price * discount_value / 100)`
- If discount_type=fixed: `final_price = bundle_price - discount_value` (min 0)
- If price_type=manual: `final_price = price` (seller overrides)

### Compare Price
- `compare_price` on bundle level for showing savings

---

## 4. Inventory Architecture

### Stock Calculation
```
bundle_stock = MIN(product.available_stock / item.quantity for each item)
```
- Stock is calculated live; no dedicated stock tracking at bundle level
- When a bundle is ordered, each child product's stock is decremented

### Stock Deduction on Order
1. Bundle's items iterated
2. Each child product's stock decremented by `item.quantity * bundle_quantity`
3. StockHistory entries created per child product

### Out-of-Stock Handling
- Before adding to cart: check all child products have sufficient stock
- Before order placement: re-check stock (pessimistic locking)
- If insufficient: validation error with specific item name

---

## 5. Order Processing Integration

### Cart
- `CartItem` gets an optional `bundle_id` and `bundle_data` (JSON: snapshot of items at add time)
- Bundle appears as a single cart item

### Order
- `OrderItem` gets optional `bundle_id`, `bundle_data` (JSON snapshot of bundle items at order time)
- When placing order:
  1. Regular items processed normally
  2. Bundle items expanded: for each bundle, create OrderItem for the bundle itself and individual stock deduction entries
  3. StockManagerService decrements each child product

### Refunds/Returns
- Return flow checks `bundle_id` on OrderItem
- Full bundle return = return all child items
- Partial: not allowed for bundles (bundle must be returned as whole)

---

## 6. Validation Rules

| Rule | Enforcement |
|------|-------------|
| Duplicate products in bundle | Validate no duplicate product_id in bundle_items |
| Invalid quantities | Each quantity ≥ 1, max 999 |
| Circular bundles | Bundle cannot contain another bundle |
| Deleted products | Filter soft-deleted products at load time |
| Hidden products | Cannot bundle inactive/hidden products |
| Cross-seller restriction | All products must belong to same seller as bundle |
| Minimum items | Fixed bundle: ≥ 2 items |
| Mix-and-match minimum | Configured via pricing_rules.min_items |

---

## 7. Security

| Risk | Mitigation |
|------|------------|
| Seller creates bundle with another seller's products | `abort_unless` on every product belonging to bundle's seller |
| Unauthorized CRUD | `abort_unless($bundle->seller_id === get_seller_id(), 403)` on all actions |
| CSRF | All POST routes use @csrf |
| Mass assignment | `$guarded = ['id']` on all models |

---

## 8. Performance

| Concern | Mitigation |
|---------|------------|
| N+1 bundle items | Eager load `items.product` on all queries |
| Stock calculation | Single query: `SELECT MIN(p.stock_in - p.stock_out) FROM products p JOIN bundle_items bi ON p.id = bi.product_id WHERE bi.bundle_id = ?` |
| Product loading | Eager load thumbnail, variants, category for index views |
| Checkout | Bundle item expansion with eager loading |

---

## 9. Production Readiness Score

| Category | Score (0-10) |
|----------|:------------:|
| Database schema | 0/10 |
| Bundle CRUD | 0/10 |
| Pricing | 0/10 |
| Inventory | 0/10 |
| Cart integration | 0/10 |
| Order processing | 0/10 |
| Validation | 0/10 |
| Security | 0/10 |
| Performance | 0/10 |
| UI/UX | 0/10 |
| **Overall** | **0/10** |

---

## 10. Implementation Plan

1. Create migration: `bundles`, `bundle_items`, `bundle_images`, `bundle_pricing_rules`
2. Create models: `Bundle`, `BundleItem`, `BundleImage`, `BundlePricingRule`
3. Create services: `BundlePricingService`, `BundleInventoryService`, `BundleValidationService`, `BundleService`
4. Create `Seller/BundleController` (index, create, store, show, edit, update, destroy, toggleVisibility, duplicate)
5. Create routes + `BundleServiceProvider`
6. Create Blade views (index, create, edit, show)
7. Register in `bootstrap/providers.php` + sidebar nav
8. Run migrations
