# [Your Marketplace Name]: Daraz-Style E-Commerce UI Guidelines

## Context and Goals

**Design intent (one sentence):** Deliver a dense, functional, trust-building e-commerce storefront in the visual language of Daraz Bangladesh — bold orange brand accent, high information density, deal/discount-forward merchandising — while meeting real WCAG 2.2 AA contrast and readability requirements that the raw Daraz production CSS does not consistently meet.

**Brand reference:** Daraz Bangladesh (daraz.com.bd) — real extracted brand colors: `#F85606` (International Orange, primary brand color), `#F5F5F5` (Wild Sand, secondary surface), `#000000`/`#191919` (near-black, text/dark surfaces).

**Note on source fidelity:** Some token values commonly scraped from live Daraz CSS (e.g. `#888888` body text at 11px/weight 200) fail WCAG AA contrast and minimum readable size. This guideline reproduces Daraz's **visual identity and layout density**, not its literal failing values — every color/type pairing below is corrected to pass AA while looking unmistakably like the same brand family.

**Product surface:** E-commerce storefront — customer-facing (browse, product detail, cart, checkout) and marketplace listing density (category grids, flash-sale rails).

**Audience:** Online shoppers and consumers, Bangladesh market, high mobile usage, price- and deal-sensitive browsing behavior.

---

## Design Tokens and Foundations

### Typography

```
font.family.primary = "Noto Sans"
font.family.stack = "Noto Sans", -apple-system, BlinkMacSystemFont, Roboto,
                     "Helvetica Neue", Helvetica, Arial, "PingFang SC",
                     "Microsoft YaHei", sans-serif
font.weight.regular = 400
font.weight.medium = 500
font.weight.bold = 700
font.size.base = 14px
font.lineHeight.base = 20px
```

> Rationale: Daraz's real production body copy renders near 11px/weight 200 in places, which fails both WCAG minimum-readable-size guidance and renders as too-thin on most displays. 14px/400 preserves the dense, compact *feel* of the source site while being genuinely readable.

**Type scale**
```
font.size.xs  = 12px   -- micro-labels, timestamps, fine print (never body copy)
font.size.sm  = 13px   -- secondary text, metadata, captions
font.size.md  = 14px   -- body / base
font.size.lg  = 16px   -- emphasized body, form labels
font.size.xl  = 20px   -- section headers, price display (large)
font.size.2xl = 24px   -- page/section titles
```
Minimum interactive-label size: `font.size.sm` (13px). Nothing smaller ever carries an action or price.

### Color — semantic tokens

```
color.brand.primary        = #F85606   -- International Orange (Daraz brand)
color.brand.primary-deep   = #C43D00   -- accessible variant for text-bearing CTAs
color.brand.primary-tint   = #FFF1EA   -- light background for orange-themed badges/rails

color.text.primary         = #191919   -- ~15.7:1 on white — headlines, body
color.text.secondary       = #595959   -- ~7:1 on white — metadata, secondary copy
color.text.tertiary        = #767676   -- ~4.6:1 on white — least-emphasis text; AA-pass
                                           at this size only, never smaller than sm
color.text.inverse         = #FFFFFF   -- text on dark/brand surfaces

color.surface.base         = #FFFFFF
color.surface.muted        = #F5F5F5   -- Wild Sand — section backgrounds, chips
color.surface.raised       = #FFFFFF   -- cards (raised via border/shadow, not tint)
color.surface.strong       = #191919   -- dark surfaces (header, footer, badges)

color.border.default       = #E5E5E5
color.border.strong        = #C7C7C7

color.feedback.success     = #1D8A45   -- in-stock, delivered, confirmed
color.feedback.danger      = #D93025   -- discount %, flash-sale, out-of-stock, errors
color.feedback.warning     = #B7791A   -- low-stock, limited-time
color.feedback.info        = #0F6FC5   -- informational badges (Free Shipping, etc.)

color.rating.star          = #FFA000   -- star rating fill (distinct from brand orange
                                           to avoid confusing rating with promo/CTA)
```

**Contrast rules (non-negotiable):**
- `color.text.primary` and `color.text.secondary` must be used for any text carrying meaning at any size.
- `color.text.tertiary` is permitted only at `font.size.sm` or larger, and never for interactive labels or prices.
- `color.brand.primary` (`#F85606`) must never carry white text directly at sizes below `font.size.xl` bold — use `color.brand.primary-deep` for any button/label combination smaller than that, verified at ≥4.5:1.
- `color.feedback.danger` on white must be used for discount tags/badges as background with white text only at `font.size.sm`+/weight 700, or as text-on-white directly (passes AA on its own).

