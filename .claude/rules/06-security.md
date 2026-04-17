---
paths:
  - "app/**/*.php"
  - "routes/**/*.php"
  - "config/**/*.php"
---
# Security Guidelines

## Vendor Ownership (CRITICAL)

### ALWAYS verify ownership before update/delete:
```php
// GOOD — Use Gate::authorize() with EventPolicy
$event = Event::findOrFail($id);
Gate::authorize('update', $event);

// Policy checks vendor->user_id === auth user
class EventPolicy
{
    public function update(User $user, Event $event): bool
    {
        return $event->vendor->user_id === $user->id;
    }
}
```

### User-Scoped Data Access
```php
// GOOD — Verify authenticated user matches requested user_id
if (auth()->id() != $id) {
    return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
}

// BAD — Anyone can view any user's orders
$orders = Order::where('user_id', $id)->get();
```

## Concurrent Ticket Booking (Race Condition Prevention)

```php
// ALWAYS use transaction + lockForUpdate for ticket booking
DB::transaction(function () use ($request) {
    $availableCount = Ticket::where('sku_id', $sku->id)
        ->where('status', 'available')
        ->lockForUpdate()     // ← Prevents double booking
        ->count();

    if ($qty > $availableCount) {
        throw new \Exception('Insufficient tickets');
    }

    // Book tickets...
    $sku->decrement('stock', $qty);  // ← Atomic stock decrement
});
```

## Input Validation

### Image Upload
```php
$request->validate([
    'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',  // 5MB max
]);
```

### Order Details
```php
$request->validate([
    'event_id' => 'required|exists:events,id',
    'order_details' => 'required|array',
    'order_details.*.sku_id' => 'required|exists:skus,id',
    'quantity' => 'required|integer|min:1',
]);
```

## Payment Security (Midtrans)

### Unique Order ID
```php
// GOOD — Unique, traceable
$orderId = 'ORDER-' . $order->id . '-' . time();

// BAD — Collisions possible
$orderId = rand(1000, 9999);
```

### Use Dependency Injection
```php
// GOOD — Testable, mockable
$midtrans = app(CreatePaymentUrlService::class);

// BAD — Untestable
$midtrans = new CreatePaymentUrlService();
```

## Google OAuth

### Check user existence BEFORE creating token
```php
// GOOD
$user = User::where('email', $payload['email'])->first();
if ($user) {
    $token = $user->createToken('auth_token')->plainTextToken;
    // return existing user
} else {
    $user = User::create([...]);
    $token = $user->createToken('auth_token')->plainTextToken;
    // return new user
}

// BAD — Crashes if user is null
$user = User::where('email', $payload['email'])->first();
$token = $user->createToken('auth_token')->plainTextToken;  // ← NPE!
if ($user) { ... }
```

## Environment & Config

```php
// GOOD — Via config
$serverKey = config('midtrans.server_key');

// BAD — Direct env() outside config
$serverKey = env('MIDTRANS_SERVER_KEY');
```

## Security Checklist

- [ ] All update/delete routes check resource ownership (EventPolicy)
- [ ] User-scoped routes verify `auth()->id() == $id`
- [ ] Ticket booking uses `DB::transaction()` + `lockForUpdate()`
- [ ] SKU stock decremented atomically after booking
- [ ] Image uploads validated (type + size)
- [ ] Midtrans order ID is unique (not `rand()`)
- [ ] Google login checks user existence before token creation
- [ ] Config values via `config()` not `env()`
