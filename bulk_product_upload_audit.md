# Bulk Product Upload — Audit Report

**Date:** 2026-07-29
**Scope:** Full audit of bulk product import/upload capabilities for seller product management

---

## 1. Existing Implementation

**There is zero existing bulk upload/import functionality.** The codebase has:

| Feature | Status |
|---------|--------|
| CSV import | ❌ Not implemented |
| XLSX import | ❌ Not implemented |
| Import validation | ❌ Not implemented |
| Import preview | ❌ Not implemented |
| Import history | ❌ Not implemented |
| Failed import reporting | ❌ Not implemented |
| Duplicate SKU detection | ❌ Not implemented |
| Product update via import | ❌ Not implemented |
| Inventory update via import | ❌ Not implemented |
| Variant import | ❌ Not implemented |
| Product image import | ❌ Not implemented |
| Category/brand mapping | ❌ Not implemented |
| Queue processing | ❌ Not implemented |

**No import-related packages** exist in `composer.json` (`maatwebsite/excel`, `phpoffice/phpspreadsheet`, `league/csv` — none present).

---

## 2. Required Infrastructure

### Database
A new `bulk_uploads` table is needed to track import jobs:
- `id`, `seller_id`, `status` (pending/processing/completed/failed), `file_path`, `file_type` (csv/xlsx), `original_filename`, `total_rows`, `success_count`, `fail_count`, `summary` (JSON), `created_at`, `updated_at`
- A `bulk_upload_rows` table for per-row results: `id`, `bulk_upload_id`, `row_number`, `status` (success/failed), `sku`, `errors` (JSON), `product_id`, `data` (JSON snapshot)

### Package Dependencies
- `phpoffice/phpspreadsheet` for XLSX parsing
- No package needed for CSV (PHP's native `fgetcsv()` suffices)

### Queue Infrastructure
- A queue job (`ProcessBulkUploadJob`) for async processing
- Chunk-based processing to avoid memory exhaustion on large files

---

## 3. Required Features

### Data Format Support
| Format | Support | Implementation |
|--------|---------|----------------|
| CSV | ✅ Required | PHP `fgetcsv()` — zero dependencies |
| XLSX | ✅ Required | `PhpSpreadsheet` reader |

### Per-Row Data Fields
| Field | Type | Required | Notes |
|-------|------|----------|-------|
| Product Name | string | Yes | Max 255 chars |
| SKU | string | No | Auto-generated if empty; uniqueness checked |
| Barcode | string | No | Unique check if provided |
| Category | string | Yes | Mapped by name/slug; created if not found? |
| Subcategory | string | No | Mapped under parent category |
| Brand | string | No | Created via Select2-style tag (like existing create form) |
| Short Description | text | No | |
| Description | text | No | |
| Price | numeric | Yes | Must be >= cost_price |
| Compare Price | numeric | No | Must be < price if set |
| Cost Price | numeric | Yes | |
| Stock | integer | No | Defaults to 0 |
| Weight | numeric | No | kg |
| Height/Width/Length | numeric | No | cm |
| Unit | string | No | Mapped to `product_units.short_name` |
| Status | string | No | draft/active; defaults to pending_approval |
| Tags | string | No | Comma-separated |
| Thumbnail URL | string | No | Downloaded from URL |
| Gallery Image URLs | string | No | Pipe or comma separated URLs |
| Variant Data | JSON/string | No | Variant rows with color/size/price/stock |
| Country of Origin | string | No | |
| Manufacturer | string | No | |

### Validation Rules
| Rule | Error Action |
|------|-------------|
| Missing product name | Fail row |
| Invalid category name | Fail row |
| Invalid brand name | Use default or fail |
| Price < cost_price | Fail row |
| Duplicate SKU (in file) | Fail row (duplicate within import) |
| Duplicate SKU (in DB) | Update existing or fail (configurable) |
| Invalid numeric values | Fail row |
| Missing required fields | Fail row |
| Invalid image URL | Skip image, continue row |

### Error Handling
- Import does **not** stop when a row fails
- Each row is processed independently within a DB transaction
- Failed rows are recorded with specific error messages
- Summary report generated at completion

---

## 4. Performance Considerations

| Concern | Mitigation |
|---------|------------|
| Large files (10K+ rows) | Queue job + chunk processing (500 rows per chunk) |
| Memory exhaustion | Read rows lazily; avoid loading entire file into memory |
| DB insert speed | Batch inserts per chunk |
| Image downloads | Queue image downloads separately; don't block row processing |
| Timeouts | Queue job has no execution timeout; chunk commits every 500 rows |

---

## 5. Security Considerations

| Risk | Mitigation |
|------|------------|
| Seller imports into other sellers' products | `seller_id` enforced on every product create/update |
| Malicious file upload | Validate MIME type + extension; limit file size (10MB) |
| CSV injection | Sanitize cells starting with `=`, `+`, `-`, `@` |
| XLSX macros | `PhpSpreadsheet` with `IOFactory::load()` doesn't execute macros |
| Duplicate submission | Prevent parallel imports for same seller (check for pending import) |
| Rate limiting | One active import per seller at a time |

---

## 6. Production Readiness Score

| Category | Score (0-10) |
|----------|:------------:|
| CSV Import | 0/10 |
| XLSX Import | 0/10 |
| Validation | 0/10 |
| Preview | 0/10 |
| Error Reporting | 0/10 |
| Queue Processing | 0/10 |
| Image Import | 0/10 |
| Inventory Update | 0/10 |
| Security | 0/10 |
| Performance | 0/10 |
| **Overall** | **0/10** |

---

## 7. Implementation Plan

1. Create `bulk_uploads` + `bulk_upload_rows` migrations
2. Create `BulkUpload` + `BulkUploadRow` models
3. Install `phpoffice/phpspreadsheet`
4. Create `ImportParserService` (CSV + XLSX readers)
5. Create `ImportValidatorService` (per-row validation)
6. Create `ImportProcessorService` (orchestrates validation + DB ops)
7. Create `ProcessBulkUploadJob` (queue job with chunking)
8. Create `BulkUploadController` (upload, preview, history, results)
9. Create routes in `app/Domain/BulkUpload/routes.php`
10. Create Blade views (upload form, preview, history, result details)
11. Register `BulkUploadServiceProvider`
