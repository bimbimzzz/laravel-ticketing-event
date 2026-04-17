<?php

namespace Tests\Feature\Routes;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_accessible_without_auth(): void
    {
        $category = EventCategory::factory()->create();
        Event::factory()->create(['event_category_id' => $category->id]);

        $response = $this->getJson('/api/events?category_id=all');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_authenticated_events_requires_auth(): void
    {
        $response = $this->getJson('/api/events/all');

        $response->assertStatus(401);
    }

    public function test_authenticated_events_returns_grouped_data(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        Ticket::factory()->count(3)->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'available',
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/events/all');

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonStructure([
                'status',
                'data' => [
                    '*' => ['id', 'name', 'vendor', 'event_category', 'tickets'],
                ],
            ]);
    }
}
