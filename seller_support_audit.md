# Seller Support Module — Audit & Implementation

## Scope

Audit + implementation of a marketplace support ticket system covering:

- Ticket creation (seller)
- Admin reply + admin-side triage
- Threaded seller↔admin communication
- Ticket status workflow + SLA
- Notifications on replies & status changes
- SLA-assigned by priority

## Audit findings (before)

| # | Area | Finding | Severity |
|---|---|---|---|
| 1 | `App\Domain\Support` module | **Exists but only Notification / SmsLog / SocialLink / StaticPage / SystemSetting** — no ticket domain at all. | critical |
| 2 | Ticket models | **None** — no SupportTicket, SupportTicketMessage, SupportTicketAttachment. | critical |
| 3 | Ticket creation | No entry point. `app/Http/Controllers/Frontend/ContactUsController.php` renders a static `contact-us` view only. | critical |
| 4 | Admin reply | No admin support inbox. | critical |
| 5 | Ticket status | No `TicketStatus`, no transitions. | critical |
| 6 | Seller communication | No two-way threaded messaging, no attachments, no internal notes. | critical |
| 7 | Service layer | None. | high |
| 8 | Notifications | Seller doesn't get notified on admin reply; admin doesn't get notified on a new ticket. | high |
| 9 | Priority / SLA | `SubscriptionPlan::priority_support` feature flag exists but is never wired to ticket queues. | high |
| 10 | Tests | **None.** | high |
| 11 | UI | Neither seller support inbox nor admin support inbox existed. | critical |

## Implementation summary

### New domain code (`app/Domain/Support/...`)

| File | Purpose |
|---|---|
| `Enums/TicketStatus.php` | `OPEN / IN_PROGRESS / AWAITING_SELLER / AWAITING_ADMIN / RESOLVED / CLOSED / REJECTED` + state transitions. |
| `Enums/TicketPriority.php` | `LOW / NORMAL / HIGH / URGENT` with weights. |
| `Enums/TicketCategory.php` | `ACCOUNT / PAYMENT / ORDER / PRODUCT / RETURN_REFUND / COMPLIANCE / SUBSCRIPTION / TECHNICAL / OTHER`. |
| `Models/SupportTicket.php` | Top-level ticket with full `@property` typing, scopes (forSeller, open, awaiting), SLA helper. |
| `Models/SupportTicketMessage.php` | Individual thread messages with morph sender + attachment relation + read tracking. |
| `Models/SupportTicketAttachment.php` | Files attached to a message (disk/path/size/mime). |
| `Models/SupportTicketEvent.php` | Append-only audit-log `SupportTicketEvent::log(...)`. |
| `Services/SupportTicketService.php` | State machine + replies + assignment + ticket-number generator + notifications. |
| `Providers/SupportServiceProvider.php` | Loads routes + migrations (registered in `bootstrap/providers.php`). |

### Migrations

| Migration | Change |
|---|---|
| `2026_07_29_012000_create_support_tickets_table.php` | New: ticket_number, subject, seller/user owner, category, priority, status, assigned_admin, order_ref, SLA timestamps, indexes for `(status, priority)`, `(seller_id, status)`, `(admin_id, status)`, `sla_due_at`. |
| `2026_07_29_012100_create_support_ticket_messages_table.php` | New: thread messages, sender_type (seller/customer/admin/system), is_internal_note, is_status_change, read_at, JSON meta. |
| `2026_07_29_012200_create_support_ticket_attachments_table.php` | New: file attachment records. |
| `2026_07_29_012300_create_support_ticket_events_table.php` | New: audit log: status_change / priority_change / assignment / resolved / closed / reopened / sla_warning. |

### Config (`config/marketplace.php`, `support` block)

```php
'sla_hours' => [
    'low'    => 72,
    'normal' => 48,
    'high'   => 24,
    'urgent' => 4,
],
'ticket_prefix' => 'SUP',
'sla_warning_minutes_before' => 60,
'allow_self_resolve' => true,
'allow_internal_notes' => true,
```

### State machine

