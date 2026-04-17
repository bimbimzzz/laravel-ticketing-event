<?php

namespace Tests\Feature\Web\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Vendor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventListingTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_listing_page_renders(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_displays_events(): void
    {
        $event = Event::factory()->create(['name' => 'Konser Musik Nusantara']);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Konser Musik Nusantara');
    }

    public function test_filter_by_category(): void
    {
        $cat1 = EventCategory::factory()->create(['name' => 'Musik']);
        $cat2 = EventCategory::factory()->create(['name' => 'Olahraga']);

        $vendor = Vendor::factory()->create();
        Event::factory()->create(['name' => 'Event Musik', 'event_category_id' => $cat1->id, 'vendor_id' => $vendor->id]);
        Event::factory()->create(['name' => 'Event Olahraga', 'event_category_id' => $cat2->id, 'vendor_id' => $vendor->id]);

        $response = $this->get('/events?category_id=' . $cat1->id);
        $response->assertStatus(200);
        $response->assertSee('Event Musik');
        $response->assertDontSee('Event Olahraga');
    }

    public function test_search_by_name(): void
    {
        $vendor = Vendor::factory()->create();
        Event::factory()->create(['name' => 'Jazz Festival', 'vendor_id' => $vendor->id]);
        Event::factory()->create(['name' => 'Rock Concert', 'vendor_id' => $vendor->id]);

        $response = $this->get('/events?search=Jazz');
        $response->assertStatus(200);
        $response->assertSee('Jazz Festival');
        $response->assertDontSee('Rock Concert');
    }

    public function test_accessible_to_guests(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_shows_categories(): void
    {
        EventCategory::factory()->create(['name' => 'Konser']);

        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Konser');
    }
}
