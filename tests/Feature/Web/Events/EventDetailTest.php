<?php

namespace Tests\Feature\Web\Events;

use App\Models\Event;
use App\Models\Sku;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDetailTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_detail_page_renders(): void
    {
        $event = Event::factory()->create(['name' => 'Festival Seni']);

        $response = $this->get('/events/' . $event->id);
        $response->assertStatus(200);
        $response->assertSee('Festival Seni');
    }

    public function test_shows_event_info_and_skus(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['name' => 'Konser Besar']);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'name' => 'VIP Ticket',
            'price' => 250000,
        ]);

        $response = $this->actingAs($user)->get('/events/' . $event->id);
        $response->assertStatus(200);
        $response->assertSee('Konser Besar');
        $response->assertSee('VIP Ticket');
    }

    public function test_buy_button_for_auth_user(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sku::factory()->create(['event_id' => $event->id]);

        $response = $this->actingAs($user)->get('/events/' . $event->id);
        $response->assertStatus(200);
        $response->assertSee('Pilih Tiket');
    }

    public function test_login_prompt_for_guest(): void
    {
        $event = Event::factory()->create();

        $response = $this->get('/events/' . $event->id);
        $response->assertStatus(200);
        $response->assertSee('Masuk untuk membeli tiket');
    }

    public function test_404_for_nonexistent_event(): void
    {
        $response = $this->get('/events/99999');
        $response->assertStatus(404);
    }
}
