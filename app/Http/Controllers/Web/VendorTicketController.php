<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;

class VendorTicketController extends Controller
{
    public function showCheck()
    {
        return view('vendor.tickets.check');
    }

    public function check(Request $request)
    {
        $request->validate([
            'ticket_code' => 'required|string',
        ]);

        $ticket = Ticket::with(['event.vendor', 'sku'])
            ->where('ticket_code', $request->ticket_code)
            ->first();

        if (!$ticket) {
            return back()->with('error', 'Tiket tidak ditemukan.')->withInput();
        }

        // Verify ticket belongs to vendor's event
        $vendor = auth()->user()->vendor;
        if ($ticket->event->vendor_id !== $vendor->id) {
            return back()->with('error', 'Tiket tidak ditemukan.')->withInput();
        }

        if ($ticket->status === 'redeem') {
            return back()->with('error', 'Tiket sudah pernah digunakan.')->withInput();
        }

        if (!in_array($ticket->status, ['sold', 'booked'])) {
            return back()->with('error', 'Tiket tidak valid untuk redeem.')->withInput();
        }

        $ticket->update([
            'status' => 'redeem',
            'ticket_date' => now(),
        ]);

        return back()->with('success', 'Tiket berhasil di-redeem! Kode: ' . $ticket->ticket_code);
    }

    public function showBulkCheck()
    {
        return view('vendor.tickets.bulk-check');
    }

    public function bulkCheck(Request $request)
    {
        $request->validate([
            'ticket_codes' => 'required|string',
        ]);

        $vendor = auth()->user()->vendor;
        $vendorEventIds = $vendor->events()->pluck('id');

        $codes = array_filter(array_map('trim', explode("\n", $request->ticket_codes)));
        $results = ['success' => [], 'failed' => []];

        foreach ($codes as $code) {
            $ticket = Ticket::with(['event'])
                ->where('ticket_code', $code)
                ->first();

            if (!$ticket) {
                $results['failed'][] = ['code' => $code, 'reason' => 'Tiket tidak ditemukan'];
                continue;
            }

            if (!$vendorEventIds->contains($ticket->event_id)) {
                $results['failed'][] = ['code' => $code, 'reason' => 'Tiket bukan milik vendor Anda'];
                continue;
            }

            if ($ticket->status === 'redeem') {
                $results['failed'][] = ['code' => $code, 'reason' => 'Tiket sudah di-redeem'];
                continue;
            }

            if (!in_array($ticket->status, ['sold', 'booked'])) {
                $results['failed'][] = ['code' => $code, 'reason' => 'Status tiket tidak valid'];
                continue;
            }

            $ticket->update(['status' => 'redeem', 'ticket_date' => now()]);
            $results['success'][] = $code;
        }

        return back()->with('results', $results);
    }
}
