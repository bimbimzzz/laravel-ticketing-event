<?php

namespace Tests\Feature\Email;

use App\Mail\OrderConfirmationMail;
use App\Mail\EticketMail;
use App\Mail\VendorStatusMail;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class OrderEmailNotificationTest extends TestCase
{
    use RefreshDatabase;

    private function createFullOrder(): array
    {
        $vendorUser = User::factory()->create(['is_vendor' => 1]);
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id]);
        $category = EventCategory::factory()->create();
        $event = Event::factory()->create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
        ]);
        $sku = Sku::factory()->create([
            'event_id' => $event->id,
            'stock' => 10,
            'price' => 100000,
        ]);
        $tickets = Ticket::factory()->count(10)->create([
            'sku_id' => $sku->id,
            'event_id' => $event->id,
            'status' => 'available',
        ]);
        $buyer = User::factory()->create();
        $order = Order::factory()->create([
            'user_id' => $buyer->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 200000,
            'status_payment' => 'pending',
        ]);

        // Book 2 tickets
        foreach ($tickets->take(2) as $ticket) {
            OrderTicket::create(['order_id' => $order->id, 'ticket_id' => $ticket->id]);
            $ticket->update(['status' => 'booked']);
        }

        return compact('buyer', 'vendor', 'vendorUser', 'event', 'sku', 'order', 'tickets');
    }

    public function test_order_confirmation_mail_can_be_rendered(): void
    {
        $data = $this->createFullOrder();
        $order = $data['order']->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);

        $mail = new OrderConfirmationMail($order);

        $this->assertNotEmpty($mail->render());
    }

    public function test_order_confirmation_mail_has_correct_content(): void
    {
        $data = $this->createFullOrder();
        $order = $data['order']->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);

        $mail = new OrderConfirmationMail($order);
        $rendered = $mail->render();

        $this->assertStringContainsString($data['event']->name, $rendered);
        $this->assertStringContainsString('200.000', $rendered);
    }

    public function test_eticket_mail_can_be_rendered(): void
    {
        $data = $this->createFullOrder();
        $order = $data['order']->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);

        $mail = new EticketMail($order);

        $this->assertNotEmpty($mail->render());
    }

    public function test_eticket_mail_contains_ticket_codes(): void
    {
        $data = $this->createFullOrder();
        $order = $data['order']->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);

        $mail = new EticketMail($order);
        $rendered = $mail->render();

        foreach ($order->orderTickets as $ot) {
            $this->assertStringContainsString($ot->ticket->ticket_code, $rendered);
        }
    }

    public function test_vendor_approved_mail_can_be_rendered(): void
    {
        $vendorUser = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id, 'verify_status' => 'approved']);

        $mail = new VendorStatusMail($vendor, 'approved');

        $this->assertNotEmpty($mail->render());
    }

    public function test_vendor_rejected_mail_can_be_rendered(): void
    {
        $vendorUser = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id, 'verify_status' => 'rejected']);

        $mail = new VendorStatusMail($vendor, 'rejected');

        $this->assertNotEmpty($mail->render());
    }

    public function test_eticket_sent_on_payment_success(): void
    {
        Mail::fake();
        $data = $this->createFullOrder();

        $this->actingAs($data['buyer'])
            ->get('/payment/success?order_id=' . $data['order']->id);

        Mail::assertSent(EticketMail::class, function ($mail) use ($data) {
            return $mail->hasTo($data['buyer']->email);
        });
    }

    public function test_vendor_status_email_sent_on_approval(): void
    {
        Mail::fake();
        $admin = User::factory()->create(['email' => 'admin@admin.com']);
        $vendorUser = User::factory()->create();
        $vendor = Vendor::factory()->create(['user_id' => $vendorUser->id, 'verify_status' => 'pending']);

        $this->actingAs($admin)
            ->patch("/superadmin/vendors/{$vendor->id}/status", ['status' => 'approved']);

        Mail::assertSent(VendorStatusMail::class, function ($mail) use ($vendorUser) {
            return $mail->hasTo($vendorUser->email);
        });
    }
}
