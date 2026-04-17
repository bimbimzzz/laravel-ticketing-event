<?php

namespace Tests\Feature\Web\Ticket;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EticketTest extends TestCase
{
    use RefreshDatabase;

    private function createTicketWithOrder(string $status = 'sold'): array
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['name' => 'Concert ABC']);
        $sku = Sku::factory()->create(['event_id' => $event->id, 'name' => 'VIP']);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'sku_id' => $sku->id,
            'ticket_code' => 'ETICKET001',
            'status' => $status,
        ]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status_payment' => 'success',
        ]);
        OrderTicket::create(['order_id' => $order->id, 'ticket_id' => $ticket->id]);

        return compact('user', 'event', 'sku', 'ticket', 'order');
    }

    public function test_eticket_page_renders_for_owner(): void
    {
        $data = $this->createTicketWithOrder('sold');

        $response = $this->actingAs($data['user'])->get("/tickets/{$data['ticket']->id}");
        $response->assertStatus(200);
        $response->assertSee('ETICKET001');
        $response->assertSee('Concert ABC');
        $response->assertSee('VIP');
    }

    public function test_eticket_shows_qr_code(): void
    {
        $data = $this->createTicketWithOrder('sold');

        $response = $this->actingAs($data['user'])->get("/tickets/{$data['ticket']->id}");
        $response->assertStatus(200);
        $response->assertSee('qrcode');
    }

    public function test_guest_cannot_view_eticket(): void
    {
        $data = $this->createTicketWithOrder('sold');

        $response = $this->get("/tickets/{$data['ticket']->id}");
        $response->assertRedirect('/login');
    }

    public function test_other_user_cannot_view_eticket(): void
    {
        $data = $this->createTicketWithOrder('sold');
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->get("/tickets/{$data['ticket']->id}");
        $response->assertStatus(403);
    }

    public function test_eticket_shows_redeemed_status(): void
    {
        $data = $this->createTicketWithOrder('redeem');

        $response = $this->actingAs($data['user'])->get("/tickets/{$data['ticket']->id}");
        $response->assertStatus(200);
        $response->assertSee('Sudah Digunakan');
    }
}
