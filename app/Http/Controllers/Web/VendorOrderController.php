<?php

namespace App\Http\Controllers\Web;

use App\Exports\VendorOrdersExport;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Order;
use Maatwebsite\Excel\Facades\Excel;

class VendorOrderController extends Controller
{
    private function authorizeEvent($eventId): Event
    {
        $event = Event::findOrFail($eventId);
        $vendor = auth()->user()->vendor;

        if ($event->vendor_id !== $vendor->id) {
            abort(403);
        }

        return $event;
    }

    public function index($eventId)
    {
        $event = $this->authorizeEvent($eventId);

        $orders = Order::with(['user'])
            ->where('event_id', $event->id)
            ->latest()
            ->paginate(10);

        return view('vendor.orders.index', compact('event', 'orders'));
    }

    public function export($eventId)
    {
        $event = $this->authorizeEvent($eventId);
        return Excel::download(new VendorOrdersExport($event->id), 'orders-' . \Str::slug($event->name) . '-' . now()->format('Ymd') . '.xlsx');
    }

    public function show($eventId, $id)
    {
        $event = $this->authorizeEvent($eventId);

        $order = Order::with(['user', 'orderTickets.ticket.sku'])
            ->where('event_id', $event->id)
            ->findOrFail($id);

        return view('vendor.orders.show', compact('event', 'order'));
    }
}