### Spacing

```
space.1 = 2px    space.5 = 12px
space.2 = 4px    space.6 = 16px
space.3 = 6px    space.7 = 20px
space.4 = 8px    space.8 = 24px
                 space.9 = 32px
```
Product grids and listing density use `space.2`–`space.4` internal gaps (tight, Daraz-like); page-level section spacing uses `space.7`–`space.9`. No one-off values outside this scale.

### Radius, shadow, motion

```
radius.xs = 2px    -- tags, chips, small badges
radius.sm = 4px    -- buttons, inputs
radius.md = 6px    -- cards
shadow.card = 0 1px 2px rgba(25,25,25,0.08)
shadow.raised = 0 2px 8px rgba(25,25,25,0.12)   -- dropdowns, modals only
motion.duration.instant = 100ms   -- hover/focus feedback
motion.duration.fast = 200ms      -- transitions, add-to-cart confirmations
motion.easing.standard = cubic-bezier(0.2, 0, 0, 1)
```

---

## Component-Level Rules

### 1. Product Card

**Anatomy:** image (1:1), discount badge (top-left, overlaid), wishlist icon (top-right, overlaid), title (2-line clamp), price row (current price + struck-through original price + discount %), rating row (stars + count), badge row (Free Shipping / Mall / verified-seller, optional).

**Variants:** `default` (grid), `compact` (rail/carousel), `flash-sale` (adds countdown timer + progress bar for stock sold).

**States:** `default`, `hover` (subtle `shadow.raised`, image slight scale ≤1.03), `focus-visible` (2px `color.brand.primary-deep` outline, 2px offset), `loading` (skeleton shimmer matching card dimensions exactly — no layout shift on load), `error` (broken-image placeholder icon, title/price still rendered from data if available), `out-of-stock` (image at 60% opacity, "Out of Stock" label overlaid, price muted to `color.text.tertiary`, not clickable to cart but still clickable to detail page).

**Interaction:**
- Keyboard: entire card is one `<a>`/link target; wishlist icon is a separate, independently focusable/tabbable control nested inside — must not be triggered when activating the card link, and must not be skipped by keyboard nav.
- Pointer: full card clickable to product detail; wishlist icon has its own hit target ≥24×24px, `stopPropagation` on click.
- Touch: minimum touch target 44×44px for wishlist icon regardless of visual icon size (pad the hit area).

**Responsive:** 2-up on mobile (<480px), 3-up tablet, 4–6-up desktop depending on rail vs. grid context. Title clamps to 2 lines at every breakpoint — never reflows to 3+.

**Edge cases:** title overflow → `-webkit-line-clamp: 2` with ellipsis, full title in `title` attribute and accessible name. Missing rating → omit rating row entirely, do not render "0 reviews." Price of 0 or null → do not render card at all; log as a data error, do not ship a broken card to the DOM.

---

### 2. Price Display

**Anatomy:** current price (bold, `color.text.primary` or `color.feedback.danger` if discounted), original price (struck-through, `color.text.tertiary`, `font.size.sm`), discount badge (`color.feedback.danger` background, white text, `font.size.xs` bold, `radius.xs`).

**Rule:** current price is always the most visually dominant element on a product card — larger and heavier than title. Currency symbol/code (৳) is always adjacent to the number, same weight, never a separate smaller/lighter treatment (that reads as a trust problem in commerce UI).

**States:** `default`, `discounted` (adds strike-through + badge), `unavailable` (price replaced with "Currently unavailable" in `color.text.secondary`, no strike-through shown).

**Accessibility:** struck-through price must have `<s>` semantic markup or `aria-label` stating "original price" — a visual line-through alone is not conveyed to screen readers.

---

### 3. Primary Button (Add to Cart / Buy Now)

**Anatomy:** label (required, always a verb phrase — "Add to Cart," never "Submit"), optional leading icon.

**Variants:** `primary` (orange-deep fill, white text — main CTA), `secondary` (white fill, orange-deep border + text), `danger` (for destructive actions — remove from cart), `ghost` (text-only, for tertiary actions in dense lists).

**States (all required, no exceptions):**
| State | Spec |
|---|---|
| default | `color.brand.primary-deep` background, white text |
| hover | darken background 8%, `motion.duration.instant` |
| focus-visible | 2px solid outline `color.brand.primary-deep`, 2px offset, visible on all backgrounds including white and dark |
| active | darken background 14%, no scale/transform jump |
| disabled | `color.surface.muted` background, `color.text.tertiary` text, `cursor: not-allowed`, no hover/active response |
| loading | label replaced with spinner (same button dimensions, no layout shift), button not clickable, `aria-busy="true"` |
| error | button returns to default state, error surfaces adjacent (toast or inline message) — button itself does not turn red/danger as its own error state |

