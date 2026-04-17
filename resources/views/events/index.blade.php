<x-layouts.landing title="Event - JagoEvent">
    <div class="min-h-screen bg-surface-50">

        {{-- Hero Header --}}
        <div class="bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 pt-28 pb-14">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl">
                    <h1 class="text-3xl sm:text-4xl font-extrabold text-white">Jelajahi Event</h1>
                    <p class="mt-3 text-lg text-white/70">Temukan event menarik dan beli tiketnya sekarang</p>
                </div>

                {{-- Search --}}
                <form method="GET" action="/events" class="mt-8 max-w-2xl">
                    <div class="flex gap-3">
                        <div class="relative flex-1">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-surface-400"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama event..."
                                class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-white text-surface-900 placeholder-surface-400 text-sm focus:outline-none focus:ring-2 focus:ring-primary-300 shadow-lg" />
                        </div>
                        @if (request('category_id'))
                            <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                        @endif
                        @if (request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                        @if (request('min_price'))
                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        @endif
                        @if (request('max_price'))
                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        @endif
                        <button type="submit"
                            class="px-6 py-3.5 bg-white text-primary-700 font-semibold text-sm rounded-xl hover:bg-primary-50 transition-colors shadow-lg">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 pb-16">
            <div class="flex flex-col lg:flex-row gap-6">

                {{-- Sidebar --}}
                <aside class="lg:w-60 shrink-0">
                    <div
                        class="bg-white rounded-xl border border-surface-200 shadow-sm sticky top-24 divide-y divide-surface-100">
                        {{-- Kategori --}}
                        <div class="p-4">
                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-surface-400 mb-3">Kategori
                            </h3>
                            <nav class="space-y-0.5">
                                @php
                                    $catUrl = fn($catId = null) => '/events?' .
                                        http_build_query(
                                            array_filter([
                                                'category_id' => $catId,
                                                'search' => request('search'),
                                                'status' => request('status'),
                                                'min_price' => request('min_price'),
                                                'max_price' => request('max_price'),
                                            ]),
                                        );
                                @endphp
                                <a href="{{ $catUrl() }}" @class([
                                    'flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-colors',
                                    'bg-primary-50 text-primary-700 font-semibold' => !request('category_id'),
                                    'text-surface-600 hover:bg-surface-50' => request('category_id'),
                                ])>
                                    Semua
                                    <span
                                        class="text-[11px] tabular-nums {{ !request('category_id') ? 'text-primary-500' : 'text-surface-400' }}">{{ $events->total() }}</span>
                                </a>
                                @foreach ($categories as $category)
                                    <a href="{{ $catUrl($category->id) }}"
                                        @class([
                                            'block px-3 py-2 rounded-lg text-sm transition-colors',
                                            'bg-primary-50 text-primary-700 font-semibold' =>
                                                request('category_id') == $category->id,
                                            'text-surface-600 hover:bg-surface-50' =>
                                                request('category_id') != $category->id,
                                        ])>{{ $category->name }}</a>
                                @endforeach
                            </nav>
                        </div>

                        {{-- Status --}}
                        <div class="p-4">
                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-surface-400 mb-3">Status
                            </h3>
                            <nav class="space-y-0.5">
                                @php
                                    $statusUrl = fn($s = null) => '/events?' .
                                        http_build_query(
                                            array_filter([
                                                'category_id' => request('category_id'),
                                                'search' => request('search'),
                                                'status' => $s,
                                                'min_price' => request('min_price'),
                                                'max_price' => request('max_price'),
                                            ]),
                                        );
                                    $statuses = [
                                        '' => ['Semua', null],
                                        'upcoming' => ['Akan Datang', 'bg-primary-500'],
                                        'ongoing' => ['Berlangsung', 'bg-emerald-500'],
                                        'past' => ['Selesai', 'bg-surface-400'],
                                    ];
                                @endphp
                                @foreach ($statuses as $val => [$label, $dot])
                                    <a href="{{ $statusUrl($val ?: null) }}" @class([
                                        'flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm transition-colors',
                                        'bg-primary-50 text-primary-700 font-semibold' =>
                                            request('status', '') === $val,
                                        'text-surface-600 hover:bg-surface-50' => request('status', '') !== $val,
                                    ])>
                                        @if ($dot)
                                            <span class="w-2 h-2 rounded-full {{ $dot }}"></span>
                                        @endif
                                        {{ $label }}
                                    </a>
                                @endforeach
                            </nav>
                        </div>

                        {{-- Rentang Harga --}}
                        <div class="p-4">
                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-surface-400 mb-3">Rentang
                                Harga</h3>
                            <form method="GET" action="/events" class="space-y-2.5">
                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif
                                @if (request('category_id'))
                                    <input type="hidden" name="category_id" value="{{ request('category_id') }}">
                                @endif
                                @if (request('status'))
                                    <input type="hidden" name="status" value="{{ request('status') }}">
                                @endif
                                <div>
                                    <input type="number" name="min_price" value="{{ request('min_price') }}"
                                        placeholder="Harga min" min="0" step="1000"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-surface-200 text-surface-700 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-300" />
                                </div>
                                <div>
                                    <input type="number" name="max_price" value="{{ request('max_price') }}"
                                        placeholder="Harga max" min="0" step="1000"
                                        class="w-full px-3 py-2 text-sm rounded-lg border border-surface-200 text-surface-700 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-primary-300" />
                                </div>
                                <button type="submit"
                                    class="w-full px-3 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors">
                                    Terapkan
                                </button>
                            </form>
                        </div>

                        {{-- Reset --}}
                        @if (request('search') || request('category_id') || request('status') || request('min_price') || request('max_price'))
                            <div class="p-4">
                                <a href="/events"
                                    class="flex items-center justify-center gap-1.5 w-full px-3 py-2.5 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset Filter
                                </a>
                            </div>
                        @endif
                    </div>
                </aside>

                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    {{-- Results --}}
                    <div
                        class="flex items-center justify-between mb-5 bg-white rounded-xl border border-surface-200 px-4 py-3">
                        <p class="text-sm text-surface-700">
                            Ditemukan <span class="font-bold text-primary-600">{{ $events->total() }}</span> event
                            @if (request('search'))
                                untuk "<span class="font-semibold text-surface-900">{{ request('search') }}</span>"
                            @endif
                            @if (request('category_id'))
                                @php $activeCat = $categories->firstWhere('id', request('category_id')); @endphp
                                @if ($activeCat)
                                    di kategori <span
                                        class="font-semibold text-surface-900">{{ $activeCat->name }}</span>
                                @endif
                            @endif
                        </p>
                    </div>

                    @if ($events->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                            @foreach ($events as $event)
                                @php
                                    // Picsum provides reliable placeholder images
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
                                    $categoryName = $event->eventCategory->name ?? '';
                                    $seeds = $categorySeeds[$categoryName] ?? $defaultSeeds;
                                    $seed = $seeds[$event->id % count($seeds)];
                                    $eventImg = "https://picsum.photos/seed/{$seed}/400/250";

                                    $status = $event->status ?? 'upcoming';
                                    $statusCfg = match ($status) {
                                        'ongoing' => ['bg-emerald-500', 'Berlangsung'],
                                        'past' => ['bg-surface-600/80 backdrop-blur', 'Selesai'],
                                        default => ['bg-primary-500', 'Akan Datang'],
                                    };
                                @endphp
                                <a href="/events/{{ $event->id }}" class="group">
                                    <div
                                        class="bg-white rounded-2xl border border-surface-200 overflow-hidden hover:shadow-xl hover:border-primary-200 hover:-translate-y-1 transition-all duration-300">
                                        {{-- Image --}}
                                        <div class="aspect-[16/10] overflow-hidden relative">
                                            <img src="{{ $eventImg }}" alt="{{ $event->name }}"
                                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700" />
                                            {{-- Gradient overlay --}}
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-black/30 via-transparent to-transparent">
                                            </div>
                                            {{-- Status --}}
                                            <span
                                                class="absolute top-3 left-3 inline-flex items-center gap-1 px-2.5 py-1 text-[11px] font-bold text-white rounded-lg {{ $statusCfg[0] }}">
                                                {{ $statusCfg[1] }}
                                            </span>
                                            {{-- Price overlay --}}
                                            @if ($event->skus->count() > 0)
                                                <div class="absolute bottom-3 right-3">
                                                    <span
                                                        class="px-2.5 py-1 bg-white/95 backdrop-blur-sm text-primary-700 text-xs font-bold rounded-lg shadow-sm">
                                                        Rp {{ number_format($event->skus->min('price'), 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Content --}}
                                        <div class="p-4">
                                            <div class="flex items-center gap-2 mb-2">
                                                @if ($event->eventCategory)
                                                    <span
                                                        class="inline-flex items-center px-2 py-0.5 text-[11px] font-semibold text-primary-700 bg-primary-50 rounded-md">{{ $event->eventCategory->name }}</span>
                                                @endif
                                            </div>
                                            <h3
                                                class="font-bold text-surface-900 group-hover:text-primary-600 transition-colors line-clamp-2 leading-snug">
                                                {{ $event->name }}</h3>
                                            @if ($event->vendor)
                                                <p class="mt-2 text-sm text-surface-500 flex items-center gap-1.5">
                                                    <span
                                                        class="w-5 h-5 rounded-full bg-surface-100 flex items-center justify-center text-[10px] font-bold text-surface-500 shrink-0">{{ strtoupper(substr($event->vendor->name, 0, 1)) }}</span>
                                                    {{ $event->vendor->name }}
                                                </p>
                                            @endif
                                            <div class="mt-3 flex items-center gap-1.5 text-xs text-surface-400">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                                @if ($event->end_date)
                                                    — {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if ($events->hasPages())
                            <div class="mt-10 flex flex-col sm:flex-row items-center justify-between gap-4">
                                <p class="text-sm text-surface-500">
                                    Menampilkan <span
                                        class="font-semibold text-surface-700">{{ $events->firstItem() }}-{{ $events->lastItem() }}</span>
                                    dari <span class="font-semibold text-surface-700">{{ $events->total() }}</span>
                                    event
                                </p>
                                <nav class="flex items-center gap-1">
                                    {{-- Previous --}}
                                    @if ($events->onFirstPage())
                                        <span
                                            class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-300 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </span>
                                    @else
                                        <a href="{{ $events->previousPageUrl() }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 19l-7-7 7-7" />
                                            </svg>
                                        </a>
                                    @endif

                                    {{-- Pages --}}
                                    @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                                        @if ($page == $events->currentPage())
                                            <span
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary-600 text-white text-sm font-semibold shadow-sm">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-600 hover:bg-primary-50 hover:text-primary-600 text-sm font-medium transition-colors">{{ $page }}</a>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if ($events->hasMorePages())
                                        <a href="{{ $events->nextPageUrl() }}"
                                            class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </a>
                                    @else
                                        <span
                                            class="w-9 h-9 flex items-center justify-center rounded-lg text-surface-300 cursor-not-allowed">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5l7 7-7 7" />
                                            </svg>
                                        </span>
                                    @endif
                                </nav>
                            </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl border border-surface-200 py-20 text-center">
                            <div
                                class="w-20 h-20 mx-auto bg-surface-100 rounded-2xl flex items-center justify-center mb-5">
                                <svg class="w-10 h-10 text-surface-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-surface-900">Event tidak ditemukan</h3>
                            <p class="mt-2 text-sm text-surface-500 max-w-sm mx-auto">Coba ubah filter atau kata kunci
                                pencarian.</p>
                            <a href="/events"
                                class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-xl hover:bg-primary-700 transition-colors">
                                Lihat Semua Event
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-surface-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-12 grid grid-cols-2 sm:grid-cols-4 gap-8">
                <div class="col-span-2 sm:col-span-1">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-lg font-bold text-white">JagoEvent</span>
                    </div>
                    <p class="text-sm text-surface-400 leading-relaxed">Platform marketplace tiket event terpercaya di
                        Indonesia.</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-surface-500 mb-4">Event</h4>
                    <ul class="space-y-2">
                        <li><a href="/events"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Semua Event</a>
                        </li>
                        <li><a href="/events?status=upcoming"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Akan Datang</a>
                        </li>
                        <li><a href="/events?status=ongoing"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Berlangsung</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-surface-500 mb-4">Kategori</h4>
                    <ul class="space-y-2">
                        @foreach ($categories->take(5) as $cat)
                            <li><a href="/events?category_id={{ $cat->id }}"
                                    class="text-sm text-surface-400 hover:text-white transition-colors">{{ $cat->name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-surface-500 mb-4">Akun</h4>
                    <ul class="space-y-2">
                        <li><a href="/login"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Masuk</a></li>
                        <li><a href="/register"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Daftar</a></li>
                        <li><a href="/register/vendor"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Jadi Vendor</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="py-5 border-t border-surface-800 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-surface-600">&copy; {{ date('Y') }} JagoEvent. All rights reserved.</p>
                <p class="text-xs text-surface-600">Powered by <a href="https://jagoflutter.com" target="_blank"
                        class="text-primary-400 hover:text-primary-300 font-medium transition-colors">JagoFlutter.com</a>
                </p>
            </div>
        </div>
    </footer>
</x-layouts.landing>
