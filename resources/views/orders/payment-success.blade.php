<x-layouts.landing title="Pembayaran Berhasil - KarcisDigital" :navDark="true">
    <div class="min-h-screen bg-surface-50 pt-20 flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
                {{-- Success Header --}}
                <div class="bg-emerald-500 px-6 py-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white">Pembayaran Berhasil!</h1>
                    <p class="text-emerald-100 text-sm mt-1">Tiket kamu sudah siap digunakan</p>
                </div>

                {{-- Order Details --}}
                <div class="p-6 space-y-4">
                    <div class="text-center pb-4 border-b border-surface-100">
                        <p class="text-xs text-surface-400 uppercase tracking-wider font-medium">Total Pembayaran</p>
                        <p class="text-2xl font-extrabold text-surface-900 mt-1">Rp
                            {{ number_format($order->total_price, 0, ',', '.') }}</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Event</span>
                            <span
                                class="font-medium text-surface-900 text-right max-w-[200px] truncate">{{ $order->event->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Jumlah Tiket</span>
                            <span class="font-medium text-surface-900">{{ $order->quantity }} tiket</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Tanggal Event</span>
                            <span
                                class="font-medium text-surface-900">{{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Tanggal Bayar</span>
                            <span class="font-medium text-surface-900">{{ now()->format('d M Y, H:i') }} WIB</span>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-4 border-t border-surface-100 space-y-3">
                        <a href="/orders/{{ $order->id }}"
                            class="block w-full py-3 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-colors text-center">
                            Lihat Detail Pesanan
                        </a>
                        <a href="/events"
                            class="block w-full py-3 bg-surface-100 text-surface-700 text-sm font-medium rounded-xl hover:bg-surface-200 transition-colors text-center">
                            Jelajahi Event Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.landing>
