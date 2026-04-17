---
paths:
  - "app/Http/Controllers/Api/**/*.php"
  - "app/Services/**/*.php"
  - "app/Helpers/**/*.php"
---
# Business Logic

## Order Creation Flow (Most Complex Operation)

```
1. Validate request
2. BEGIN TRANSACTION
   a. Check ticket availability per SKU (with lockForUpdate)
   b. Calculate total price
   c. Create Order record (status_payment = pending)
   d. For each SKU in order_details:
      - Find available ticket (lockForUpdate)
      - Create OrderTicket (pivot)
      - Update ticket status → 'booked'
      - Decrement SKU stock
   e. Generate Midtrans payment URL
   f. Update order with payment_url
3. COMMIT TRANSACTION
4. Return success with order data
```

### Key Safety Mechanisms
- **`DB::transaction()`** — atomic, rolls back everything on failure
- **`lockForUpdate()`** — prevents two buyers booking same ticket
- **`$sku->decrement('stock', $qty)`** — atomic stock decrement
- **Availability check BEFORE booking** — early fail with 422

## SKU + Ticket Creation Flow

```
1. Vendor creates SKU (name, category, price, stock, day_type)
2. System auto-generates N Ticket records (N = stock)
3. Each ticket gets unique ticket_code via UniqueCodeHelper
4. All tickets start with status = 'available'
```

```php
// UniqueCodeHelper generates 10-char alphanumeric code
$ticket_code = UniqueCodeHelper::generateUniqueCode('tickets', 'ticket_code');
```

## Ticket Lifecycle

```
┌──────────┐     Order      ┌────────┐    Payment    ┌──────┐    Scan     ┌────────┐
│ available │ ──────────────→│ booked │ ─────────────→│ sold │ ──────────→│ redeem │
└──────────┘                 └────────┘               └──────┘            └────────┘
                                │
                                │ Payment failed/timeout
                                ▼
                           ┌──────────┐
                           │ available │ (TODO: release mechanism)
                           └──────────┘
```

## Event Data Grouping (getAllEvents)

The `/events/all` endpoint groups tickets by SKU with available count:

```json
{
    "id": 1,
    "name": "Event Name",
    "vendor": { ... },
    "event_category": { ... },
    "tickets": [
        {
            "sku": { "id": 1, "name": "VIP", "price": 500000, "stock": 10 },
            "ticket_count": 7
        }
    ]
}
```

## Vendor Verification Flow

```
1. User registers as buyer
2. User creates vendor profile → verify_status = 'pending'
3. User.is_vendor = true
4. Admin approves via Filament → verify_status = 'approved'
5. Vendor can now create events
```

## Authorization Rules

| Action | Who Can Do It |
|--------|--------------|
| Browse events | Anyone (public) |
| View event detail | Anyone (public) |
| Create event | Authenticated vendor |
| Update event | Event's vendor owner only |
| Delete event | Event's vendor owner only |
| Create order | Authenticated buyer |
| View own orders | Authenticated user (own data only) |
| View vendor orders | Vendor owner (own events only) |
| Redeem ticket | Anyone with ticket_code (public endpoint) |
