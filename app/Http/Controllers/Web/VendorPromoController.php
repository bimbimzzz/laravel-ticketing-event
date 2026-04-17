<?php

namespace App\Http\Controllers\Web;

use App\Helpers\DemoHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\PromoCode;
use Illuminate\Http\Request;

class VendorPromoController extends Controller
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
        $promos = PromoCode::where('event_id', $event->id)->latest()->get();

        return view('vendor.promos.index', compact('event', 'promos'));
    }

    public function store(Request $request, $eventId)
    {
        $event = $this->authorizeEvent($eventId);

        $request->validate([
            'code' => 'required|string|max:50|unique:promo_codes,code',
            'discount_type' => 'required|in:fixed,percentage',
            'discount_value' => 'required|integer|min:1',
            'max_usage' => 'required|integer|min:1',
            'expires_at' => 'required|date|after:today',
        ]);

        PromoCode::create([
            'event_id' => $event->id,
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'max_usage' => $request->max_usage,
            'used_count' => 0,
            'expires_at' => $request->expires_at,
        ]);

        return back()->with('success', 'Promo code berhasil ditambahkan.');
    }

    public function destroy($eventId, $promoId)
    {
        if (DemoHelper::isDemoAccount()) {
            return back()->with('error', 'Akun demo tidak dapat menghapus data. Silakan daftar akun baru.');
        }

        $event = $this->authorizeEvent($eventId);
        $promo = PromoCode::where('event_id', $event->id)->findOrFail($promoId);
        $promo->delete();

        return back()->with('success', 'Promo code berhasil dihapus.');
    }
}
