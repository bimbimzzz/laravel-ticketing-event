<?php

namespace App\Console\Commands;

use App\Mail\OrderConfirmationMail;
use App\Mail\EticketMail;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use App\Services\Xendit\XenditPaymentService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailFlow extends Command
{
    protected $signature = 'test:email-flow {email}';
    protected $description = 'Test full email flow: Order Confirmation (with Xendit payment link) → E-Ticket → Invoice PDF';

    public function handle()
    {
        $email = $this->argument('email');
        $this->info("Testing email flow to: {$email}");

        // 1. Create test data
        $user = User::firstOrCreate(
            ['email' => $email],
            ['name' => 'Saiful Bahri', 'password' => bcrypt('password')]
        );

        $vendor = Vendor::first() ?? Vendor::create([
            'user_id' => User::factory()->create()->id,
            'name' => 'JagoEvent Production',
            'description' => 'Event organizer terpercaya',
            'location' => 'Jakarta',
            'city' => 'Jakarta',
            'phone' => '081234567890',
            'verify_status' => 'approved',
        ]);

        $category = EventCategory::first() ?? EventCategory::create(['name' => 'Musik']);

        $event = Event::create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $category->id,
            'name' => 'JagoEvent Music Festival 2026',
            'description' => 'Festival musik terbesar tahun ini!',
            'image' => 'test.png',
            'start_date' => now()->addDays(7),
            'end_date' => now()->addDays(8),
        ]);

        $sku = Sku::create([
            'event_id' => $event->id,
            'name' => 'VIP Pass',
            'category' => 'Premium',
            'price' => 350000,
            'stock' => 10,
            'day_type' => 'weekend',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => 2,
            'total_price' => 700000,
            'event_date' => now()->addDays(7)->format('Y-m-d'),
            'status_payment' => 'pending',
        ]);

        // Create tickets
        $tickets = [];
        for ($i = 1; $i <= 2; $i++) {
            $ticket = Ticket::create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'ticket_code' => 'JGE-' . strtoupper(substr(md5(uniqid()), 0, 8)),
                'status' => 'booked',
            ]);
            OrderTicket::create([
                'order_id' => $order->id,
                'ticket_id' => $ticket->id,
            ]);
            $tickets[] = $ticket;
        }

        $order->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);

        // 2. Generate Xendit payment URL
        $this->info('');
        $this->info('[1/4] Creating Xendit payment invoice...');
        try {
            $xenditService = app(XenditPaymentService::class);
            $paymentUrl = $xenditService->createInvoice(
                [['sku_id' => $sku->id, 'qty' => 2]],
                $order
            );
            $order->update(['payment_url' => $paymentUrl]);
            $order->refresh();
            $order->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);
            $this->info("  ✓ Xendit invoice created: {$paymentUrl}");
        } catch (\Exception $e) {
            $this->warn("  ⚠ Xendit failed: {$e->getMessage()}");
            $this->warn("  → Sending email without payment link...");
        }

        // 3. Send Order Confirmation email (pending, with payment link)
        $this->info('[2/4] Sending Order Confirmation email...');
        try {
            Mail::to($email)->send(new OrderConfirmationMail($order));
            $this->info('  ✓ Order Confirmation email sent!');
        } catch (\Exception $e) {
            $this->error('  ✗ Failed: ' . $e->getMessage());
            return 1;
        }

        // 4. Simulate payment success → send E-Ticket email
        $order->update(['status_payment' => 'success']);
        foreach ($tickets as $ticket) {
            $ticket->update(['status' => 'sold']);
        }
        $order->refresh();
        $order->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);

        $this->info('[3/4] Sending E-Ticket email...');
        try {
            Mail::to($email)->send(new EticketMail($order));
            $this->info('  ✓ E-Ticket email sent!');
        } catch (\Exception $e) {
            $this->error('  ✗ Failed: ' . $e->getMessage());
            return 1;
        }

        // 5. Generate Invoice PDF
        $this->info('[4/4] Generating Invoice PDF...');
        try {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order', compact('order'));
            $pdfPath = storage_path('app/invoice-test-' . $order->id . '.pdf');
            $pdf->save($pdfPath);
            $this->info('  ✓ Invoice PDF saved to: ' . $pdfPath);
        } catch (\Exception $e) {
            $this->error('  ✗ Failed: ' . $e->getMessage());
            return 1;
        }

        $this->info('');
        $this->info('=== All done! ===');
        $this->info("Order ID: #{$order->id}");
        $this->info("Event: {$event->name}");
        $this->info("Tickets: " . collect($tickets)->pluck('ticket_code')->join(', '));
        $this->info("Total: Rp " . number_format($order->total_price, 0, ',', '.'));
        if ($order->payment_url) {
            $this->info("Payment URL: {$order->payment_url}");
        }
        $this->info('');
        $this->info("Check {$email} for:");
        $this->info("  1. Email 'Konfirmasi Pesanan' (with Xendit payment link)");
        $this->info("  2. Email 'E-Ticket' (with ticket codes & QR)");
        $this->info("  3. Invoice PDF at: {$pdfPath}");

        return 0;
    }
}
