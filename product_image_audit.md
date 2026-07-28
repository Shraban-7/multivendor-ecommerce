# Product Image Module Audit

**Date:** 2026-07-29
**Score:** 4/10

---

## Existing Implementation

### Database: `product_images` table

```sql
id          BIGINT PK
product_id  BIGINT UNSIGNED, NULLABLE, NO FK constraint
image       VARCHAR, NULLABLE
created_at  TIMESTAMP
updated_at  TIMESTAMP
```

**Missing columns:** `type` (thumbnail/gallery/variant), `position` (ordering), `is_primary` (primary flag)

### Model: `ProductImage`

- Minimal model with only `$guarded` and `product()` BelongsTo
- No accessors (`imageUrl`, `thumbnailUrl`)
- No scopes (`scopePrimary`, `scopeOrdered`)
- No `$casts`

### Product Model Image Methods

- `images()` — HasMany (correct)
- `imageUrl()` — Attribute, returns `storage_url($this->thumbnail)` or default
- `toDetailsArray()` — Merges thumbnail + gallery images + variant images into one flat array

### Storage

- **Path pattern:** `{seller->username}/products/{filename}.webp`
- **Recommended:** `products/{seller_id}/{product_id}/{filename}.webp`
- All images stored on `public` disk
- `storage_url()` helper generates URLs

### ImageOptimizerService

- Converts all uploads to **WebP** format
- Scales down to max 1200px width
- Quality: 85%
- Uses **Intervention Image** v3

### Controllers

| Method | Route | Handles |
|--------|-------|---------|
| `store()` | `POST /seller/products/store` | Single thumbnail upload via `uploadThumbnail()` |
| `update()` | `POST /seller/products/{slug}/update` | Thumbnail replacement + bulk gallery upload via `files` input |
| `uploadImages()` | `POST seller/products/images/upload` | Dedicated gallery upload (requires product to exist) |
| `deleteImage()` | `DELETE seller/products/images/{image}/delete` | Single image deletion |
| `deleteProduct()` | — | Cleans up thumbnail + all gallery + variant images |

### Views

- **create.blade.php** — Thumbnail upload only (via `x-image-input`), no gallery upload
- **edit.blade.php** — Thumbnail cropper + `upload-images` partial for gallery
- **upload-images.blade.php** — Existing gallery management with upload modal

---

## Issues Found

### 1. ❌ Migration — Missing Columns
- No `type` column to distinguish thumbnail vs gallery vs variant image
- No `position` column for sort order
- No `is_primary` column for marking primary gallery image
- No foreign key constraint on `product_id`

### 2. ❌ Create Page — No Gallery Upload
- Product creation form has only thumbnail input
- Gallery images can only be added **after** product creation via separate endpoint
- No preview/remove before submit

### 3. ❌ No Image Ordering
- No UI or backend for reordering images
- `position` column doesn't exist
- Images displayed in arbitrary order

### 4. ❌ No Primary Image
- Gallery images cannot be marked as primary
- First image should serve as secondary thumbnail

### 5. ❌ No Image Replacement
- Gallery images cannot be replaced inline
- Must delete and re-upload

### 6. ❌ No Dedicated Media Page
- Image management is embedded in edit page
- No full-page media manager with grid view
- No bulk operations

### 7. ⚠️ Weak Validation
- `thumbnail: max:10000` (10MB) — too large
- `images.*: max:4000` (4MB) — reasonable but no dimension validation
- No min/max dimension checks
- No aspect ratio validation

### 8. ⚠️ Storage Path Inconsistency
- Uses `{seller->username}/products/` — username can change
- No product ID in path
- Recommended: `products/{seller_id}/{product_id}/`

### 9. ⚠️ No Accessor on ProductImage
- `ProductImage` lacks `imageUrl` accessor
- Views manually call `storage_url($image->image)`

### 10. ⚠️ Delete Image — No Old File Cleanup Fallback
- `deleteImage()` calls `delete_file()` then deletes DB record
- If `delete_file()` fails, DB record is still removed (orphaned file)
- No logging on failure

---

## Required Changes

### Database
- [x] New migration: add `type` (enum: thumbnail, gallery, variant), `position` (int, default 0), `is_primary` (bool, default false)
- [x] Add foreign key constraint on `product_id`

### Model
- [x] Add `$casts` for `is_primary` (boolean), `position` (integer)
- [x] Add `imageUrl` accessor
- [x] Add `scopePrimary()`, `scopeOrdered()` scopes

### Controller
- [x] Create `ProductMediaController` with dedicated media management
- [x] `index()` — Show media grid page
- [x] `upload()` — AJAX upload with preview
- [x] `delete()` — AJAX delete with confirmation
- [x] `reorder()` — AJAX sort order update
- [x] `setPrimary()` — AJAX set primary image
- [x] `replace()` — AJAX replace existing image

### Routes
- [x] `GET /seller/products/{product}/media` — Media page
- [x] `POST /seller/products/{product}/media/upload` — AJAX upload
- [x] `DELETE /seller/products/{product}/media/{image}` — AJAX delete
- [x] `POST /seller/products/{product}/media/reorder` — AJAX reorder
- [x] `POST /seller/products/{product}/media/{image}/primary` — AJAX set primary
- [x] `POST /seller/products/{product}/media/{image}/replace` — AJAX replace

### Views
- [x] `seller.products.media.index` — Full media management page
- [x] Update `upload-images` partial to support new columns
- [x] Add media link to product action buttons

### Security
- [x] All media routes check `seller_id === get_seller_id()`
- [x] Delete removes file from storage before DB record
- [x] Thumbnail deletion clears `product.thumbnail` field

### Performance
- [x] WebP conversion on all uploads (already implemented)
- [x] Lazy loading on gallery images
- [x] Image compression via Intervention (already implemented)
