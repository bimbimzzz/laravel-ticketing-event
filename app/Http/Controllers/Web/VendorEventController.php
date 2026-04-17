<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use Illuminate\Http\Request;
use App\Helpers\DemoHelper;
use Illuminate\Support\Facades\Gate;

class VendorEventController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        $events = Event::with('eventCategory')
            ->where('vendor_id', $vendor->id)
            ->latest()
            ->paginate(10);

        return view('vendor.events.index', compact('events'));
    }

    public function create()
    {
        $categories = EventCategory::all();
        return view('vendor.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $vendor = auth()->user()->vendor;
        if ($vendor->verify_status !== 'approved') {
            return redirect('/vendor/dashboard')->with('error', 'Vendor belum diverifikasi. Anda tidak bisa membuat event.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $vendor = auth()->user()->vendor;

        $event = Event::create([
            'vendor_id' => $vendor->id,
            'event_category_id' => $validated['event_category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'image' => 'default.png',
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            $event->update(['image' => $filename]);
        }

        return redirect('/vendor/events')->with('success', 'Event berhasil dibuat.');
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        Gate::authorize('update', $event);

        $categories = EventCategory::all();
        return view('vendor.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        Gate::authorize('update', $event);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'event_category_id' => 'required|exists:event_categories,id',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event->update([
            'event_category_id' => $validated['event_category_id'],
            'name' => $validated['name'],
            'description' => $validated['description'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        if ($request->hasFile('image')) {
            if ($event->image && $event->image !== 'default.png') {
                $oldPath = public_path('images/events/' . $event->image);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            $event->update(['image' => $filename]);
        }

        return redirect('/vendor/events')->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy($id)
    {
        if (DemoHelper::isDemoAccount()) {
            return redirect('/vendor/events')->with('error', 'Akun demo tidak dapat menghapus data. Silakan daftar akun baru.');
        }

        $event = Event::findOrFail($id);
        Gate::authorize('delete', $event);

        $event->delete();

        return redirect('/vendor/events')->with('success', 'Event berhasil dihapus.');
    }
}
