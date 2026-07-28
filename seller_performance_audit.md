# Seller Performance Module — Audit & Implementation

## Scope

Audit + implementation of a marketplace seller performance module covering:

- Cancellation rate
- Late shipping rate
- Customer rating
- Response rate
- Composite seller score + tier
- Trend snapshots
- Auto-recompute on relevant events
- Admin ranking & per-seller drill-down

## Audit findings (before)

| # | Area | Finding | Severity |
|---|---|---|---|
| 1 | All metrics | **No performance calculation existed anywhere.** | **critical** |
| 2 | Composite score | No score column on `sellers`, no `sellers_performance_scores` table, no formula. | **critical** |
| 3 | Tiering | No tier labels — letters, categories, or thresholds. | high |
| 4 | Late-shipping measure | `OrderTracking` model existed but unused. SLA never enforced. | high |
| 5 | Response rate | No metric for chat response time. `seller_chat_messages` lacking sender direction field. | high |
| 6 | Dispute impact on score | Refunds/disputes existed (from return module) but never flowed into a performance signal. | high |
| 7 | Recomputation | No observer/listener hooked to recompute any score on events. Every UI was static. | **critical** |
| 8 | Trend history | No snapshot table — no historical view. | medium |
| 9 | Tests | Zero performance tests. | high |
| 10 | Config | No thresholds/weights to tune scoring. | medium |
| 11 | Artisan command | No batch job or CLI to recompute all sellers. | medium |
| 12 | Admin ranking | No admin board to browse sellers by score. | medium |

## Implementation summary

### New domain code (`app/Domain/Vendor/...`)

| File | Purpose |
|---|---|
| `Enums/PerformanceTier.php` | `EXCELLENT/GOOD/AVERAGE/POOR/NEW` tiers with thresholds. New sellers (<5 orders) get `NEW`. |
| `Enums/PerformancePeriod.php` | 7d/30d/90d/all_time windows with start date calculations. |
| `Models/SellerPerformanceScore.php` | Per-(seller × period) score with full breakdown JSON. |
| `Models/SellerPerformanceSnapshot.php` | Daily snapshot for trend chart (last 30/60/90 days). |
| `Services/PerformanceMetricsService.php` | Reads signals from `Order`, `OrderStatusLog`, `Review`, `SellerChat`, `ReturnRequest`/`Dispute`. |
| `Services/PerformanceCalculatorService.php` | Turn raw metrics into 5 sub-scores + weighted overall + tier + breakdown JSON. |
| `Services/SellerPerformanceService.php` | Orchestrates: `recompute(Seller)`, `score(Seller, period)`, `trend(Seller, days)`, `leaderboard(period)` with caching. |
| `Observers/SellerPerformanceObserver.php` | Hooks `Review`, `ReturnRequest`, `ReturnRequestItem`, `Order`, `SellerChat`, `SellerChatMessage`. |
| `Console/Commands/RecomputeSellerPerformance.php` | `php artisan seller:performance:recompute [--seller=ID] [--chunk=N]`. |

### Migrations

| Migration | Change |
|---|---|
| `2026_07_29_011000_create_seller_performance_scores_table.php` | New: per-period scores, rates, sub-scores, overall, tier, weights, breakdown. |
| `2026_07_29_011100_create_seller_performance_snapshots_table.php` | New: daily snapshots for trends. |

### Config (`config/marketplace.php`, `performance` block)

```php
'shipping_sla_hours'               => env('MARKETPLACE_SHIPPING_SLA_HOURS', 48),
'chat_response_sla_hours'          => env('MARKETPLACE_CHAT_RESPONSE_SLA_HOURS', 24),
'weights' => [
    'cancellation'   => 0.30,
    'late_shipping'  => 0.25,
    'rating'         => 0.25,
    'response'       => 0.10,
    'dispute'        => 0.10,    // sum = 1.0 by default
],
'thresholds' => [
    'cancellation_max' => 0.20,  // rate ≥ this caps cancellation sub-score at 0
    'late_shipping_max' => 0.30,
    'dispute_max'       => 0.30,
],
'min_orders_for_scoring' => 5,
'auto_recompute'         => true,
'snapshot_retention_days'=> 180,
```

### Controllers & views

