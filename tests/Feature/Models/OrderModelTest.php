<?php

namespace Tests\Feature\Models;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_order_uses_status_payment_field(): void
    {
        $order = Order::factory()->create(['status_payment' => 'pending']);

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status_payment' => 'pending',
        ]);

        $this->assertTrue(in_array('status_payment', $order->getFillable()));
        $this->assertFalse(in_array('status', $order->getFillable()));
    }

    public function test_order_has_no_direct_sku_relationship(): void
    {
        $order = new Order();

        $this->assertFalse(method_exists($order, 'sku'));
    }

    public function test_order_has_many_order_tickets(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $ticket = Ticket::factory()->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
        ]);

        OrderTicket::create([
            'order_id' => $order->id,
            'ticket_id' => $ticket->id,
        ]);

        $this->assertCount(1, $order->orderTickets);
        $this->assertEquals($sku->id, $order->orderTickets->first()->ticket->sku->id);
    }
}
