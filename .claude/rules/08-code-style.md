---
paths:
  - "app/**/*.php"
  - "routes/**/*.php"
  - "tests/**/*.php"
---
# Code Style

## PHP/Laravel Conventions

### General
- PSR-12 compliant
- Type hints di parameter dan return type (recommended)
- Always use curly braces for control structures
- Use `HasFactory` trait on ALL models

### Naming Conventions

| Type | Convention | Example |
|------|------------|---------|
| Class | PascalCase | `OrderController`, `CreatePaymentUrlService` |
| Method | camelCase | `getOrderByUserId()`, `checkTicketValid()` |
| Variable | camelCase | `$orderDetail`, `$eventIds` |
| Constant | UPPER_SNAKE | `STATUS_PENDING`, `STATUS_AVAILABLE` |
| Config key | snake_case | `midtrans.server_key` |
| Database | snake_case | `order_tickets`, `status_payment` |
| Factory | PascalCase + Factory | `OrderFactory`, `SkuFactory` |

### PHP 8 Features

```php
// Match Expression (for status mapping)
$variant = match ($ticket->status) {
    'available' => 'success',
    'booked' => 'warning',
    'sold' => 'info',
    'redeem' => 'secondary',
    default => 'default',
};

// Null Safe Operator
$vendorName = $event->vendor?->name;
```

### Eloquent Queries

```php
// GOOD — Eager loading
$orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
    ->where('user_id', $id)
    ->get();

// GOOD — Lock for concurrent access
$ticket = Ticket::where('sku_id', $sku->id)
    ->where('status', 'available')
    ->lockForUpdate()
    ->first();

// BAD — N+1 in loops
foreach ($orders as $order) { echo $order->user->name; }
// GOOD — Eager load first
$orders = Order::with('user')->get();
```

### Response Format (Always Consistent)

```php
// Success
return response()->json([
    'status' => 'success',
    'message' => 'Order created successfully',
    'data' => $order,
], 201);

// Error
return response()->json([
    'status' => 'error',
    'message' => 'Ticket is not available',
], 422);
```

## Dependency Injection

```php
// GOOD — Mockable in tests
$midtrans = app(CreatePaymentUrlService::class);
$client = app(Google_Client::class, ['config' => [...]]);

// BAD — Not mockable
$midtrans = new CreatePaymentUrlService();
$client = new Google_Client([...]);
```

## Anti-Patterns to Avoid

```php
// BAD — Wrong column name
$order->status = $request->status;
// GOOD — Match migration
$order->status_payment = $request->status_payment;

// BAD — Race condition
$ticket = Ticket::where('status', 'available')->first();
// GOOD — Lock for update
$ticket = Ticket::where('status', 'available')->lockForUpdate()->first();

// BAD — Non-unique order ID
$orderId = rand(1000, 9999);
// GOOD — Unique and traceable
$orderId = 'ORDER-' . $order->id . '-' . time();

// BAD — No transaction for multi-table ops
$order = Order::create($data);
// tickets, order_tickets, sku stock... any failure = inconsistent state
// GOOD — Wrap in transaction
DB::transaction(function () { ... });

// BAD — Check user after using
$user = User::where('email', $email)->first();
$token = $user->createToken('auth_token');  // NPE if null!
if ($user) { ... }
// GOOD — Check first
$user = User::where('email', $email)->first();
if ($user) {
    $token = $user->createToken('auth_token');
}
```
