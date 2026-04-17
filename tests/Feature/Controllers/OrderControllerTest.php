<?php

namespace Tests\Feature\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Midtrans\CreatePaymentUrlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
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

    public function test_successful_order_creation(): void
    {
        $this->mockMidtrans();

        $data = $this->createEventWithTickets(5);
        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/order', [
                'event_id' => $data['event']->id,
                'order_details' => [
                    ['sku_id' => $data['sku']->id, 'qty' => 2],
                ],
                'quantity' => 2,
                'event_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'event_id' => $data['event']->id,
            'status_payment' => 'pending',
            'total_price' => 200000,
        ]);

        // Check tickets are booked
        $this->assertEquals(2, Ticket::where('sku_id', $data['sku']->id)->where('status', 'booked')->count());
        // Check stock decremented
        $this->assertEquals(3, $data['sku']->fresh()->stock);
    }

    public function test_order_is_atomic_rolls_back_on_failure(): void
    {
        // Create event with only 1 ticket but request 2 via a sku that exists + one that doesn't
        $data = $this->createEventWithTickets(1);
        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/order', [
                'event_id' => $data['event']->id,
                'order_details' => [
                    ['sku_id' => $data['sku']->id, 'qty' => 2],
                ],
                'quantity' => 2,
                'event_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

        $response->assertStatus(422);

        // No order should be created
        $this->assertDatabaseCount('orders', 0);
        // Tickets should remain available
        $this->assertEquals(1, Ticket::where('status', 'available')->count());
    }

    public function test_cannot_book_already_booked_tickets(): void
    {
        $this->mockMidtrans();

        $data = $this->createEventWithTickets(2);
        $buyer = User::factory()->create();

        // Book all tickets first
        foreach ($data['tickets'] as $ticket) {
            $ticket->update(['status' => 'booked']);
        }

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/order', [
                'event_id' => $data['event']->id,
                'order_details' => [
                    ['sku_id' => $data['sku']->id, 'qty' => 1],
                ],
                'quantity' => 1,
                'event_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

        $response->assertStatus(422);
    }

    public function test_sku_stock_decremented(): void
    {
        $this->mockMidtrans();

        $data = $this->createEventWithTickets(10);
        $buyer = User::factory()->create();

        $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/order', [
                'event_id' => $data['event']->id,
                'order_details' => [
                    ['sku_id' => $data['sku']->id, 'qty' => 3],
                ],
                'quantity' => 3,
                'event_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

        $this->assertEquals(7, $data['sku']->fresh()->stock);
    }

    public function test_insufficient_tickets_returns_error(): void
    {
        $data = $this->createEventWithTickets(2);
        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/order', [
                'event_id' => $data['event']->id,
                'order_details' => [
                    ['sku_id' => $data['sku']->id, 'qty' => 5],
                ],
                'quantity' => 5,
                'event_date' => now()->addDays(7)->format('Y-m-d'),
            ]);

        $response->assertStatus(422);
    }

    private function mockMidtrans(): void
    {
        $mock = Mockery::mock(CreatePaymentUrlService::class);
        $mock->shouldReceive('getPaymentUrl')
            ->andReturn('https://midtrans.test/payment');
        $this->app->instance(CreatePaymentUrlService::class, $mock);
    }
}
