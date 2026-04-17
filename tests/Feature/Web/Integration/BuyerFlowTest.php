<?php

namespace Tests\Feature\Web\Integration;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BuyerFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_buyer_flow_browse_to_order(): void
    {
        // Setup: vendor with event and tickets
        $vendorUser = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $category = EventCategory::factory()->create(['name' => 'Musik']);
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
            'name' => 'Flow Test Event',
        ]);
        $sku = Sku::factory()->create(['event_id' => $event->id, 'stock' => 3, 'price' => 100000]);
        for ($i = 0; $i < 3; $i++) {
            Ticket::factory()->create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'status' => 'available',
            ]);
        }

        $buyer = User::factory()->create();

        // Step 1: Browse events
        $response = $this->actingAs($buyer)->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Flow Test Event');

        // Step 2: View event detail
        $response = $this->actingAs($buyer)->get("/events/{$event->id}");
        $response->assertStatus(200);
        $response->assertSee('Flow Test Event');
        $response->assertSee($sku->name);

        // Step 3: View orders (should be empty)
        $response = $this->actingAs($buyer)->get('/orders');
        $response->assertStatus(200);
        $response->assertSee('Belum ada pesanan');
    }

    public function test_full_vendor_flow_create_to_manage(): void
    {
        // Register as vendor
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id, 'verify_status' => 'approved']);
        $category = EventCategory::factory()->create(['name' => 'Seminar']);

        // Step 1: Access vendor dashboard
        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');

        // Step 2: Create event
        $response = $this->actingAs($user)->get('/vendor/events/create');
        $response->assertStatus(200);
        $response->assertSee('Buat Event');

        // Step 3: List vendor events
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Vendor Flow Event',
        ]);

        $response = $this->actingAs($user)->get('/vendor/events');
        $response->assertStatus(200);
        $response->assertSee('Vendor Flow Event');

        // Step 4: Manage SKUs
        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/skus");
        $response->assertStatus(200);

        // Step 5: Create SKU
        $response = $this->actingAs($user)->post("/vendor/events/{$event->id}/skus", [
            'name' => 'Regular',
            'category' => 'Standard',
            'price' => 50000,
            'stock' => 10,
            'day_type' => 'weekday',
        ]);
        $response->assertRedirect("/vendor/events/{$event->id}/skus");
        $this->assertDatabaseCount('tickets', 10);

        // Step 6: Check ticket validation page
        $response = $this->actingAs($user)->get('/vendor/tickets/check');
        $response->assertStatus(200);
        $response->assertSee('Validasi Tiket');
    }

    public function test_webhook_completes_order_cycle(): void
    {
        config(['xendit.webhook_token' => null]);
        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id, 'stock' => 5]);
        $buyer = User::factory()->create();

        // Create tickets
        $tickets = [];
        for ($i = 0; $i < 2; $i++) {
            $tickets[] = Ticket::factory()->create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'status' => 'booked',
            ]);
        }

        // Create pending order
        $order = Order::factory()->create([
            'user_id' => $buyer->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 200000,
            'status_payment' => 'pending',
        ]);

        foreach ($tickets as $ticket) {
            \App\Models\OrderTicket::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
            ]);
        }

        // Webhook: Xendit PAID
        $response = $this->postJson('/api/xendit/webhook', [
            'id' => 'inv_test_123',
            'external_id' => "ORDER-{$order->id}-" . time(),
            'status' => 'PAID',
            'amount' => 200000,
        ]);
        $response->assertStatus(200);

        // Verify order success
        $this->assertDatabaseHas('orders', ['id' => $order->id, 'status_payment' => 'success']);

        // Verify tickets sold
        foreach ($tickets as $ticket) {
            $this->assertDatabaseHas('tickets', ['id' => $ticket->id, 'status' => 'sold']);
        }

        // Buyer can view order
        $response = $this->actingAs($buyer)->get("/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertSee('Berhasil');
    }
}
