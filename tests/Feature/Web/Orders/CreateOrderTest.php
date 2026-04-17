<?php

namespace Tests\Feature\Web\Orders;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Xendit\XenditPaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class CreateOrderTest extends TestCase
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

    private function mockMidtrans(): void
    {
        $mock = Mockery::mock(XenditPaymentService::class);
        $mock->shouldReceive('createInvoice')
            ->andReturn('https://xendit.test/payment');
        $this->app->instance(XenditPaymentService::class, $mock);
    }

    public function test_guest_is_redirected(): void
    {
        $event = Event::factory()->create();

        $response = $this->post('/events/' . $event->id . '/order', []);
        $response->assertRedirect('/login');
    }

    public function test_valid_order_redirects_to_payment(): void
    {
        $this->mockMidtrans();
        $data = $this->createEventWithTickets(5);
        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer)->post('/events/' . $data['event']->id . '/order', [
            'order_details' => [
                ['sku_id' => $data['sku']->id, 'qty' => 2],
            ],
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertRedirect('https://xendit.test/payment');
    }

    public function test_validates_required_fields(): void
    {
        $data = $this->createEventWithTickets(5);
        $buyer = User::factory()->create();

        $response = $this->actingAs($buyer)->post('/events/' . $data['event']->id . '/order', []);
        $response->assertSessionHasErrors(['order_details', 'event_date']);
    }

    public function test_fails_when_no_tickets_available(): void
    {
        $data = $this->createEventWithTickets(1);
        $buyer = User::factory()->create();

        // Book all tickets
        foreach ($data['tickets'] as $ticket) {
            $ticket->update(['status' => 'booked']);
        }

        $response = $this->actingAs($buyer)->post('/events/' . $data['event']->id . '/order', [
            'order_details' => [
                ['sku_id' => $data['sku']->id, 'qty' => 1],
            ],
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        // Should fail - redirect back with error
        $this->assertDatabaseCount('orders', 0);
    }

    public function test_creates_order_tickets_and_books_tickets(): void
    {
        $this->mockMidtrans();
        $data = $this->createEventWithTickets(5);
        $buyer = User::factory()->create();

        $this->actingAs($buyer)->post('/events/' . $data['event']->id . '/order', [
            'order_details' => [
                ['sku_id' => $data['sku']->id, 'qty' => 2],
            ],
            'event_date' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $this->assertDatabaseHas('orders', [
            'user_id' => $buyer->id,
            'event_id' => $data['event']->id,
            'status_payment' => 'pending',
            'total_price' => 200000,
        ]);

        $this->assertEquals(2, Ticket::where('sku_id', $data['sku']->id)->where('status', 'booked')->count());
        $this->assertEquals(3, $data['sku']->fresh()->stock);
    }
}
