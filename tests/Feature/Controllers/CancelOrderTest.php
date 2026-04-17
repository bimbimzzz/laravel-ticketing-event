<?php

namespace Tests\Feature\Controllers;

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

class CancelOrderTest extends TestCase
{
    use RefreshDatabase;

    private function createPaidOrder(array $overrides = []): array
    {
        $buyer = User::factory()->create();
        $vendorUser = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(11),
        ]);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'stock' => 0, // already sold
            'price' => 100000,
        ]);

        $order = Order::factory()->paid()->create(array_merge([
            'user_id' => $buyer->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 200000,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
        ], $overrides));

        $tickets = Ticket::factory()->count(2)->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'sold',
        ]);

        foreach ($tickets as $ticket) {
            OrderTicket::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
            ]);
        }

        return compact('buyer', 'vendor', 'event', 'sku', 'order', 'tickets');
    }

    public function test_buyer_can_cancel_paid_order_within_deadline(): void
    {
        $data = $this->createPaidOrder();

        $response = $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'refund_pending',
        ]);
    }

    public function test_cannot_cancel_order_past_deadline(): void
    {
        $data = $this->createPaidOrder();
        // Set event start_date to 2 days from now (within H-3 deadline)
        $data['event']->update(['start_date' => now()->addDays(2)]);

        $response = $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'success',
        ]);
    }

    public function test_cannot_cancel_other_users_order(): void
    {
        $data = $this->createPaidOrder();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser, 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'success',
        ]);
    }

    public function test_cannot_cancel_pending_order_via_cancel_endpoint(): void
    {
        $data = $this->createPaidOrder();
        $data['order']->update(['status_payment' => 'pending']);

        $response = $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        $response->assertStatus(422);
    }

    public function test_cannot_cancel_already_cancelled_order(): void
    {
        $data = $this->createPaidOrder();
        $data['order']->update(['status_payment' => 'refund_pending']);

        $response = $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        $response->assertStatus(422);
    }

    public function test_cannot_cancel_if_ticket_already_redeemed(): void
    {
        $data = $this->createPaidOrder();
        // Mark one ticket as redeemed
        $data['tickets'][0]->update(['status' => 'redeem']);

        $response = $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'success',
        ]);
    }

    public function test_cancel_restores_ticket_status_and_stock(): void
    {
        $data = $this->createPaidOrder();

        $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", [
                'cancel_reason' => 'Saya tidak bisa hadir di acara tersebut karena ada urusan mendadak.',
            ]);

        // Tickets should be available again
        foreach ($data['tickets'] as $ticket) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ticket->id,
                'status' => 'available',
            ]);
        }

        // Stock should be restored (+2)
        $this->assertEquals(2, $data['sku']->fresh()->stock);
    }

    public function test_cancel_requires_reason(): void
    {
        $data = $this->createPaidOrder();

        $response = $this->actingAs($data['buyer'], 'sanctum')
            ->postJson("/api/orders/{$data['order']->id}/cancel", []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('cancel_reason');
    }
}
