<?php

namespace Tests\Feature\Web\Vendor;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VendorAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    private function createVendorWithOrders(): array
    {
        $user = User::factory()->create(['is_vendor' => 1]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);
        $sku1 = Sku::factory()->create(['event_id' => $event->id, 'price' => 100000, 'name' => 'Regular']);
        $sku2 = Sku::factory()->create(['event_id' => $event->id, 'price' => 200000, 'name' => 'VIP']);

        // Create some orders
        Order::factory()->count(3)->create([
            'event_id' => $event->id,
            'total_price' => 100000,
            'status_payment' => 'success',
            'quantity' => 1,
        ]);
        Order::factory()->create([
            'event_id' => $event->id,
            'total_price' => 200000,
            'status_payment' => 'pending',
            'quantity' => 1,
        ]);

        Ticket::factory()->count(3)->create([
            'sku_id' => $sku1->id,
            'event_id' => $event->id,
            'status' => 'sold',
        ]);
        Ticket::factory()->count(2)->create([
            'sku_id' => $sku2->id,
            'event_id' => $event->id,
            'status' => 'sold',
        ]);

        return compact('user', 'vendor', 'event', 'sku1', 'sku2');
    }

    public function test_vendor_dashboard_shows_analytics_data(): void
    {
        $data = $this->createVendorWithOrders();

        $response = $this->actingAs($data['user'])->get('/vendor/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('totalRevenue');
        $response->assertViewHas('totalOrders');
        $response->assertViewHas('totalTicketsSold');
        $response->assertViewHas('revenueChart');
        $response->assertViewHas('topSkus');
    }

    public function test_vendor_dashboard_revenue_is_correct(): void
    {
        $data = $this->createVendorWithOrders();

        $response = $this->actingAs($data['user'])->get('/vendor/dashboard');

        // 3 success orders x 100000
        $response->assertViewHas('totalRevenue', 300000);
    }

    public function test_vendor_dashboard_only_shows_own_data(): void
    {
        $data = $this->createVendorWithOrders();

        // Create another vendor with different data
        $otherUser = User::factory()->create(['is_vendor' => 1]);
        $otherVendor = Vendor::factory()->create(['user_id' => $otherUser->id]);
        $otherEvent = Event::factory()->create(['vendor_id' => $otherVendor->id]);
        Order::factory()->create([
            'event_id' => $otherEvent->id,
            'total_price' => 999999,
            'status_payment' => 'success',
        ]);

        $response = $this->actingAs($data['user'])->get('/vendor/dashboard');

        // Should only see own revenue (300000), not other vendor's (999999)
        $response->assertViewHas('totalRevenue', 300000);
    }
}
