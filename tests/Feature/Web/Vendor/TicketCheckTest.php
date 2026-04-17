<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketCheckTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorUser(): User
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_check_page_renders(): void
    {
        $user = $this->createVendorUser();

        $response = $this->actingAs($user)->get('/vendor/tickets/check');
        $response->assertStatus(200);
        $response->assertSee('Validasi Tiket');
    }

    public function test_valid_ticket_is_redeemed(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'sku_id' => $sku->id,
            'ticket_code' => 'VALIDCODE1',
            'status' => 'sold',
        ]);

        $response = $this->actingAs($user)->post('/vendor/tickets/check', [
            'ticket_code' => 'VALIDCODE1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => 'redeem',
        ]);
    }

    public function test_invalid_code_returns_error(): void
    {
        $user = $this->createVendorUser();

        $response = $this->actingAs($user)->post('/vendor/tickets/check', [
            'ticket_code' => 'INVALIDXXX',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tiket tidak ditemukan.');
    }

    public function test_already_redeemed_returns_error(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        Ticket::factory()->create([
            'event_id' => $event->id,
            'sku_id' => $sku->id,
            'ticket_code' => 'REDEEMED01',
            'status' => 'redeem',
        ]);

        $response = $this->actingAs($user)->post('/vendor/tickets/check', [
            'ticket_code' => 'REDEEMED01',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Tiket sudah pernah digunakan.');
    }
}
