<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SkuTicketApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_skus_by_user_returns_correct_format(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create(['vendor_id' => $vendor->id]);
        Sku::factory()->create(['event_id' => $event->id, 'name' => 'VIP']);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/skus/user/{$user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => ['*' => ['id', 'name', 'category', 'event_id', 'price', 'stock', 'day_type', 'event']],
        ]);
    }

    public function test_create_sku_returns_correct_format(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create(['vendor_id' => $vendor->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/sku', [
                'name' => 'Regular',
                'price' => 50000,
                'category' => 'Standard',
                'event_id' => $event->id,
                'stock' => 3,
                'day_type' => 'weekday',
            ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'message', 'data',
        ]);

        // Verify tickets auto-generated
        $this->assertDatabaseCount('tickets', 3);
    }

    public function test_get_tickets_by_user_returns_correct_format(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create(['vendor_id' => $vendor->id]);
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        Ticket::factory()->create([
            'event_id' => $event->id,
            'sku_id' => $sku->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/tickets/user/{$user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'data' => ['*' => ['id', 'sku_id', 'event_id', 'ticket_code', 'status', 'sku', 'event']],
        ]);
    }

    public function test_check_ticket_valid_returns_correct_format(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        Ticket::factory()->create([
            'event_id' => $event->id,
            'sku_id' => $sku->id,
            'ticket_code' => 'TESTCODE01',
            'status' => 'available',
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/check-ticket', ['ticket_code' => 'TESTCODE01']);

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'isValid']);
        $this->assertTrue($response->json('isValid'));
    }
}
