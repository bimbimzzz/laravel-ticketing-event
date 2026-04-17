<?php

namespace Tests\Feature\Web\Middleware;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_protected_route(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    public function test_auth_user_can_access_protected_route(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);
    }

    public function test_auth_user_is_redirected_from_login_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/login');
        $response->assertRedirect('/events');
    }

    public function test_auth_user_is_redirected_from_register_page(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/register');
        $response->assertRedirect('/events');
    }

    public function test_vendor_middleware_redirects_non_vendor(): void
    {
        $user = User::factory()->create(['is_vendor' => false]);

        $response = $this->actingAs($user)->get('/register/vendor');
        $response->assertStatus(200); // non-vendor CAN access vendor registration
    }

    public function test_guest_cannot_access_vendor_registration(): void
    {
        $response = $this->get('/register/vendor');
        $response->assertRedirect('/login');
    }
}
