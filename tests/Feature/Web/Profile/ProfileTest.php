<?php

namespace Tests\Feature\Web\Profile;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected(): void
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_page_renders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/profile');
        $response->assertStatus(200);
        $response->assertSee('Profil Saya');
    }

    public function test_update_name(): void
    {
        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->actingAs($user)->put('/profile', [
            'name' => 'New Name',
            'email' => $user->email,
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'name' => 'New Name']);
    }

    public function test_update_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => 'newemail@example.com',
        ]);

        $response->assertRedirect('/profile');
        $this->assertDatabaseHas('users', ['id' => $user->id, 'email' => 'newemail@example.com']);
    }

    public function test_email_unique_validation(): void
    {
        $existingUser = User::factory()->create(['email' => 'taken@example.com']);
        $user = User::factory()->create();

        $response = $this->actingAs($user)->put('/profile', [
            'name' => $user->name,
            'email' => 'taken@example.com',
        ]);

        $response->assertSessionHasErrors('email');
    }
}
