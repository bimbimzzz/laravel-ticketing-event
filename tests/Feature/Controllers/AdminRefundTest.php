<?php

namespace Tests\Feature\Controllers;

use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminRefundTest extends TestCase
{
    use RefreshDatabase;

    private function createRefundPendingOrder(): array
    {
        $buyer = User::factory()->create();
        $vendorUser = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(11),
        ]);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'stock' => 2, // tickets were released back
            'price' => 100000,
        ]);

        $order = Order::factory()->refundPending()->create([
            'user_id' => $buyer->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 200000,
            'event_date' => now()->addDays(10)->format('Y-m-d'),
        ]);

        $tickets = Ticket::factory()->count(2)->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'available', // released during cancel
        ]);

        foreach ($tickets as $ticket) {
            OrderTicket::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
            ]);
        }

        return compact('buyer', 'vendor', 'event', 'sku', 'order', 'tickets');
    }

    private function createAdmin(): User
    {
        return User::factory()->create(['email' => 'superadmin@admin.com']);
    }

    public function test_admin_can_approve_refund(): void
    {
        $admin = $this->createAdmin();
        $data = $this->createRefundPendingOrder();

        $response = $this->actingAs($admin)
            ->post(route('admin.refunds.approve', $data['order']->id), [
                'refund_note' => 'Refund disetujui.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'refunded',
        ]);
    }

    public function test_admin_can_reject_refund(): void
    {
        $admin = $this->createAdmin();
        $data = $this->createRefundPendingOrder();

        $response = $this->actingAs($admin)
            ->post(route('admin.refunds.reject', $data['order']->id), [
                'refund_note' => 'Refund ditolak, tiket masih berlaku.',
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'success',
        ]);
    }

    public function test_non_admin_cannot_manage_refunds(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $data = $this->createRefundPendingOrder();

        $response = $this->actingAs($user)
            ->get(route('admin.refunds'));

        $response->assertStatus(403);
    }

    public function test_approve_refund_sets_refunded_status(): void
    {
        $admin = $this->createAdmin();
        $data = $this->createRefundPendingOrder();

        $this->actingAs($admin)
            ->post(route('admin.refunds.approve', $data['order']->id), [
                'refund_note' => 'Dana dikembalikan via transfer.',
            ]);

        $order = $data['order']->fresh();
        $this->assertEquals('refunded', $order->status_payment);
        $this->assertEquals('Dana dikembalikan via transfer.', $order->refund_note);
        $this->assertNotNull($order->refunded_at);
    }

    public function test_reject_refund_reverts_to_success_and_rebooks_tickets(): void
    {
        $admin = $this->createAdmin();
        $data = $this->createRefundPendingOrder();

        $this->actingAs($admin)
            ->post(route('admin.refunds.reject', $data['order']->id), [
                'refund_note' => 'Refund ditolak.',
            ]);

        // Order should be back to success
        $this->assertDatabaseHas('orders', [
            'id' => $data['order']->id,
            'status_payment' => 'success',
        ]);

        // Tickets should be re-booked (sold)
        foreach ($data['tickets'] as $ticket) {
            $this->assertDatabaseHas('tickets', [
                'id' => $ticket->id,
                'status' => 'sold',
            ]);
        }

        // Stock should be decremented back
        $this->assertEquals(0, $data['sku']->fresh()->stock);
    }
}
