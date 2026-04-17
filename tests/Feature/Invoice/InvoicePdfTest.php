<?php

namespace Tests\Feature\Invoice;

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

class InvoicePdfTest extends TestCase
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
        ]);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'stock' => 10,
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

        $ticket = Ticket::factory()->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'sold',
        ]);
        OrderTicket::create(['order_id' => $order->id, 'ticket_id' => $ticket->id]);

        return compact('buyer', 'order', 'event', 'sku');
    }

    public function test_guest_cannot_download_invoice(): void
    {
        $data = $this->createPaidOrder();
        $response = $this->get("/orders/{$data['order']->id}/invoice");
        $response->assertRedirect('/login');
    }

    public function test_other_user_cannot_download_invoice(): void
    {
        $data = $this->createPaidOrder();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->get("/orders/{$data['order']->id}/invoice");
        $response->assertStatus(403);
    }

    public function test_cannot_download_invoice_for_pending_order(): void
    {
        $data = $this->createPaidOrder();
        $data['order']->update(['status_payment' => 'pending']);

        $response = $this->actingAs($data['buyer'])
            ->get("/orders/{$data['order']->id}/invoice");
        $response->assertStatus(404);
    }

    public function test_owner_can_download_invoice_pdf(): void
    {
        $data = $this->createPaidOrder();

        $response = $this->actingAs($data['buyer'])
            ->get("/orders/{$data['order']->id}/invoice");

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