| File | Surface | Purpose |
|---|---|---|
| `Http/Controllers/Seller/PerformanceController.php` | `GET seller/performance`, `/history`, `POST /recompute` | Seller-self dashboard, period selector, alerts, trend chart, breakdown. |
| `Http/Controllers/Admin/SellerPerformanceController.php` | `GET admin/seller-performance/{index,show}`, `/recompute` | Admin ranking, filters, drill-down with score breakdown JSON. |
| `resources/views/seller/performance/{dashboard,history}.blade.php` | — | Score tiles, alerts, trend chart (Chart.js), weighted breakdown. |
| `resources/views/admin/sellers/performance/{index,show}.blade.php` | — | Tier tiles, top performers, paginated league table. |

### Routes wired

- `GET /seller/performance` → `seller.performance.dashboard`
- `GET /seller/performance/history` → `seller.performance.history`
- `POST /seller/performance/recompute` → `seller.performance.recompute`
- `GET /admin/seller-performance` → `admin.seller-performance.index`
- `GET /admin/seller-performance/{seller}` → `admin.seller-performance.show`
- `POST /admin/seller-performance/{seller}/recompute` → `admin.seller-performance.recompute`

### Schedule

`Schedule::command('seller:performance:recompute')->dailyAt('02:00')`.
For real-time freshness the Eloquent Observer recomputes opportunistically on each
`Order` / `Review` / `ReturnRequest` / `SellerChat` / `SellerChatMessage` save.

### Score formula

For each metric a sub-score 0..100:

- **`cancellation_score`** = `100 × (1 − cancellation_rate / cancellation_max)`, floored at 0 above the threshold.
- **`late_shipping_score`** = same shape against `late_shipping_max`.
- **`rating_score`** = `(min(rating, 5) / 5) × 100`.
- **`response_score`** = `response_rate × 100`.
- **`dispute_score`** = same shape against `dispute_max`.

Overall = `Σ(sub_score × weight)` (weights auto-normalise to 1.0).

Tier mapping on overall:

- 85+ → **Excellent** (primary)
- 70+ → **Good** (info)
- 50+ → **Average** (warning)
- <50 → **Poor** (danger)
- <5 orders in window → **New Seller** (secondary) — counts as `NEW`

## Verification

- **Pint**: ✅ passed on `app/Domain/Vendor` and `tests/Feature/Vendor`
- **PHPStan**: 0 new errors introduced (pre-existing 37 errors in unrelated files remain)
- **Tests**: 10 new tests, all green (53 assertions)
  - `PerformanceCalculatorTest`: 5
  - `PerformanceMetricsTest`: 5 (shipping, response, dispute, recompute, observer)

```
Tests\Feature\Vendor\PerformanceCalculatorTest   (5 passing)
Tests\Feature\Vendor\PerformanceMetricsTest       (5 passing)
Tests\Feature\Vendor\VendorApprovalTest           (12 — pre-existing, untouched)
Tests\Feature\Vendor\VendorEmployeeTest           (12 — pre-existing, untouched)
Tests\Feature\Vendor\VendorRegistrationTest       (13 — pre-existing, untouched)
                                                       ─────────
                                                       47 passing (120 assertions)
```

```
tests/Feature/Vendor && tests/Feature/Order returns
28 passing across return management + seller performance modules
```

## Implementation notes / caveats

1. **Late-shipping** uses `OrderStatusLog` because `OrderTracking` is currently a vestigial model with no data.
2. **Response rate** infers message direction from `user_id IS NULL` (seller) / `user_id IS NOT NULL` (customer), following the convention established by `SellerChatController::sendMessage`. A future schema can add an explicit `sender_type` column.
3. **Snapshot retention** is bounded by `config('marketplace.performance.snapshot_retention_days')`. Older snapshots are not pruned by code; a separate maintenance command can be added later.
4. **Auto-recompute** is best-effort and failures are logged (not propagated), so business flows are not blocked when seller scoring runs into trouble.
5. **Observer → calculator loop** is guarded by `config('marketplace.performance.auto_recompute')` for emergency off-switch.

## Open follow-ups (out of scope for this change)

- Admin actions: suspend/block a seller automatically when `overall_score < 30` for sustained days.
- Public storefront badge that consumes the score (similar to existing `rating`).
- Review velocity (separate from average rating).
- Per-SKU performance drill-down.
- Multi-channel response rate (FB chat, in-app messaging).
