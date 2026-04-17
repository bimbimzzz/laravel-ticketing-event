<x-layouts.landing title="Pembayaran Gagal - KarcisDigital" :navDark="true">
    <div class="min-h-screen bg-surface-50 pt-20 flex items-center justify-center px-4">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl border border-surface-200 shadow-sm overflow-hidden">
                {{-- Failed Header --}}
                <div class="bg-red-500 px-6 py-8 text-center">
                    <div class="w-16 h-16 mx-auto bg-white/20 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h1 class="text-2xl font-extrabold text-white">Pembayaran Gagal</h1>
                    <p class="text-red-100 text-sm mt-1">Pembayaran kamu belum berhasil diproses</p>
                </div>

                {{-- Details --}}
                <div class="p-6 space-y-4">
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Event</span>
                            <span
                                class="font-medium text-surface-900 text-right max-w-[200px] truncate">{{ $order->event->name ?? '-' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-surface-500">Total</span>
                            <span class="font-medium text-surface-900">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    @if ($order->status_payment === 'pending' && $order->payment_url)
                        <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                            <p class="text-sm text-amber-800">Pesanan masih aktif. Kamu bisa coba bayar lagi.</p>
                        </div>
                    @endif

                    <div class="pt-4 border-t border-surface-100 space-y-3">
                        @if ($order->status_payment === 'pending' && $order->payment_url)
                            <a href="{{ $order->payment_url }}"
                                class="block w-full py-3 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-colors text-center">
                                Coba Bayar Lagi
                            </a>
                        @endif
                        <a href="/orders/{{ $order->id }}"
                            class="block w-full py-3 bg-surface-100 text-surface-700 text-sm font-medium rounded-xl hover:bg-surface-200 transition-colors text-center">
                            Lihat Detail Pesanan
                        </a>
                        <a href="/events"
                            class="block w-full py-3 text-surface-500 text-sm font-medium text-center hover:text-surface-700 transition-colors">
                            Kembali ke Event
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.landing>
