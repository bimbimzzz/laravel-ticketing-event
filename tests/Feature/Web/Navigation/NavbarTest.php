<?php

namespace Tests\Feature\Web\Navigation;

use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NavbarTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_sees_login_and_register(): void
    {
        $response = $this->get('/events');
        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Daftar');
    }

    public function test_auth_user_sees_profile_link(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/events');
        $response->assertStatus(200);
        $response->assertSee('/profile');
    }

    public function test_vendor_sees_dashboard_link(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/events');
        $response->assertStatus(200);
        $response->assertSee('/vendor/dashboard');
    }

    public function test_non_vendor_does_not_see_vendor_dashboard_link(): void
    {
        $user = User::factory()->create(['is_vendor' => false]);

        $response = $this->actingAs($user)->get('/events');
        $response->assertStatus(200);
        $response->assertDontSee('/vendor/dashboard');
    }

    public function test_mobile_sidebar_has_overlay(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('sidebarOpen');
    }
}
