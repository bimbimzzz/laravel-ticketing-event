---
paths:
  - "routes/api.php"
  - "app/Http/Controllers/Api/**/*.php"
---
# API Standards

## Route Structure

```php
// routes/api.php — ALL routes are API (no web views)

// Public (no auth)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/login/google', [AuthController::class, 'loginGoogle']);
Route::get('/events', [EventController::class, 'index']);              // Public event listing
Route::get('/event-categories', [EventController::class, 'categories']);
Route::get('/event/{event_id}', [EventController::class, 'detail']);
Route::post('/check-ticket', [TicketController::class, 'checkTicketValid']);

// Authenticated (auth:sanctum)
Route::get('/events/all', [EventController::class, 'getAllEvents']);    // With grouped tickets
Route::post('/events', [EventController::class, 'create']);
Route::post('/event/update/{event_id}', [EventController::class, 'update']);
Route::delete('/event/{event_id}', [EventController::class, 'delete']);
Route::post('/order', [OrderController::class, 'create']);
Route::put('/orders/{id}', [OrderController::class, 'updateStatus']);
Route::get('/orders/user/{id}', [OrderController::class, 'getOrderByUserId']);
Route::get('/orders/user/{id}/vendor', [OrderController::class, 'getOrderByVendor']);
```

## Response Format

### Success
```json
{
    "status": "success",
    "message": "Event created successfully",
    "data": { ... }
}
```

### Success List
```json
{
    "status": "success",
    "message": "Events fetched successfully",
    "data": [ ... ]
}
```

### Error
```json
{
    "status": "error",
    "message": "Ticket is not available. Only 2 tickets left."
}
```

### HTTP Status Codes
| Code | Usage |
|------|-------|
| `200` | Success (get, update) |
| `201` | Created (store) |
| `400` | Bad request (invalid token) |
| `401` | Unauthenticated |
| `403` | Unauthorized (wrong owner) |
| `404` | Not found |
| `422` | Validation error / business logic error |
| `500` | Server error |

## Route Naming Conventions

| Pattern | Example | Auth |
|---------|---------|------|
| Public listing | `GET /events` | No |
| Detail | `GET /event/{id}` | No |
| Create | `POST /events` | Yes |
| Update | `POST /event/update/{id}` | Yes |
| Delete | `DELETE /event/{id}` | Yes |
| User-scoped | `GET /orders/user/{id}` | Yes |
| Vendor-scoped | `GET /orders/user/{id}/vendor` | Yes |

## Important Rules

1. **Public vs Auth routes** — Never duplicate a route path with different middleware (Bug #2 was caused by this)
2. **Use `auth:sanctum`** middleware for all authenticated routes
3. **Validate all input** — even in API controllers
4. **Use `Gate::authorize()`** for resource ownership checks (Laravel 12 has no `AuthorizesRequests` trait in base Controller)
5. **Always return JSON** — never return views from API controllers
6. **Use `DB::transaction()`** for multi-table operations (orders)
7. **Use `lockForUpdate()`** for concurrent ticket booking
