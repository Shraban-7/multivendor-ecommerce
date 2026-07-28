# Seller Dashboard Audit Report

**Generated:** July 28, 2026
**Scope:** Complete analysis of the seller dashboard module

---

## Current Architecture

### Files Analyzed

| File | Path |
|------|------|
| DashboardController | `app/Domain/Vendor/Http/Controllers/Seller/DashboardController.php` |
| Dashboard View | `resources/views/seller/dashboard.blade.php` |
| Seller Model | `app/Domain/Vendor/Models/Seller.php` |
| Order Model | `app/Domain/Order/Models/Order.php` |
| Product Model | `app/Domain/Product/Models/Product.php` |
| OrderStatus Enum | `app/Domain/Order/Enums/OrderStatus.php` |
| OrderRepositoryInterface | `app/Domain/Order/Repositories/Contracts/OrderRepositoryInterface.php` |
| SellerExpense Model | `app/Domain/Vendor/Models/SellerExpense.php` |
| API DashboardController | `app/Http/Controllers/Api/Seller/DashboardController.php` |
| Helpers | `app/Helpers/helpers.php` |
| Routes | `app/Domain/Vendor/routes.php` |

### Current Data Flow

```
Request → DashboardController.dashboard()
  → Fetches Seller model directly (no repository)
  → Runs 12+ separate Eloquent queries
  → Caches result for 5 minutes
  → Passes raw data to Blade view
  → View renders stats cards + chart + tables
```

### Existing Dashboard Features

| Feature | Status | Notes |
|---------|--------|-------|
| Stat cards (8) | ✅ Complete | Total Orders, Pending, Delivered, Cancelled, Expenses, Stock Value, Profit, Commission |
| Sales chart (line) | ✅ Complete | Chart.js with orders/sales/profit datasets |
| Top 5 products | ✅ Complete | Ordered by sales count |
| Latest 20 orders | ✅ Complete | With status badges and links |
| Date range filter | ✅ Complete | start_date/end_date inputs |
| Profile completion alert | ✅ Complete | Warning for incomplete profiles |
| Cache (5 min) | ✅ Partial | No invalidation strategy |

### Missing Features

| Feature | Priority | Reason |
|---------|----------|--------|
| Low stock alerts | High | Sellers need to know which products need restocking |
| Pending payout summary | High | New payout module exists but not surfaced on dashboard |
| Average order value | Medium | Key business metric |
| Order status donut chart | Medium | Visual distribution of order statuses |
| Revenue vs expense chart | Medium | Compare earnings against costs |
| Refund amount | Medium | Track lost revenue |
| Year-over-year growth | Low | Nice-to-have |
| Customer activity feed | Low | Nice-to-have |

---

## Code Quality Issues

### 1. No Service Layer
The `DashboardController` contains all business logic inline. No `DashboardService` exists. This violates separation of concerns and makes the controller untestable.

### 2. Duplicate Queries
The same base query `Order::where('seller_id', $seller_id)->whereBetween('created_at', [...])` is cloned and executed **8 times**:

```
Line 35: $ordersQuery = Order::where('seller_id', $seller->id)->whereBetween(...)
Line 38: (clone $ordersQuery)->delivered()              → COUNT query
Line 40: Order::selectRaw(...) join order_items         → Aggregation query  
Line 49: Order::where('seller_id', $seller->id)         → pluck('id')
Line 50: OrderItem::whereIn('order_id', $orderIds)      → pluck('product_id')
Line 51: OrderItem::whereIn('order_id', $orderIds)      → sum('cost_price')
Line 52: (clone $ordersQuery)->sum('seller_earnings')   → SUM
Line 83: Order::where('seller_id', $seller->id)         → sum('total_commission') [out of date range?]
Line 88: SellerExpense::where('seller_id', $seller->id) → sum('amount')
Line 96: (clone $ordersQuery)->selectRaw(...)           → Status counts
Line 103: (clone $ordersQuery)->sum('seller_earnings')  → total_sales (duplicate of line 52!)
Line 104: (clone $ordersQuery)->distinct('user_id')     → customer count
Line 106: Product::where('seller_id', $seller->id)      → product count
Line 108: (clone $ordersQuery)->with(...)->latest()     → 20 latest orders
```

**Total: 12+ queries per page load** (before caching kicks in).

### 3. Inefficient `top_selling_products` Query
```php
$orderIds = Order::where('seller_id', $seller->id)->whereBetween(...)->pluck('id');
$orderItemProductIds = OrderItem::whereIn('order_id', $orderIds)->pluck('product_id');
$top_selling_products = Product::where('seller_id', $seller->id)
    ->whereIn('id', $orderItemProductIds)
    ->withCount(...)
```
This fetches ALL order IDs, then ALL product IDs, then filters products. Can be replaced with a single join and subquery.

### 4. Hard-coded Chart Colors
Chart.js colors (`#F85606`, `#0ea5e9`, `#1D8A45`) are hard-coded in the Blade template instead of using CSS variables defined in `custom.css`.

### 5. No Cache Invalidation
Cache key is based on dates but not on data changes. If a new order is placed, the dashboard still shows stale data for up to 5 minutes. No `Cache::forget()` on order creation events.

