# Shop Management Audit

## Overview
The shop functionality is embedded directly into the `Seller` model/table. There is **no dedicated `shops` table** — the `sellers` table serves double duty as both the seller/vendor record and the shop record. The `Shop` model (`app/Domain/Vendor/Models/Shop.php`) is a bare stub with no table mapping, no relationships, and no usage anywhere.

---

## 1. Database Schema Audit

### `sellers` table (serves as shops table)
| Column | Type | Status | Notes |
|--------|------|--------|-------|
| `business_name` | string(255), nullable | ✅ | Shop name |
| `business_logo` | string, nullable | ✅ | Shop logo image path |
| `business_email` | string, nullable, unique | ✅ | Shop contact email |
| `business_address` | text, nullable | ✅ | Shop physical address |
| `business_description` | — | ❌ **MISSING** | Referenced in `frontend.shops.details` view line 205 but no column exists |
| `shop_image` | string, nullable | ✅ | Shop front/storefront image |
| `shop_type` | string(20), default 'individual' | ⚠️ Column exists but **no UX** — never displayed or editable in any form |
| `cover_image` | string, nullable | ✅ | Shop cover/banner photo |
| `trade_license_no` | string, nullable | ✅ | Business registration |
| `trade_license_image` | string, nullable | ✅ | License document image |
| `commission_type` | string, nullable | ⚠️ Admin-only, not seller-facing |
| `commission_amount` | decimal(6,1), nullable | ⚠️ Admin-only, not seller-facing |
| `shipping_cost` | decimal(6,2), nullable | ✅ | Editable in settings |
| `division_id` | bigint, nullable | ✅ | FK to divisions |
| `district_id` | bigint, nullable | ✅ | FK to districts |
| `status` | tinyint, default 0 | ✅ | 0=pending, 1=active, 2=blocked, 4=deleted |
| `is_best_seller` | boolean, default 0 | ✅ | Featured/verified flag |
| `rating` | decimal(3,2), default 0 | ✅ | Avg rating |
| `rating_count` | unsigned int, default 0 | ✅ | Rating count |
| `total_followers` | bigint, default 0 | ✅ | Denormalized count |
| `total_sold` | bigint, default 0 | ✅ | Denormalized count |
| `total_items` | bigint, default 0 | ✅ | Product count |
| `balance` | decimal(10,2), default 0 | ✅ | Wallet balance |
| `code` | string(10) | ✅ | Seller code (e.g. "ABC123") |
| `sku_counter` | integer, default 0 | ✅ | Auto-increment for product SKUs |

### Related tables
| Table | Purpose | Status |
|-------|---------|--------|
| `seller_banner_images` | Additional banner images (gallery) | ✅ |

---

## 2. Feature Audit

### ✅ Shop Creation
- Via frontend multi-step signup (`AuthController@sellerSignup`): personal → business → documents
- Via admin create (`Admin\SellerController@create/store`): 3-step form with plan selection
- `RegisterVendorAction` and `VendorService@register` handle creation logic
- Uses `RegisterVendorRequest` validation

### ⚠️ Shop Profile (needs improvements)
- `SellerController@profile` handles GET/POST for profile editing
- Split into 4 sections: personal, business, documents, password
- **Missing:** No `business_description` field in the form
- **Missing:** No `shop_type` field in the form

### ✅ Shop Logo
- `business_logo` column, editable via profile (business section) and settings page
- Accessor `getBusinessAvatarAttribute()` provides default fallback

### ✅ Shop Banner
- `cover_image` column on `sellers` table (single cover photo)
- `seller_banner_images` table for additional banner images
- Settings page handles both via `SettingController`

### ❌ Shop Description
- Reference `$seller->business_description` exists in `frontend.shops.details` view (About Shop tab)
- But **no `business_description` column exists** in the database
- No way for seller to enter/edit a shop description anywhere in the UI

### ✅ Address Management
- `business_address`, `division_id`, `district_id` (with cascading district dropdown)
- Division/district set during signup and editable in profile → business section

### ❓ Business Information
- `business_name`, `business_email`, `trade_license_no` all present and editable
- `commission_type` / `commission_amount` are **admin-only**, not shown to sellers
- `shop_type` column exists but is **not used anywhere** (no form field, no display)

### ✅ Shop Status
- `status` column with 4 states (pending/active/blocked/deleted)
- Admin controller has `updateStatus()`, `toggleBlock()`, approve flow
- Scopes: `active()`, `pending()`

---

## 3. Code Quality Issues

### Critical
1. **`Shop.php` model is a dead stub**: `app/Domain/Vendor/Models/Shop.php` — 13 lines, no `$table`, no relationships, unused. Confusing for new developers.
2. **`profileCompleted()` accessor has typos**: references `trade_licenso_no` and `trade_licenso_image` (should be `trade_license_no` / `trade_license_image`). Returns wrong results silently.
3. **`profileCompleted()` not in `$casts` array**: The `Attribute` cast won't work if the accessor isn't defined in `$casts`.
4. **No `business_description` column**: Frontend references it but it doesn't exist in any migration — always null.

### Medium
5. **Settings vs Profile overlap**: `setting/index.blade.php` and `profile-information.blade.php` both have `business_name`, `business_email`, `business_address`, `business_logo`. Seller confusion about where to edit what.
6. **Missing `shop_type` UX**: Column was added but no form field, no display on shop page or admin profile.
7. **SettingController doesn't handle `shop_image`**: Only profile form allows shop image upload, settings page doesn't.
8. **Commission type/amount not seller-facing**: Sellers can't see their commission rate anywhere.

### Low
9. **No Shop policies**: No `ShopPolicy` or `SellerPolicy` for authorization.
10. **`cover_image` not in `profileCompleted()`**: Cover photo is a key shop branding element but not required for "profile complete."
11. **Admin profile view uses `$seller->address`**: Should likely be `$seller->business_address` (line 48 of admin profile view).

---

## 4. Query & Performance
- No N+1 issues specific to shop management
- Division/district queries are reasonable (caching could help)
- The dashboard report already addressed query optimization for seller stats

---

## 5. Recommendations

### Must fix
1. Add `business_description` column migration
2. Fix `profileCompleted()` typos and register in `$casts`
3. Add `business_description` and `shop_type` fields to profile form + validation
4. Display `shop_type` and `business_description` on frontend shop details page

### Should fix
5. Either remove the dead `Shop.php` model or give it a real `shops` table + relationships
6. Add `shop_image` to settings controller/validation
7. Display `commission_type`/`commission_amount` to sellers (read-only)
8. Add `cover_image` to `profileCompleted()` check
9. Fix `$seller->address` → `$seller->business_address` in admin profile

### Nice to have
10. Create a proper `SellerShopController` to separate shop management from seller profile
11. Add shop-specific policies
12. Add shop URL slug for SEO (using existing `username`)
