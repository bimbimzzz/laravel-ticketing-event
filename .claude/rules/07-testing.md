---
paths:
  - "tests/**/*.php"
  - "database/factories/**/*.php"
  - "phpunit.xml"
---
# Testing Guidelines

## Overview

Project ini menggunakan **PHPUnit 11** dengan **SQLite in-memory** untuk testing. Pendekatan **TDD** (Test-Driven Development) direkomendasikan.

## Test Configuration

```xml
<!-- phpunit.xml -->
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

## Test Structure

```
tests/
├── Feature/
│   ├── Controllers/
│   │   ├── OrderControllerTest.php    # Order creation, atomicity, stock
│   │   ├── EventControllerTest.php    # Image validation
│   │   └── AuthControllerTest.php     # Google OAuth
│   ├── Models/
│   │   └── OrderModelTest.php         # Fillable, relationships
│   ├── Policies/
│   │   ├── EventPolicyTest.php        # Owner can update/delete
│   │   └── OrderPolicyTest.php        # User can view own orders
│   └── Routes/
│       └── EventRoutesTest.php        # Public vs auth routes
└── Unit/
    └── ExampleTest.php
```

## Base Test Setup

```php
<?php

namespace Tests\Feature\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    private function createEventWithTickets(int $ticketCount = 5): array
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'stock' => $ticketCount,
            'price' => 100000,
        ]);
        $tickets = Ticket::factory()->count($ticketCount)->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'available',
        ]);

        return compact('user', 'vendor', 'event', 'sku', 'tickets');
    }
}
```

## Test Naming Convention

```php
// Method naming: test_[scenario_description]
public function test_successful_order_creation(): void { }
public function test_order_is_atomic_rolls_back_on_failure(): void { }
public function test_cannot_book_already_booked_tickets(): void { }
public function test_owner_can_update(): void { }
public function test_other_cannot_update(): void { }
public function test_rejects_non_image(): void { }
public function test_google_login_creates_new_user(): void { }
```

## Mocking External Services

### Midtrans Payment
```php
private function mockMidtrans(): void
{
    $mock = Mockery::mock(CreatePaymentUrlService::class);
    $mock->shouldReceive('getPaymentUrl')
        ->andReturn('https://midtrans.test/payment');
    $this->app->instance(CreatePaymentUrlService::class, $mock);
}
```

### Google Client
```php
private function mockGoogleClient(array $payload): void
{
    $googleClient = Mockery::mock(\Google_Client::class);
    $googleClient->shouldReceive('verifyIdToken')
        ->andReturn($payload);
    $this->app->bind(\Google_Client::class, function () use ($googleClient) {
        return $googleClient;
    });
}
```

## Key Test Patterns

### Authorization Test
```php
public function test_other_cannot_update(): void
{
    $data = $this->createVendorEvent();
    $otherUser = User::factory()->create();

    $response = $this->actingAs($otherUser, 'sanctum')
        ->postJson('/api/event/update/' . $data['event']->id, [...]);

    $response->assertStatus(403);
}
```

### Transaction Atomicity Test
```php
public function test_order_is_atomic_rolls_back_on_failure(): void
{
    $data = $this->createEventWithTickets(1);
    $buyer = User::factory()->create();

    // Request more tickets than available
    $response = $this->actingAs($buyer, 'sanctum')
        ->postJson('/api/order', [
            'order_details' => [['sku_id' => $data['sku']->id, 'qty' => 2]],
            ...
        ]);

    $response->assertStatus(422);
    $this->assertDatabaseCount('orders', 0);       // Rolled back
    $this->assertEquals(1, Ticket::where('status', 'available')->count());
}
```

### Image Validation Test
```php
public function test_rejects_non_image(): void
{
    $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

    $response = $this->actingAs($user, 'sanctum')
        ->postJson('/api/events', ['image' => $file, ...]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['image']);
}
```

## Running Tests

```bash
php artisan test                                    # All tests
php artisan test --filter=OrderControllerTest       # By class
php artisan test --filter=test_successful_order     # By method
```

## Best Practices

1. **Use `RefreshDatabase`** — clean state per test
2. **Use factories** — never manually insert test data
3. **Test one thing per test** — single responsibility
4. **Mock external services** — Midtrans, Google OAuth
5. **Always test authorization** — owner vs non-owner
6. **Test transaction rollback** — ensure atomicity
7. **Test validation rules** — image type/size, required fields
8. **Use `actingAs($user, 'sanctum')`** for authenticated requests
9. **Assert database state** — `assertDatabaseHas`, `assertDatabaseCount`
