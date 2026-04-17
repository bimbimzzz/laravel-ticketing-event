# API Contract — JagoEvent Backend ↔ Flutter App

## Flutter App
- **Path**: `/Users/bahri/development/fic22/trial/flutter_ayo_piknik`
- **Package**: `flutter_JagoEvent`
- **State Management**: BLoC
- **HTTP**: dart:http with Bearer token auth

## Base Configuration
```
Base URL: http://{host}:8000
Image URL: http://{host}:8000/images/events/{filename}
```

---

## Endpoints

### 1. AUTH

#### POST /api/register
```json
// Request
{ "name": "string", "email": "string", "password": "string", "confirm_password": "string" }

// Response 201
{ "status": "success", "message": "User created successfully", "data": { User } }
```

#### POST /api/login
```json
// Request
{ "email": "string", "password": "string" }

// Response 200
{
  "status": "success",
  "message": "User logged in successfully",
  "data": {
    "user": {
      "id": 1, "name": "string", "email": "string",
      "email_verified_at": null, "phone": null,
      "is_vendor": 0,  // INTEGER (0 or 1, NOT boolean)
      "vendor": null,   // or Vendor object if is_vendor=1
      "created_at": "2026-01-01T00:00:00.000000Z",
      "updated_at": "2026-01-01T00:00:00.000000Z"
    },
    "token": "1|abc123..."
  }
}
```

#### POST /api/logout (Auth: Bearer)
```json
// Response 200
{ "status": "success", "message": "User logged out successfully" }
```

---

### 2. EVENTS

#### GET /api/events (Auth: Bearer)
Returns ALL events with grouped available tickets per SKU.
```json
{
  "status": "success",
  "data": [
    {
      "id": 1, "name": "Concert", "description": "...", "image": "file.jpg",
      "start_date": "2026-04-01", "end_date": "2026-04-02",
      "vendor": { "id": 1, "user_id": 1, "name": "...", "location": "...", "phone": "...", "email": null, "city": "...", "description": "...", "verify_status": "approved" },
      "event_category": { "id": 1, "name": "Musik", "description": "..." },
      "tickets": [
        {
          "sku": { "id": 1, "name": "VIP", "category": "Premium", "price": "100000", "stock": 10, "day_type": "weekday" },
          "ticket_count": 8
        }
      ]
    }
  ]
}
```

#### GET /api/events/user/{userId} (Auth: Bearer)
Same response format as above, filtered by vendor's events.

#### GET /api/event-categories (Auth: Bearer)
```json
{
  "status": "success",
  "message": "Event categories fetched successfully",
  "data": [{ "id": 1, "name": "Musik", "description": "..." }]
}
```

#### POST /api/events (Auth: Bearer, Multipart)
Fields: `vendor_id, event_category_id, name, description, image(file), start_date, end_date`
```json
// Response 201
{ "status": "success", "message": "Event created successfully", "data": { Event } }
```

#### POST /api/event/update/{id} (Auth: Bearer, Multipart)
Same fields as create, image optional.

#### DELETE /api/event/{id} (Auth: Bearer)
```json
{ "status": "success", "message": "Event deleted successfully" }
```

---

### 3. ORDERS

#### POST /api/order (Auth: Bearer)
```json
// Request
{
  "event_id": 1,
  "order_details": [{ "sku_id": 1, "qty": 2 }],
  "quantity": 2,
  "event_date": "2026-04-01"
}

// Response 201
{
  "status": "success",
  "message": "Order created successfully",
  "data": {
    "id": 1, "user_id": 1, "event_id": 1,
    "quantity": 2, "total_price": 200000,
    "event_date": "2026-04-01",
    "payment_url": "https://snap.midtrans.com/...",
    "created_at": "...", "updated_at": "...",
    "user": { User },
    "orderItems": [{ "sku_id": 1, "qty": 2 }]
  }
}
```

#### GET /api/orders/user/{userId} (Auth: Bearer)
```json
{
  "status": "success",
  "message": "Get all order history",
  "data": [
    {
      "id": 1, "user_id": 1, "event_id": 1,
      "quantity": 2, "total_price": "200000",
      "event_date": "2026-04-01",
      "status_payment": "success", "payment_url": "...",
      "created_at": "...", "updated_at": "...",
      "orderTickets": [
        {
          "id": 1, "order_id": 1, "ticket_id": 1,
          "total_quantity": 2,
          "ticket": {
            "id": 1, "sku_id": 1, "event_id": 1,
            "ticket_code": "ABCDEF1234", "status": "sold",
            "sku": { "id": 1, "name": "VIP", ... },
            "event": { "id": 1, "name": "...", ... }
          }
        }
      ],
      "user": { User },
      "event": { Event with vendor }
    }
  ]
}
```

#### GET /api/orders/user/{userId}/vendor (Auth: Bearer)
Same format as above.

#### GET /api/orders/user/{userId}/vendor/total (Auth: Bearer)
```json
{ "status": "success", "message": "Get total price order history by vendor", "data": 500000 }
```

---

### 4. SKUs

#### GET /api/skus/user/{userId} (Auth: Bearer)
```json
{ "status": "success", "data": [{ "id": 1, "name": "VIP", "category": "Premium", "event_id": 1, "price": "100000", "stock": 10, "day_type": "weekday", "event": { Event } }] }
```

#### POST /api/sku (Auth: Bearer)
```json
// Request
{ "name": "VIP", "price": 100000, "category": "Premium", "event_id": 1, "stock": 10, "day_type": "weekday" }

// Response 200
{ "status": "success", "message": "Sku created successfully", "data": { Sku } }
```

---

### 5. TICKETS

#### GET /api/tickets/user/{userId} (Auth: Bearer)
```json
{ "status": "success", "data": [{ "id": 1, "sku_id": 1, "event_id": 1, "ticket_code": "ABC123", "ticket_date": null, "status": "available", "sku": { Sku }, "event": { Event } }] }
```

#### POST /api/check-ticket (Auth: Bearer)
```json
// Request
{ "ticket_code": "ABCDEF1234" }

// Response 200 (valid)
{ "status": "success", "message": "Ticket redeemed successfully", "isValid": true }

// Response 400 (already redeemed)
{ "status": "error", "message": "Ticket already redeemed", "isValid": false }

// Response 404 (not found)
{ "status": "error", "message": "Ticket not found", "isValid": false }
```

---

### 6. VENDORS

#### GET /api/vendors/user/{userId} (Auth: Bearer)
```json
{ "status": "success", "message": "Get vendor by user", "data": [{ Vendor }] }
```

#### POST /api/vendor (Auth: Bearer)
```json
// Request
{ "user_id": 1, "name": "string", "description": "string", "location": "string", "phone": "string", "city": "string" }

// Response 201
{ "status": "success", "message": "Vendor created successfully", "data": { Vendor } }
```

---

### 7. WEBHOOK

#### POST /api/midtrans/webhook (No Auth)
```json
// Midtrans sends
{ "transaction_status": "settlement|pending|expire|cancel", "order_id": 1, "fraud_status": "accept" }

// Response 200
{ "status": "success" }
```
