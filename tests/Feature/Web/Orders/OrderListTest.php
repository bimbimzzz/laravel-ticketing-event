<?php

namespace Tests\Feature\Web\Orders;

use App\Models\Event;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderListTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected(): void
    {
        $response = $this->get('/orders');
        $response->assertRedirect('/login');
    }

    public function test_page_renders(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);
        $response->assertSee('Pesanan Saya');
    }

    public function test_shows_user_orders(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['name' => 'My Event Order']);
        Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);
        $response->assertSee('My Event Order');
    }

    public function test_doesnt_show_other_users_orders(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create(['name' => 'Secret Event']);
        Order::factory()->create([
            'user_id' => $otherUser->id,
            'event_id' => $event->id,
        ]);

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);
        $response->assertDontSee('Secret Event');
    }

    public function test_empty_state(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/orders');
        $response->assertStatus(200);
        $response->assertSee('Belum ada pesanan');
    }
}
