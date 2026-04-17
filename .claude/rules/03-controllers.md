---
paths:
  - "app/Http/Controllers/Api/**/*.php"
---
# Controller Standards

## API Controller Pattern

```php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $categoryId = $request->input('category_id');
        $events = $categoryId == 'all'
            ? Event::all()
            : Event::where('event_category_id', $categoryId)->get();

        $events->load('eventCategory', 'vendor');

        return response()->json([
            'status' => 'success',
            'message' => 'Events fetched successfully',
            'data' => $events,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([...]);

        $event = Event::findOrFail($id);
        Gate::authorize('update', $event);  // Authorization check

        $event->update($request->validated());
        return response()->json([
            'status' => 'success',
            'message' => 'Event updated successfully',
            'data' => $event,
        ]);
    }
}
```

## Controller with Transaction (OrderController)

```php
public function create(Request $request)
{
    $request->validate([...]);

    try {
        $result = DB::transaction(function () use ($request) {
            // 1. Check availability with lockForUpdate()
            // 2. Calculate total
            // 3. Create order
            // 4. Book tickets + decrement stock
            // 5. Generate payment URL
            return $order;
        });

        return response()->json([
            'status' => 'success',
            'data' => $result,
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 422);
    }
}
```

## Authorization Patterns

### Resource Ownership (EventPolicy)
```php
// Use Gate::authorize() — NOT $this->authorize()
// Laravel 12 base Controller doesn't include AuthorizesRequests trait
Gate::authorize('update', $event);
Gate::authorize('delete', $event);
```

### User-Scoped Access
```php
// For routes like /orders/user/{id}
if (auth()->id() != $id) {
    return response()->json(['status' => 'error', 'message' => 'Unauthorized'], 403);
}
```

## Validation Rules

```php
// Event creation — image validation
$request->validate([
    'vendor_id' => 'required',
    'event_category_id' => 'required',
    'name' => 'required',
    'description' => 'required',
    'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
    'start_date' => 'required',
    'end_date' => 'required',
]);

// Order creation
$request->validate([
    'event_id' => 'required|exists:events,id',
    'order_details' => 'required|array',
    'order_details.*.sku_id' => 'required|exists:skus,id',
    'quantity' => 'required|integer|min:1',
    'event_date' => 'required',
]);
```

## Best Practices

1. **Use `Gate::authorize()`** for ownership checks (not `$this->authorize()`)
2. **Use `DB::transaction()`** for order creation (atomic operations)
3. **Use `lockForUpdate()`** on ticket queries (prevent race conditions)
4. **Always validate image uploads** — `image|mimes:jpeg,png,jpg,webp|max:5120`
5. **Use `app()` for DI** — `app(CreatePaymentUrlService::class)` instead of `new CreatePaymentUrlService()`
6. **Decrement stock** — `$sku->decrement('stock', $qty)` after booking tickets
7. **Use `status_payment`** — NOT `status` (column name in migration is `status_payment`)
8. **Eager load relationships** — `Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])`
