<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_returns_correct_format(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123',
            'confirm_password' => 'password123',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => ['id', 'name', 'email'],
        ]);
    }

    public function test_login_returns_correct_format_with_int_is_vendor(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'is_vendor' => false,
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                'user' => ['id', 'name', 'email', 'is_vendor', 'phone'],
                'token',
            ],
        ]);

        // CRITICAL: is_vendor must be integer (0 or 1), NOT boolean
        $userData = $response->json('data.user');
        $this->assertIsInt($userData['is_vendor']);
        $this->assertEquals(0, $userData['is_vendor']);
    }

    public function test_login_vendor_returns_vendor_relation_and_int_is_vendor(): void
    {
        $user = User::factory()->create([
            'email' => 'vendor@example.com',
            'password' => bcrypt('password123'),
            'is_vendor' => true,
        ]);
        Vendor::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson('/api/login', [
            'email' => 'vendor@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200);

        $userData = $response->json('data.user');
        $this->assertIsInt($userData['is_vendor']);
        $this->assertEquals(1, $userData['is_vendor']);
        $this->assertNotNull($userData['vendor']);
        $this->assertArrayHasKey('name', $userData['vendor']);
    }

    public function test_logout_returns_correct_format(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200);
        $response->assertJson(['status' => 'success']);
    }
}
