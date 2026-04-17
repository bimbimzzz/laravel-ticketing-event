<?php

namespace App\Http\Controllers\Web;

use App\Exports\EventsExport;
use App\Exports\OrdersExport;
use App\Exports\RevenueReportExport;
use App\Exports\UsersExport;
use App\Exports\VendorsExport;
use App\Helpers\DemoHelper;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Order;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use App\Services\CancelOrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'vendors' => Vendor::count(),
            'events' => Event::count(),
            'orders' => Order::count(),
            'revenue' => Order::where('status_payment', 'success')->sum('total_price'),
            'ticketsSold' => Ticket::whereIn('status', ['sold', 'redeem'])->count(),
            'pendingVendors' => Vendor::where('verify_status', 'pending')->count(),
            'pendingOrders' => Order::where('status_payment', 'pending')->count(),
        ];

        $recentOrders = Order::with(['user', 'event'])->latest()->limit(5)->get();
        $pendingVendors = Vendor::with('user')->where('verify_status', 'pending')->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'pendingVendors'));
    }

    // --- Users ---
    public function users(Request $request)
    {
        $query = User::withCount('orders');

        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"));
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        return view('admin.users', compact('users'));
    }

    // --- Vendors ---
    public function vendors(Request $request)
    {
        $query = Vendor::with('user')->withCount('events');

        if ($request->filled('status')) {
            $query->where('verify_status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $vendors = $query->latest()->paginate(15)->withQueryString();
        return view('admin.vendors', compact('vendors'));
    }

    public function vendorUpdateStatus(Request $request, $id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->update(['verify_status' => $request->status]);

        // Send email notification (skip for demo accounts)
        try {
            if (in_array($request->status, ['approved', 'rejected']) && $vendor->user && !DemoHelper::isDemoAccount($vendor->user->email)) {
                \Illuminate\Support\Facades\Mail::to($vendor->user->email)
                    ->send(new \App\Mail\VendorStatusMail($vendor, $request->status));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Failed to send vendor status email: ' . $e->getMessage());
        }

        return back()->with('success', "Vendor \"{$vendor->name}\" status diubah ke {$request->status}.");
    }

    // --- Events ---
    public function events(Request $request)
    {
        $query = Event::with(['vendor', 'eventCategory'])->withCount('skus');

        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        $events = $query->latest()->paginate(15)->withQueryString();
        return view('admin.events', compact('events'));
    }

    // --- Orders ---
    public function orders(Request $request)
    {
        $query = Order::with(['user', 'event']);

        if ($request->filled('status')) {
            $query->where('status_payment', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();
        return view('admin.orders', compact('orders'));
    }

    // --- Refunds ---
    public function refunds(Request $request)
    {
        $tab = $request->get('tab', 'pending');

        $statuses = match ($tab) {
            'history' => ['refunded', 'success'],
            default => ['refund_pending'],
        };

        $query = Order::with(['user', 'event'])
            ->whereIn('status_payment', $statuses)
            ->when($tab === 'history', fn($q) => $q->whereNotNull('cancel_reason'));

        if ($request->filled('search')) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('email', 'like', "%{$request->search}%"));
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $counts = [
            'pending' => Order::where('status_payment', 'refund_pending')->count(),
            'history' => Order::whereIn('status_payment', ['refunded', 'success'])->whereNotNull('cancel_reason')->count(),
        ];

        return view('admin.refunds', compact('orders', 'tab', 'counts'));
    }

    public function approveRefund(Request $request, $id)
    {
        $request->validate([
            'refund_proof' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'refund_note' => 'required|string|min:5',
        ]);

        $order = Order::with('orderTickets.ticket.sku')->findOrFail($id);

        $file = $request->file('refund_proof');
        $filename = time() . '_' . $id . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/refunds'), $filename);

        $cancelService = app(CancelOrderService::class);
        $cancelService->approveRefund($order, $request->refund_note, $filename);

        return back()->with('success', "Refund untuk order #{$id} berhasil disetujui.");
    }

    public function rejectRefund(Request $request, $id)
    {
        $request->validate([
            'refund_note' => 'required|string|min:5',
        ]);

        $order = Order::with('orderTickets.ticket.sku')->findOrFail($id);

        $cancelService = app(CancelOrderService::class);
        $cancelService->rejectRefund($order, $request->refund_note);

        return back()->with('success', "Refund untuk order #{$id} ditolak. Tiket dikembalikan.");
    }

    // --- Reports ---
    public function reports(Request $request)
    {
        $period = $request->get('period', '30'); // days

        // Revenue per day — fill all days in range
        $revenueRaw = Order::where('status_payment', 'success')
            ->where('created_at', '>=', now()->subDays($period))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $revenueChart = collect();
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $revenueChart->push((object) [
                'date' => $date,
                'total' => (float) ($revenueRaw[$date] ?? 0),
            ]);
        }

        // Orders per day (all statuses)  — fill all days
        $ordersRaw = Order::where('created_at', '>=', now()->subDays($period))
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date');

        $ordersChart = collect();
        for ($i = $period - 1; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $ordersChart->push((object) [
                'date' => $date,
                'count' => (int) ($ordersRaw[$date] ?? 0),
            ]);
        }

        // Revenue by category
        $revenueByCategory = Order::where('orders.status_payment', 'success')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->join('event_categories', 'events.event_category_id', '=', 'event_categories.id')
            ->select('event_categories.name as category', DB::raw('SUM(orders.total_price) as total'), DB::raw('COUNT(orders.id) as count'))
            ->groupBy('event_categories.name')
            ->orderByDesc('total')
            ->get();

        // Top events by revenue
        $topEvents = Order::where('orders.status_payment', 'success')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->join('vendors', 'events.vendor_id', '=', 'vendors.id')
            ->select('events.name as event_name', 'vendors.name as vendor_name', DB::raw('SUM(orders.total_price) as revenue'), DB::raw('SUM(orders.quantity) as tickets_sold'))
            ->groupBy('events.id', 'events.name', 'vendors.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Top vendors by revenue
        $topVendors = Order::where('orders.status_payment', 'success')
            ->join('events', 'orders.event_id', '=', 'events.id')
            ->join('vendors', 'events.vendor_id', '=', 'vendors.id')
            ->select('vendors.name as vendor_name', DB::raw('SUM(orders.total_price) as revenue'), DB::raw('COUNT(DISTINCT events.id) as events_count'), DB::raw('SUM(orders.quantity) as tickets_sold'))
            ->groupBy('vendors.id', 'vendors.name')
            ->orderByDesc('revenue')
            ->limit(10)
            ->get();

        // Order status distribution
        $orderStatus = Order::select('status_payment', DB::raw('COUNT(*) as count'))
            ->groupBy('status_payment')
            ->pluck('count', 'status_payment');

        // Ticket status distribution
        $ticketStatus = Ticket::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        // Summary stats
        $summary = [
            'totalRevenue' => Order::where('status_payment', 'success')->sum('total_price'),
            'totalOrders' => Order::count(),
            'successOrders' => Order::where('status_payment', 'success')->count(),
            'avgOrderValue' => Order::where('status_payment', 'success')->avg('total_price') ?? 0,
            'totalTicketsSold' => Ticket::whereIn('status', ['sold', 'redeem'])->count(),
            'totalTicketsAvailable' => Ticket::where('status', 'available')->count(),
            'conversionRate' => Order::count() > 0
                ? round(Order::where('status_payment', 'success')->count() / Order::count() * 100, 1)
                : 0,
            'newUsersThisMonth' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.reports', compact(
            'revenueChart', 'ordersChart', 'revenueByCategory',
            'topEvents', 'topVendors', 'orderStatus', 'ticketStatus',
            'summary', 'period'
        ));
    }

    // --- Exports ---
    public function exportUsers(Request $request)
    {
        return Excel::download(new UsersExport($request->search), 'users-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportVendors(Request $request)
    {
        return Excel::download(new VendorsExport($request->search, $request->status), 'vendors-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportEvents(Request $request)
    {
        return Excel::download(new EventsExport($request->search), 'events-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportOrders(Request $request)
    {
        return Excel::download(new OrdersExport($request->search, $request->status), 'orders-' . now()->format('Ymd') . '.xlsx');
    }

    public function exportReports(Request $request)
    {
        $period = (int) $request->get('period', 30);
        return Excel::download(new RevenueReportExport($period), 'laporan-revenue-' . now()->format('Ymd') . '.xlsx');
    }

    // --- Categories ---
    public function categories()
    {
        $categories = EventCategory::withCount('events')->get();
        return view('admin.categories', compact('categories'));
    }

    public function categoryStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:event_categories,name']);
        EventCategory::create(['name' => $request->name]);
        return back()->with('success', "Kategori \"{$request->name}\" berhasil ditambahkan.");
    }

    public function categoryDestroy($id)
    {
        if (DemoHelper::isDemoAccount()) {
            return back()->with('error', 'Akun demo tidak dapat menghapus data. Silakan daftar akun baru.');
        }

        $cat = EventCategory::findOrFail($id);
        $cat->delete();
        return back()->with('success', "Kategori \"{$cat->name}\" berhasil dihapus.");
    }
}
