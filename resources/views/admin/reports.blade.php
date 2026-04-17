<x-layouts.admin title="Laporan - Admin KarcisDigital">
    <x-slot:header>Laporan & Analisis</x-slot:header>

    {{-- Period Filter --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-surface-500">Ringkasan performa platform</p>
        <div class="flex items-center gap-2">
            <form method="GET" class="flex items-center gap-2">
                <select name="period" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm border border-surface-300 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-primary-300">
                    <option value="7" {{ $period == '7' ? 'selected' : '' }}>7 Hari Terakhir</option>
                    <option value="30" {{ $period == '30' ? 'selected' : '' }}>30 Hari Terakhir</option>
                    <option value="90" {{ $period == '90' ? 'selected' : '' }}>3 Bulan Terakhir</option>
                    <option value="365" {{ $period == '365' ? 'selected' : '' }}>1 Tahun Terakhir</option>
                </select>
            </form>
            <a href="{{ route('admin.reports.export', ['period' => $period]) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Export Excel
            </a>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-9 h-9 bg-emerald-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Total Revenue</p>
            <p class="text-xl font-extrabold text-surface-900">Rp
                {{ number_format($summary['totalRevenue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-9 h-9 bg-primary-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Rata-rata Order</p>
            <p class="text-xl font-extrabold text-surface-900">Rp
                {{ number_format($summary['avgOrderValue'], 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-9 h-9 bg-sky-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Tiket Terjual</p>
            <p class="text-xl font-extrabold text-surface-900">{{ number_format($summary['totalTicketsSold']) }}</p>
            <p class="text-xs text-surface-400 mt-0.5">{{ number_format($summary['totalTicketsAvailable']) }} tersedia
            </p>
        </div>
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <div class="flex items-center gap-2 mb-2">
                <div class="w-9 h-9 bg-amber-50 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Conversion Rate</p>
            <p class="text-xl font-extrabold text-surface-900">{{ $summary['conversionRate'] }}%</p>
            <p class="text-xs text-surface-400 mt-0.5">{{ $summary['successOrders'] }}/{{ $summary['totalOrders'] }}
                order sukses</p>
        </div>
    </div>

    {{-- Revenue Chart --}}
    <div class="bg-white rounded-xl border border-surface-200 p-5 mb-6">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm font-bold text-surface-900">Revenue Harian</h3>
            <p class="text-xs text-surface-400">{{ $period }} hari terakhir</p>
        </div>
        @php
            $maxRevenue = $revenueChart->max('total') ?: 1;
            $totalPeriodRevenue = $revenueChart->sum('total');
        @endphp
        <p class="text-xs text-surface-500 mb-4">Total: <span class="font-semibold text-surface-700">Rp
                {{ number_format($totalPeriodRevenue, 0, ',', '.') }}</span></p>

        {{-- Y-axis labels + bars --}}
        <div class="flex gap-2">
            {{-- Y axis --}}
            <div class="flex flex-col justify-between text-[10px] text-surface-400 text-right w-16 shrink-0 pb-5">
                <span>Rp {{ number_format($maxRevenue / 1000000, 1) }}jt</span>
                <span>Rp {{ number_format($maxRevenue / 2000000, 1) }}jt</span>
                <span>0</span>
            </div>
            {{-- Bars --}}
            <div class="flex-1 flex items-end gap-px h-52 border-b border-l border-surface-200 relative">
                {{-- Horizontal grid lines --}}
                <div class="absolute inset-0 flex flex-col justify-between pointer-events-none">
                    <div class="border-b border-dashed border-surface-100"></div>
                    <div class="border-b border-dashed border-surface-100"></div>
                    <div></div>
                </div>
                @foreach ($revenueChart as $day)
                    @php
                        $height = $day->total > 0 ? max(2, ($day->total / $maxRevenue) * 100) : 0;
                    @endphp
                    <div class="flex-1 group relative flex flex-col items-center justify-end h-full z-10">
                        {{-- Tooltip --}}
                        @if ($day->total > 0)
                            <div
                                class="absolute -top-10 left-1/2 -translate-x-1/2 bg-surface-800 text-white text-[10px] px-2 py-1 rounded-md whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none z-20 shadow-lg">
                                <span class="font-semibold">Rp {{ number_format($day->total, 0, ',', '.') }}</span>
                                <br>{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}
                            </div>
                        @endif
                        <div class="{{ $day->total > 0 ? 'bg-primary-500 hover:bg-primary-600 cursor-pointer' : 'bg-surface-100' }} w-full rounded-t transition-colors"
                            style="height: {{ $height }}%"></div>
                    </div>
                @endforeach
            </div>
        </div>
        {{-- X axis labels --}}
        <div class="flex gap-2">
            <div class="w-16 shrink-0"></div>
            <div class="flex-1 flex justify-between mt-1.5">
                @php
                    $labelInterval = max(1, (int) ceil($revenueChart->count() / 7));
                @endphp
                @foreach ($revenueChart as $i => $day)
                    @if ($i % $labelInterval === 0 || $i === $revenueChart->count() - 1)
                        <span
                            class="text-[10px] text-surface-400">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</span>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Order Status Distribution --}}
        <div class="bg-white rounded-xl border border-surface-200 p-5">
            <h3 class="text-sm font-bold text-surface-900 mb-4">Distribusi Status Order</h3>
            @php
                $totalOrders = $orderStatus->sum() ?: 1;
                $statusConfig = [
                    'success' => [
                        'label' => 'Sukses',
                        'color' => 'bg-emerald-500',
                        'text' => 'text-emerald-700',
                        'bg' => 'bg-emerald-50',
                    ],
                    'pending' => [
                        'label' => 'Pending',
                        'color' => 'bg-amber-500',
                        'text' => 'text-amber-700',
                        'bg' => 'bg-amber-50',
                    ],
                    'cancel' => [
                        'label' => 'Batal',
                        'color' => 'bg-red-500',
                        'text' => 'text-red-700',
                        'bg' => 'bg-red-50',
                    ],
                ];
            @endphp
            {{-- Stacked bar --}}
            <div class="flex rounded-full overflow-hidden h-4 mb-4">
                @foreach ($statusConfig as $key => $cfg)
                    @if (($orderStatus[$key] ?? 0) > 0)
                        <div class="{{ $cfg['color'] }}"
                            style="width: {{ ($orderStatus[$key] / $totalOrders) * 100 }}%"></div>
                    @endif
                @endforeach
            </div>
            <div class="space-y-3">
                @foreach ($statusConfig as $key => $cfg)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full {{ $cfg['color'] }}"></div>
                            <span class="text-sm font-medium text-surface-700">{{ $cfg['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="text-sm font-bold text-surface-900">{{ number_format($orderStatus[$key] ?? 0) }}</span>
                            <span
                                class="text-xs text-surface-400 w-12 text-right">{{ round((($orderStatus[$key] ?? 0) / $totalOrders) * 100, 1) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ticket Status Distribution --}}
        <div class="bg-white rounded-xl border border-surface-200 p-5">
            <h3 class="text-sm font-bold text-surface-900 mb-4">Distribusi Status Tiket</h3>
            @php
                $totalTickets = $ticketStatus->sum() ?: 1;
                $ticketConfig = [
                    'available' => ['label' => 'Tersedia', 'color' => 'bg-surface-400', 'text' => 'text-surface-700'],
                    'booked' => ['label' => 'Dipesan', 'color' => 'bg-amber-500', 'text' => 'text-amber-700'],
                    'sold' => ['label' => 'Terjual', 'color' => 'bg-emerald-500', 'text' => 'text-emerald-700'],
                    'redeem' => ['label' => 'Digunakan', 'color' => 'bg-sky-500', 'text' => 'text-sky-700'],
                ];
            @endphp
            <div class="flex rounded-full overflow-hidden h-4 mb-4">
                @foreach ($ticketConfig as $key => $cfg)
                    @if (($ticketStatus[$key] ?? 0) > 0)
                        <div class="{{ $cfg['color'] }}"
                            style="width: {{ ($ticketStatus[$key] / $totalTickets) * 100 }}%"></div>
                    @endif
                @endforeach
            </div>
            <div class="space-y-3">
                @foreach ($ticketConfig as $key => $cfg)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-3 h-3 rounded-full {{ $cfg['color'] }}"></div>
                            <span class="text-sm font-medium text-surface-700">{{ $cfg['label'] }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span
                                class="text-sm font-bold text-surface-900">{{ number_format($ticketStatus[$key] ?? 0) }}</span>
                            <span
                                class="text-xs text-surface-400 w-12 text-right">{{ round((($ticketStatus[$key] ?? 0) / $totalTickets) * 100, 1) }}%</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Revenue by Category --}}
    <div class="bg-white rounded-xl border border-surface-200 p-5 mb-6">
        <h3 class="text-sm font-bold text-surface-900 mb-4">Revenue per Kategori</h3>
        @if ($revenueByCategory->count() > 0)
            @php $maxCatRevenue = $revenueByCategory->max('total') ?: 1; @endphp
            <div class="space-y-3">
                @foreach ($revenueByCategory as $cat)
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm font-medium text-surface-700">{{ $cat->category }}</span>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-surface-400">{{ $cat->count }} order</span>
                                <span class="text-sm font-bold text-surface-900">Rp
                                    {{ number_format($cat->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="w-full bg-surface-100 rounded-full h-2">
                            <div class="bg-primary-500 h-2 rounded-full"
                                style="width: {{ ($cat->total / $maxCatRevenue) * 100 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="py-8 text-center text-sm text-surface-400">Belum ada data</div>
        @endif
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Top Events --}}
        <div class="bg-white rounded-xl border border-surface-200">
            <div class="px-5 py-4 border-b border-surface-100">
                <h3 class="text-sm font-bold text-surface-900">Top 10 Event (Revenue)</h3>
            </div>
            <div class="divide-y divide-surface-100">
                @forelse($topEvents as $i => $event)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-full {{ $i < 3 ? 'bg-primary-600 text-white' : 'bg-surface-100 text-surface-500' }} flex items-center justify-center text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-surface-900 truncate">{{ $event->event_name }}</p>
                            <p class="text-xs text-surface-500">{{ $event->vendor_name }} &middot;
                                {{ number_format($event->tickets_sold) }} tiket</p>
                        </div>
                        <p class="text-sm font-bold text-surface-900 shrink-0">Rp
                            {{ number_format($event->revenue, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-surface-400">Belum ada data</div>
                @endforelse
            </div>
        </div>

        {{-- Top Vendors --}}
        <div class="bg-white rounded-xl border border-surface-200">
            <div class="px-5 py-4 border-b border-surface-100">
                <h3 class="text-sm font-bold text-surface-900">Top 10 Vendor (Revenue)</h3>
            </div>
            <div class="divide-y divide-surface-100">
                @forelse($topVendors as $i => $vendor)
                    <div class="px-5 py-3 flex items-center gap-3">
                        <span
                            class="w-6 h-6 rounded-full {{ $i < 3 ? 'bg-emerald-600 text-white' : 'bg-surface-100 text-surface-500' }} flex items-center justify-center text-xs font-bold shrink-0">{{ $i + 1 }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-surface-900 truncate">{{ $vendor->vendor_name }}</p>
                            <p class="text-xs text-surface-500">{{ $vendor->events_count }} event &middot;
                                {{ number_format($vendor->tickets_sold) }} tiket</p>
                        </div>
                        <p class="text-sm font-bold text-surface-900 shrink-0">Rp
                            {{ number_format($vendor->revenue, 0, ',', '.') }}</p>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-surface-400">Belum ada data</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
