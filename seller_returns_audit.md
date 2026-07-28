# Seller Return Management — Audit & Implementation

## Scope

Audit + implementation of a marketplace-grade return workflow covering:

- Return request creation (customer)
- Approval / rejection (seller + admin)
- End-to-end refund process (gateway → wallet fallback)
- Return status state machine + event log
- Dispute handling (customer → seller response → admin resolution)

## Audit findings (before)

| # | Area | Finding | Severity |
|---|---|---|---|
| 1 | Return request | Worked but controller-only, no service layer | low |
| 2 | Approval/rejection routes | **Admin routes (`approve`, `reject`, `show`, `resolveDispute`) were not registered** → 404 from views | **critical** |
| 3 | Seller control plane | **No `Seller\ReturnController`, no seller views, no approval/rejection UI** | **critical** |
| 4 | Refund process | Only `Order::refund_amount` was written and `seller_earning` was decremented on `OrderController::updateStatus()`. **No actual customer-side refund, no `RefundTransaction`, no gateway/wallet flow.** | **critical** |
| 5 | Return status | Status stored as raw string. No `ITEM_RECEIVED`, no `AWAITING_SHIPMENT`, no `EXCHANGE_SHIPPED`. No state machine. OrderSide `refund` flag could be wrong when return wasn't fully refunded. | high |
| 6 | Dispute handling | Customer could only dispute **rejected** returns. Seller could not respond. No admin `assigned_to`, no `seller_response`. | high |
| 7 | Notifications | **None**. Sellers/customers/admins were not notified at any step. | high |
| 8 | Service layer | Three controllers each duplicated the same logic (state transitions, refund math, seller earning reversal). | high |
| 9 | Marketplace config | `config('marketplace.return_window_days')` was referenced but `config/marketplace.php` didn't exist. | medium |
| 10 | Shipment tracking | No record of buyer→seller shipment or seller→customer exchange shipment. | medium |
| 11 | Tests | Zero tests for any of the return paths. | high |
| 12 | Status log | Order `status_logs` were written but the rest of the return lifecycle wasn't audited. | medium |

## Implementation summary

The implementation lives in `app/Domain/Order/` and is wired through `OrderServiceProvider` (already existed).

### New domain code

| File | Purpose |
|---|---|
| `Enums/ReturnStatus.php` | Full return state machine: `PENDING → AWAITING_SHIPMENT → ITEM_RECEIVED → APPROVED → REFUNDED / REJECTED / EXCHANGE_SHIPPED / COMPLETED / CANCELLED` |
| `Enums/DisputeStatus.php` | Dispute lifecycle: `OPEN / UNDER_REVIEW / SELLER_RESPONSE / RESOLVED / CLOSED` |
| `Enums/ReturnEventType.php` | Audit log event types |
| `Enums/DisputeResolution.php` | Admin decisions: `APPROVED / REJECTED / PARTIAL_REFUND / WALLET_CREDIT` |
| `Services/ReturnService.php` | Single source of truth for the return workflow — validates state transitions, dispatches notifications, writes status logs, reverses seller's earnings |
| `Services/RefundService.php` | Refund orchestrator: tries gateway (bKash/aamarPay) → falls back to wallet credit; records every attempt in `refund_transactions` |
| `Services/DisputeService.php` | Dispute flow: open, seller respond, admin resolve (with optional partial refund) |
| `Models/ReturnEvent.php` | Append-only audit log record |
| `Models/ReturnShipment.php` | Tracks carrier, tracking number, status (to_seller/to_customer) |
| `Models/RefundTransaction.php` | Refund attempt ledger: gateway/wallet/manual, success/pending/failed |
| `Http/Controllers/Seller/ReturnController.php` | **New** — full seller UI: list, show, approve, reject, record shipment, mark received, respond to dispute |
| `Http/Controllers/Api/ReturnController.php` | New — Sanctum endpoints for mobile |
| `app/Services/Refund/{Bkash,Aamarpay}RefundGateway.php` | Pluggable gateway stubs — throw clear `RuntimeException` until real API wiring is done |

### Schema additions

| Migration | Change |
|---|---|
| `2026_07_29_010000_refine_return_requests_table.php` | Adds `refunded_amount / refund_method / refund_reference / cancellation_reason`, indexes |
| `2026_07_29_010100_create_return_events_table.php` | New — type / actor / from→to / note / meta |
| `2026_07_29_010200_create_return_shipments_table.php` | New — bidirectional shipment tracking |
| `2026_07_29_010300_extend_disputes_table.php` | Adds `assigned_admin_id / seller_response / seller_responded_at` |
| `2026_07_29_010400_create_refund_transactions_table.php` | New — refund attempts with gateway reference |
| `2026_07_29_010500_add_admin_id_to_notifications_table.php` | New — enables `notify_admin(...)` helper |

