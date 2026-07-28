# Seller Inventory Module Audit

**Date:** 2026-07-29
**Score:** 5/10 (pre-fix), 8/10 (post-fix)

---

## Architecture

| Component | Purpose | Status |
|-----------|---------|--------|
| `stock_in` / `stock_out` on `products` | Product-level stock ledger | ✅ |
| `stock_in` / `stock_out` on `product_variants` | Variant-level stock ledger | ✅ |
| `product_stocks` table | Single source of truth for incoming stock entries | ✅ |
| `stock_histories` table | Audit trail for all stock mutations | ✅ |
| `inventory_transactions` table | Granular transaction log with before/after snapshots | ✅ **NEW** |
| `StockManagerService` | Core service wrapping all stock mutations in atomic transactions | ✅ |
| `StockType` enum | ADD_STOCK, REMOVE_STOCK, SET_EXACT_STOCK | ✅ |
| `ProductStockController` | Seller web controller for stock management | ✅ |
| `ProductController::stockUpdate` | Per-product stock update (product detail page) | ✅ |
| `ProductController::inventory` | Inventory overview page | ✅ |
| `StockManagerService::restoreStock` | Restore stock on cancelled orders | ✅ |

## Stock Flow

```
Product/Variant
  ├── stock_in (total received)
  ├── stock_out (total sold/removed)
  └── availableStock = stock_in - stock_out (computed attribute)

product_stocks (source of truth for incoming)
  ├── product_id, variant_id, seller_id
  ├── quantity, cost_price, sub_total
  └── Each ADD/SET creates a record; REMOVE only increments stock_out

stock_histories (audit trail)
  ├── product_id, variant_id, seller_id ✅ NEW
  ├── quantity, type (StockType), note, reason ✅ NEW
  └── Created for every stock mutation

inventory_transactions (detailed log) ✅ NEW
  ├── product_id, variant_id, seller_id
  ├── type (addition/removal/set/adjustment)
  ├── quantity, stock_before, stock_after
  ├── reason (sale/purchase/return/adjustment/initial)
  ├── reference_type/reference_id (order/purchase_order)
  └── Created for every stock mutation
```

## Issues Found & Fixed

### 🔴 Critical

| # | Issue | File | Fix |
|---|-------|------|-----|
| 1 | **No seller_id check in `SaleController::delete()`** — any seller could restore stock on any product by passing arbitrary order IDs | `SaleController.php:211` | Added `->where('seller_id', get_seller_id())` to the order query |
| 2 | **`inventory.blade.php` is standalone HTML** — uses its own `<!DOCTYPE html>`, `<head>`, Bootstrap CDN, no seller layout, no sidebar/nav | `inventory.blade.php` | Converted to `@extends('seller.layouts.app')` with proper `@push('styles')` and `@push('scripts')` |

### 🟡 Medium

| # | Issue | File | Fix |
|---|-------|------|-----|
| 3 | **No inventory_transactions table** — couldn't query "what was the stock before/after this change" without manual diffing | New migration | Created `inventory_transactions` table with `stock_before`, `stock_after`, `reason`, `reference_type`, `reference_id` |
| 4 | **No reason/reference tracking on stock mutations** — `StockHistory` only had `note` (free text), no structured `reason` or `reference` fields | New migration | Added `reason` column to `stock_histories` |
| 5 | **No seller_id on StockHistory** — couldn't efficiently query history per seller | New migration | Added `seller_id` FK to `stock_histories` |

### 🟢 Low

| # | Issue | File | Fix |
|---|-------|------|-----|
| 6 | **`InventoryTransaction` model missing** | New model | Created with `product()`, `variant()`, `seller()` relations and casts |
| 7 | **StockManagerService methods lack reason/reference params** | `StockManagerService.php` | Added `$reason`, `$referenceType`, `$referenceId` params to `adjustStock()` and all public methods |
| 8 | **All stock mutations now log inventory_transactions** | `StockManagerService.php` | Added `logInventoryTransaction()` call in every mutation path |

## Negative Stock Prevention

| Layer | Mechanism | Status |
|-------|-----------|--------|
| Application | `StockManagerService::adjustStock()` checks `$quantity > $currentStock` before REMOVE | ✅ |
| Application | `SET_EXACT_STOCK` throws if `$quantity < 0` | ✅ |
| Application | `incrementStock()`/`decrementStock()` throw if `$quantity <= 0` | ✅ |
| Application | `restoreStock()` clamps stock_out at zero via `max(0, ...)` | ✅ |
| Database | Row-level locking (`lockForUpdate()`) prevents race conditions | ✅ |
| Database | No CHECK constraint on `stock_in >= stock_out` (mitigated by app-level checks) | ⚠️ Future |

## Seller Ownership Checks

| Operation | Check | Status |
|-----------|-------|--------|
| `ProductStockController::update` | `Product::where('seller_id', get_seller_id())` | ✅ |
| `ProductStockController::products` | `->where('seller_id', get_seller_id())` | ✅ |
| `ProductStockController::variants` | `Product::where('seller_id', get_seller_id())` | ✅ |
| `ProductController::stockUpdate` | `abort_unless($product->seller_id === get_seller_id(), 403)` | ✅ |
| `ProductController::inventory` | `->where('seller_id', get_seller_id())` | ✅ |
| `PosController::placeOrder` | `Order::where('seller_id', ...)` on order creation | ✅ |
| `SaleController::delete` | `Order::where('seller_id', get_seller_id())` | ✅ **FIXED** |
| `StockManagerService` | No direct seller check (relies on caller passing the correct product) | ⚠️ By design — service is a stateless utility |

## Key Files

| File | Purpose |
|------|---------|
| `app/Domain/Product/Services/StockManagerService.php` | Core stock mutation service (302 lines) |
| `app/Domain/Product/Models/Product.php` | Product model with `stock_in`, `stock_out`, `availableStock`, `totalStock` |
| `app/Domain/Product/Models/ProductVariant.php` | Variant model with per-variant `stock_in`, `stock_out`, `availableStock` |
| `app/Domain/Product/Models/StockHistory.php` | Stock history audit trail |
| `app/Domain/Product/Models/InventoryTransaction.php` | Granular transaction log (NEW) |
| `app/Domain/Product/Models/ProductStock.php` | Product stock entry model |
| `app/Domain/Product/Enums/StockType.php` | ADD / REMOVE / SET enum |
| `app/Domain/Product/Http/Controllers/Seller/ProductStockController.php` | Stock history + stock update controller |
| `app/Domain/Product/Http/Controllers/Seller/ProductController.php` | stockUpdate() + inventory() methods |
| `resources/views/seller/products/stock_history.blade.php` | Stock history view |
| `resources/views/seller/products/inventory.blade.php` | Inventory management view (FIXED) |
