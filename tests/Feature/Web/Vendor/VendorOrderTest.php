<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorOrderTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorUser(): User
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_list_orders_for_event(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);
        $buyer = User::factory()->create(['name' => 'John Buyer']);
        Order::factory()->create([
            'event_id' => $event->id,
            'user_id' => $buyer->id,
        ]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/orders");
        $response->assertStatus(200);
        $response->assertSee('John Buyer');
    }

    public function test_show_order_detail(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);
        $buyer = User::factory()->create(['name' => 'Jane Buyer']);
        $order = Order::factory()->create([
            'event_id' => $event->id,
            'user_id' => $buyer->id,
        ]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/orders/{$order->id}");
        $response->assertStatus(200);
        $response->assertSee('Jane Buyer');
    }

    public function test_cannot_see_other_vendors_orders(): void
    {
        $user = $this->createVendorUser();
        $otherVendor = Vendor::factory()->create();
        $event = Event::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/orders");
        $response->assertStatus(403);
    }
}
