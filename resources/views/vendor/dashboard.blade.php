<x-layouts.app title="Dashboard Vendor - KarcisDigital">
    <x-slot:header>Dashboard</x-slot:header>
    @php
        $vendor = auth()->user()->vendor;
        $user = auth()->user();
    @endphp

    <div class="space-y-6">
        {{-- Welcome Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-surface-900">Dashboard</h1>
                <p class="mt-1 text-sm text-surface-500">Selamat datang kembali, <span
                        class="font-medium text-surface-700">{{ $vendor->name }}</span></p>
            </div>
            @if ($vendor->verify_status === 'approved')
                <a href="{{ route('vendor.events.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Buat Event Baru
                </a>
            @endif
        </div>

        {{-- Verification Alert --}}
        @if ($vendor->verify_status === 'pending')
            <div class="flex items-start gap-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-amber-800">Menunggu Verifikasi</p>
                    <p class="text-sm text-amber-700 mt-0.5">Akun vendor Anda sedang diverifikasi oleh admin. Anda belum
                        bisa membuat event.</p>
                </div>
            </div>
        @elseif($vendor->verify_status === 'rejected')
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 rounded-xl">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-800">Verifikasi Ditolak</p>
                    <p class="text-sm text-red-700 mt-0.5">Akun vendor Anda telah ditolak. Silakan hubungi admin untuk
                        informasi lebih lanjut.</p>
                </div>
            </div>
        @endif

        {{-- Stats Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-primary-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Total Event</p>
                        <p class="text-2xl font-bold text-surface-900">{{ $totalEvents }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Pesanan Sukses</p>
                        <p class="text-2xl font-bold text-surface-900">{{ $totalOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-sky-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-surface-900">Rp
                            {{ number_format($totalRevenue, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-violet-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Tiket Terjual</p>
                        <p class="text-2xl font-bold text-surface-900">{{ $totalTicketsSold }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Analytics Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
            {{-- Revenue Chart (last 30 days) --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-surface-900">Pendapatan 30 Hari Terakhir</h2>
                        <p class="text-xs text-surface-500 mt-0.5">Rp
                            {{ number_format($revenueChart->sum('total'), 0, ',', '.') }} total</p>
                    </div>
                </div>
                @php $maxRevenue = $revenueChart->max('total') ?: 1; @endphp
                <div class="flex items-end gap-[3px]" style="height: 192px;">
                    @foreach ($revenueChart as $day)
                        @php
                            $pct = ($day->total / $maxRevenue) * 100;
                            $heightPx = max(round(($pct / 100) * 192), 4);
                        @endphp
                        <div class="flex-1 group relative">
                            <div
                                class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block z-10">
                                <div
                                    class="bg-surface-800 text-white text-[10px] px-2 py-1 rounded-lg whitespace-nowrap shadow-lg">
                                    <p class="font-semibold">{{ \Carbon\Carbon::parse($day->date)->format('d M') }}</p>
                                    <p>Rp {{ number_format($day->total, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="w-full rounded-t transition-colors {{ $day->total > 0 ? 'bg-primary-500 hover:bg-primary-600' : 'bg-surface-100 hover:bg-surface-200' }}"
                                style="height: {{ $heightPx }}px;"></div>
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-2">
                    <span
                        class="text-[10px] text-surface-400">{{ \Carbon\Carbon::parse($revenueChart->first()->date)->format('d M') }}</span>
                    <span
                        class="text-[10px] text-surface-400">{{ \Carbon\Carbon::parse($revenueChart->last()->date)->format('d M') }}</span>
                </div>
            </div>

            {{-- Top SKU --}}
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <h2 class="text-lg font-semibold text-surface-900 mb-4">SKU Terlaris</h2>
                @if ($topSkus->count() > 0)
                    <div class="space-y-3">
                        @foreach ($topSkus as $i => $sku)
                            @php $skuPct = $topSkus->first()->sold_count > 0 ? ($sku->sold_count / $topSkus->first()->sold_count) * 100 : 0; @endphp
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-medium text-surface-900 truncate">{{ $sku->name }}
                                        </p>
                                        <p class="text-[11px] text-surface-400 truncate">
                                            {{ $sku->event->name ?? '-' }}</p>
                                    </div>
                                    <span
                                        class="text-sm font-bold text-surface-900 ml-3 tabular-nums">{{ $sku->sold_count }}</span>
                                </div>
                                <div class="w-full h-1.5 bg-surface-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full {{ $i === 0 ? 'bg-primary-500' : ($i === 1 ? 'bg-primary-400' : 'bg-primary-300') }}"
                                        style="width: {{ $skuPct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-surface-500 py-8 text-center">Belum ada data penjualan</p>
                @endif
            </div>
        </div>

        {{-- Additional Stats Row --}}
        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Pesanan Pending</p>
                        <p class="text-2xl font-bold text-surface-900">{{ $pendingOrders }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Tiket Redeemed</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-2xl font-bold text-surface-900">{{ $ticketsRedeemed }}</p>
                            <span class="text-xs text-surface-400">({{ $redemptionRate }}%)</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-rose-50 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-surface-500">Promo Aktif</p>
                        <p class="text-2xl font-bold text-surface-900">{{ $totalPromos }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Orders Table --}}
        <div class="bg-white rounded-xl border border-surface-200">
            <div class="flex items-center justify-between p-5 border-b border-surface-200">
                <h2 class="text-lg font-semibold text-surface-900">Pesanan Terbaru</h2>
                @if ($totalEvents > 0)
                    <a href="{{ route('vendor.events.index') }}"
                        class="text-sm text-primary-600 hover:text-primary-700 font-medium">Lihat Semua Event</a>
                @endif
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-surface-100">
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Buyer</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Event</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Qty</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Total</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Status</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($recentOrders as $order)
                            <tr class="hover:bg-surface-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-8 h-8 rounded-full bg-surface-100 flex items-center justify-center text-xs font-semibold text-surface-600">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-surface-900">{{ $order->user->name }}</p>
                                            <p class="text-xs text-surface-500">{{ $order->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-3.5 text-surface-700">{{ Str::limit($order->event->name, 30) }}
                                </td>
                                <td class="px-5 py-3.5 text-surface-700">{{ $order->quantity }}</td>
                                <td class="px-5 py-3.5 font-medium text-surface-900">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $statusConfig = match ($order->status_payment) {
                                            'success' => ['bg-emerald-50 text-emerald-700', 'Sukses'],
                                            'pending' => ['bg-amber-50 text-amber-700', 'Pending'],
                                            'cancel' => ['bg-red-50 text-red-700', 'Batal'],
                                            default => ['bg-surface-50 text-surface-700', $order->status_payment],
                                        };
                                    @endphp
                                    <span
                                        class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-md {{ $statusConfig[0] }}">
                                        {{ $statusConfig[1] }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-surface-500 text-xs">
                                    {{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center">
                                    <svg class="mx-auto w-12 h-12 text-surface-300" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                    <p class="mt-3 text-sm text-surface-500">Belum ada pesanan masuk</p>
                                    @if ($vendor->verify_status === 'approved')
                                        <a href="{{ route('vendor.events.create') }}"
                                            class="mt-2 inline-block text-sm text-primary-600 hover:text-primary-700 font-medium">Buat
                                            event pertamamu</a>
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
