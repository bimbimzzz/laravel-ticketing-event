<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorUser(): User
    {
        $user = User::factory()->create(['is_vendor' => true]);
        Vendor::factory()->create(['user_id' => $user->id]);
        return $user;
    }

    public function test_guest_is_redirected(): void
    {
        $response = $this->get('/vendor/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_non_vendor_is_redirected(): void
    {
        $user = User::factory()->create(['is_vendor' => false]);

        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertRedirect('/events');
    }

    public function test_vendor_sees_dashboard(): void
    {
        $user = $this->createVendorUser();

        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    }

    public function test_shows_stat_cards(): void
    {
        $user = $this->createVendorUser();
        $vendor = $user->vendor;

        // Create events and orders
        $event = Event::factory()->create(['vendor_id' => $vendor->id]);
        Order::factory()->create([
            'event_id' => $event->id,
            'user_id' => User::factory()->create()->id,
            'total_price' => 100000,
            'status_payment' => 'success',
        ]);

        $response = $this->actingAs($user)->get('/vendor/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Total Event');
        $response->assertSee('Pesanan Sukses');
        $response->assertSee('Total Pendapatan');
    }
}
