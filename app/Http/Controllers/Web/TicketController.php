<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function show($id)
    {
        $ticket = Ticket::with(['event.vendor', 'sku', 'orderTickets.order'])->findOrFail($id);

        // Check ownership: ticket must belong to current user via order
        $order = $ticket->orderTickets->first()?->order;
        if (!$order || $order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('tickets.show', compact('ticket', 'order'));
    }
}
