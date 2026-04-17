<?php

namespace Tests\Feature\Policies;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPolicyTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorEvent(): array
    {
        $owner = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $owner->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);

        return compact('owner', 'vendor', 'event');
    }

    public function test_owner_can_update(): void
    {
        $data = $this->createVendorEvent();

        $response = $this->actingAs($data['owner'], 'sanctum')
            ->postJson('/api/event/update/' . $data['event']->id, [
                'vendor_id' => $data['vendor']->id,
                'event_category_id' => $data['event']->event_category_id,
                'name' => 'Updated Event',
                'description' => 'Updated description',
                'start_date' => now()->addDays(7)->format('Y-m-d'),
                'end_date' => now()->addDays(14)->format('Y-m-d'),
            ]);

        $response->assertStatus(200);
    }

    public function test_other_cannot_update(): void
    {
        $data = $this->createVendorEvent();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser, 'sanctum')
            ->postJson('/api/event/update/' . $data['event']->id, [
                'vendor_id' => $data['vendor']->id,
                'event_category_id' => $data['event']->event_category_id,
                'name' => 'Hacked Event',
                'description' => 'Hacked description',
                'start_date' => now()->addDays(7)->format('Y-m-d'),
                'end_date' => now()->addDays(14)->format('Y-m-d'),
            ]);

        $response->assertStatus(403);
    }

    public function test_owner_can_delete(): void
    {
        $data = $this->createVendorEvent();

        $response = $this->actingAs($data['owner'], 'sanctum')
            ->deleteJson('/api/event/' . $data['event']->id);

        $response->assertStatus(200);
        $this->assertDatabaseMissing('events', ['id' => $data['event']->id]);
    }

    public function test_other_cannot_delete(): void
    {
        $data = $this->createVendorEvent();
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser, 'sanctum')
            ->deleteJson('/api/event/' . $data['event']->id);

        $response->assertStatus(403);
        $this->assertDatabaseHas('events', ['id' => $data['event']->id]);
    }
}
