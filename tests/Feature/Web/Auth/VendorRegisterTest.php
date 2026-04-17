<?php

namespace Tests\Feature\Web\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorRegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_vendor_register_page_renders_for_auth_user(): void
    {
        $user = User::factory()->create(['is_vendor' => false]);

        $response = $this->actingAs($user)->get('/register/vendor');
        $response->assertStatus(200);
        $response->assertSee('Daftar Sebagai Vendor');
    }

    public function test_guest_is_redirected(): void
    {
        $response = $this->get('/register/vendor');
        $response->assertRedirect('/login');
    }

    public function test_valid_vendor_registration(): void
    {
        $user = User::factory()->create(['is_vendor' => false]);

        $response = $this->actingAs($user)->post('/register/vendor', [
            'name' => 'My Event Organizer',
            'phone' => '08123456789',
            'city' => 'Jakarta',
            'location' => 'Jl. Sudirman No. 1',
            'description' => 'Event organizer terbaik',
        ]);

        $response->assertRedirect('/events');
        $this->assertDatabaseHas('vendors', [
            'user_id' => $user->id,
            'name' => 'My Event Organizer',
            'verify_status' => 'pending',
        ]);
        $this->assertEquals(1, $user->fresh()->is_vendor);
    }

    public function test_required_fields_validation(): void
    {
        $user = User::factory()->create(['is_vendor' => false]);

        $response = $this->actingAs($user)->post('/register/vendor', []);

        $response->assertSessionHasErrors(['name', 'phone', 'city', 'location']);
    }

    public function test_existing_vendor_is_redirected(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);

        $response = $this->actingAs($user)->get('/register/vendor');
        $response->assertRedirect('/events');
    }
}