**Interaction:**
- Keyboard: `Enter`/`Space` activates. Loading state must not trap focus.
- Pointer: no double-submit — button disables immediately on click until response received.
- Touch: minimum 44×44px hit target regardless of visual button height.

**Responsive:** full-width on mobile in cart/checkout contexts; auto-width inline elsewhere.

---

### 4. Input (Search / Text)

**Anatomy:** label (visible or `sr-only`, never placeholder-only), input field, optional leading/trailing icon, helper/error text below.

**States:** `default` (`color.border.default`), `hover` (`color.border.strong`), `focus-visible` (2px `color.brand.primary-deep` outline + border color change), `disabled` (`color.surface.muted` background, `color.text.tertiary`), `error` (`color.feedback.danger` border + icon + message below, `aria-invalid="true"`, `aria-describedby` pointing to the error text), `filled` (has value, label may shrink/float if using floating-label pattern).

**Interaction:** placeholder text alone is never an acceptable substitute for a label (fails on clear/focus, fails for screen readers on some AT). Search input specifically must support `Enter` to submit and expose autocomplete suggestions (if present) as a listbox navigable via arrow keys, `Escape` to dismiss.

**Edge cases:** long input value → truncate visually with ellipsis but retain full value in the field (never truncate actual data); empty search submit → show "Enter a search term" inline, do not silently no-op.

---

### 5. Navigation Link (category nav, footer, breadcrumb)

Given known density on Daraz-style pages (hundreds of links per page — categories, footer, filters), link styling must stay restrained and consistent, not decorative per instance.

**States:** `default` (`color.text.secondary`, no underline), `hover` (`color.text.primary` + underline), `focus-visible` (visible outline, not just color change — color-only focus indication fails AA 2.2), `visited` (not distinguished by color in dense nav/category contexts — visited-state color differentiation is reserved for content links like order history, not navigational chrome), `active/current` (bold weight + `color.brand.primary-deep`, plus `aria-current="page"` or `"true"` as appropriate).

**Rule:** never rely on color alone to indicate current/active nav item — pair with weight change or an underline/indicator.

---

### 6. List (product list rows, order history, cart items)

**Anatomy:** row with thumbnail, primary text, secondary metadata, trailing action(s) or price.

**States:** `default`, `hover` (subtle background `color.surface.muted`, list rows only — not product grid cards), `focus-visible` on any interactive row, `empty` (see Content Standards below), `loading` (skeleton rows matching final row height).

**Responsive:** on mobile, trailing actions collapse into a single overflow/kebab menu if more than 2 actions exist per row — never let action buttons wrap the row height taller on small screens.

---

## Section-Level Rules (Hero, Rails, Header, Footer)

Sections are structural — each one has a specific merchandising job, not a decorative one. Avoid the generic "big headline + gradient + stock photo" hero pattern; this storefront's hero job is **immediate deal visibility and category entry**, not brand storytelling — that's what makes it read as an e-commerce site rather than a landing page.

### 7. Hero / Top Banner

**Job:** get the shopper into a deal or category within one glance and one click — not brand messaging. This is the single biggest departure from a generic SaaS/marketing hero.

**Anatomy:**
- Full-width promotional carousel (primary campaign banners, `radius.md`, auto-advancing) occupying roughly 65–70% of the hero width on desktop.
- Adjacent category quick-nav panel (25–30% width) listing top-level categories as a compact icon+label list — this is what makes it feel like a marketplace immediately, not a brand page.
- Below the fold of the hero itself (not overlapping it): a flash-sale/deals rail begins immediately — the hero should not be a full viewport-height moment the user has to scroll past before seeing product.

**States:**
- `default` — auto-advancing carousel, `motion.duration` for slide transition should be slow/deliberate (600–800ms ease, not `motion.duration.fast` which is reserved for micro-interactions), pauses on hover/focus.
- `hover/focus-visible` — carousel pauses; visible focus outline on the active slide's CTA and on pagination dots.
- `loading` — skeleton block at the exact aspect ratio of the final banner, never a blank flash or layout jump when the image loads.
- `reduced-motion` — auto-advance disabled entirely; user must manually advance via visible arrows/dots.

