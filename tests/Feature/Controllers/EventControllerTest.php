<?php

namespace Tests\Feature\Controllers;

use App\Models\EventCategory;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_rejects_non_image(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();

        $file = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/events', [
                'vendor_id' => $vendor->id,
                'event_category_id' => $category->id,
                'name' => 'Test Event',
                'description' => 'Test Description',
                'image' => $file,
                'start_date' => now()->addDays(7)->format('Y-m-d'),
                'end_date' => now()->addDays(14)->format('Y-m-d'),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_rejects_oversized_image(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();

        $file = UploadedFile::fake()->image('big.jpg')->size(6000); // 6MB > 5MB

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/events', [
                'vendor_id' => $vendor->id,
                'event_category_id' => $category->id,
                'name' => 'Test Event',
                'description' => 'Test Description',
                'image' => $file,
                'start_date' => now()->addDays(7)->format('Y-m-d'),
                'end_date' => now()->addDays(14)->format('Y-m-d'),
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    public function test_accepts_valid_image(): void
    {
        $user = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();

        $file = UploadedFile::fake()->image('event.jpg')->size(1000);

        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/events', [
                'vendor_id' => $vendor->id,
                'event_category_id' => $category->id,
                'name' => 'Test Event',
                'description' => 'Test Description',
                'image' => $file,
                'start_date' => now()->addDays(7)->format('Y-m-d'),
                'end_date' => now()->addDays(14)->format('Y-m-d'),
            ]);

        $response->assertStatus(201);
    }
}
