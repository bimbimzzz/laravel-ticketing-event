<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class XenditWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable webhook token verification for tests
        config(['xendit.webhook_token' => null]);
    }

    private function createOrderWithTickets(string $statusPayment = 'pending', string $ticketStatus = 'booked'): Order
    {
        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id, 'stock' => 2]);
        $order = Order::factory()->create([
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 200000,
            'status_payment' => $statusPayment,
        ]);

        for ($i = 0; $i < 2; $i++) {
            $ticket = Ticket::factory()->create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'status' => $ticketStatus,
            ]);
            OrderTicket::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
            ]);
        }

        return $order;
    }

    private function webhookPayload(Order $order, string $status = 'PAID'): array
    {
        return [
            'id' => 'inv_test_123',
            'external_id' => "ORDER-{$order->id}-" . time(),
            'status' => $status,
            'amount' => $order->total_price,
        ];
    }

    public function test_paid_marks_order_success_and_tickets_sold(): void
    {
        $order = $this->createOrderWithTickets('pending', 'booked');

        $response = $this->postJson('/api/xendit/webhook', $this->webhookPayload($order, 'PAID'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_payment' => 'success',
        ]);

        foreach ($order->orderTickets as $ot) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ot->ticket_id,
                'status' => 'sold',
            ]);
        }
    }

    public function test_pending_does_not_change_order(): void
    {
        $order = $this->createOrderWithTickets('pending', 'booked');

        $response = $this->postJson('/api/xendit/webhook', $this->webhookPayload($order, 'PENDING'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_payment' => 'pending',
        ]);
    }

    public function test_expired_cancels_order_and_restores_tickets(): void
    {
        $order = $this->createOrderWithTickets('pending', 'booked');
        $sku = $order->orderTickets->first()->ticket->sku;
        $originalStock = $sku->stock;

        $response = $this->postJson('/api/xendit/webhook', $this->webhookPayload($order, 'EXPIRED'));

        $response->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_payment' => 'cancel',
        ]);

        foreach ($order->orderTickets as $ot) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ot->ticket_id,
                'status' => 'available',
            ]);
        }

        $sku->refresh();
        $this->assertEquals($originalStock + 2, $sku->stock);
    }

    public function test_invalid_order_returns_404(): void
    {
        $response = $this->postJson('/api/xendit/webhook', [
            'id' => 'inv_test_123',
            'external_id' => 'ORDER-99999-' . time(),
            'status' => 'PAID',
            'amount' => 100000,
        ]);

        $response->assertStatus(404);
    }
}
