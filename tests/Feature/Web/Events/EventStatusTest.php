<?php

namespace Tests\Feature\Web\Events;

use App\Models\Event;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventStatusTest extends TestCase
{
    use RefreshDatabase;

    public function test_upcoming_event_shows_badge(): void
    {
        $event = Event::factory()->create([
            'name' => 'Future Event',
            'start_date' => now()->addDays(7)->format('Y-m-d'),
            'end_date' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Future Event');
    }

    public function test_past_event_shows_on_listing(): void
    {
        $event = Event::factory()->create([
            'name' => 'Past Event',
            'start_date' => now()->subDays(14)->format('Y-m-d'),
            'end_date' => now()->subDays(7)->format('Y-m-d'),
        ]);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Past Event');
    }

    public function test_event_detail_shows_status(): void
    {
        $event = Event::factory()->create([
            'name' => 'Ongoing Event',
            'start_date' => now()->subDays(1)->format('Y-m-d'),
            'end_date' => now()->addDays(5)->format('Y-m-d'),
        ]);

        $response = $this->get("/events/{$event->id}");
        $response->assertStatus(200);
        $response->assertSee('Berlangsung');
    }

    public function test_vendor_events_show_status(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);
        $vendor = $user->vendor;

        Event::factory()->create([
            'vendor_id' => $vendor->id,
            'name' => 'Vendor Future',
            'start_date' => now()->addDays(7)->format('Y-m-d'),
            'end_date' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $response = $this->actingAs($user)->get('/vendor/events');
        $response->assertStatus(200);
        $response->assertSee('Akan Datang');
    }

    public function test_past_event_cannot_be_ordered(): void
    {
        $event = Event::factory()->create([
            'start_date' => now()->subDays(14)->format('Y-m-d'),
            'end_date' => now()->subDays(7)->format('Y-m-d'),
        ]);

        $response = $this->get("/events/{$event->id}");
        $response->assertStatus(200);
        $response->assertSee('Selesai');
    }
}
