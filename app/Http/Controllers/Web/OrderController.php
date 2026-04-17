<?php

namespace App\Http\Controllers\Web;

use App\Helpers\DemoHelper;
use App\Http\Controllers\Controller;
use App\Mail\EticketMail;
use App\Models\Event;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Ticket;
use App\Services\CancelOrderService;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['event.vendor', 'event.eventCategory', 'orderTickets.ticket.sku'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with(['event.vendor', 'orderTickets.ticket.sku'])
            ->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.show', compact('order'));
    }

    public function checkout(Request $request, $eventId)
    {
        $validated = $request->validate([
            'order_details' => 'required|array|min:1',
            'order_details.*.sku_id' => 'required|exists:skus,id',
            'order_details.*.qty' => 'required|integer|min:1',
            'event_date' => 'required|date',
        ]);

        $event = Event::with(['vendor', 'eventCategory'])->findOrFail($eventId);
        $user = $request->user();

        // Build checkout items
        $items = [];
        $totalPrice = 0;
        foreach ($validated['order_details'] as $detail) {
            $sku = Sku::findOrFail($detail['sku_id']);
            $available = Ticket::where('sku_id', $sku->id)
                ->where('status', 'available')
                ->count();
            $subtotal = $sku->price * $detail['qty'];
            $totalPrice += $subtotal;
            $items[] = [
                'sku_id' => $sku->id,
                'name' => $sku->name,
                'category' => $sku->category,
                'price' => $sku->price,
                'qty' => $detail['qty'],
                'subtotal' => $subtotal,
                'available' => $available,
            ];
        }

        $totalQty = collect($items)->sum('qty');

        return view('orders.checkout', compact(
            'event', 'user', 'items', 'totalPrice', 'totalQty', 'validated'
        ));
    }

    public function store(Request $request, $eventId)
    {
        $validated = $request->validate([
            'order_details' => 'required|array',
            'order_details.*.sku_id' => 'required|exists:skus,id',
            'order_details.*.qty' => 'required|integer|min:1',
            'event_date' => 'required|date',
            'promo_code' => 'nullable|string',
        ]);

        $totalQty = collect($validated['order_details'])->sum('qty');

        $orderService = app(OrderService::class);
        $order = $orderService->createOrder(
            $request->user(),
            $eventId,
            $validated['order_details'],
            $validated['event_date'],
            $totalQty,
            $validated['promo_code'] ?? null
        );

        if ($order->payment_url) {
            return redirect($order->payment_url);
        }

        return redirect('/orders/' . $order->id);
    }

    public function invoice($id)
    {
        $order = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if ($order->status_payment !== 'success') {
            abort(404);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('invoices.order', compact('order'));
        return $pdf->download('invoice-' . $order->id . '.pdf');
    }

    /**
     * Cancel an order — restore tickets and stock.
     * For paid orders (success), use CancelOrderService to request refund.
     * For pending orders, direct cancel.
     */
    public function cancel(Request $request, $id)
    {
        $order = Order::with(['orderTickets.ticket.sku', 'event'])->findOrFail($id);

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        if (in_array($order->status_payment, ['cancel', 'refund_pending', 'refunded'])) {
            return back()->with('error', 'Pesanan sudah dibatalkan atau dalam proses refund.');
        }

        // Paid order → use CancelOrderService (refund flow)
        if (in_array($order->status_payment, ['success', 'paid'])) {
            $request->validate([
                'cancel_reason' => 'required|string|min:10',
            ], [
                'cancel_reason.required' => 'Alasan pembatalan wajib diisi.',
                'cancel_reason.min' => 'Alasan pembatalan minimal 10 karakter.',
            ]);

            try {
                app(CancelOrderService::class)->cancel($order, auth()->user(), $request->cancel_reason);
                return back()->with('success', 'Pesanan berhasil dibatalkan. Pengajuan refund sedang diproses.');
            } catch (\InvalidArgumentException $e) {
                return back()->with('error', $e->getMessage());
            }
        }

        // Pending order → direct cancel
        if ($order->event && now()->gt(\Carbon\Carbon::parse($order->event->end_date))) {
            return back()->with('error', 'Tidak bisa membatalkan pesanan untuk event yang sudah selesai.');
        }

        $hasRedeemed = $order->orderTickets->contains(fn($ot) => $ot->ticket->status === 'redeem');
        if ($hasRedeemed) {
            return back()->with('error', 'Tidak bisa membatalkan pesanan karena tiket sudah digunakan.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
            $order->update(['status_payment' => 'cancel']);

            foreach ($order->orderTickets as $ot) {
                if (in_array($ot->ticket->status, ['booked', 'sold'])) {
                    $ot->ticket->update(['status' => 'available']);
                    $ot->ticket->sku->increment('stock');
                }
            }
        });

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    /**
     * Simulate payment success (since webhook can't reach localhost).
     * Updates order status to 'success' and tickets to 'sold'.
     */
    public function paymentSuccess(Request $request)
    {
        $order = Order::with(['event.vendor', 'orderTickets.ticket'])
            ->findOrFail($request->query('order_id'));

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Update order & tickets if still pending
        if ($order->status_payment === 'pending') {
            $order->update(['status_payment' => 'success']);

            foreach ($order->orderTickets as $orderTicket) {
                if ($orderTicket->ticket->status === 'booked') {
                    $orderTicket->ticket->update(['status' => 'sold']);
                }
            }

            // Send e-ticket email (skip for demo accounts)
            $order->load(['user', 'event.vendor', 'orderTickets.ticket.sku']);
            if (!DemoHelper::isDemoAccount($order->user->email)) {
                Mail::to($order->user->email)->send(new EticketMail($order));
            }
        }

        return view('orders.payment-success', compact('order'));
    }

    /**
     * Payment failed/cancelled page.
     */
    public function paymentFailed(Request $request)
    {
        $order = Order::with(['event.vendor'])
            ->findOrFail($request->query('order_id'));

        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('orders.payment-failed', compact('order'));
    }
}
