# Flutter Mobile App Reference

## Project Location
- **Path**: `/Users/bahri/development/fic22/trial/flutter_ayo_piknik`
- **Package Name**: `flutter_JagoEvent`
- **State Management**: flutter_bloc
- **HTTP Client**: dart:http package

## Base URL Configuration
```dart
// lib/core/constants/variabels.dart
class Variables {
  static const String baseUrl = 'http://192.168.18.40:8000';
  static const String imageStorage = '$baseUrl/images';
}
```

## Multi-Role Architecture
- **Buyer**: Browse events → Order tickets → Pay via Midtrans → View e-tickets
- **Vendor (Partner)**: Manage events → Create SKUs → View orders → Scan tickets
- Determined by `is_vendor` field (int: 0 or 1) on User model

## API Contract — All Endpoints Used by Flutter

### Auth
| Method | URL | Auth | Request | Response Key Fields |
|--------|-----|------|---------|-------------------|
| POST | `/api/register` | No | `{name, email, password, confirm_password}` | `{status, message, data: User}` |
| POST | `/api/login` | No | `{email, password}` | `{status, message, data: {user: User, token: String}}` |
| POST | `/api/logout` | Bearer | - | `{status, message}` |

### Events
| Method | URL | Auth | Response Key Fields |
|--------|-----|------|-------------------|
| GET | `/api/events` | Bearer | `{status, data: [EventModel]}` — each event MUST include `tickets` (grouped by SKU) |
| GET | `/api/events/user/{userId}` | Bearer | Same format as above |
| POST | `/api/events` | Bearer | Multipart: vendor_id, event_category_id, name, description, image, start_date, end_date |
| POST | `/api/event/update/{id}` | Bearer | Multipart: same fields, image optional |
| DELETE | `/api/event/{id}` | Bearer | `{status, message}` |

### Event Categories
| Method | URL | Auth | Response |
|--------|-----|------|----------|
| GET | `/api/event-categories` | Bearer | `{status, message, data: [EventCategoryModel]}` |

### Orders
| Method | URL | Auth | Request/Response |
|--------|-----|------|-----------------|
| POST | `/api/order` | Bearer | `{event_id, order_details: [{sku_id, qty}], quantity, event_date}` → `{status, message, data: CreateOrder}` |
| GET | `/api/orders/user/{userId}` | Bearer | `{status, message, data: [OrderModel]}` — includes orderTickets.ticket.sku |
| GET | `/api/orders/user/{userId}/vendor` | Bearer | Same format |
| GET | `/api/orders/user/{userId}/vendor/total` | Bearer | `{status, message, data: int(sum)}` |

### SKUs
| Method | URL | Auth | Request/Response |
|--------|-----|------|-----------------|
| GET | `/api/skus/user/{userId}` | Bearer | `{status, data: [SkuModel]}` with event relation |
| POST | `/api/sku` | Bearer | `{name, price, category, event_id, stock, day_type}` |

### Tickets
| Method | URL | Auth | Response |
|--------|-----|------|----------|
| GET | `/api/tickets/user/{userId}` | Bearer | `{status, data: [TicketModel]}` with sku & event relations |
| POST | `/api/check-ticket` | Bearer | `{ticket_code}` → `{status, message, isValid: bool}` |

### Vendors
| Method | URL | Auth | Response |
|--------|-----|------|----------|
| GET | `/api/vendors/user/{userId}` | Bearer | `{status, message, data: [Vendor]}` |
| POST | `/api/vendor` | Bearer | `{user_id, name, description, location, phone, city}` |

### Webhook
| Method | URL | Auth | Notes |
|--------|-----|------|-------|
| POST | `/api/midtrans/webhook` | None | Midtrans server callback |

## Critical Response Formats

### EventModel (Flutter expects)
```json
{
  "id": 1,
  "name": "Concert",
  "description": "...",
  "image": "filename.jpg",
  "start_date": "2026-04-01",
  "end_date": "2026-04-02",
  "vendor": { "id": 1, "user_id": 1, "name": "...", ... },
  "event_category": { "id": 1, "name": "Musik", ... },
  "tickets": [
    {
      "sku": { "id": 1, "name": "VIP", "category": "Premium", "price": "100000", "stock": 10, "day_type": "weekday" },
      "ticket_count": 8
    }
  ]
}
```

### User/Login (Flutter expects `is_vendor` as int 0/1, NOT boolean)
```json
{
  "id": 1,
  "name": "John",
  "email": "john@example.com",
  "phone": null,
  "is_vendor": 1,
  "vendor": { ... }
}
```

### CreateOrder Response (Flutter expects `payment_url` and `orderItems`)
```json
{
  "status": "success",
  "data": {
    "id": 1,
    "user_id": 1,
    "event_id": 1,
    "quantity": 2,
    "total_price": 200000,
    "event_date": "2026-04-01",
    "payment_url": "https://snap.midtrans.com/...",
    "user": { ... },
    "orderItems": [{"sku_id": 1, "qty": 2}]
  }
}
```

## Image URL Pattern
Flutter constructs image URLs as: `${Variables.imageStorage}/events/{filename}`
→ Maps to: `http://host:8000/images/events/{filename}`
→ Laravel serves from: `public/images/events/`
