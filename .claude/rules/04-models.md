---
paths:
  - "app/Models/**/*.php"
---
# Model Standards

## Base Model Pattern

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'event_category_id',
        'name',
        'description',
        'image',
        'start_date',
        'end_date',
    ];

    // ==================
    // RELATIONSHIPS
    // ==================

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function eventCategory(): BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function skus(): HasMany
    {
        return $this->hasMany(Sku::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }
}
```

## Entity Relationship Map

```
User (1) ──── (1) Vendor
                   │
                   ├── (N) Event
                   │        ├── (N) Sku
                   │        │       └── (N) Ticket
                   │        ├── (N) Ticket (also belongs to Sku)
                   │        └── (N) Order
                   │                └── (N) OrderTicket ──── Ticket

User (1) ──── (N) Order
                   └── (N) OrderTicket ──── Ticket

EventCategory (1) ──── (N) Event
```

## Key Model Rules

### Order Model
```php
// IMPORTANT: Column is `status_payment` NOT `status`
protected $fillable = [
    'user_id', 'event_id', 'quantity', 'total_price',
    'status_payment',  // ← CORRECT (enum: pending, success, cancel)
    'event_date', 'payment_url',
];

// NO direct sku() relationship — access via orderTickets
// $order->orderTickets->first()->ticket->sku
```

### Ticket Status Flow
```
available → booked (order created) → sold (payment success) → redeem (scanned)
```

### Vendor Model
```php
// No `email` in fillable — column doesn't exist in migration
protected $fillable = [
    'user_id', 'name', 'description', 'location',
    'phone', 'city', 'verify_status',
];
```

## All Models MUST Have

1. **`HasFactory` trait** — for testing
2. **`$fillable` array** — never use `$guarded = []`
3. **Relationship methods** — with explicit return types
4. **Match migration columns** — `$fillable` must match actual DB columns

## Status Constants (Recommended Pattern)

```php
class Ticket extends Model
{
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_BOOKED = 'booked';
    public const STATUS_SOLD = 'sold';
    public const STATUS_REDEEM = 'redeem';
}

class Order extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_SUCCESS = 'success';
    public const STATUS_CANCEL = 'cancel';
}

class Vendor extends Model
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
}
```
