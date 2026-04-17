<x-layouts.landing title="{{ $event->name }} - KarcisDigital" :navDark="true">
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
        $categoryName = $event->eventCategory->name ?? '';
        $seeds = $categorySeeds[$categoryName] ?? $defaultSeeds;
        $seed = $seeds[$event->id % count($seeds)];
        $heroImg = "https://picsum.photos/seed/{$seed}/1200/500";

        $statusCfg = match ($event->status) {
            'ongoing' => ['bg-emerald-500', 'Berlangsung'],
            'past' => ['bg-surface-500', 'Selesai'],
            default => ['bg-primary-500', 'Akan Datang'],
        };

        $totalAvailable = $skusWithAvailability->sum('available_tickets');
        $totalStock = $event->skus->sum('stock');
    @endphp

    <div class="min-h-screen bg-surface-50 pt-20">

        {{-- Breadcrumb --}}
        <div class="bg-white border-b border-surface-200">
            <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex items-center gap-2 text-sm text-surface-500">
                    <a href="/events" class="hover:text-primary-600 transition-colors">Event</a>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-surface-900 font-medium truncate">{{ $event->name }}</span>
                </nav>
            </div>
        </div>

        {{-- Hero Image --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            <div class="relative rounded-2xl overflow-hidden h-[280px] sm:h-[340px]">
                <img src="{{ $heroImg }}" alt="{{ $event->name }}" class="w-full h-full object-cover" />
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                {{-- Badges on image --}}
                <div class="absolute top-4 left-4 flex items-center gap-2">
                    @if ($event->eventCategory)
                        <span
                            class="px-2.5 py-1 text-xs font-bold text-white bg-white/25 backdrop-blur-sm rounded-lg">{{ $event->eventCategory->name }}</span>
                    @endif
                    <span
                        class="px-2.5 py-1 text-xs font-bold text-white rounded-lg {{ $statusCfg[0] }}">{{ $statusCfg[1] }}</span>
                </div>
                {{-- Price on image --}}
                @if ($event->skus->count() > 0)
                    <div class="absolute bottom-4 right-4">
                        <span
                            class="px-3 py-1.5 bg-white/95 backdrop-blur-sm text-primary-700 text-sm font-bold rounded-xl shadow-sm">
                            Mulai Rp {{ number_format($event->skus->min('price'), 0, ',', '.') }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        {{-- Content --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row gap-6">

                {{-- Left Column --}}
                <div class="flex-1 min-w-0 space-y-5">

                    {{-- Title Card --}}
                    <div class="bg-white rounded-xl border border-surface-200 p-6">
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-surface-900">{{ $event->name }}</h1>
                        @if ($event->vendor)
                            <p class="mt-2 text-surface-500 text-sm">oleh <span
                                    class="font-semibold text-surface-700">{{ $event->vendor->name }}</span></p>
                        @endif

                        {{-- Quick Info --}}
                        <div class="flex flex-wrap gap-x-6 gap-y-3 mt-4 pt-4 border-t border-surface-100">
                            <div class="flex items-center gap-2 text-sm text-surface-600">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>{{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}@if ($event->end_date)
                                        — {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                    @endif
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-surface-600">
                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                </svg>
                                <span>{{ $totalAvailable }} tiket tersedia</span>
                            </div>
                            @if ($event->skus->count() > 0)
                                <div class="flex items-center gap-2 text-sm text-surface-600">
                                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span>Rp {{ number_format($event->skus->min('price'), 0, ',', '.') }} — Rp
                                        {{ number_format($event->skus->max('price'), 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Description --}}
                    <div class="bg-white rounded-xl border border-surface-200 p-6">
                        <h2 class="text-base font-bold text-surface-900 mb-3">Tentang Event</h2>
                        <div class="text-sm text-surface-600 leading-relaxed whitespace-pre-line">
                            {{ $event->description }}</div>
                    </div>

                    {{-- Vendor --}}
                    @if ($event->vendor)
                        <div class="bg-white rounded-xl border border-surface-200 p-6">
                            <h2 class="text-base font-bold text-surface-900 mb-4">Penyelenggara</h2>
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-11 h-11 rounded-xl bg-primary-100 flex items-center justify-center shrink-0">
                                    <span
                                        class="text-base font-bold text-primary-600">{{ strtoupper(substr($event->vendor->name, 0, 1)) }}</span>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-surface-900 text-sm">{{ $event->vendor->name }}</p>
                                    @if ($event->vendor->city)
                                        <p class="text-xs text-surface-500 flex items-center gap-1 mt-0.5">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            {{ $event->vendor->city }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @if ($event->vendor->description)
                                <p class="mt-3 text-sm text-surface-500 leading-relaxed">
                                    {{ $event->vendor->description }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Right: Order Panel --}}
                <div class="lg:w-[340px] shrink-0">
                    <div class="bg-white rounded-xl border border-surface-200 shadow-sm sticky top-24 overflow-hidden">
                        <div class="px-5 py-4 border-b border-surface-100">
                            <h2 class="text-base font-bold text-surface-900">Pilih Tiket</h2>
                        </div>

                        @auth
                            <form method="POST" action="/events/{{ $event->id }}/checkout" x-data="{
                                skus: @js($skusWithAvailability->map(fn($s) => ['id' => $s->id, 'price' => $s->price, 'available' => $s->available_tickets, 'qty' => 0])),
                                loading: false,
                                get total() { return this.skus.reduce((sum, s) => sum + (s.price * s.qty), 0) },
                                get totalQty() { return this.skus.reduce((sum, s) => sum + s.qty, 0) },
                                submitOrder(e) {
                                    this.loading = true;
                                    const form = e.target;
                                    // Remove old dynamic inputs
                                    form.querySelectorAll('.dynamic-input').forEach(el => el.remove());
                                    // Add only SKUs with qty > 0
                                    let idx = 0;
                                    this.skus.forEach(s => {
                                        if (s.qty > 0) {
                                            let skuInput = document.createElement('input');
                                            skuInput.type = 'hidden';
                                            skuInput.name = 'order_details[' + idx + '][sku_id]';
                                            skuInput.value = s.id;
                                            skuInput.className = 'dynamic-input';
                                            form.appendChild(skuInput);
                                            let qtyInput = document.createElement('input');
                                            qtyInput.type = 'hidden';
                                            qtyInput.name = 'order_details[' + idx + '][qty]';
                                            qtyInput.value = s.qty;
                                            qtyInput.className = 'dynamic-input';
                                            form.appendChild(qtyInput);
                                            idx++;
                                        }
                                    });
                                }
                            }"
                                x-on:submit="submitOrder($event)">
                                @csrf
                                <input type="hidden" name="event_date"
                                    value="{{ \Carbon\Carbon::parse($event->start_date)->format('Y-m-d') }}" />

                                <div class="p-4 space-y-3 max-h-[400px] overflow-y-auto">
                                    @foreach ($skusWithAvailability as $index => $sku)
                                        <div class="rounded-xl border p-3.5 transition-all"
                                            :class="skus[{{ $index }}].qty > 0 ? 'border-primary-300 bg-primary-50/40' :
                                                'border-surface-200'">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h4 class="font-semibold text-surface-900 text-sm">{{ $sku->name }}
                                                    </h4>
                                                    <span class="text-xs text-surface-400">{{ $sku->category }}</span>
                                                </div>
                                                <span class="text-sm font-bold text-primary-600">Rp
                                                    {{ number_format($sku->price, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex items-center justify-between mt-2.5">
                                                @if ($sku->available_tickets > 0)
                                                    <span class="text-xs text-surface-400">{{ $sku->available_tickets }}
                                                        tersedia</span>
                                                    <div class="flex items-center">
                                                        <button type="button"
                                                            @click="skus[{{ $index }}].qty = Math.max(0, skus[{{ $index }}].qty - 1)"
                                                            class="w-7 h-7 rounded-l-lg border border-surface-300 flex items-center justify-center text-surface-500 hover:bg-surface-100 text-xs">−</button>
                                                        <span
                                                            class="w-8 h-7 flex items-center justify-center border-t border-b border-surface-300 text-xs font-semibold text-surface-900 bg-white"
                                                            x-text="skus[{{ $index }}].qty"></span>
                                                        <button type="button"
                                                            @click="skus[{{ $index }}].qty = Math.min({{ $sku->available_tickets }}, skus[{{ $index }}].qty + 1)"
                                                            class="w-7 h-7 rounded-r-lg border border-surface-300 flex items-center justify-center text-surface-500 hover:bg-surface-100 text-xs">+</button>
                                                    </div>
                                                @else
                                                    <span class="text-xs text-red-500 font-medium">Habis</span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="px-4 pb-4">
                                    <div class="flex justify-between items-center py-3 border-t border-surface-100 mb-3">
                                        <span class="text-sm text-surface-500">Total <span x-show="totalQty > 0"
                                                class="text-surface-400"
                                                x-text="'(' + totalQty + ' tiket)'"></span></span>
                                        <span class="text-lg font-extrabold text-surface-900"
                                            x-text="'Rp ' + total.toLocaleString('id-ID')"></span>
                                    </div>
                                    <button type="submit" x-bind:disabled="totalQty === 0 || loading"
                                        class="w-full py-3 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 disabled:bg-surface-200 disabled:text-surface-400 disabled:cursor-not-allowed transition-colors flex items-center justify-center gap-2">
                                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="none"
                                            viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                                stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        <span x-text="loading ? 'Memproses...' : 'Lanjut ke Checkout'"></span>
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="p-5 text-center">
                                <div
                                    class="w-12 h-12 mx-auto bg-surface-100 rounded-xl flex items-center justify-center mb-3">
                                    <svg class="w-6 h-6 text-surface-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                                <p class="text-sm text-surface-600 font-medium mb-1">Masuk untuk membeli tiket</p>
                                <p class="text-xs text-surface-400 mb-4">Buat akun atau masuk untuk memesan</p>
                                <a href="/login"
                                    class="block w-full py-3 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-colors text-center">
                                    Masuk / Daftar
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Footer --}}
    <footer class="bg-surface-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="py-10 grid grid-cols-2 sm:grid-cols-4 gap-8">
                <div class="col-span-2 sm:col-span-1">
                    <div class="flex items-center gap-2 mb-3">
                        <div class="w-7 h-7 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-base font-bold text-white">KarcisDigital</span>
                    </div>
                    <p class="text-xs text-surface-400 leading-relaxed">Platform marketplace tiket event terpercaya di
                        Indonesia.</p>
                </div>
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-surface-500 mb-3">Event</h4>
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
                    <h4 class="text-xs font-bold uppercase tracking-widest text-surface-500 mb-3">Akun</h4>
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
                <div>
                    <h4 class="text-xs font-bold uppercase tracking-widest text-surface-500 mb-3">Bantuan</h4>
                    <ul class="space-y-2">
                        <li><a href="#"
                                class="text-sm text-surface-400 hover:text-white transition-colors">FAQ</a></li>
                        <li><a href="#"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Kontak</a></li>
                        <li><a href="#"
                                class="text-sm text-surface-400 hover:text-white transition-colors">Kebijakan
                                Privasi</a></li>
                    </ul>
                </div>
            </div>
            <div class="py-4 border-t border-surface-800 flex flex-col sm:flex-row items-center justify-between gap-2">
                <p class="text-xs text-surface-600">&copy; {{ date('Y') }} KarcisDigital. All rights reserved.</p>
                <p class="text-xs text-surface-600">Powered by <a href="https://jagoflutter.com" target="_blank"
                        class="text-primary-400 hover:text-primary-300 font-medium transition-colors">JagoFlutter.com</a>
                </p>
            </div>
        </div>
    </footer>
</x-layouts.landing>
