<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventApiContractTest extends TestCase
{
    use RefreshDatabase;

    private function createAuthToken(): array
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return [$user, $vendor, $token];
    }

    public function test_get_events_returns_grouped_tickets(): void
    {
        [$user, $vendor, $token] = $this->createAuthToken();

        $category = EventCategory::factory()->create(['name' => 'Musik']);
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
            'name' => 'Test Concert',
        ]);
        $sku = Sku::factory()->create(['event_id' => $event->id, 'name' => 'VIP', 'stock' => 5]);

        for ($i = 0; $i < 5; $i++) {
            Ticket::factory()->create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'status' => $i < 3 ? 'available' : 'sold',
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/events');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id', 'name', 'description', 'image', 'start_date', 'end_date',
                    'vendor' => ['id', 'user_id', 'name'],
                    'event_category' => ['id', 'name'],
                    'tickets' => [
                        '*' => [
                            'sku' => ['id', 'name', 'category', 'price', 'stock', 'day_type'],
                            'ticket_count',
                        ],
                    ],
                ],
            ],
        ]);

        // Verify ticket_count only counts available tickets
        $ticketData = $response->json('data.0.tickets.0');
        $this->assertEquals(3, $ticketData['ticket_count']);
    }

    public function test_get_events_by_user_returns_grouped_tickets(): void
    {
        [$user, $vendor, $token] = $this->createAuthToken();

        $event = Event::factory()->create(['vendor_id' => $vendor->id]);
        $sku = Sku::factory()->create(['event_id' => $event->id, 'stock' => 3]);
        for ($i = 0; $i < 3; $i++) {
            Ticket::factory()->create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'status' => 'available',
            ]);
        }

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/events/user/{$user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => [
                '*' => [
                    'id', 'name', 'vendor', 'event_category',
                    'tickets' => ['*' => ['sku', 'ticket_count']],
                ],
            ],
        ]);
    }

    public function test_get_event_categories_returns_correct_format(): void
    {
        [$user, $vendor, $token] = $this->createAuthToken();
        EventCategory::factory()->create(['name' => 'Seminar']);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson('/api/event-categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => ['*' => ['id', 'name', 'description']],
        ]);
    }
}
