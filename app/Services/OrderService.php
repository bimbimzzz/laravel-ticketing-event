<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\PromoCode;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Services\Xendit\XenditPaymentService;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function createOrder(User $user, int $eventId, array $orderDetails, string $eventDate, int $totalQty, ?string $promoCode = null): Order
    {
        return DB::transaction(function () use ($user, $eventId, $orderDetails, $eventDate, $totalQty, $promoCode) {
            // Check ticket availability with lock
            foreach ($orderDetails as $detail) {
                $sku = Sku::find($detail['sku_id']);
                $qty = $detail['qty'];
                $availableCount = Ticket::where('sku_id', $sku->id)
                    ->where('status', 'available')
                    ->lockForUpdate()
                    ->count();

                if ($qty > $availableCount) {
                    throw new \Exception('Ticket "' . $sku->name . '" is not available. Only ' . $availableCount . ' tickets left.');
                }
            }

            // Calculate total
            $total = 0;
            foreach ($orderDetails as $detail) {
                $sku = Sku::find($detail['sku_id']);
                $total += $sku->price * $detail['qty'];
            }

            // Apply promo code
            $discountAmount = 0;
            $appliedPromoCode = null;
            if ($promoCode) {
                $promo = PromoCode::where('code', strtoupper($promoCode))
                    ->where('event_id', $eventId)
                    ->first();

                if ($promo && $promo->isValid()) {
                    $discountAmount = $promo->calculateDiscount($total);
                    $appliedPromoCode = $promo->code;
                    $promo->increment('used_count');
                }
            }

            $finalPrice = max(0, $total - $discountAmount);

            // Create order
            $order = Order::create([
                'user_id' => $user->id,
                'event_id' => $eventId,
                'event_date' => $eventDate,
                'quantity' => $totalQty,
                'total_price' => $finalPrice,
                'status_payment' => 'pending',
                'promo_code' => $appliedPromoCode,
                'discount_amount' => $discountAmount,
            ]);

            // Book tickets & decrement stock
            foreach ($orderDetails as $detail) {
                $sku = Sku::find($detail['sku_id']);
                $qty = $detail['qty'];

                for ($i = 0; $i < $qty; $i++) {
                    $ticket = Ticket::where('sku_id', $sku->id)
                        ->where('status', 'available')
                        ->lockForUpdate()
                        ->first();

                    OrderTicket::create([
                        'order_id' => $order->id,
                        'ticket_id' => $ticket->id,
                    ]);

                    $ticket->update(['status' => 'booked']);
                }

                $sku->decrement('stock', $qty);
            }

            // Generate payment URL
            $xendit = app(XenditPaymentService::class);
            $paymentUrl = $xendit->createInvoice($orderDetails, $order);
            $order->update(['payment_url' => $paymentUrl]);

            return $order->fresh();
        });
    }
}
