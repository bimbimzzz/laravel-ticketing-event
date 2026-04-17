<?php

namespace Tests\Feature\PromoCode;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\PromoCode;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PromoCodeTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorWithEvent(): array
    {
        $user = User::factory()->create(['is_vendor' => 1]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);

        return compact('user', 'vendor', 'event');
    }

    // --- Model Tests ---

    public function test_promo_code_belongs_to_event(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'PROMO10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertEquals($data['event']->id, $promo->event->id);
    }

    public function test_promo_code_is_valid_check(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'VALID10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertTrue($promo->isValid());
    }

    public function test_expired_promo_is_invalid(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'EXPIRED',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->subDays(1),
        ]);

        $this->assertFalse($promo->isValid());
    }

    public function test_maxed_usage_promo_is_invalid(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'MAXED',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_usage' => 5,
            'used_count' => 5,
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertFalse($promo->isValid());
    }

    public function test_fixed_discount_calculation(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'FIXED50K',
            'discount_type' => 'fixed',
            'discount_value' => 50000,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertEquals(50000, $promo->calculateDiscount(200000));
    }

    public function test_percentage_discount_calculation(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'PERCENT10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertEquals(20000, $promo->calculateDiscount(200000));
    }

    public function test_discount_cannot_exceed_total(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'BIGFIXED',
            'discount_type' => 'fixed',
            'discount_value' => 500000,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $this->assertEquals(200000, $promo->calculateDiscount(200000));
    }

    // --- Vendor CRUD Tests ---

    public function test_vendor_can_create_promo_code(): void
    {
        $data = $this->createVendorWithEvent();

        $response = $this->actingAs($data['user'])
            ->post("/vendor/events/{$data['event']->id}/promos", [
                'code' => 'NEWPROMO',
                'discount_type' => 'percentage',
                'discount_value' => 15,
                'max_usage' => 50,
                'expires_at' => now()->addDays(30)->format('Y-m-d'),
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('promo_codes', [
            'event_id' => $data['event']->id,
            'code' => 'NEWPROMO',
        ]);
    }

    public function test_vendor_cannot_create_promo_for_other_vendor_event(): void
    {
        $data = $this->createVendorWithEvent();
        $otherUser = User::factory()->create(['is_vendor' => 1]);
        $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);

        $response = $this->actingAs($otherUser)
            ->post("/vendor/events/{$data['event']->id}/promos", [
                'code' => 'HACK',
                'discount_type' => 'percentage',
                'discount_value' => 100,
                'max_usage' => 1,
                'expires_at' => now()->addDays(30)->format('Y-m-d'),
            ]);

        $response->assertStatus(403);
    }

    public function test_vendor_can_delete_promo_code(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'TODELETE',
            'discount_type' => 'fixed',
            'discount_value' => 10000,
            'max_usage' => 10,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $response = $this->actingAs($data['user'])
            ->delete("/vendor/events/{$data['event']->id}/promos/{$promo->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('promo_codes', ['id' => $promo->id]);
    }

    // --- API Apply Promo ---

    public function test_api_apply_valid_promo_code(): void
    {
        $data = $this->createVendorWithEvent();
        $promo = PromoCode::create([
            'event_id' => $data['event']->id,
            'code' => 'APPLY10',
            'discount_type' => 'percentage',
            'discount_value' => 10,
            'max_usage' => 100,
            'used_count' => 0,
            'expires_at' => now()->addDays(30),
        ]);

        $buyer = User::factory()->create();
        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/promo/apply', [
                'code' => 'APPLY10',
                'event_id' => $data['event']->id,
                'total_price' => 200000,
            ]);

        $response->assertOk()
            ->assertJsonFragment([
                'discount' => 20000,
                'final_price' => 180000,
            ]);
    }

    public function test_api_reject_invalid_promo_code(): void
    {
        $buyer = User::factory()->create();
        $data = $this->createVendorWithEvent();

        $response = $this->actingAs($buyer, 'sanctum')
            ->postJson('/api/promo/apply', [
                'code' => 'NONEXIST',
                'event_id' => $data['event']->id,
                'total_price' => 200000,
            ]);

        $response->assertStatus(422);
    }
}
