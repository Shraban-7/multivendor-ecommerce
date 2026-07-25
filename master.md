# Master Execution Order — Laravel Multivendor Re-Architecture

Run these in order. Do not skip ahead — each stage depends on the outputs of the ones before it. Stop and review outputs at every ⏸ before continuing.

---

## Stage 0 — Setup (one-time)
1. **Cursor rules file** — `.cursor/rules/architecture.mdc` (modular monolith version, with repository pattern rules added).
2. **Protected models guard** — generates `/docs/protected-models.md`. Run this BEFORE any refactor/deletion prompt, and re-run it periodically as more models get migrated, so the list stays current.

⏸ Review `/docs/protected-models.md` before continuing.

---

## Stage 1 — Ground-truth inspection (no code changes)
3. **Five reference docs prompt** — generates:
   - `.cursor/rules/architecture.mdc` filled with real project details
   - `/docs/database-schema.md`
   - `/docs/api-inventory.md`
   - `/docs/module-boundary-map.md`
   - `/docs/tech-debt-inventory.md`

⏸ Review all five files.

4. **Feature gap analysis prompt** — generates `/docs/feature-gap-analysis.md`, comparing your app against standard marketplace features.

⏸ Decide which missing/partial features you actually want built (not all marketplaces need all features).

---

## Stage 2 — Planning (no code changes)
5. **Task file generation prompt** — generates `/tasks/00-inspection/findings.md`, one task folder per module, and `/tasks/STATUS.md`.
6. **Missing-features task prompt** — turns your approved gap-analysis items into task files, added into `/tasks`.

⏸ Review task files. Reorder/edit/delete any that don't fit. This is your master backlog now.

---

## Stage 3 — Duplicate model audit (no code changes)
7. **Model inventory + DB error diagnosis prompt** — generates `/docs/model-inventory-report.md` and `/docs/db-error-diagnosis.md`.
8. **Duplicate models audit prompt** (codebase-wide version) — generates/updates `/docs/duplicate-models-report.md`, and auto-fixes only the clearly-identical LOW-risk duplicates (shims only, no reference updates yet).

⏸ Review both reports. Decide which HOLD groups (money-path models) you approve for the next stage — you already approved all 4 (Seller, Order, Payment, Affiliate).

---

## Stage 4 — Execute the conversion (code changes begin here)
9. **Full modular monolith + repository pattern conversion prompt** — works through `/tasks` in order: framework upgrade → module extraction (Vendor, Product, Review, Shipping, Order, Payment) → repository interface + implementation per module → callers refactored to use repositories. One task at a time, stops after each for your go-ahead.

Run in parallel with this stage as needed:
10. **Image handling prompt work** — implement `ImageStorageInterface` + Cloudinary/R2 implementation in `app/Support/Media`, per the image handling guide. Fits in as its own module, can run anytime after Stage 0.

⏸ This stage is long — review after every module, not just at the end.

---

## Stage 5 — Reference consolidation for held groups
11. **Held-groups reference-update prompt** — updates actual `App\Models\X` → Domain FQCN references (not just shims) for Seller → Affiliate → Order → Payment, one model at a time, full test suite after each.

⏸ Let this run in staging with production-like data for a while before continuing. Don't rush into Stage 6.

---

## Stage 6 — Layer-by-layer refactor
Run these three in order:
12. **Controllers refactor prompt** — routes all controller data access through repositories, extracts remaining business logic into Services/Actions. Module order: Shipping → Review → Product → Vendor → Seller → Affiliate → Order → Payment.
13. **Services/Actions refactor prompt** — ensures Services only use repository interfaces, never direct Eloquent or cross-module models. Same module order.
14. **Final sweep prompt** — catches remaining `App\Models\*` references in Jobs, Events, Listeners, Notifications, Policies, Form Requests, views, Resources, Console Commands, Observers, config, morph maps.

⏸ Review `/docs/final-model-reference-sweep.md`. Do not proceed to Stage 7 until this is clean with zero flags on Order/Payment/Seller/Affiliate.

---

## Stage 7 — Final cleanup (destructive — run last, run carefully)
15. **Root model shim removal prompt** — deletes `app/Models/*.php` shim files, one at a time, full test suite after each, only for models confirmed fully reference-updated. Order: Shipping → Review → Product → Vendor → Seller → Affiliate → Order → Payment (payment last).

⏸ Only run this after Stage 6 has been stable in production/staging for a real observation period — not immediately after Stage 6 finishes.

---

## Ongoing (not one-time)
- **Performance & scaling pass** (from the original re-architecture guide): N+1 fixes, caching, queue offload, search — do this once the module structure is stable, guided by real profiling data.
- Re-run the **protected models guard** (#2) periodically as new models get migrated, so it never goes stale.

---

## Quick reference: what NOT to do
- Don't run Stage 4+ before Stage 1–3 are done — the agent will be guessing instead of working from real analysis.
- Don't run Stage 5 (Payment/Order/Seller/Affiliate reference updates) before Stages 1–3 show zero unresolved divergence.
- Don't run Stage 7 (deletion) same-session as Stage 5/6 — let things sit and prove stable first.
- Every prompt that touches Payment/Order/Seller/Affiliate should run against staging with realistic data before you trust it in production.