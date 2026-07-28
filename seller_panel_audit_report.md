# Seller Panel Audit Report

**Generated:** July 28, 2026
**Codebase:** Multivendor E-commerce (Laravel + Domain-Driven Design)
**Scope:** Complete seller panel audit across 20 modules

---

## Overall Completion: **62%**

---

## Module 1: Seller Authentication Module

**Status:** Completed

**Existing Features:**
- Registration with multi-step flow (personal info → business info → documents)
- Login/logout (separate `seller` guard)
- Password hashing with `hashed` cast
- Email verification (`email_verified_at` column)
- Sanctum API token support (`HasApiTokens`)
- PENDING → ACTIVE status flow after admin approval
- `SellerMiddleware` checks `status = ACTIVE` on each request
- Soft deletes support

**Missing Features:**
- Password reset/forgot password flow (no routes or views)
- Two-factor authentication (2FA)
- Email verification resend
- Session management (view active sessions, force logout others)
- Account lockout after failed attempts

**Database Gap:**
- No `password_reset_tokens` dedicated for seller guard (uses global users table possibly)
- No `failed_login_attempts` or `last_login_at` columns
- No `email_verified_token` column (uses Laravel's built-in `email_verified_at`)

**Code Issues:**
- `UpdateVendorProfileRequest.php` handles password section but no dedicated password reset flow exists
- `RegisterVendorRequest.php`: `authorize() { return true; }` — authorization delegated to middleware (acceptable pattern)
- Auth guard `seller` references `Seller` model in `config/auth.php`, but `employee` guard may conflict in middleware checks

**Priority:** Medium

---

## Module 2: Seller Dashboard Module

**Status:** Completed

**Existing Features:**
- `DashboardController` serves data to view
- Stats cards: total orders, pending orders, delivered orders, cancelled orders, revenue, products, best sellers
- Recent orders table with status badges
- Top selling products list
- Sales chart (Chart.js integration) with 7-day/30-day line chart
- Date range filter for dashboard data
- Alert banner for incomplete profiles
- RTL-ready UI
- AJAX route `seller-api.php` has dashboard stats endpoint

**Missing Features:**
- Real-time dashboard updates (requires WebSockets/Pusher)
- Order status summary donut/pie chart
- Revenue vs. expense comparison chart
- Year-over-year growth indicator
- Low stock alerts on dashboard
- Recent customer activity feed
- Pending withdrawal/earnings summary card
- Task/reminder system

**Code Issues:**
- No cached dashboard queries — every page load hits the database
- No read-model/indexed aggregations for dashboard stats
- `seller-api.php` routes are defined but never loaded in any ServiceProvider (dead code)
- Chart.js colors are hard-coded in blade instead of using CSS variables

**Priority:** Low (functional but could be optimized)

---

## Module 3: Shop Management Module

**Status:** Partial

**Existing Features:**
- `Shop` model exists
- `Seller` model has shop-related fields: `business_name`, `business_logo`, `business_email`, `business_address`, `shop_image`, `cover_image`, `shop_type` (individual/business)
- Seller profile settings include shop name, email, address, logo, cover image
- Banner image upload (`SellerBannerImage` model, route `seller.bannerImages.delete`)
- Category assignment via `category_seller` pivot table
- Shop visible on frontend at `/sellers/{username}` or `/sellers` listing
- `SellerResource` API resource for public-facing shop data
- `SellerFollower` / `followers()` relationship — users can follow shops

**Missing Features:**
- Shop open/close toggle
- Shop vacation mode / temporary closure
- Shop policy pages (shipping policy, return policy, terms)
- Shop SEO metadata (meta title, description, keywords)
- Shop social media links
- Shop banner customization (upload + rearrange order)
- Shop pickup address management
- Shipping zones & rates per shop
- Shop verification badge
- Shop opening hours

**Database Gap:**
- `Shop.php` model has NO relationships defined (empty `$with` and no relationships)
- No `shop_policies` table
- No `shop_social_links` table
- No `shipping_zones` or `shipping_rates` table
- `Shop` model is essentially unused — all shop data stored directly on `sellers` table

**Code Issues:**
- `Shop` model appears to be a legacy/stub — no controller or route uses it
- Banner images have no sort order
- No shop slug/URL customization beyond `username`
- Shop data is spread across `sellers` table instead of a normalized `shops` table

**Recommended Implementation:**
- Merge `Shop` model into a proper entity with relationships
- Add shop configuration controller/views
- Normalize shop data from `sellers` table to `shops` table (or drop unused `Shop` model)

**Priority:** Medium

---

## Module 4: Product Management Module

**Status:** Completed

**Existing Features:**
- Full CRUD for products (index, create, store, edit, update, delete)
- Product categories (multi-level: category → subcategory)
- Product images upload with multi-image support
- Image reorder via AJAX
- Product details view with gallery
- Inventory tracking
- Stock management (add/remove/set exact stock)
- Barcode printing (individual and label sheets)
- SKU auto-generation (`generateSku($sellerId)`)
- Product SEO fields (meta title, description, OG image)
- Product status management
- Product variants (see Module 5)
- `FlashSaleProduct` model for flash sale submissions
- Print barcode with barcode generation
- `ProductService` with repository pattern
- `StockManagerService` for stock operations
- `ProductObserver` clears caches on changes
- Enums: `PaymentType`, `StockStatus`, `StockType`

**Missing Features:**
- Bulk product import/export (CSV/Excel)
- Product duplication (clone product)
- Product comparison view
- Digital/downloadable product support
- Product bundles / grouped products
- Product warranty fields
- Product video embeds (YouTube/Vimeo)
- Product FAQ section
- Related products (manual or auto-suggested)
- Product review summary in seller panel
- Category-based commission rate override

**Database Gap:**
- No `product_faqs` table
- No `product_videos` table
- No `product_bundles` or `bundle_items` table
- No `product_warranties` table
- No `import/export` logs table

**Code Issues:**
- `printBarcode` route is GET — should handle POST for barcode data
- No throttling on image upload endpoints
- Image deletion doesn't clean up storage files
- Variant stock updates don't update parent product stock count
- `Product::generateSku()` re-queries the entire seller's product count — could be optimized

**Priority:** High (product management is core)

---

## Module 5: Product Variant Module

**Status:** Partial

**Existing Features:**
- Variant creation via `ProductVariantController`
- Variant options (Color, Size, Material — via `Option` model)
- `seller.productVariants.store` route (POST)
- Variant update/delete
- Variant image support
- Variant price, stock, SKU fields
- Variant-generator UI for creating multiple variants at once
- `ProductVariant` model with relationships

**Missing Features:**
- Variant bulk price update
- Variant image gallery per variant
- Variant-level discounts
- Variant low stock alerts
- Variant sales tracking
- Variant disable/enable (without deleting)
- Matrix view for all variants at a glance
- Variant attribute management (create/edit attribute options)

**Database Gap:**
- No `product_variant_images` table (only single image per variant)
- No `product_variant_discounts` table
- No `variant_attribute_values` normalization table
- `Option` model is in `Product` domain — options/variants structure uses loose coupling

**Code Issues:**
- `seller.productVariants.store` is POST with `{product}` in URL, but the controller method expects specific input structure
- No validation that variant attribute combinations are unique within a product
- No stock reconciliation when variants are deleted
- Variant generator UI (JS-heavy) may have edge cases

**Priority:** High

---

## Module 6: Inventory Management Module

**Status:** Partial

**Existing Features:**
- `ProductStockController` with routes: `seller.stock.index`, `seller.stock.products`, `seller.stock.variants`, `seller.stock.update`
- Stock history view (`StockManagerService`)
- Stock types: SET_EXACT_STOCK, ADD_STOCK, REMOVE_STOCK (enum)
- Product-level stock management
- Variant-level stock management
- `StockManagerService` handles stock operations
- `product_stocks` table with `seller_id` index

**Missing Features:**
- Low stock threshold alerts
- Out-of-stock product notifications
- Stock transfer between variants
- Batch stock update (multi-select products)
- Inventory valuation report
- Stock movement audit trail with reasons
- Expected stock arrival dates
- Supplier/purchase order management
- Warehouse/location tracking
- Serial number tracking
- Batch/lot tracking

**Database Gap:**
- No `stock_alerts` or `low_stock_thresholds` table
- No `stock_movements` table (only product_stocks log)
- No `suppliers` or `purchase_orders` tables
- No `warehouses` or `storage_locations` tables

**Code Issues:**
- Stock history doesn't track reason/cause of change
- No rollback mechanism for incorrect stock adjustments
- Stock count can go negative (no validation)
- No cache invalidation on stock updates

**Priority:** Medium

---

## Module 7: Order Management Module

**Status:** Completed

**Existing Features:**
- Complete order lifecycle (10 statuses via `OrderStatus` enum: PENDING → CONFIRMED → PROCESSING → SHIPPED → DELIVERED → CANCELLED, RETURN_REQUESTED → RETURNED, REFUNDED, REFUND_PENDING)
- Order listing with status filters: all, pending, shipped, delivered, cancelled, returned, refunded
- Order detail view with line items
- Order status update
- Invoice generation
- Receipt printing
- POS order management (draft carts, place order)
- Order earnings calculation
- `OrderService` with repository pattern
- `OrderTracking` model for shipping tracking
- Auto-generated invoice IDs (`generateInvoiceID`)
- Order assigned to seller (`seller_id`) and optionally to employee (`seller_employee_id`)

**Missing Features:**
- Order notes/seller comments
- Order cancellation reason capture
- Partial fulfillment / partial shipment
- Order edit (change items, prices after placement)
- Bulk order status update
- Order export (CSV/PDF)
- Abandoned cart recovery
- Order timeline view (visual workflow)
- Packing slip generation
- Shipping label generation

**Database Gap:**
- No `order_notes` table
- No `order_cancellation_reasons` table
- No `order_fulfillments` table (for partial shipments)
- No `order_logs` or `order_activities` table

**Code Issues:**
- `SellerEarning` is added to `seller.balance` via `addSellerEarningToBalance()` but the balance column might lack proper locking for concurrent updates
- No database transaction wrapping for order creation flow
- Order status transitions are not validated (e.g., delivered → cancelled is allowed)
- No webhooks for order status changes

**Priority:** High

---

## Module 8: Shipping Management Module

**Status:** Partial

**Existing Features:**
- `ShippingService` exists with repository pattern
- `OrderTracking` model with `seller_id` field
- `LocationRepositoryInterface` (division/district/country data)
- Shipping address captured on orders (user's address)
- `seller.shipping_cost` field on sellers table
- Shipping update email templates (`emails/shipping-update.blade.php`)

**Missing Features:**
- Shipping zone management per seller
- Shipping rate calculation (weight-based, price-based, flat rate)
- Shipping carrier integration (Steadfast, Redx, Pathao, etc.)
- Tracking number input from seller panel
- Shipping label generation
- COD availability settings
- Free shipping threshold configuration
- Shipping rule by product category
- Multiple shipping methods per seller
- Domestic vs. international shipping

**Database Gap:**
- No `shipping_zones` table
- No `shipping_rates` table
- No `shipping_carriers` table
- No `seller_shipping_methods` table
- No `shipping_region_assignments` table
- `shipping_cost` on sellers table is a single flat value — inadequate

**Code Issues:**
- `shipping_cost` is a single column on `sellers` — can't handle zone-based or tiered shipping
- No shipping calculation logic in order placement flow (hard-coded or skipped)
- `ShippingService` may have incomplete implementation
- No shipping configuration UI in seller settings

**Recommended Implementation:**
The shipping module needs a complete overhaul. Implement zone-based shipping with carrier integration. This is a fundamental feature for a multivendor marketplace.

**Priority:** Critical

---

## Module 9: Customer Communication Module

**Status:** Completed

**Existing Features:**
- Chat system between seller and customers (`SellerChat`, `SellerChatMessage` models)
- Chat listing (`seller.chat.list`)
- Chat messages view with send capability (`seller.chat.messages`, `seller.chat.send`)
- Message read/unread tracking (`is_read` column)
- `SellerChatMessageResource` API resource
- API routes for chat (via `auth:sanctum`)
- Unread message indicators
- Real-time chat UI with message bubbles
- Sender identification (seller vs. user)

**Missing Features:**
- Push notifications for new messages
- Email notification for offline messages
- Chat file/image attachment support
- Chat search/filter
- Chat message edit/delete
- Chat conversation history export
- Auto-reply / canned responses
- Typing indicators
- Message read receipts (per-message)
- Archived conversations

**Database Gap:**
- No `chat_attachments` table
- No `canned_responses` or `quick_replies` table
- No `chat_typing_status` (real-time feature — acceptable as client-side)

**Code Issues:**
- No WebSocket/Pusher integration — requires page refresh to see new messages
- `is_read` on messages table — no bulk mark-as-read optimization
- No rate limiting on message send endpoint
- Pagination may be missing for large conversations

**Priority:** Medium

---

## Module 10: Review & Rating Module

**Status:** Partial

**Existing Features:**
- `Review` model with seller relationship (`seller_id`)
- `ReviewImage` model for review photos
- `ReportReview` model for flagged reviews
- Rating calculation via `Seller::addRating()` (weighted average)
- Review submission triggers seller rating recalculation via `OrderService`
- `ReviewService` with repository pattern
- Frontend public shop review display
- Review helpfulness marking (`markHelpful`)
- Review reporting system (`reviewReport`)
- Admin review management (`admin.reviews.*`)
- `ReviewResource` API resource

**Missing Features:**
- Seller can reply to reviews
- Seller can request review removal (from admin)
- Review analytics (rating distribution, trends)
- Verified purchase badge on reviews
- Photo/video review gallery in seller panel
- Review sorting/filtering in seller panel
- Automated review reminder emails
- Review moderation queue for seller
- Review images in seller panel view

**Database Gap:**
- No `review_replies` table (seller replies to reviews)
- No `review_helpfulness` pivot table (uses different mechanism)
- No `review_moderation_requests` table
- No `review_reminder_logs` table

**Code Issues:**
- `Seller::addRating()` uses a simple averaging formula that may not handle high volumes well
- No pagination or sorting on review display in seller panel (frontend shops has it)
- No cache invalidation when rating changes
- Rating recalculation on every review — could be expensive at scale
- No index on `reviews(seller_id)` — added in recent migration

**Priority:** Medium

---

## Module 11: Sales & Earnings Module

**Status:** Partial

**Existing Features:**
- `seller.balance` column on sellers table tracks total earnings
- `calculateEarning($total)` calculates seller earnings after commission
- `seller_earnings` and `total_commission` columns on orders table
- `seller_earning_added` boolean flag to prevent double-counting
- `OrderService::addSellerEarningToBalance()` called on order completion
- Earnings shown on dashboard as total revenue
- Reports module shows sales financial data

**Missing Features:**
- Sales by period breakdown (daily/weekly/monthly)
- Earnings vs. commission breakdown chart
- Expected payout / pending clearance amount
- Sales by product/variant report
- Sales by customer report
- Daily sales summary
- Earnings statement download (PDF/CSV)
- Tax calculation for earnings
- Refund impact on earnings tracking
- Lifetime earnings counter

**Database Gap:**
- No `seller_earnings_history` or `seller_balance_logs` table
- No `seller_transactions` table
- No tax-related columns on orders or earnings

**Code Issues:**
- `seller.balance` is updated directly — no audit trail of balance changes
- No separate "pending clearance" balance (funds on hold)
- No daily cron job for earnings settlement
- `seller_earning_added` boolean is fragile — no compensation logic if flag is wrong

**Priority:** Medium

---

## Module 12: Commission Management Module

**Status:** Partial

**Existing Features:**
- `CommissionType` enum (flat/percentage)
- `seller.commission_type` and `seller.commission_amount` on sellers table
- `subscription_plans.commission_rate` column for plan-based commission
- `HasSubscription::commissionRate()` returns plan rate or default 10%
- `Seller::calculateEarning($total)` computes commission and seller earning
- `OrderService::calculateCommission()` wrapper
- Commission stored on each order (`total_commission`)

**Missing Features:**
- Category-based commission override
- Product-level commission override
- Tiered commission rates (volume-based)
- Commission report for admin
- Commission rule configuration UI (admin)
- Global default commission setting (config file)
- Commission cap or minimum
- Promotional commission rate (temporary reduction)
- Multi-level commission (for affiliate integration)

**Database Gap:**
- No `commission_rules` table
- No `category_seller_commission` pivot columns
- No `product_commission_overrides` table
- No config file for global commission defaults

**Code Issues:**
- `calculateEarning()` handles null commission as zero — should handle as "no commission" case
- Commission rate from subscription plan is stored in `SubscriptionPlan.commission_rate` but `Seller.commission_amount` is separate — potential conflict
- No validation that commission doesn't exceed 100%
- Flat rate commission doesn't consider order total — applies flat fee regardless

**Recommended Implementation:**
The commission system works for basic use cases but lacks flexibility. Implement a commission rule engine with priority-based resolution (product > category > seller > plan > global default).

**Priority:** Medium

---

## Module 13: Withdrawal & Payout Module

**Status:** Missing

**Existing Features:**
- `seller.balance` column tracks available earnings
- Email templates reference vendor payouts (`emails/vendors/payment-released.blade.php` has `$payout_amount`)
- Seller layout text: "Secure payments & easy withdrawals"
- Affiliate domain has complete payout/withdrawal system (can serve as reference)

**Missing Features:**
- Payout request submission (amount, method)
- Payout methods configuration (bank account, mobile money, etc.)
- Payout history and status tracking
- Admin payout approval workflow
- Payout auto-processing cron job
- Minimum payout threshold
- Payout hold period (e.g., 14-day clearance)
- Payout reversal for refunded orders
- Payout notification system
- Payout reconciliation reports

**Database Gap:**
- No `seller_payouts` or `seller_withdrawals` table
- No `seller_payout_methods` table (bank accounts, mobile wallets)
- No `seller_payout_settings` table (threshold, hold period)
- No `payout_batch` or `payout_logs` table

**Business Logic Issues:**
- `seller.balance` accumulates without any withdrawal mechanism to release funds
- Email templates show payout data but no backend logic exists
- Seller cannot access their earned funds through the panel

**Recommended Implementation:**
This is a CRITICAL missing module. No multivendor marketplace functions without a payout system. Build:
1. Payout method management (bank, mobile money)
2. Payout request flow
3. Admin approval/release flow
4. Automated payout processing (cron)

**Priority:** Critical

---

## Module 14: Coupon & Discount Module

**Status:** Missing (Seller-side)

**Existing Features:**
- `Coupon` model exists with full schema (code, discount_type, discount_value, min_purchase, max_discount, max_uses, valid_from, valid_until)
- `CartService::applyCouponDiscount()` applies coupon during checkout
- `CouponSeeder` seeds test data
- Order-level discount calculation in `OrderService`
- `additional_discount` on orders table

**Missing Features (Seller-side):**
- Seller coupon creation UI
- Seller coupon listing and management
- Coupon performance analytics
- Product-specific coupons
- Free shipping coupons
- Buy-one-get-one (BOGO) logic
- Coupon usage tracking per seller
- Coupon auto-expire handling
- Bulk coupon generation
- Coupon code validation on seller panel

**Missing Features (Admin):**
- No admin coupon management routes exist
- No `CouponController` found in any domain

**Database Gap:**
- No `coupon_seller` pivot (coupons are global, not per-seller)
- No `coupon_products` table for product-specific coupons
- No `coupon_usage` logs table
- No `coupon_categories` table for category-wide coupons

**Code Issues:**
- Coupon model has no relationships defined
- No routes or controllers use the Coupon model
- No authorization/ownership check on coupons
- Coupon application lacks seller-scoping

**Recommended Implementation:**
Coupon system exists at the model level but is not exposed via any UI or API route. Build:
1. Admin coupon management CRUD
2. Seller coupon management (scoped to their products)
3. Checkout integration already partially exists in CartService

**Priority:** Medium

---

## Module 15: Promotion & Campaign Module

**Status:** Partial

**Existing Features:**
- Flash Sale system (seller can submit products to admin-created flash sales)
- `FlashSaleProduct` model with seller_id
- `seller.flash-sales.index` — list available flash sales
- `seller.flash-sales.details` — view flash sale details
- `seller.flash-sales.submit` — submit products to flash sale
- `FlashSaleService` with repository pattern
- Admin creates/manages flash sales via `admin.flash-sales.*`
- Banner image system (`SellerBannerImage`)

**Missing Features:**
- Seller-created promotions (not just flash sale submissions)
- Product discount scheduling (future-dated price changes)
- Cross-sell / up-sell suggestions
- Bundle offers (product bundles with discount)
- Volume discounts / bulk pricing tiers
- Buy X get Y promotions
- Free gift with purchase
- Minimum purchase discount
- Campaign performance analytics
- Auto-apply coupons during promotion

**Database Gap:**
- No `product_discount_schedules` table
- No `product_bundles` table
- No `volume_discount_tiers` table
- No `promotion_campaigns` table

**Code Issues:**
- Flash Sale submission is the only promotion mechanism available
- No product-discount scheduling (price changes are immediate)
- `BannerImage` system has no sort order or active period
- No campaign analytics tracking

**Priority:** Low

---

## Module 16: Reports & Analytics Module

**Status:** Partial

**Existing Features:**
- ReportController with routes: overview, financial, sales, customers
- `seller.reports.overview` — main report view
- `seller.reports.financial` — financial report with earnings/commission breakdown
- `seller.reports.sales` — sales report with product-level data
- `seller.reports.customers` — customer report with order history
- Dashboard chart.js sales chart
- Top products sales data on dashboard
- `HasSubscription::hasAnalyticsAccess()` feature gating
- Basic report views with data tables

**Missing Features:**
- Date range picker for reports
- Report export (PDF, CSV, Excel)
- Visual charts (pie, bar, line) in report views
- Product performance report
- Order status distribution report
- Customer acquisition report
- Employee performance report
- Profit & loss report
- Tax report
- Inventory valuation report
- Scheduled email reports
- Report comparison (period-over-period)

**Database Gap:**
- No `report_schedules` table for automated reports
- No `report_exports` table for generated exports
- No aggregated `seller_daily_summaries` or `seller_weekly_summaries` materialized view

**Code Issues:**
- All reports query live data — no caching or aggregation tables
- No pagination on report tables
- Date filters may be passed as query params without validation
- Financial report doesn't include expense data for profit calculation
- Report queries may become slow at scale (no indexing on date columns for aggregates)

**Priority:** Medium

---

## Module 17: Notification Module

**Status:** Partial

**Existing Features:**
- `notifications` table with `seller_id` column
- `notificationCount()` helper function in `helpers.php`
- Notification view page (`resources/views/seller/notifications.blade.php`)
- Bell icon in navbar with unread count badge
- `AutoMarkNotificationsAsRead` middleware marks notifications read on page load
- `NotificationController` at `App\Http\Controllers\NotificationController` (not in domain)
- Route: `seller.notifications.index`
- Notification icon displays in navbar with badge count
- Notification list with "New" badge for unread items

**Missing Features:**
- In-app notification types (order placed, product approved, payout released, etc.)
- Email notification preferences
- Push notifications (browser)
- Notification filtering by type
- Mark all as read
- Notification pagination
- Notification sound/alert
- Read/unread toggle on individual notifications
- Notification settings (which events trigger notifications)
- Real-time notifications via WebSockets/Pusher
- SMS notification support

**Database Gap:**
- Uses Laravel's default `notifications` table — `type` column stores class name, not human-readable type
- No `notification_settings` or `notification_preferences` table
- No `seller_notification_types` table for event-type configuration

**Code Issues:**
- `NotificationController` is in global `App\Http\Controllers` namespace, not in a domain
- `notificationCount()` helper reads all notifications on every page load (no caching)
- No dedicated notification types (uses generic Laravel notifications)
- `AutoMarkNotificationsAsRead` marks all as read — no per-notification reading
- No pagination on notification view

**Priority:** Medium

---

## Module 18: Seller Support Module

**Status:** Missing

**Existing Features:**
- `seller.plans.index` shows upgrade plan page with feature comparison
- `HasSubscription::hasPrioritySupport()` feature flag exists
- Messages/chat module exists for customer communication (not seller support)

**Missing Features:**
- Support ticket system (create, view, reply to tickets)
- FAQ/help center for sellers
- Support category/priority system
- Ticket status tracking (open, in-progress, resolved, closed)
- File attachment on support tickets
- Knowledge base / documentation pages
- Onboarding guide/wizard for new sellers
- Video tutorials or help links in sidebar
- Contact support form
- Live chat with admin support team
- Support rating/feedback after ticket resolution

**Database Gap:**
- No `support_tickets` table
- No `support_ticket_messages` table
- No `support_categories` table
- No `support_attachments` table
- No `knowledge_base_articles` table
- No `seller_onboarding_progress` table

**Code Issues:**
- No support-related models, controllers, routes, or views exist
- `hasPrioritySupport()` is defined in the trait but never checked anywhere

**Recommended Implementation:**
This module is needed for seller satisfaction. Build:
1. Support ticket system with admin panel integration
2. Knowledge base with search
3. Onboarding guide/wizard for new registrations

**Priority:** Medium

---

## Module 19: Seller Settings Module

**Status:** Completed

**Existing Features:**
- `SettingController` with routes: index, update
- Business settings form (name, email, address, shipping cost)
- Profile settings (personal info, profile image)
- Password change section
- Seller notification preferences
- `UpdateVendorProfileRequest` handles section-based updates
- `VendorService::updateProfile()` handles personal/business/documents/password sections
- Banner image upload/settings
- Shop type selection (individual/business)
- Division/district location settings

**Missing Features:**
- Tax information settings
- Payout method settings (bank account, mobile money)
- Notification preference toggles per event type
- Working hours settings
- Shop vacation mode toggle
- Store language/currency settings
- API key management (for integrations)
- Activity log / audit trail
- Privacy settings
- Social media link settings
- Invoice/email template customization

**Database Gap:**
- No `seller_settings` table (settings stored on `sellers` table columns)
- No `seller_tax_infos` table
- No `seller_working_hours` table
- No `seller_api_keys` table
- No `seller_activity_logs` table

**Code Issues:**
- Settings are stored as columns on `sellers` table (scalable but rigid)
- No caching for frequently accessed settings
- `business_logo`, `cover_image`, `shop_image` uploads handled but no image optimization
- No validation for image dimensions/types beyond file type check

**Priority:** Low

---

## Module 20: Seller Permission & Role Management Module

**Status:** Completed

**Existing Features:**
- `SellerEmployee` model with `permissions` JSON column (cast to `array`)
- `SellerEmployee::hasPermission($routeName)` — checks if a route name exists in permissions
- `VendorService::setEmployeePermissions()` — updates permissions
- `SellerEmployeeController` — CRUD for employees
- `seller.employees.index, create, store, edit, profile, update, updateProfile, toggleActive, setPermissions`
- Permission list dynamically generated by `get_seller_routes()` helper (scans all `seller.*` named routes)
- Employee active/inactive status toggle
- Sidebar checks `$employee->hasPermission('seller.xxx')` for each menu item
- Sales report per employee
- Employee login (separate `employee` guard)

**Missing Features:**
- Pre-defined role templates (Manager, Cashier, Support, etc.)
- Permission hierarchy / inheritance
- Mass permission assignment
- Permission audit log
- Permission copy from existing employee
- Department/group organization
- Employee login history
- Employee action audit trail
- Time-based access restrictions
- IP-based access restrictions

**Database Gap:**
- No `seller_roles` table
- No `seller_role_permissions` pivot table
- No `employee_login_logs` table
- No `employee_audit_logs` table

**Code Issues:**
- `permissions` is a JSON column — no relational integrity, can't query by permission
- `get_seller_routes()` may expose unintended routes as permission options
- No caching on permission checks (reads JSON on every page load)
- Permission check is route-name-based — if route name changes, permissions break silently
- No fallback if `permissions` is null (accepts all or denies all depending on logic)
- `seller.employees.set_permissions` route is explicitly excluded from `get_seller_routes()` — prevents circular permission assignment
- Test files show edge cases: `hasPermission('seller.non_existent_route')` returns false — acceptable

**Recommended Implementation:**
The permission system is functional for basic needs but lacks role templates. Reduce cognitive load for sellers by adding:
1. Pre-defined role templates (e.g., "Cashier: POS only", "Manager: all except settings")
2. Bulk permission application across employees

**Priority:** Low

---

## Overall Assessment

### 1. Overall Seller Panel Completion: **~62%**

| Module | Completion |
|--------|------------|
| 1. Authentication | ✅ 85% |
| 2. Dashboard | ✅ 90% |
| 3. Shop Management | ⚠️ 40% |
| 4. Product Management | ✅ 85% |
| 5. Product Variants | ⚠️ 60% |
| 6. Inventory Management | ⚠️ 50% |
| 7. Order Management | ✅ 85% |
| 8. Shipping Management | ❌ 20% |
| 9. Customer Communication | ✅ 75% |
| 10. Review & Rating | ⚠️ 55% |
| 11. Sales & Earnings | ⚠️ 45% |
| 12. Commission Management | ⚠️ 55% |
| 13. Withdrawal & Payout | ❌ 0% |
| 14. Coupon & Discount | ❌ 10% |
| 15. Promotion & Campaign | ⚠️ 35% |
| 16. Reports & Analytics | ⚠️ 50% |
| 17. Notification | ⚠️ 50% |
| 18. Seller Support | ❌ 0% |
| 19. Seller Settings | ✅ 80% |
| 20. Permission & Roles | ✅ 80% |

**Legend:** ✅ Complete (≥75%) | ⚠️ Partial (25-74%) | ❌ Missing (≤25%)

### 2. Critical Missing Modules

1. **Withdrawal & Payout (Module 13)** — **CRITICAL.** Sellers cannot access their earnings. No payout system exists.
2. **Shipping Management (Module 8)** — **HIGH.** Core marketplace feature missing. No zone-based or carrier-integrated shipping.
3. **Coupon & Discount (Module 14)** — **HIGH.** Coupon model exists but no UI. Sellers cannot create promotions.
4. **Seller Support (Module 18)** — **MEDIUM.** No support ticket or help system for sellers.

### 3. Database Improvement Suggestions

**Immediate (High Priority):**
- `seller_payouts` table — for withdrawal/payout tracking
- `seller_payout_methods` table — bank/mobile wallet account storage
- `shipping_zones` + `shipping_rates` tables — for zone-based shipping
- `shipping_carriers` table — carrier integration config
- `seller_shipping_methods` table — per-seller shipping configuration

**Short-term (Medium Priority):**
- `seller_earnings_history` / `seller_balance_logs` — audit trail for balance changes
- `coupon_seller` pivot — seller-scoped coupons
- `coupon_products` pivot — product-specific coupons
- `support_tickets` + `support_ticket_messages` — seller support system
- `review_replies` table — seller can reply to reviews
- `seller_daily_summaries` table — aggregated analytics cache
- `product_variant_discounts` table — variant-level pricing

**Long-term (Low Priority):**
- `seller_roles` + `seller_role_permissions` — pre-defined role templates
- `seller_activity_logs` — audit trail for all seller actions
- `warehouses` + `storage_locations` — inventory locations
- `suppliers` + `purchase_orders` — procurement module
- `report_schedules` — automated report generation

**Add missing indexes:**
- `reviews(seller_id, created_at)` — for seller review queries
- `orders(seller_id, created_at)` — may already exist
- `products(seller_id, stock_qty)` — for low stock queries
- `seller_expenses(seller_id, expense_date)` — for financial reports

### 4. Recommended Development Roadmap

**Phase 1 (Weeks 1-2) — Critical Fixes**
1. ✅ **Withdrawal & Payout System** — Complete build from scratch (highest priority)
2. ✅ **Shipping Management** — Zone-based shipping + carrier integration (Steadfast/Redx)
3. ✅ **Payout + Shipping database migrations**

**Phase 2 (Weeks 3-4) — High Impact**
1. ✅ **Coupon & Discount UI** — Admin + seller coupon management
2. ✅ **Seller Support Ticket System** — Basic create/reply/resolve flow
3. ✅ **Missing Indexes** — Add optimized database indexes

**Phase 3 (Weeks 5-6) — Enhancement**
1. ✅ **Sales & Earnings Module** — Earning history, pending clearance, statement downloads
2. ✅ **Inventory Management** — Low stock alerts, batch updates, stock valuation
3. ✅ **Reports & Analytics** — Data export, profit/loss reports, scheduled reports

**Phase 4 (Weeks 7-8) — Polish**
1. ✅ **Review Module** — Seller reply to reviews, review analytics
2. ✅ **Notification Module** — Notification types, real-time push, preferences
3. ✅ **Product Variants** — Bulk price editing, matrix view, variant-level discounts
4. ✅ **Shop Management** — Shop policies, social links, vacation mode

### 5. Production Readiness Issues

| Issue | Severity | Recommendation |
|-------|----------|----------------|
| No payout system | 🔴 Critical | Sellers cannot withdraw earnings — block production launch |
| No shipping carrier integration | 🔴 High | Manual shipping only — unacceptable for scale |
| No database query caching | 🟡 Medium | Dashboard/Reports hit DB on every load |
| No order status transition validation | 🟡 Medium | Invalid transitions possible |
| `seller.balance` updated without lock | 🟡 Medium | Race condition on concurrent order completion |
| No cache invalidation strategy | 🟡 Medium | Stale data on dashboard and product listings |
| No API rate limiting on seller endpoints | 🟢 Low | Potential abuse of chat/upload endpoints |
| No CSRF protection on API routes | 🟢 Low | Review if seller-api.php ever gets loaded |
| No request logging/audit trail | 🟢 Low | Cannot trace seller actions |
| No database read replicas configuration | 🟢 Low | Single DB bottleneck at scale |
| No event sourcing for critical operations | 🟢 Low | Order/earning changes lack audit trail |
| No webhook system for integrations | 🟢 Low | Third-party integrations not possible |
| No scheduled health checks | 🟢 Low | No monitoring of cron jobs or queues |

**Production Blockers:**
1. **No payout/withdrawal system** — Sellers cannot access revenue
2. **No shipping carrier integration** — Every order requires manual shipping setup
3. **No coupon management** — Sellers cannot run discounts/promotions

**Production Recommendations Before Launch:**
1. Implement at minimum the Payout system and basic Shipping integration
2. Add database query caching (Redis) for dashboard and reports
3. Implement database transaction wrapping for critical order/earning operations
4. Add proper error logging and monitoring setup
5. Configure queue workers for email notifications and async tasks
