<?php

namespace Tests\Feature\Web\Events;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sku;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventSearchFilterTest extends TestCase
{
    use RefreshDatabase;

    private function seedEvents(): array
    {
        $vendor = Vendor::factory()->create();
        $catMusic = EventCategory::factory()->create(['name' => 'Musik']);
        $catSport = EventCategory::factory()->create(['name' => 'Olahraga']);

        $ev1 = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $catMusic->id,
            'name' => 'Rock Concert Jakarta',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
        ]);
        Sku::factory()->create(['event_id' => $ev1->id, 'price' => 100000]);

        $ev2 = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $catSport->id,
            'name' => 'Marathon Bandung',
            'start_date' => now()->addDays(14),
            'end_date' => now()->addDays(15),
        ]);
        Sku::factory()->create(['event_id' => $ev2->id, 'price' => 50000]);

        $ev3 = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $catMusic->id,
            'name' => 'Jazz Festival Surabaya',
            'start_date' => now()->subDays(7),
            'end_date' => now()->subDays(1),
        ]);
        Sku::factory()->create(['event_id' => $ev3->id, 'price' => 200000]);

        return compact('vendor', 'catMusic', 'catSport', 'ev1', 'ev2', 'ev3');
    }

    public function test_events_page_loads(): void
    {
        $this->seedEvents();
        $response = $this->get('/events');
        $response->assertStatus(200);
    }

    public function test_search_by_name(): void
    {
        $this->seedEvents();
        $response = $this->get('/events?search=Rock');
        $response->assertStatus(200);
        $response->assertSee('Rock Concert Jakarta');
        $response->assertDontSee('Marathon Bandung');
    }

    public function test_filter_by_category(): void
    {
        $data = $this->seedEvents();
        $response = $this->get('/events?category_id=' . $data['catSport']->id);
        $response->assertStatus(200);
        $response->assertSee('Marathon Bandung');
        $response->assertDontSee('Rock Concert Jakarta');
    }

    public function test_filter_by_status_upcoming(): void
    {
        $this->seedEvents();
        $response = $this->get('/events?status=upcoming');
        $response->assertStatus(200);
        $response->assertSee('Rock Concert Jakarta');
        $response->assertDontSee('Jazz Festival Surabaya');
    }

    public function test_filter_by_status_past(): void
    {
        $this->seedEvents();
        $response = $this->get('/events?status=past');
        $response->assertStatus(200);
        $response->assertSee('Jazz Festival Surabaya');
        $response->assertDontSee('Rock Concert Jakarta');
    }

    public function test_filter_by_price_range(): void
    {
        $this->seedEvents();
        $response = $this->get('/events?min_price=60000&max_price=150000');
        $response->assertStatus(200);
        $response->assertSee('Rock Concert Jakarta');
        $response->assertDontSee('Marathon Bandung');
    }

    public function test_combined_filters(): void
    {
        $data = $this->seedEvents();
        $response = $this->get('/events?category_id=' . $data['catMusic']->id . '&status=upcoming');
        $response->assertStatus(200);
        $response->assertSee('Rock Concert Jakarta');
        $response->assertDontSee('Jazz Festival Surabaya');
        $response->assertDontSee('Marathon Bandung');
    }

    // --- API Tests ---
    public function test_api_events_filter_by_category(): void
    {
        $data = $this->seedEvents();
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/events?category_id=' . $data['catSport']->id);

        $response->assertOk();
        $events = collect($response->json('data'));
        $this->assertTrue($events->every(fn($e) => $e['event_category']['id'] == $data['catSport']->id));
    }

    public function test_api_events_search_by_name(): void
    {
        $this->seedEvents();
        $user = \App\Models\User::factory()->create();

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/events?search=Marathon');

        $response->assertOk();
        $events = collect($response->json('data'));
        $this->assertTrue($events->contains(fn($e) => str_contains($e['name'], 'Marathon')));
    }
}
