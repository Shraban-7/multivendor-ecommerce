# Seller Panel Tailwind CSS Migration — Status

## Documents

- **Audit Report**: `docs/seller-panel-bootstrap-audit.md`
- **Redesign Spec**: `docs/seller-panel-redesign.md`
- **Task Roadmap**: `tasks/12-seller-panel-tailwind-migration.md`

---

## Phase 1 — Audit (COMPLETED)

- [x] List every Blade view/partial under `resources/views/seller` (81 files)
- [x] Inventory Bootstrap grid, utility, and component classes
- [x] Identify all 98 `data-bs-*` JS-driven elements
- [x] Flag custom CSS blocks and inline script references
- [x] Generate `docs/seller-panel-bootstrap-audit.md`

---

## Phase 2 — Tailwind CDN & Theme Setup

- [x] **2.1** — Update `layouts/app.blade.php` & `auth.blade.php` — Add Tailwind CDN + theme config
- [x] **2.2** — Ensure `bootstrap.bundle.min.js` remains loaded
- [x] **2.3** — Document CDN caveats for production

### CDN Caveats & Production Notes

1. **Play CDN is dev-only**: `https://cdn.tailwindcss.com` (Play CDN) is not recommended for production. It ships the full Tailwind runtime (~400 KB) and compiles styles in-browser at load time. After Phase 3 completes, replace it with a proper Tailwind build (`tailwindcss-cli` or PostCSS) that tree-shakes unused utilities.
2. **Local fallback available**: `public/assets/libs/tailwindcss/3.4.16.js` exists for offline/dev use if the CDN is unreachable.
3. **Preflight disabled during transition**: `corePlugins: { preflight: false }` is set in the theme config to prevent Tailwind's CSS reset from conflicting with Bootstrap component styles during the Phase 3 migration. **Re-enable Preflight in Phase 4** once `theme.css` and `custom.css` (Bootstrap CSS) are removed.
4. **Bootstrap CSS retained**: `theme.css` and `custom.css` remain loaded in both layouts until Phase 4. This allows incremental page-by-page migration without breaking un-migrated pages.
5. **`bootstrap.bundle.min.js` must stay**: Even after Bootstrap CSS is removed in Phase 4, the JS bundle is required for all `data-bs-*` interactive components (modals, dropdowns, tabs, offcanvas, collapse, alerts).

---

## Phase 3 — Page-by-Page Refactor

### Group 1: Shared Layouts & Partials
- [x] `seller/layouts/app.blade.php`
- [x] `seller/layouts/auth.blade.php`
- [x] `seller/layouts/navbar.blade.php`
- [x] `seller/layouts/sidebar.blade.php`

### Group 2: Dashboard
- [x] `seller/dashboard.blade.php`

### Group 3: Authentication
- [x] `seller/auth/login.blade.php`
- [x] `seller/auth/signup.blade.php` (empty file)

### Group 4: Product Management
- [x] `seller/products/index.blade.php`
- [x] `seller/products/create.blade.php`
- [x] `seller/products/edit.blade.php`
- [x] `seller/products/details.blade.php`
- [x] `seller/products/inventory.blade.php`
- [x] `seller/products/stock_history.blade.php`
- [x] `seller/products/variant-generator.blade.php`
- [x] `seller/products/attributes/index.blade.php`
- [x] `seller/products/attributes/edit.blade.php`
- [x] `seller/products/media/index.blade.php`
- [x] `seller/products/partials/upload-images.blade.php`
- [x] `seller/products/partials/variant-section.blade.php`

### Group 5: Order Management
- [x] `seller/orders/index.blade.php`
- [x] `seller/orders/details.blade.php`
- [x] `seller/orders/tracking.blade.php`
- [x] `seller/orders/receipt.blade.php`
- [x] `seller/orders/receipt-old.blade.php`

### Group 6: Reports & Performance
- [x] `seller/reports/overview.blade.php`
- [x] `seller/reports/sales.blade.php`
- [x] `seller/reports/financial.blade.php`
- [x] `seller/reports/customers.blade.php`
- [x] `seller/performance/dashboard.blade.php`
- [x] `seller/performance/history.blade.php`

### Group 7: Chats
- [x] `seller/chats/index.blade.php`
- [x] `seller/chats/messages.blade.php`

### Group 8: Settings & Profile
- [x] `seller/settings/index.blade.php`
- [x] `seller/profile.blade.php`
- [x] `seller/profile-information.blade.php`
- [x] `seller/notifications.blade.php`
- [x] `seller/subscription-plans.blade.php`
- [x] `seller/customers/index.blade.php` (added — was missed in original roadmap)

### Group 9: Employee Management
- [x] `seller/employees/index.blade.php`
- [x] `seller/employees/create.blade.php`
- [x] `seller/employees/edit.blade.php`
- [x] `seller/employees/profile.blade.php`
- [x] `seller/employees/sales_report.blade.php`

### Group 10: Coupons & Marketing
- [x] `seller/coupons/index.blade.php`
- [x] `seller/coupons/create.blade.php`
- [x] `seller/coupons/edit.blade.php`
- [x] `seller/coupons/analytics.blade.php`
- [x] `seller/coupons/_form.blade.php`
- [x] `seller/flash-sales/index.blade.php`
- [x] `seller/flash-sales/details.blade.php`
- [x] `seller/bundles/index.blade.php`
- [x] `seller/bundles/create.blade.php`
- [x] `seller/bundles/edit.blade.php`
- [x] `seller/bundles/show.blade.php`

### Group 11: Barcodes & Bulk Upload
- [x] `seller/barcodes/index.blade.php`
- [x] `seller/barcodes/print.blade.php` (print-specific CSS in `<style>` blocks)
- [x] `seller/barcodes/print_new.blade.php` (print-specific CSS in `<style>` blocks)
- [x] `seller/bulk-upload/index.blade.php`
- [x] `seller/bulk-upload/preview.blade.php`
- [x] `seller/bulk-upload/show.blade.php`

### Group 12: Expenses, Payouts & Payment Listener
- [x] `seller/expenses/index.blade.php`
- [x] `seller/payouts/index.blade.php`
- [x] `seller/payouts/create.blade.php`
- [x] `seller/payouts/methods.blade.php`
- [x] `seller/payouts/show.blade.php`
- [x] `seller/payouts/_method_form.blade.php`
- [x] `seller/payment-listener/index.blade.php`
- [x] `seller/payment-listener/payments.blade.php`

### Group 13: Shipping, Returns, Reviews & Support
- [x] `seller/shipping/shipments.blade.php`
- [x] `seller/shipping/shipment_create.blade.php`
- [x] `seller/shipping/shipment_show.blade.php`
- [x] `seller/shipping/zones.blade.php`
- [x] `seller/shipping/_zone_form.blade.php`
- [x] `seller/returns/index.blade.php`
- [x] `seller/returns/show.blade.php`
- [x] `seller/reviews/index.blade.php`
- [x] `seller/support/index.blade.php`
- [x] `seller/support/create.blade.php`
- [x] `seller/support/show.blade.php`

### Excluded (old/backup files not in active use)
- `seller/products/create-old.blade.php`
- `seller/products/index_old.blade.php`

---

## Phase 4 — Cleanup & Final Verification

- [ ] Verify all 81 templates have zero remaining Bootstrap visual classes
- [ ] Verify every `data-bs-*` component functions correctly
- [ ] Remove `theme.css` and `custom.css` from layout templates
- [ ] Retain `bootstrap.bundle.min.js`
- [ ] Conduct full responsive visual & functional QA pass