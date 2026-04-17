<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class VendorVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_unverified_vendor_sees_pending_message_on_dashboard(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id, 'verify_status' => 'pending']);

        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Menunggu Verifikasi');
    }

    public function test_unverified_vendor_cannot_create_event(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id, 'verify_status' => 'pending']);
        $category = EventCategory::factory()->create();

        $response = $this->actingAs($user)->post('/vendor/events', [
            'name' => 'Test Event',
            'event_category_id' => $category->id,
            'description' => 'Test',
            'image' => UploadedFile::fake()->image('event.jpg'),
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-02',
        ]);

        $response->assertRedirect('/vendor/dashboard');
        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('events', ['name' => 'Test Event']);
    }

    public function test_approved_vendor_can_create_event(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id, 'verify_status' => 'approved']);
        $category = EventCategory::factory()->create();

        $response = $this->actingAs($user)->post('/vendor/events', [
            'name' => 'Approved Event',
            'event_category_id' => $category->id,
            'description' => 'Test desc',
            'image' => UploadedFile::fake()->image('event.jpg'),
            'start_date' => '2026-04-01',
            'end_date' => '2026-04-02',
        ]);

        $response->assertRedirect('/vendor/events');
        $this->assertDatabaseHas('events', ['name' => 'Approved Event']);
    }

    public function test_rejected_vendor_sees_rejected_message(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id, 'verify_status' => 'rejected']);

        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Ditolak');
    }
}
