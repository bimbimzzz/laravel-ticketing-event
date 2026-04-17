<x-layouts.landing title="Checkout - JagoEvent" :navDark="true">
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
        $catName = $event->eventCategory->name ?? '';
        $seeds = $categorySeeds[$catName] ?? $defaultSeeds;
        $seed = $seeds[$event->id % count($seeds)];
        $eventImg = "https://picsum.photos/seed/{$seed}/400/250";
    @endphp

    <div class="min-h-screen bg-surface-50 pt-20" x-data="{ loading: false }">

        {{-- Loading Overlay --}}
        <div x-show="loading" x-cloak
            class="fixed inset-0 z-[100] bg-white/80 backdrop-blur-sm flex items-center justify-center">
            <div class="text-center">
                <svg class="w-10 h-10 animate-spin text-primary-600 mx-auto mb-3" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm font-semibold text-surface-700">Memproses pesanan...</p>
                <p class="text-xs text-surface-500 mt-1">Mohon tunggu sebentar</p>
            </div>
        </div>

        {{-- Breadcrumb --}}
        <div class="bg-white border-b border-surface-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex items-center gap-2 text-sm text-surface-500">
                    <a href="/events" class="hover:text-primary-600 transition-colors">Event</a>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <a href="/events/{{ $event->id }}"
                        class="hover:text-primary-600 transition-colors truncate max-w-[200px]">{{ $event->name }}</a>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    <span class="text-surface-900 font-medium">Checkout</span>
                </nav>
            </div>
        </div>

        {{-- Header --}}
        <div class="bg-white border-b border-surface-200">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-surface-900">Checkout</h1>
                        <p class="text-sm text-surface-500">Periksa pesanan sebelum melanjutkan pembayaran</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Content --}}
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <form method="POST" action="/events/{{ $event->id }}/order" x-on:submit="loading = true">
                @csrf
                {{-- Pass order details as hidden inputs --}}
                @foreach ($validated['order_details'] as $i => $detail)
                    <input type="hidden" name="order_details[{{ $i }}][sku_id]"
                        value="{{ $detail['sku_id'] }}" />
                    <input type="hidden" name="order_details[{{ $i }}][qty]" value="{{ $detail['qty'] }}" />
                @endforeach
                <input type="hidden" name="event_date" value="{{ $validated['event_date'] }}" />

                <div class="flex flex-col lg:flex-row gap-6">

                    {{-- Left Column --}}
                    <div class="flex-1 min-w-0 space-y-5">

                        {{-- Event Info --}}
                        <div class="bg-white rounded-xl border border-surface-200 p-5">
                            <h2 class="text-sm font-bold text-surface-400 uppercase tracking-wider mb-4">Detail Event
                            </h2>
                            <div class="flex gap-4">
                                <div class="w-24 h-16 rounded-lg overflow-hidden bg-surface-100 shrink-0">
                                    <img src="{{ $eventImg }}" alt="{{ $event->name }}"
                                        class="w-full h-full object-cover" />
                                </div>
                                <div class="min-w-0">
                                    <h3 class="font-bold text-surface-900 truncate">{{ $event->name }}</h3>
                                    @if ($event->vendor)
                                        <p class="text-sm text-surface-500 mt-0.5">oleh {{ $event->vendor->name }}</p>
                                    @endif
                                    <div class="flex items-center gap-1.5 mt-1.5 text-xs text-surface-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ \Carbon\Carbon::parse($validated['event_date'])->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Buyer Info --}}
                        <div class="bg-white rounded-xl border border-surface-200 p-5">
                            <h2 class="text-sm font-bold text-surface-400 uppercase tracking-wider mb-4">Informasi
                                Pembeli</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="text-xs font-medium text-surface-500 mb-1 block">Nama Lengkap</label>
                                    <div
                                        class="flex items-center gap-2 px-3 py-2.5 bg-surface-50 rounded-lg border border-surface-200">
                                        <svg class="w-4 h-4 text-surface-400 shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        <span class="text-sm font-medium text-surface-900">{{ $user->name }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-surface-500 mb-1 block">Email</label>
                                    <div
                                        class="flex items-center gap-2 px-3 py-2.5 bg-surface-50 rounded-lg border border-surface-200">
                                        <svg class="w-4 h-4 text-surface-400 shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-sm font-medium text-surface-900">{{ $user->email }}</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-surface-500 mb-1 block">No. Telepon</label>
                                    <div
                                        class="flex items-center gap-2 px-3 py-2.5 bg-surface-50 rounded-lg border border-surface-200">
                                        <svg class="w-4 h-4 text-surface-400 shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                        <span
                                            class="text-sm font-medium text-surface-900">{{ $user->phone ?? '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ticket Details --}}
                        <div class="bg-white rounded-xl border border-surface-200 p-5">
                            <h2 class="text-sm font-bold text-surface-400 uppercase tracking-wider mb-4">Detail Tiket
                            </h2>
                            <div class="space-y-3">
                                @foreach ($items as $item)
                                    <div
                                        class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-surface-100' : '' }}">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-9 h-9 rounded-lg bg-primary-50 flex items-center justify-center shrink-0">
                                                <svg class="w-4.5 h-4.5 text-primary-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-surface-900">{{ $item['name'] }}
                                                </p>
                                                <p class="text-xs text-surface-400">{{ $item['category'] }} &middot;
                                                    {{ $item['qty'] }}x @ Rp
                                                    {{ number_format($item['price'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                        <span class="text-sm font-bold text-surface-900">Rp
                                            {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Right: Summary --}}
                    <div class="lg:w-[340px] shrink-0">
                        <div
                            class="bg-white rounded-xl border border-surface-200 shadow-sm sticky top-24 overflow-hidden">
                            <div class="px-5 py-4 border-b border-surface-100">
                                <h2 class="text-base font-bold text-surface-900">Ringkasan Pesanan</h2>
                            </div>

                            <div class="p-5 space-y-3">
                                @foreach ($items as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-surface-600">{{ $item['name'] }} <span
                                                class="text-surface-400">x{{ $item['qty'] }}</span></span>
                                        <span class="font-medium text-surface-900">Rp
                                            {{ number_format($item['subtotal'], 0, ',', '.') }}</span>
                                    </div>
                                @endforeach

                                <div class="border-t border-surface-100 pt-3 mt-3">
                                    <div class="flex justify-between text-sm mb-1">
                                        <span class="text-surface-500">Jumlah Tiket</span>
                                        <span class="font-medium text-surface-700">{{ $totalQty }} tiket</span>
                                    </div>
                                </div>

                                {{-- Promo Code --}}
                                <div class="border-t border-surface-100 pt-3 mt-3" x-data="{
                                    code: '',
                                    loading: false,
                                    applied: false,
                                    error: '',
                                    discount: 0,
                                    discountLabel: '',
                                    finalPrice: {{ $totalPrice }},
                                    originalPrice: {{ $totalPrice }},
                                    async applyPromo() {
                                        if (!this.code.trim()) return;
                                        this.loading = true;
                                        this.error = '';
                                        try {
                                            const res = await fetch('/promo/apply', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                                                body: JSON.stringify({
                                                    code: this.code,
                                                    event_id: {{ $event->id }},
                                                    total_price: this.originalPrice
                                                })
                                            });
                                            const data = await res.json();
                                            if (data.status === 'success') {
                                                this.applied = true;
                                                this.discount = data.data.discount;
                                                this.finalPrice = data.data.final_price;
                                                this.discountLabel = data.data.discount_type === 'percentage' ?
                                                    data.data.discount_value + '%' :
                                                    'Rp ' + new Intl.NumberFormat('id-ID').format(data.data.discount_value);
                                            } else {
                                                this.error = data.message || 'Kode promo tidak valid';
                                            }
                                        } catch (e) {
                                            this.error = 'Gagal memverifikasi kode promo';
                                        }
                                        this.loading = false;
                                    },
                                    removePromo() {
                                        this.applied = false;
                                        this.code = '';
                                        this.discount = 0;
                                        this.finalPrice = this.originalPrice;
                                        this.error = '';
                                    }
                                }">
                                    <label
                                        class="text-xs font-semibold text-surface-500 uppercase tracking-wider mb-2 block">Kode
                                        Promo</label>

                                    {{-- Input --}}
                                    <div x-show="!applied" class="flex gap-2">
                                        <div class="flex-1 relative">
                                            <input type="text" x-model="code" name="promo_code"
                                                placeholder="Masukkan kode promo"
                                                class="w-full px-3 py-2.5 text-sm border border-surface-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 placeholder:text-surface-400 uppercase"
                                                x-on:keydown.enter.prevent="applyPromo()" :disabled="loading" />
                                        </div>
                                        <button type="button" @click="applyPromo()"
                                            :disabled="loading || !code.trim()"
                                            class="px-4 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed shrink-0">
                                            <span x-show="!loading">Pakai</span>
                                            <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none"
                                                viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                    stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- Error --}}
                                    <p x-show="error" x-text="error" class="text-xs text-red-600 mt-1.5"></p>

                                    {{-- Applied --}}
                                    <div x-show="applied" x-cloak
                                        class="bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2.5">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="text-sm font-semibold text-emerald-800"
                                                    x-text="code.toUpperCase()"></span>
                                                <span class="text-xs text-emerald-600"
                                                    x-text="'(-' + discountLabel + ')'"></span>
                                            </div>
                                            <button type="button" @click="removePromo()"
                                                class="text-xs text-surface-500 hover:text-red-600 transition-colors font-medium">Hapus</button>
                                        </div>
                                        <input type="hidden" name="promo_code" :value="code" />
                                    </div>
                                </div>

                                {{-- Total --}}
                                <div class="border-t border-surface-200 pt-3">
                                    {{-- Discount row --}}
                                    <div x-show="applied" x-cloak class="flex justify-between text-sm mb-2">
                                        <span class="text-surface-500">Subtotal</span>
                                        <span class="font-medium text-surface-600">Rp
                                            {{ number_format($totalPrice, 0, ',', '.') }}</span>
                                    </div>
                                    <div x-show="applied" x-cloak class="flex justify-between text-sm mb-2">
                                        <span class="text-emerald-600">Diskon</span>
                                        <span class="font-semibold text-emerald-600"
                                            x-text="'-Rp ' + new Intl.NumberFormat('id-ID').format(discount)"></span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-surface-600">Total Bayar</span>
                                        <span class="text-xl font-extrabold text-primary-600"
                                            x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(finalPrice)">Rp
                                            {{ number_format($totalPrice, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="px-5 pb-5 space-y-3">
                                <button type="submit"
                                    class="w-full py-3.5 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-colors flex items-center justify-center gap-2"
                                    x-bind:disabled="loading">
                                    <svg x-show="!loading" class="w-4 h-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <svg x-show="loading" x-cloak class="w-4 h-4 animate-spin" fill="none"
                                        viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor"
                                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <span x-text="loading ? 'Memproses Pembayaran...' : 'Bayar Sekarang'"></span>
                                </button>

                                <a href="/events/{{ $event->id }}"
                                    class="block w-full py-3 text-center text-sm font-medium text-surface-600 bg-surface-100 rounded-xl hover:bg-surface-200 transition-colors">
                                    Kembali ke Event
                                </a>
                            </div>

                            {{-- Security Note --}}
                            <div class="px-5 pb-4">
                                <div class="flex items-start gap-2 text-xs text-surface-400">
                                    <svg class="w-3.5 h-3.5 mt-0.5 shrink-0" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <span>Pembayaran diproses secara aman melalui payment gateway terpercaya.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layouts.landing>