### Config

| File | Purpose |
|---|---|
| `config/marketplace.php` | `return_window_days`, `dispute_window_days`, refund config (wallet fallback, require_item_received, etc.) |

### Routes wired up

| Prefix | New endpoints |
|---|---|
| `admin.returns.{index, show, approve, reject, cancel, markReceived}` | registered — were missing |
| `admin.returns.resolveDispute` | registered — was referenced by view |
| `seller.returns.{index, show, approve, reject, recordShipment, markReceived}` | **new** |
| `seller.returns.disputeRespond` | **new** |
| `api.returns.{index, show, store, cancel, recordShipment, dispute}` | **new** (Sanctum) |

### Helpers

- `notify_admin($id, ...)`, `notify_admins(...)` in `app/Helpers/helpers.php`

### Refactored controllers

- `Frontend\ReturnController` — now delegates to `ReturnService` + `DisputeService`
- `Admin\ReturnManageController` — same; adds `markReceived`, `cancel`; uses `DisputeResolution` enum for outcomes
- `Seller\OrderController::updateStatus` — still works but the duplicate return-state logic has moved into `ReturnService` (called inline for legacy parity)

### New views

- `resources/views/seller/returns/index.blade.php` — dashboard tiles + search + filters
- `resources/views/seller/returns/show.blade.php` — items, timeline, dispute, refund history, workflow actions
- `resources/views/admin/returns/show.blade.php` — extended with timeline + refund attempts + new states + resolve options

### Tests (new — all green)

```
Tests\Feature\Order\ReturnStatusEnumTest           (5 passing)
Tests\Feature\Order\ReturnServiceWorkflowTest       (7 passing)
Tests\Feature\Order\DisputeServiceTest              (4 passing)
Tests\Feature\Order\MarketplaceConfigTest           (2 passing)
                                                       ─────────────
                                                       18 passing (62 assertions)
```

Pest is configured with sqlite in-memory; run all return tests with:

```
php -d memory_limit=2G artisan test tests/Feature/Order
```

(PHP CLI memory limit is 128M — order tests need more because `RefreshDatabase` runs ~80 migrations.)

## Marketplace state machine

```
PENDING ─approve(seller|admin)──► APPROVED ─shipment_tracked──► AWAITING_SHIPMENT
                          │                                       │
                          │                                       ▼
                          └──reject(seller|admin, reason)──► REJECTED ─dispute──► dispute open ─► resolved(dispute)
                                                                                                 │
                                                                                                 ▼
                                                                                                 (rejected stays terminal; resolution=APPROVED forces APPROVED → item_received)

APPROVED ──record shipment──► AWAITING_SHIPMENT ──mark received──► ITEM_RECEIVED
                                                                              │
                                                                              ├── non-exchange → auto refund
                                                                              └── exchange     → admin keeps order at APPROVED; no refund

markReceived (admin|seller) → restores order stock → marks order RETURNED → triggers refund (skip for exchange)

Refund: gateway(bkash|aamarpay|...) try → on failure → wallet credit (config.marketplace.refund.auto_credit_wallet_when_gateway_fails)
Refund success → order REFUNDED, seller_earning deltas reversed, customer wallet or gateway credited
```

## Notes / TODO before going live

1. **bKash / aamarPay refund integration** — the `BkashRefundGateway` and `AamarpayRefundGateway` classes currently throw `RuntimeException` so the workflow fails closed. They need to be wired against each gateway's refund endpoint before production traffic.
2. **Admin assignment** — `dispute.assigned_admin_id` is settable but no admin queue UI ships in this change. Add manual queue assignment + filter.
3. **Stock-of-record on exchange** — `markReceived` restores stock for refunds; for exchanges, the seller is expected to mark `EXCHANGE_SHIPPED` after shipping the replacement. Add explicit button / state once fulfilment integration is ready.
4. **Image evidence on return request** — the return form doesn't yet capture photos. Add an `images` column + uploader (out of scope of this implementation).
5. **Email** — notifications are in-app only; pair with FCM/sms via existing `NotificationService` once wired.
6. **Routes file location** — currently the order routes file mixes admin + seller + api. Consider splitting per surface for clarity.
