<?php

namespace Tests\Feature\Policies;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderPolicyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_own_orders(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $event = Event::factory()->create(['vendor_id' => $vendor->id]);

        Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/orders/user/' . $user->id);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success');
    }

    public function test_cannot_view_other_orders(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();

        Order::factory()->create([
            'user_id' => $otherUser->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user, 'sanctum')
            ->getJson('/api/orders/user/' . $otherUser->id);

        $response->assertStatus(403);
    }
}
