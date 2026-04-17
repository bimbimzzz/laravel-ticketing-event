<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private function mockGoogleClient(array $payload): void
    {
        $googleClient = Mockery::mock(\Google_Client::class);
        $googleClient->shouldReceive('verifyIdToken')
            ->andReturn($payload);

        $this->app->bind(\Google_Client::class, function () use ($googleClient) {
            return $googleClient;
        });
    }

    public function test_google_login_returns_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'existing@example.com',
            'name' => 'Existing User',
        ]);

        $this->mockGoogleClient([
            'email' => 'existing@example.com',
            'name' => 'Existing User',
            'sub' => 'google-id-123',
        ]);

        $response = $this->postJson('/api/login/google', [
            'id_token' => 'fake-google-token',
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.user.email', 'existing@example.com');

        $this->assertDatabaseCount('users', 1);
    }

    public function test_google_login_creates_new_user(): void
    {
        $this->mockGoogleClient([
            'email' => 'new@example.com',
            'name' => 'New User',
            'sub' => 'google-id-456',
        ]);

        $response = $this->postJson('/api/login/google', [
            'id_token' => 'fake-google-token',
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('status', 'success')
            ->assertJsonPath('data.user.email', 'new@example.com');

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users', ['email' => 'new@example.com']);
    }
}
