---
paths:
  - "app/Services/Midtrans/**/*.php"
  - "config/midtrans.php"
---
# Payment Integration (Midtrans)

## Architecture

```
app/Services/Midtrans/
├── Midtrans.php                  # Base class — configures Midtrans SDK
└── CreatePaymentUrlService.php   # Generates Snap payment URL
```

## Configuration

```php
// config/midtrans.php
return [
    'merchant_id' => env('MIDTRANS_MERCHANT_ID'),  // ← NOT 'mercant_id'
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'is_production' => env('IS_PRODUCTION'),
    'is_sanitized' => false,
    'is_3ds' => false,
];
```

## Payment Flow

```
1. Buyer creates order → OrderController::create()
2. Order created in DB::transaction()
3. CreatePaymentUrlService::getPaymentUrl() generates Snap URL
4. Snap URL returned to Flutter app
5. Buyer completes payment in Midtrans
6. (TODO) Webhook callback updates order status_payment
```

## Order ID Format

```php
// MUST be unique — used as Midtrans transaction identifier
$orderId = 'ORDER-' . $order->id . '-' . time();

// Example: ORDER-42-1709901234
```

## Payment URL Generation

```php
$midtrans = app(CreatePaymentUrlService::class);  // ← Use DI, not `new`
$paymentUrl = $midtrans->getPaymentUrl($orderDetails, $order);
$order->update(['payment_url' => $paymentUrl]);
```

## Testing

Mock `CreatePaymentUrlService` in tests — never call real Midtrans API:

```php
$mock = Mockery::mock(CreatePaymentUrlService::class);
$mock->shouldReceive('getPaymentUrl')
    ->andReturn('https://midtrans.test/payment');
$this->app->instance(CreatePaymentUrlService::class, $mock);
```

## Status Mapping

| Midtrans Status | `status_payment` |
|----------------|------------------|
| pending | `pending` |
| settlement/capture | `success` |
| cancel/deny/expire | `cancel` |

## Important Notes

1. **Config key is `merchant_id`** — not `mercant_id` (typo was fixed)
2. **Use `app()` for DI** — makes service mockable in tests
3. **Order ID must be unique** — `ORDER-{id}-{timestamp}` format
4. **Always use `status_payment`** — not `status` when updating orders
5. **Wrap payment in transaction** — rolls back order if payment URL generation fails
