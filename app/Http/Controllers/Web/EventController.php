<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Ticket;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with(['vendor', 'eventCategory', 'skus']);

        if ($request->filled('category_id')) {
            $query->where('event_category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $now = now()->format('Y-m-d');
            match ($request->status) {
                'upcoming' => $query->where('start_date', '>', $now),
                'ongoing' => $query->where('start_date', '<=', $now)
                    ->where(fn ($q) => $q->whereNull('end_date')->orWhere('end_date', '>=', $now)),
                'past' => $query->where(fn ($q) => $q->where('end_date', '<', $now)
                    ->orWhere(fn ($q2) => $q2->whereNull('end_date')->where('start_date', '<', $now))),
                default => null,
            };
        }

        // Price range filter
        if ($request->filled('min_price')) {
            $query->whereHas('skus', fn($q) => $q->where('price', '>=', $request->min_price));
        }
        if ($request->filled('max_price')) {
            $query->whereHas('skus', fn($q) => $q->where('price', '<=', $request->max_price));
        }

        $events = $query->latest()->paginate(12)->withQueryString();
        $categories = EventCategory::all();

        return view('events.index', compact('events', 'categories'));
    }

    public function show($id)
    {
        $event = Event::with(['vendor', 'eventCategory', 'skus'])->findOrFail($id);

        // Get available ticket counts per SKU in a single query
        $availableCounts = Ticket::where('event_id', $event->id)
            ->where('status', 'available')
            ->selectRaw('sku_id, COUNT(*) as count')
            ->groupBy('sku_id')
            ->pluck('count', 'sku_id');

        $skusWithAvailability = $event->skus->map(function ($sku) use ($availableCounts) {
            $sku->available_tickets = $availableCounts[$sku->id] ?? 0;
            return $sku;
        });

        return view('events.show', compact('event', 'skusWithAvailability'));
    }
}
