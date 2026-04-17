# Project Overview

## Event Ticketing Marketplace

**Laravel Ticketing Backend** adalah API backend untuk marketplace tiket event. Vendor bisa membuat event, menambah SKU (tipe tiket), dan menjual tiket ke buyer. Pembayaran via Midtrans.

## Tech Stack

| Category | Technology |
|----------|------------|
| Backend | PHP 8.3, Laravel 12 |
| Database | SQLite (testing), MySQL (production) |
| Auth | Laravel Sanctum (token-based) |
| Payment | Midtrans Snap |
| Admin Panel | Filament |
| Testing | PHPUnit 11 |
| Mobile | Flutter (consumer) |

## Target User

- **Vendor**: Event organizer yang menjual tiket
- **Buyer**: Pengguna yang membeli tiket event
- **Admin**: Mengelola vendor verification via Filament

## Core Concepts

### Multi-Role Architecture
```
Admin (via Filament)
├── Vendor verification
└── Platform management

User (API)
├── Buyer (default)
│   ├── Browse events
│   ├── Order tickets
│   └── View order history
└── Vendor (is_vendor = true)
    ├── Create events
    ├── Manage SKUs & tickets
    └── View sales reports
```

### Key Entities
```
User → Vendor → Event → SKU → Ticket
                  │
                  └── Order → OrderTicket → Ticket
```

### Business Flow
1. Vendor creates Event with image
2. Vendor adds SKU (tipe tiket: VIP, Regular, etc) with price & stock
3. System auto-generates Ticket records per SKU stock
4. Buyer creates Order → tickets marked "booked" → Midtrans payment URL generated
5. Webhook updates order status_payment → tickets marked "sold"
6. Ticket redemption via ticket_code scan

## Directory Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/              # All API controllers
│   │       ├── AuthController.php
│   │       ├── EventController.php
│   │       ├── OrderController.php
│   │       ├── SkuController.php
│   │       ├── TicketController.php
│   │       └── VendorController.php
│   └── Controllers/Controller.php
├── Models/
│   ├── User.php
│   ├── Vendor.php
│   ├── Event.php
│   ├── EventCategory.php
│   ├── Sku.php
│   ├── Ticket.php
│   ├── Order.php
│   └── OrderTicket.php
├── Policies/
│   └── EventPolicy.php
├── Helpers/
│   └── UniqueCodeHelper.php
├── Services/
│   └── Midtrans/
│       ├── Midtrans.php
│       └── CreatePaymentUrlService.php
├── Filament/                  # Admin panel
└── Providers/

routes/
├── api.php                    # All API routes
└── web.php                    # Filament admin

database/
├── migrations/                # 12 migration files
├── factories/                 # 7 factories (User, Vendor, Event, EventCategory, Sku, Ticket, Order)
└── seeders/

tests/
├── Feature/
│   ├── Controllers/           # API endpoint tests
│   ├── Models/                # Model relationship tests
│   ├── Policies/              # Authorization tests
│   └── Routes/                # Route-level tests
└── Unit/
```

## Modules

| Module | Description |
|--------|-------------|
| Auth | Register, login (email + Google OAuth), logout |
| Events | CRUD event, categories, public listing, vendor's events |
| SKUs | Ticket type definitions per event |
| Tickets | Auto-generated from SKU stock, redemption |
| Orders | Ticket purchasing, Midtrans payment |
| Vendors | Vendor registration, verification |