**Interaction:**
- Keyboard: arrow keys or Tab moves between slides when carousel is focused; pagination dots are individually focusable buttons with `aria-label="Go to slide N of M"`.
- Pointer: swipe-draggable on touch, click-through arrows on desktop.
- Auto-advance must pause on any user interaction (hover, focus, touch) and must never auto-advance faster than 5s per slide (WCAG 2.2.2 timing).

**Responsive:** on mobile, category quick-nav panel collapses into a horizontal scrollable chip row directly beneath the (now full-width, shorter aspect-ratio) banner carousel — never stacked as a tall vertical list that pushes products below the fold.

**Signature element carried through:** the hero banner's active pagination indicator uses the same chamfered/cut-corner motif as product cards if your broader system uses one — a small, consistent structural echo rather than a new decorative device per section.

---

### 8. Category / Flash-Sale Rail

**Job:** dense horizontal merchandising — this is where Daraz's information density is most visually distinctive, and where restraint matters most (don't add card shadows, gradients, or decoration beyond the token system already defined).

**Anatomy:** section header (title + optional countdown timer for flash sales + "See All" link, right-aligned) above a horizontally scrollable row of `compact` product cards.

**States:** `default`; `scroll-hint` (subtle edge fade or arrow affordance indicating more content exists off-screen — required, since undiscoverable horizontal scroll is a real usability failure); `loading` (skeleton cards, same count as will actually render, not a fixed arbitrary number); `empty` (rail is omitted entirely from the page if there's no data — never render an empty rail with just a header).

**Interaction:** keyboard users can Tab into the rail and arrow-key or Tab through cards; the rail is a `region` with an accessible label (e.g. `aria-label="Flash Sale deals"`) so screen reader users can jump to it via landmark navigation given how many rails a category/home page has.

**Responsive:** horizontal scroll-snap on mobile (`scroll-snap-type: x mandatory`), card widths sized so exactly 2.2–2.4 cards are visible at once on mobile (signals scrollability without needing the hint affordance to do all the work).

---

### 9. Header / Navigation Bar

**Anatomy:** logo, search input (dominant width — this is the primary navigation method in dense marketplaces, not category browsing), category menu trigger, account/cart/wishlist icons with count badges.

**States:** `default` (white or `color.surface.base` background, `color.border.default` bottom hairline — not a heavy shadow); `scrolled` (optional: compress header height slightly, add `shadow.card` once user scrolls past hero, `motion.duration.fast` transition); `search-focused` (search input visibly expands/emphasizes, rest of header content may dim slightly or category menu closes if open — one focus target at a time).

**Cart/wishlist count badge:** `color.feedback.danger` background, white text, positioned top-right of icon, must update with an `aria-live="polite"` region so screen reader users hear "Cart updated, 3 items" without focus moving — never a silent visual-only count change.

**Responsive:** mobile header collapses to logo + search icon (expands to full-width overlay search on tap) + hamburger category menu + cart icon — account/wishlist move into the hamburger menu rather than staying as top-level icons, since horizontal space is the constraint.

---

### 10. Footer

**Job:** trust signals + dense link directory (matches the known high link-count density of marketplace footers) — not a visual afterthought.

**Anatomy, top to bottom:** trust/value-proposition strip (payment methods accepted, delivery promise, return policy — icons + short labels, `color.surface.strong` or `color.surface.muted` background to visually separate from product content above), then multi-column link directory grouped by category (Customer Service, About, Payment, Categories, Follow Us), then legal/copyright bar.

**States:** links follow the same Navigation Link component rules defined above — `color.text.secondary` default, `color.text.primary` + underline on hover, visible focus outline (footers are dense enough that a missing focus indicator here is a common, easy-to-miss AA failure).

**Responsive:** column groups collapse into single-column accordions on mobile (each group header is a disclosure button with `aria-expanded`) rather than one long unbroken link list — given the density (hundreds of links possible), an accordion keeps the mobile footer navigable instead of an endless scroll.

---

## Accessibility Requirements and Testable Acceptance Criteria

All criteria below **must** be testable by an implementer without design judgment calls.

| # | Rule | Pass/Fail Test |
|---|---|---|
| A1 | All body/UI text ≥ 4.5:1 contrast against its background | Automated contrast check (axe, Lighthouse) on every text/background token pairing in this doc — must report 0 failures |
| A2 | Large text (≥19px, or ≥16px bold) ≥ 3:1 contrast | Same tooling, filtered to large-text rule |
| A3 | Every interactive element has a visible focus indicator distinguishable from hover | Tab through every page; every focusable element shows the 2px outline spec; zero elements show `outline: none` without a replacement indicator |
| A4 | Every interactive element reachable via keyboard alone, in logical order | Full keyboard-only pass through each page/flow; no dead-ends, no keyboard traps |
| A5 | Touch targets ≥ 44×44px | Automated + manual check on all icon-only buttons, wishlist icons, close buttons |
| A6 | Color is never the sole means of conveying state (error, discount, active nav) | Visual audit: every color-coded state also has an icon, text label, weight change, or ARIA attribute |
| A7 | Images have meaningful `alt` text; decorative images have `alt=""` | Automated + manual spot-check on product images (should describe product, not filename) |
| A8 | Form errors are programmatically associated with their field | Check `aria-describedby`/`aria-invalid` present on every field-level error |
| A9 | Loading states use `aria-busy` and do not shift layout | Automated layout-shift (CLS) check on all skeleton/loading states |
| A10 | Reduced motion respected | `prefers-reduced-motion: reduce` disables non-essential transitions/animations app-wide |

---

## Content and Tone Standards

- **Buttons name the action, from the user's side:** "Add to Cart," "Buy Now," "Track Order" — never "Submit," "OK," or "Click Here."
- **Errors state what happened and how to fix it**, in the interface's voice: "This item is out of stock. We'll notify you when it's back." — not "Error 404" or an apologetic tone.
- **Empty states are an invitation to act:** an empty cart says "Your cart is empty — browse today's deals" with a link, not just "No items."
- **Discount/urgency language must be accurate, never fabricated:** a countdown timer or "X left in stock" must reflect real inventory/campaign data — never a decorative fake-urgency element, which is both a dark pattern and an accessibility/trust problem.
- **Consistency of vocabulary:** if a button says "Add to Cart," the resulting confirmation says "Added to cart," not "Item saved" or other drift.

---

## Anti-Patterns and Prohibited Implementations

- ❌ Reproducing Daraz's raw low-contrast body text (`#888` on white/black, sub-4.5:1) anywhere in this system.
- ❌ Font weights below 400 for any body, label, or price text.
- ❌ Placeholder text used as the only label for an input.
- ❌ Color-only differentiation for discount tags, stock status, or active navigation state.
- ❌ Removing focus outlines (`outline: none`) without a compliant replacement.
- ❌ One-off spacing/type values outside the defined scale, anywhere in product/category/cart/checkout surfaces.
- ❌ Fake/decorative urgency (countdown timers, "X people viewing this") not backed by real data.
- ❌ Card or button loading states that cause layout shift when content resolves.
- ❌ Icon-only interactive controls with no accessible name (`aria-label` required whenever there's no visible text label).

---

## QA Checklist

- [ ] All text/background pairings pass automated contrast check (A1/A2)
- [ ] Full keyboard-only pass completed on: home, category grid, product detail, cart, checkout (A3/A4)
- [ ] All icon-only controls have accessible names and ≥44×44px touch targets (A5)
- [ ] Every discount badge, stock-status indicator, and active nav state has a non-color signal (A6)
- [ ] All product images have descriptive alt text; no `alt="image123.jpg"` in the wild (A7)
- [ ] All form errors are screen-reader announced and associated with their field (A8)
- [ ] Loading skeletons match final content dimensions — zero CLS on data resolve (A9)
- [ ] `prefers-reduced-motion` verified in browser dev tools emulation (A10)
- [ ] No token value used anywhere outside this document's defined scale (spacing, type, color)
- [ ] Button labels audited for verb-first, user-facing language — no "Submit"/"OK"
- [ ] Empty-state copy reviewed on cart, wishlist, order history, search-no-results
- [ ] Mobile pass (< 480px): product grid 2-up, buttons full-width in cart/checkout, no horizontal scroll anywhere unintended
- [ ] Hero carousel: auto-advance pauses on interaction, never advances faster than 5s/slide, fully disabled under `prefers-reduced-motion` (A10, WCAG 2.2.2)
- [ ] Hero carousel pagination dots individually keyboard-focusable with descriptive `aria-label`
- [ ] Every rail (flash-sale, category, recommended) has an accessible region label and is skippable via landmark navigation
- [ ] Empty rails are omitted from the DOM entirely, never rendered with a header and no content
- [ ] Cart/wishlist count badge changes are announced via `aria-live="polite"`, not visual-only
- [ ] Footer link groups collapse to accessible accordions (`aria-expanded`) on mobile, not an unbroken link wall
- [ ] Header search input is reachable and operable via keyboard as the primary nav path, not just category menu