```
                reply(buyer)             reply(admin)
        ┌──────┐ ◄────────────┐
        │ OPEN │                │
        └──┬───┘                ▼
           │            AWAITING_SELLER
           ▼                  ▲
      ┌─────────┐  reply(admin) │  reply(buyer)  ┌──────────────┐
      │ IN_PROG │ ◄──────────────┘                 └──────────────┘
      └────┬────┘                                     AWAITING_ADMIN
           │
           ▼
  RESOLVED → CLOSED → (reopen → OPEN)
                REJECTED → (reopen → OPEN)
```

Each transition writes a `SupportTicketEvent` record + a system message in the thread so the user sees why.

### Routes wired

**Seller**
- `GET  /seller/support` — inbox
- `GET  /seller/support/create` — new form
- `POST /seller/support/store` — submit
- `GET  /seller/support/{ticket}` — thread
- `POST /seller/support/{ticket}/reply`
- `POST /seller/support/{ticket}/resolve`
- `POST /seller/support/{ticket}/reopen`

**Admin**
- `GET  /admin/support` — overall board (filters + overdue banner)
- `GET  /admin/support/{ticket}` — thread + activity panel + status/priority/assign controls
- `POST /admin/support/{ticket}/reply`
- `POST /admin/support/{ticket}/status`
- `POST /admin/support/{ticket}/priority`
- `POST /admin/support/{ticket}/assign` / `/self-assign`
- `POST /admin/support/{ticket}/resolve` / `/reopen`

### Notifications

- **New ticket** → `notify_admin(...)` for every admin
- **Admin reply** → `notify_seller(...)` (and `notify_admin` for assigned admin)  
- **Seller reply** → `notify_admin` for the assigned admin  
- **Status change** → notify both parties

### Views

- Seller: `index`, `create`, `show` (in `resources/views/seller/support/`)
- Admin: `index`, `show` (in `resources/views/admin/support/`)

Admin show includes a single-page workflow panel: change priority / change status / assign / self-assign / resolve / reopen + show the conversation with `is_internal_note` tags.

## Verification

- **Pint**: ✅ passed on `app/Domain/Support` and `tests/Feature/Support`
- **PHPStan**: **0 errors** in `app/Domain/Support` (full-app analysis shows 351 pre-existing errors in unrelated modules — none introduced here)
- **Tests**: **9 new tests, all green (39 assertions)** — `SupportTicketServiceTest`

  ```
  Tests\Feature\Support\SupportTicketServiceTest
    ✓ createTicket generates sequential ticket number with prefix
    ✓ ticket moves to awaiting_admin / awaiting_seller on replies
    ✓ admin reply creates message + sender, increments reply_count
    ✓ priority change updates sla_due_at based on config
    ✓ resolve / close / reopen transitions work and are recorded in events
    ✓ state machine: OPEN → RESOLVED allowed; RESOLVED → IN_PROGRESS rejected
    ✓ attachments can be added with a reply and are visible on the message
    ✓ assignTo records event and stamps system message
    ✓ internal note is not visible to seller when replying as seller
  ```

## Implementation notes / caveats

1. **Ticket numbers** use `SUP-YYMMDD-####`. The counter is per-day; if the seller creates many in a single day it remains stable enough.
2. **Internal notes** (`is_internal_note=true`) are visible to everyone in the admin view as a `locked` annotation but **never** rendered on the seller-facing views.
3. **Auto-status promotion**: when a party replies on an `open` ticket, the system flips status to `awaiting_*` of the *other* party. Status change is a single-row update plus an audit event plus a system message in the thread.
4. **Resolver & re-opener** are symmetric: `RESOLVED → OPEN` is allowed via `reopen()` (sellers can reopen their own ticket when they think it wasn't truly fixed).
5. **Attachments** are stored on the `public` disk under `support/{ticket_id}` (10 MB cap per file). Switch to S3 by setting `SUPPORT_DISK` later.
6. **No Eloquent observer needed** — notification logic is inside the service so we don't double-fire on cascade saves.

## Open follow-ups (out of scope for this change)

- SLA-breach scheduled job to push notifications + auto-escalate priority.
- SLA-warning emails (currently logged-only via `sla_warning_minutes_before` config).
- Admin dashboard widget for "open tickets assigned to me" + "new since yesterday".
- Full-text search inside ticket thread.
- Customer-side frontend tickets (currently seller-only).