### 6. Inconsistent Variable Naming
```php
$TotalBuyingPrice  // PascalCase instead of camelCase
$orderItemProductIds  // plural but used as collection
```

### 7. Raw SQL in Status Counts
```php
->selectRaw('COUNT(*) as total, SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending, ...')
```
Could use Eloquent's `where()->count()` with proper scopes.

### 8. No Pagination on Latest Orders
`limit(20)` is fine for a dashboard widget, but there's no "view all" link to the full orders page (which exists at `seller.orders.index`).

---

## Database Query Analysis

### Current Query Profile (per request, without cache)

| # | Query | Tables | Type | Cost |
|---|-------|--------|------|------|
| 1 | `SELECT * FROM sellers WHERE id = ?` | sellers | Primary key lookup | Low |
| 2 | `SELECT COUNT(*) FROM orders WHERE seller_id = ? AND status = 3 AND created_at BETWEEN ? AND ?` | orders | Index scan | Medium |
| 3 | `SELECT DATE(...), COUNT(...), SUM(...) FROM orders JOIN order_items ... GROUP BY DATE(...)` | orders, order_items | Full scan + join | **High** |
| 4 | `SELECT id FROM orders WHERE seller_id = ? AND created_at BETWEEN ? AND ?` | orders | Index scan | Medium |
| 5 | `SELECT product_id FROM order_items WHERE order_id IN (...)` | order_items | Index scan | Medium |
| 6 | `SELECT SUM(cost_price) FROM order_items WHERE order_id IN (...)` | order_items | Index scan | Medium |
| 7 | `SELECT SUM(seller_earnings) FROM orders WHERE seller_id = ? AND created_at BETWEEN ? AND ?` | orders | Index scan | Medium |
| 8 | `SELECT SUM(total_commission) FROM orders WHERE seller_id = ? AND created_at BETWEEN ? AND ?` | orders | Index scan | Medium |
| 9 | `SELECT SUM(amount) FROM seller_expenses WHERE seller_id = ? AND expense_date BETWEEN ? AND ?` | seller_expenses | Index scan | Medium |
| 10 | `SELECT SUM(...) FROM product_variants JOIN products ...` | product_variants, products | Full scan | **High** |
| 11 | `SELECT COUNT(*), SUM(CASE ...) FROM orders WHERE ...` | orders | Index scan | Medium |
| 12 | `SELECT COUNT(DISTINCT user_id) FROM orders WHERE ...` | orders | Index scan | Medium |
| 13 | `SELECT COUNT(*) FROM products WHERE seller_id = ?` | products | Index scan | Low |
| 14 | `SELECT * FROM products WHERE seller_id = ? AND id IN (...) ORDER BY sales_count DESC LIMIT 5` | products, order_items | Join + sort | **High** |
| 15 | `SELECT * FROM orders WHERE seller_id = ? AND created_at BETWEEN ? AND ? ORDER BY created_at DESC LIMIT 20` | orders, users, billing_address, order_items | Multiple joins | **High** |

**Total: ~15 queries, 2 high-cost, 3 medium-high cost.**

---

## Recommendations

### Immediate (High Impact)
1. **Extract `DashboardService`** — Move all data aggregation logic out of the controller
2. **Batch order aggregations** — Use a single query with multiple aggregate functions instead of 8 separate queries
3. **Add low stock alerts** — Query products with `stock_qty <= low_stock_threshold`
4. **Add pending payout card** — Surface `SellerPayout::pending()->where('seller_id', $seller_id)->sum('amount')`
5. **Add average order value** — Simple `total_sales / total_orders`
6. **Add refund amount** — Query cancelled/refunded order totals
7. **Use CSS variables for chart colors** — Reference `var(--bs-primary)` etc.

### Short-term (Medium Impact)
1. **Add order status donut chart** — Visual distribution of all order statuses
2. **Add revenue vs expense comparison** — Side-by-side chart
3. **Add view-all order link** — Link from latest orders to full order list
4. **Cache invalidation on order events** — Clear dashboard cache when orders are created/updated

### Long-term (Low Impact)
1. **Materialized dashboard tables** — Pre-aggregated daily summary table
2. **Year-over-year growth metrics** — Compare current period vs same period last year
3. **Real-time updates via WebSockets** — Pusher integration for live dashboard

---

## Proposed Architecture

```
DashboardController
  → DashboardService (@singleton or fresh instance)
    → OrderRepositoryInterface (for order queries)
    → ProductRepositoryInterface (for product queries)
    → Direct Eloquent queries for specific aggregations
  → Caching layer (tagged cache for selective invalidation)
  → Blade view receives pre-computed data
```

### Service Method Signatures

```php
class DashboardService
{
    public function overview(int $sellerId, string $startDate, string $endDate): array;
    public function chartData(int $sellerId, string $startDate, string $endDate): array;
    public function topProducts(int $sellerId, string $startDate, string $endDate, int $limit = 5): Collection;
    public function latestOrders(int $sellerId, string $startDate, string $endDate, int $limit = 20): Collection;
    public function lowStockProducts(int $sellerId, int $threshold = 10): Collection;
    public function pendingPayout(int $sellerId): float;
    public function dashboard(int $sellerId, string $startDate, string $endDate): array;
}
```
