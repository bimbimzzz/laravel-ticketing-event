<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BulkCheckinTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorWithTickets(): array
    {
        $user = User::factory()->create(['is_vendor' => 1]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);
        $sku = Sku::factory()->create(['event_id' => $event->id]);

        $tickets = Ticket::factory()->count(5)->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'sold',
        ]);

        return compact('user', 'vendor', 'event', 'tickets');
    }

    public function test_bulk_checkin_page_loads(): void
    {
        $data = $this->createVendorWithTickets();
        $response = $this->actingAs($data['user'])->get('/vendor/tickets/bulk-check');
        $response->assertStatus(200);
    }

    public function test_bulk_checkin_redeems_multiple_tickets(): void
    {
        $data = $this->createVendorWithTickets();
        $codes = $data['tickets']->take(3)->pluck('ticket_code')->toArray();

        $response = $this->actingAs($data['user'])
            ->post('/vendor/tickets/bulk-check', [
                'ticket_codes' => implode("\n", $codes),
            ]);

        $response->assertRedirect();

        foreach ($data['tickets']->take(3) as $ticket) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ticket->id,
                'status' => 'redeem',
            ]);
        }
        // Remaining 2 tickets should still be sold
        foreach ($data['tickets']->skip(3) as $ticket) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ticket->id,
                'status' => 'sold',
            ]);
        }
    }

    public function test_bulk_checkin_reports_invalid_codes(): void
    {
        $data = $this->createVendorWithTickets();

        $response = $this->actingAs($data['user'])
            ->post('/vendor/tickets/bulk-check', [
                'ticket_codes' => $data['tickets'][0]->ticket_code . "\nINVALIDCODE123",
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('results');
    }

    public function test_bulk_checkin_skips_already_redeemed(): void
    {
        $data = $this->createVendorWithTickets();
        $data['tickets'][0]->update(['status' => 'redeem']);

        $codes = $data['tickets']->take(2)->pluck('ticket_code')->toArray();

        $response = $this->actingAs($data['user'])
            ->post('/vendor/tickets/bulk-check', [
                'ticket_codes' => implode("\n", $codes),
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('results');
    }

    public function test_bulk_checkin_rejects_other_vendor_tickets(): void
    {
        $data = $this->createVendorWithTickets();

        // Create another vendor's ticket
        $otherUser = User::factory()->create(['is_vendor' => 1]);
        $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);
        $otherEvent = Event::factory()->create(['vendor_id' => $otherVendor->id]);
        $otherSku = Sku::factory()->create(['event_id' => $otherEvent->id]);
        $otherTicket = Ticket::factory()->create([
            'sku_id' => $otherSku->id,
            'event_id' => $otherEvent->id,
            'status' => 'sold',
        ]);

        $response = $this->actingAs($data['user'])
            ->post('/vendor/tickets/bulk-check', [
                'ticket_codes' => $otherTicket->ticket_code,
            ]);

        $response->assertRedirect();
        // Other vendor's ticket should NOT be redeemed
        $this->assertDatabaseHas('tickets', [
            'id' => $otherTicket->id,
            'status' => 'sold',
        ]);
    }

    // --- API Tests ---
    public function test_api_bulk_checkin(): void
    {
        $data = $this->createVendorWithTickets();
        $codes = $data['tickets']->take(2)->pluck('ticket_code')->toArray();

        $response = $this->actingAs($data['user'], 'sanctum')
            ->postJson('/api/tickets/bulk-check', [
                'ticket_codes' => $codes,
            ]);

        $response->assertOk()
            ->assertJsonStructure([
                'status',
                'data' => [
                    'success',
                    'failed',
                ],
            ]);
    }
}
