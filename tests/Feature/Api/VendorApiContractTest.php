<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_vendor_returns_correct_format(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/vendor', [
                'user_id' => $user->id,
                'name' => 'Test Vendor',
                'description' => 'A test vendor',
                'location' => 'Jakarta',
                'phone' => '08123456789',
                'city' => 'Jakarta',
            ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status', 'message',
            'data' => ['id', 'user_id', 'name', 'location', 'phone', 'city', 'verify_status'],
        ]);
        $this->assertDatabaseHas('users', ['id' => $user->id, 'is_vendor' => true]);
    }

    public function test_get_vendor_by_user_returns_correct_format(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/vendors/user/{$user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'message',
            'data' => ['*' => ['id', 'user_id', 'name', 'location', 'phone', 'city', 'verify_status']],
        ]);
    }
}
