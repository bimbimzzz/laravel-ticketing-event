<?php

namespace App\Services;

use App\Helpers\UniqueCodeHelper;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CancelOrderService
{
    public function cancel(Order $order, User $user, string $reason): Order
    {
        if ($order->user_id !== $user->id) {
            throw new \InvalidArgumentException('Order ini bukan milik Anda.');
        }

        if (!in_array($order->status_payment, ['success', 'paid'])) {
            throw new \InvalidArgumentException('Hanya order dengan status pembayaran success/paid yang bisa di-cancel.');
        }

        if (!$this->canCancel($order)) {
            throw new \InvalidArgumentException('Batas waktu cancel sudah terlewat (H-' . config('order.cancel_deadline_days') . ' sebelum event).');
        }

        // Check if any ticket already redeemed
        $hasRedeemed = $order->orderTickets()->whereHas('ticket', function ($q) {
            $q->where('status', 'redeem');
        })->exists();

        if ($hasRedeemed) {
            throw new \InvalidArgumentException('Tidak bisa cancel karena tiket sudah di-redeem.');
        }

        return DB::transaction(function () use ($order, $reason) {
            $order->update([
                'status_payment' => 'refund_pending',
                'cancel_reason' => $reason,
                'cancelled_at' => now(),
            ]);

            // Release tickets: sold → available, restore stock, regenerate ticket_code
            foreach ($order->orderTickets as $ot) {
                $ticket = $ot->ticket;
                if ($ticket->status === 'sold') {
                    $ticket->update([
                        'status' => 'available',
                        'ticket_code' => UniqueCodeHelper::generateUniqueCode('tickets', 'ticket_code'),
                    ]);
                    $ticket->sku->increment('stock');
                }
            }

            return $order->fresh();
        });
    }

    public function approveRefund(Order $order, string $note, ?string $proofFilename = null): Order
    {
        if ($order->status_payment !== 'refund_pending') {
            throw new \InvalidArgumentException('Order tidak dalam status refund_pending.');
        }

        $data = [
            'status_payment' => 'refunded',
            'refund_note' => $note,
            'refunded_at' => now(),
        ];

        if ($proofFilename) {
            $data['refund_proof'] = $proofFilename;
        }

        $order->update($data);

        return $order->fresh();
    }

    public function rejectRefund(Order $order, string $note): Order
    {
        if ($order->status_payment !== 'refund_pending') {
            throw new \InvalidArgumentException('Order tidak dalam status refund_pending.');
        }

        return DB::transaction(function () use ($order, $note) {
            $order->update([
                'status_payment' => 'success',
                'refund_note' => $note,
            ]);

            // Re-book tickets: available → sold, decrement stock
            foreach ($order->orderTickets as $ot) {
                $ticket = $ot->ticket;
                if ($ticket->status === 'available') {
                    $ticket->update(['status' => 'sold']);
                    $ticket->sku->decrement('stock');
                }
            }

            return $order->fresh();
        });
    }

    public function canCancel(Order $order): bool
    {
        $order->loadMissing('event');
        $deadline = Carbon::parse($order->event->start_date)
            ->subDays(config('order.cancel_deadline_days'));

        return now()->lt($deadline);
    }
}
