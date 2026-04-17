<?php

namespace Tests\Feature\Web\Orders;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected(): void
    {
        $order = Order::factory()->create();

        $response = $this->get('/orders/' . $order->id);
        $response->assertRedirect('/login');
    }

    public function test_page_renders(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['name' => 'Detail Event']);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user)->get('/orders/' . $order->id);
        $response->assertStatus(200);
        $response->assertSee('Detail Event');
    }

    public function test_shows_order_info_and_tickets(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id, 'name' => 'Regular Seat']);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);
        $ticket = Ticket::factory()->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'ticket_code' => 'TKT-TESTCODE',
            'status' => 'booked',
        ]);
        OrderTicket::create([
            'order_id' => $order->id,
            'ticket_id' => $ticket->id,
        ]);

        $response = $this->actingAs($user)->get('/orders/' . $order->id);
        $response->assertStatus(200);
        $response->assertSee('Regular Seat');
        $response->assertSee('TKT-TESTCODE');
    }

    public function test_403_for_other_users_order(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($user)->get('/orders/' . $order->id);
        $response->assertStatus(403);
    }

    public function test_qr_code_for_sold_tickets(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status_payment' => 'success',
        ]);
        $ticket = Ticket::factory()->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'ticket_code' => 'TKT-QRTEST',
            'status' => 'sold',
        ]);
        OrderTicket::create([
            'order_id' => $order->id,
            'ticket_id' => $ticket->id,
        ]);

        $response = $this->actingAs($user)->get('/orders/' . $order->id);
        $response->assertStatus(200);
        $response->assertSee('TKT-QRTEST');
    }
}
