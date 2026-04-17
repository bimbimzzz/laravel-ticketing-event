<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CleanupExpiredOrders extends Command
{
    protected $signature = 'orders:cleanup-expired';

    protected $description = 'Cancel pending orders older than 1 hour and release booked tickets';

    public function handle()
    {
        $expiredOrderIds = Order::where('status_payment', 'pending')
            ->where('created_at', '<', now()->subHour())
            ->pluck('id');

        if ($expiredOrderIds->isEmpty()) {
            $this->info('No expired orders found.');
            return;
        }

        $count = 0;
        foreach ($expiredOrderIds as $orderId) {
            DB::transaction(function () use ($orderId, &$count) {
                // Lock the order row to prevent race condition with webhook
                $order = Order::lockForUpdate()->find($orderId);

                // Guard: skip if already processed by webhook
                if (!$order || $order->status_payment !== 'pending') {
                    return;
                }

                $order->update(['status_payment' => 'cancel']);

                $order->load('orderTickets.ticket.sku');
                foreach ($order->orderTickets as $ot) {
                    $ticket = $ot->ticket;
                    if ($ticket->status === 'booked') {
                        $ticket->update(['status' => 'available']);
                        $ticket->sku->increment('stock');
                    }
                }

                $count++;
            });
        }

        $this->info("Cleaned up {$count} expired orders.");
        Log::info("CleanupExpiredOrders: cancelled {$count} orders and released tickets.");
    }
}
