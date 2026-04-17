<?php

namespace Tests\Feature\Refund;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefundTest extends TestCase
{
    use RefreshDatabase;

    private function createPaidOrder(): array
    {
        $vendorUser = User::factory()->create(['is_vendor' => 1]);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
            'start_date' => now()->addDays(14),
            'end_date' => now()->addDays(21),
        ]);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'stock' => 8,
            'price' => 100000,
        ]);
        $buyer = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $buyer->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 200000,
            'status_payment' => 'success',
        ]);

        $tickets = [];
        for ($i = 0; $i < 2; $i++) {
            $ticket = Ticket::factory()->create([
                'sku_id' => $sku->id,
                'event_id' => $event->id,
                'status' => 'sold',
            ]);
            OrderTicket::create(['order_id' => $order->id, 'ticket_id' => $ticket->id]);
            $tickets[] = $ticket;
        }

        return compact('buyer', 'vendorUser', 'vendor', 'event', 'sku', 'order', 'tickets');
    }

    public function test_buyer_can_request_cancellation(): void
    {
        $data = $this->createPaidOrder();

        $response = $this->actingAs($data['buyer'])
            ->post("/orders/{$data['order']->id}/cancel");

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'cancel',
        ]);
    }

    public function test_tickets_restored_on_cancellation(): void
    {
        $data = $this->createPaidOrder();

        $this->actingAs($data['buyer'])
            ->post("/orders/{$data['order']->id}/cancel");

        foreach ($data['tickets'] as $ticket) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ticket->id,
                'status' => 'available',
            ]);
        }

        // Stock should be restored
        $this->assertEquals(10, $data['sku']->fresh()->stock);
    }

    public function test_cannot_cancel_already_cancelled_order(): void
    {
        $data = $this->createPaidOrder();
        $data['order']->update(['status_payment' => 'cancel']);

        $response = $this->actingAs($data['buyer'])
            ->post("/orders/{$data['order']->id}/cancel");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_cannot_cancel_order_for_past_event(): void
    {
        $data = $this->createPaidOrder();
        $data['event']->update([
            'start_date' => now()->subDays(7),
            'end_date' => now()->subDays(1),
        ]);

        $response = $this->actingAs($data['buyer'])
            ->post("/orders/{$data['order']->id}/cancel");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    public function test_other_user_cannot_cancel_order(): void
    {
        $data = $this->createPaidOrder();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->post("/orders/{$data['order']->id}/cancel");

        $response->assertStatus(403);
    }

    public function test_cannot_cancel_order_with_redeemed_tickets(): void
    {
        $data = $this->createPaidOrder();
        $data['tickets'][0]->update(['status' => 'redeem']);

        $response = $this->actingAs($data['buyer'])
            ->post("/orders/{$data['order']->id}/cancel");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
