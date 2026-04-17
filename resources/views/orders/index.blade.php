<x-layouts.landing title="Pesanan Saya - JagoEvent" :navDark="true">
    @php
        $categorySeeds = [
            'Musik' => [1015, 1033, 1044],
            'Pantai' => [1024, 1029, 1051],
            'Gunung' => [1018, 1036, 1039],
            'Budaya' => [1060, 1069, 1076],
            'Olahraga' => [1058, 1070, 1077],
            'Kuliner' => [1080, 292, 312],
            'Teknologi' => [180, 160, 201],
            'Keluarga' => [1073, 1074, 1064],
        ];
        $defaultSeeds = [1052, 1053, 1057];
    @endphp

    <div class="min-h-screen bg-surface-50 pt-20">

        {{-- Header --}}
        <div class="bg-white border-b border-surface-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold text-surface-900">Pesanan Saya</h1>
                        <p class="mt-1 text-sm text-surface-500">Riwayat pembelian tiket event kamu</p>
                    </div>
                    <a href="/events"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari Event
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            @if ($orders->count() > 0)
                {{-- Stats --}}
                @php
                    $totalOrders = $orders->total();
                    $successOrders = \App\Models\Order::where('user_id', auth()->id())
                        ->where('status_payment', 'success')
                        ->count();
                    $pendingOrders = \App\Models\Order::where('user_id', auth()->id())
                        ->where('status_payment', 'pending')
                        ->count();
                    $totalSpent = \App\Models\Order::where('user_id', auth()->id())
                        ->where('status_payment', 'success')
                        ->sum('total_price');
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
                    <div class="bg-white rounded-xl border border-surface-200 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-surface-400 uppercase tracking-wider">Total</span>
                        </div>
                        <p class="text-2xl font-extrabold text-surface-900">{{ $totalOrders }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-surface-200 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 bg-emerald-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-surface-400 uppercase tracking-wider">Berhasil</span>
                        </div>
                        <p class="text-2xl font-extrabold text-emerald-600">{{ $successOrders }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-surface-200 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 bg-amber-50 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-surface-400 uppercase tracking-wider">Menunggu</span>
                        </div>
                        <p class="text-2xl font-extrabold text-amber-600">{{ $pendingOrders }}</p>
                    </div>
                    <div class="bg-white rounded-xl border border-surface-200 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="w-8 h-8 bg-surface-100 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-surface-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-xs font-medium text-surface-400 uppercase tracking-wider">Belanja</span>
                        </div>
                        <p class="text-xl font-extrabold text-surface-900">Rp
                            {{ number_format($totalSpent, 0, ',', '.') }}</p>
                    </div>
                </div>

                {{-- Order List --}}
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        @php
                            $catName = $order->event->eventCategory->name ?? '';
                            $seeds = $categorySeeds[$catName] ?? $defaultSeeds;
                            $seed = $seeds[($order->event->id ?? 0) % count($seeds)];
                            $orderImg = "https://picsum.photos/seed/{$seed}/200/120";

                            $statusConfig = match ($order->status_payment) {
                                'success' => [
                                    'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'Berhasil',
                                    'text-emerald-600',
                                ],
                                'pending' => [
                                    'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'Menunggu Bayar',
                                    'text-amber-600',
                                ],
                                'cancel' => ['bg-red-50 text-red-700 ring-red-600/20', 'Dibatalkan', 'text-red-600'],
                                default => [
                                    'bg-surface-50 text-surface-700 ring-surface-600/20',
                                    $order->status_payment,
                                    'text-surface-600',
                                ],
                            };
                        @endphp
                        <a href="/orders/{{ $order->id }}" class="block group">
                            <div
                                class="bg-white rounded-xl border border-surface-200 hover:border-primary-300 hover:shadow-lg transition-all overflow-hidden">
                                <div class="flex flex-col sm:flex-row">
                                    {{-- Image --}}
                                    <div class="shrink-0 w-full sm:w-36 h-28 sm:h-auto bg-surface-100">
                                        <img src="{{ $orderImg }}" alt="{{ $order->event->name ?? '' }}"
                                            class="w-full h-full object-cover" />
                                    </div>

                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0 p-4 sm:p-5">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="min-w-0">
                                                <h3
                                                    class="font-bold text-surface-900 group-hover:text-primary-600 transition-colors truncate text-base">
                                                    {{ $order->event->name ?? 'Event' }}
                                                </h3>
                                                <div
                                                    class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5 text-xs text-surface-500">
                                                    @if ($order->event && $order->event->vendor)
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-3.5 h-3.5" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                            </svg>
                                                            {{ $order->event->vendor->name }}
                                                        </span>
                                                    @endif
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        {{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}
                                                    </span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                                        </svg>
                                                        {{ $order->quantity }} tiket
                                                    </span>
                                                </div>
                                            </div>
                                            <span
                                                class="shrink-0 inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg ring-1 ring-inset {{ $statusConfig[0] }}">
                                                {{ $statusConfig[1] }}
                                            </span>
                                        </div>

                                        {{-- Bottom --}}
                                        <div
                                            class="flex items-center justify-between mt-4 pt-3 border-t border-surface-100">
                                            <span class="text-lg font-extrabold text-surface-900">Rp
                                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                                            <div class="flex items-center gap-3">
                                                @if ($order->status_payment === 'pending' && $order->payment_url)
                                                    <span
                                                        class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg ring-1 ring-amber-200">Bayar
                                                        Sekarang</span>
                                                @endif
                                                <span
                                                    class="text-xs text-surface-400">{{ $order->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if ($orders->hasPages())
                    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                        <p class="text-sm text-surface-500">
                            Menampilkan <span
                                class="font-semibold text-surface-700">{{ $orders->firstItem() }}-{{ $orders->lastItem() }}</span>
                            dari <span class="font-semibold text-surface-700">{{ $orders->total() }}</span> pesanan
                        </p>
                        <nav class="flex items-center gap-1">
                            @if ($orders->onFirstPage())
                                <span
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </span>
                            @else
                                <a href="{{ $orders->previousPageUrl() }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7" />
                                    </svg>
                                </a>
                            @endif
                            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                @if ($page == $orders->currentPage())
                                    <span
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-600 text-white text-sm font-semibold shadow-sm">{{ $page }}</span>
                                @else
                                    <a href="{{ $url }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-600 hover:bg-primary-50 hover:text-primary-600 text-sm font-medium transition-colors">{{ $page }}</a>
                                @endif
                            @endforeach
                            @if ($orders->hasMorePages())
                                <a href="{{ $orders->nextPageUrl() }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @else
                                <span
                                    class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-300 cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl border border-surface-200 py-20 text-center">
                    <div class="w-20 h-20 mx-auto bg-surface-100 rounded-2xl flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-surface-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-surface-900">Belum ada pesanan</h3>
                    <p class="mt-2 text-sm text-surface-500 max-w-sm mx-auto">
                        Kamu belum membeli tiket event apapun. Mulai jelajahi event seru dan dapatkan tiketnya!
                    </p>
                    <a href="/events"
                        class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Jelajahi Event
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-surface-900 mt-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-4 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-surface-600">&copy; {{ date('Y') }} JagoEvent. All rights reserved.</p>
                <p class="text-xs text-surface-600">Powered by <a href="https://jagoflutter.com" target="_blank"
                        class="text-primary-400 hover:text-primary-300 font-medium transition-colors">JagoFlutter.com</a>
                </p>
            </div>
        </div>
    </footer>
</x-layouts.landing>
