<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\Sku;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkuManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorUser(): User
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_list_skus(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);
        Sku::factory()->create(['event_id' => $event->id, 'name' => 'VIP Ticket']);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/skus");
        $response->assertStatus(200);
        $response->assertSee('VIP Ticket');
    }

    public function test_create_form_renders(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/skus/create");
        $response->assertStatus(200);
        $response->assertSee('Tambah SKU');
    }

    public function test_store_valid_sku_generates_tickets(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);

        $response = $this->actingAs($user)->post("/vendor/events/{$event->id}/skus", [
            'name' => 'Regular',
            'category' => 'Standard',
            'price' => 100000,
            'stock' => 5,
            'day_type' => 'weekday',
        ]);

        $response->assertRedirect("/vendor/events/{$event->id}/skus");
        $this->assertDatabaseHas('skus', ['name' => 'Regular', 'event_id' => $event->id]);
        $this->assertDatabaseCount('tickets', 5);
    }

    public function test_store_validates_required_fields(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);

        $response = $this->actingAs($user)->post("/vendor/events/{$event->id}/skus", []);
        $response->assertSessionHasErrors(['name', 'category', 'price', 'stock', 'day_type']);
    }

    public function test_cannot_manage_other_vendors_event_sku(): void
    {
        $user = $this->createVendorUser();
        $otherVendor = Vendor::factory()->create();
        $event = Event::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/skus");
        $response->assertStatus(403);
    }
}
