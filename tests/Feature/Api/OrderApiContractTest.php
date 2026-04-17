<?php

namespace Tests\Feature\Api;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderApiContractTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_orders_by_user_returns_correct_format(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create();
        $sku = Sku::factory()->create(['event_id' => $event->id]);
        $order = Order::factory()->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'status_payment' => 'success',
        ]);
        $ticket = Ticket::factory()->create([
            'event_id' => $event->id,
            'sku_id' => $sku->id,
            'status' => 'sold',
        ]);
        OrderTicket::create(['order_id' => $order->id, 'ticket_id' => $ticket->id]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/orders/user/{$user->id}");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status',
            'message',
            'data' => [
                '*' => [
                    'id', 'user_id', 'event_id', 'quantity', 'total_price',
                    'event_date', 'status_payment', 'payment_url',
                    'created_at', 'updated_at',
                    'orderTickets' => [
                        '*' => [
                            'id', 'order_id', 'ticket_id', 'total_quantity',
                            'ticket' => ['id', 'ticket_code', 'status', 'sku'],
                        ],
                    ],
                    'user' => ['id', 'name', 'email'],
                    'event',
                ],
            ],
        ]);

        // Verify orderTickets format
        $orderData = $response->json('data.0');
        $this->assertArrayHasKey('orderTickets', $orderData);
        $this->assertArrayHasKey('total_quantity', $orderData['orderTickets'][0]);
    }

    public function test_get_orders_by_vendor_returns_correct_format(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create(['vendor_id' => $vendor->id]);
        $buyer = User::factory()->create();
        Order::factory()->create([
            'user_id' => $buyer->id,
            'event_id' => $event->id,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/orders/user/{$user->id}/vendor");

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'status', 'message', 'data',
        ]);
    }

    public function test_get_orders_total_by_vendor(): void
    {
        $user = User::factory()->create(['is_vendor' => true]);
        $vendor = Vendor::factory()->create(['user_id' => $user->id]);
        $token = $user->createToken('auth_token')->plainTextToken;

        $event = Event::factory()->create(['vendor_id' => $vendor->id]);
        Order::factory()->create([
            'user_id' => User::factory()->create()->id,
            'event_id' => $event->id,
            'total_price' => 100000,
        ]);

        $response = $this->withHeader('Authorization', "Bearer $token")
            ->getJson("/api/orders/user/{$user->id}/vendor/total");

        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data']);
    }
}
