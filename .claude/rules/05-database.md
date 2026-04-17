---
paths:
  - "database/migrations/**/*.php"
  - "database/seeders/**/*.php"
  - "database/factories/**/*.php"
---
# Database Conventions

## Schema Overview (12 Tables)

| # | Table | Key Columns | Key Relations |
|---|-------|-------------|---------------|
| 1 | `users` | name, email, password, phone, is_vendor | — |
| 2 | `vendors` | user_id, name, description, location, phone, city, verify_status | user_id FK |
| 3 | `event_categories` | name, description | — |
| 4 | `events` | vendor_id, event_category_id, name, description, image, start_date, end_date | vendor_id, event_category_id FK |
| 5 | `skus` | event_id, name, category, price, stock, day_type | event_id FK |
| 6 | `tickets` | event_id, sku_id, ticket_code, ticket_date, status | event_id, sku_id FK |
| 7 | `orders` | user_id, event_id, quantity, total_price, event_date, **status_payment**, payment_url | user_id, event_id FK |
| 8 | `order_tickets` | order_id, ticket_id | order_id, ticket_id FK |
| 9 | `personal_access_tokens` | (Sanctum) | — |
| 10-12 | `cache`, `jobs`, `sessions` | (Framework) | — |

## Critical Column Names

```php
// orders table — ENUM column is `status_payment` NOT `status`
$table->enum('status_payment', ['pending', 'success', 'cancel'])->default('pending');

// tickets table — ENUM column is `status`
$table->enum('status', ['available', 'booked', 'sold', 'redeem'])->default('available');

// vendors table — ENUM column is `verify_status`
$table->enum('verify_status', ['pending', 'approved', 'rejected'])->default('pending');

// vendors table — NO `email` column
```

## Column Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Primary Key | `id` | `id` |
| Foreign Key | `{table}_id` | `vendor_id`, `event_id`, `sku_id` |
| Boolean | `is_*` | `is_vendor` |
| Date | contextual | `start_date`, `end_date`, `event_date`, `ticket_date` |
| Amount/Money | `decimal(10,2)` | `total_price`, `price` |
| Status | `enum` string | `status`, `status_payment`, `verify_status` |
| Code | `string` | `ticket_code` |
| URL | `string nullable` | `payment_url` |

## Data Types

| Data | Type | Parameters |
|------|------|------------|
| Money | `decimal` | `10, 2` |
| Short text | `string` | `255` default |
| Long text | `text` | — |
| Status | `enum` | with default |
| Count | `integer` | — |
| Date | `date` | — |
| Image path | `string` | — |

## Foreign Key Conventions

```php
// Standard cascade delete
$table->foreignId('user_id')->constrained()->onDelete('cascade');
$table->foreignId('event_id')->constrained()->onDelete('cascade');
$table->foreignId('sku_id')->constrained()->onDelete('cascade');
$table->foreignId('order_id')->constrained()->onDelete('cascade');
$table->foreignId('ticket_id')->constrained()->onDelete('cascade');
```

## Factory Standards

```php
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'event_id' => Event::factory(),
            'quantity' => fake()->numberBetween(1, 5),
            'total_price' => fake()->numberBetween(50000, 1000000),
            'event_date' => now()->addDays(7),
            'status_payment' => 'pending',  // ← Match migration column name
        ];
    }
}
```

## Query Best Practices

```php
// Use eager loading
$orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])->get();

// Use transactions for order creation
DB::transaction(function () use ($data) {
    $order = Order::create($data);
    // ... book tickets, decrement stock
});

// Use lockForUpdate for concurrent access
$ticket = Ticket::where('sku_id', $sku->id)
    ->where('status', 'available')
    ->lockForUpdate()
    ->first();
```
