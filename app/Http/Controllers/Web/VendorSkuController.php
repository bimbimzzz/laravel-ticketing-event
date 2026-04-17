<?php

namespace App\Http\Controllers\Web;

use App\Helpers\UniqueCodeHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use Illuminate\Http\Request;

class VendorSkuController extends Controller
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
        $skus = Sku::where('event_id', $event->id)->get();

        return view('vendor.skus.index', compact('event', 'skus'));
    }

    public function create($eventId)
    {
        $event = $this->authorizeEvent($eventId);

        return view('vendor.skus.create', compact('event'));
    }

    public function store(Request $request, $eventId)
    {
        $event = $this->authorizeEvent($eventId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'day_type' => 'required|string',
        ]);

        $sku = Sku::create([
            'event_id' => $event->id,
            'name' => $validated['name'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'stock' => $validated['stock'],
            'day_type' => $validated['day_type'],
        ]);

        for ($i = 0; $i < $validated['stock']; $i++) {
            Ticket::create([
                'event_id' => $event->id,
                'sku_id' => $sku->id,
                'ticket_code' => UniqueCodeHelper::generateUniqueCode('tickets', 'ticket_code'),
                'status' => 'available',
            ]);
        }

        return redirect("/vendor/events/{$eventId}/skus")->with('success', 'SKU berhasil dibuat.');
    }
}
