<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorUser(): User
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_list_vendor_events(): void
    {
        $user = $this->createVendorUser();
        $vendor = $user->vendor;
        Event::factory()->create(['vendor_id' => $vendor->id, 'name' => 'My Vendor Event']);

        $response = $this->actingAs($user)->get('/vendor/events');
        $response->assertStatus(200);
        $response->assertSee('My Vendor Event');
    }

    public function test_create_form_renders(): void
    {
        $user = $this->createVendorUser();
        EventCategory::factory()->create(['name' => 'Musik']);

        $response = $this->actingAs($user)->get('/vendor/events/create');
        $response->assertStatus(200);
        $response->assertSee('Buat Event');
        $response->assertSee('Musik');
    }

    public function test_store_valid_event(): void
    {
        $user = $this->createVendorUser();
        $category = EventCategory::factory()->create();

        $response = $this->actingAs($user)->post('/vendor/events', [
            'name' => 'Event Baru',
            'event_category_id' => $category->id,
            'description' => 'Deskripsi event baru',
            'image' => UploadedFile::fake()->image('event.jpg'),
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-02',
        ]);

        $response->assertRedirect('/vendor/events');
        $this->assertDatabaseHas('events', ['name' => 'Event Baru']);
    }

    public function test_store_validates_required_fields(): void
    {
        $user = $this->createVendorUser();

        $response = $this->actingAs($user)->post('/vendor/events', []);
        $response->assertSessionHasErrors(['name', 'event_category_id', 'description', 'image', 'start_date', 'end_date']);
    }

    public function test_edit_form_renders_with_data(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create([
            'vendor_id' => $user->vendor->id,
            'name' => 'Edit This Event',
        ]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/edit");
        $response->assertStatus(200);
        $response->assertSee('Edit This Event');
    }

    public function test_update_event(): void
    {
        $user = $this->createVendorUser();
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $user->vendor->id,
            'name' => 'Old Name',
        ]);

        $response = $this->actingAs($user)->put("/vendor/events/{$event->id}", [
            'name' => 'Updated Name',
            'event_category_id' => $category->id,
            'description' => 'Updated description',
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-02',
        ]);

        $response->assertRedirect('/vendor/events');
        $this->assertDatabaseHas('events', ['id' => $event->id, 'name' => 'Updated Name']);
    }

    public function test_delete_event(): void
    {
        $user = $this->createVendorUser();
        $event = Event::factory()->create(['vendor_id' => $user->vendor->id]);

        $response = $this->actingAs($user)->delete("/vendor/events/{$event->id}");
        $response->assertRedirect('/vendor/events');
        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }

    public function test_cannot_edit_other_vendors_event(): void
    {
        $user = $this->createVendorUser();
        $otherVendor = Vendor::factory()->create();
        $event = Event::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($user)->get("/vendor/events/{$event->id}/edit");
        $response->assertStatus(403);
    }

    public function test_cannot_delete_other_vendors_event(): void
    {
        $user = $this->createVendorUser();
        $otherVendor = Vendor::factory()->create();
        $event = Event::factory()->create(['vendor_id' => $otherVendor->id]);

        $response = $this->actingAs($user)->delete("/vendor/events/{$event->id}");
        $response->assertStatus(403);
    }
}
