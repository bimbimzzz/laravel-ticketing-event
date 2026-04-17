<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PromoCode;
use App\Models\Sku;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class VendorDashboardController extends Controller
{
    public function index()
    {
        $vendor = auth()->user()->vendor;
        $eventIds = $vendor->events()->pluck('id');

        $totalEvents = $eventIds->count();
        $totalOrders = Order::whereIn('event_id', $eventIds)->where('status_payment', 'success')->count();
        $totalRevenue = (int) Order::whereIn('event_id', $eventIds)->where('status_payment', 'success')->sum('total_price');
        $totalTicketsSold = Ticket::whereIn('event_id', $eventIds)->whereIn('status', ['sold', 'redeem'])->count();

        $recentOrders = Order::with(['user', 'event'])
            ->whereIn('event_id', $eventIds)
            ->latest()
            ->limit(10)
            ->get();

        // Revenue chart (last 30 days)
        $revenueRaw = Order::whereIn('event_id', $eventIds)
            ->where('status_payment', 'success')
            ->where('created_at', '>=', now()->subDays(30))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $revenueChart = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenueChart->push((object) [
                'date' => $date,
                'total' => (float) ($revenueRaw[$date] ?? 0),
            ]);
        }

        // Top SKUs by tickets sold
        $topSkus = Sku::whereIn('event_id', $eventIds)
            ->withCount(['tickets as sold_count' => function ($q) {
                $q->whereIn('status', ['sold', 'redeem']);
            }])
            ->with('event')
            ->orderByDesc('sold_count')
            ->limit(5)
            ->get();

        // Additional stats (moved from blade to avoid N+1)
        $ticketsRedeemed = Ticket::whereIn('event_id', $eventIds)->where('status', 'redeem')->count();
        $redemptionRate = $totalTicketsSold > 0 ? round(($ticketsRedeemed / $totalTicketsSold) * 100, 1) : 0;
        $pendingOrders = Order::whereIn('event_id', $eventIds)->where('status_payment', 'pending')->count();
        $totalPromos = PromoCode::whereIn('event_id', $eventIds)->count();

        return view('vendor.dashboard', compact(
            'totalEvents', 'totalOrders', 'totalRevenue', 'totalTicketsSold',
            'recentOrders', 'revenueChart', 'topSkus',
            'ticketsRedeemed', 'redemptionRate', 'pendingOrders', 'totalPromos'
        ));
    }
}
