<x-layouts.landing title="E-Ticket - JagoEvent" :navDark="true">
    @php
        $statusCfg = match ($ticket->status) {
            'sold' => ['bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'Berlaku'],
            'booked' => ['bg-amber-50 text-amber-700 ring-amber-600/20', 'Menunggu Bayar'],
            'redeem' => ['bg-sky-50 text-sky-700 ring-sky-600/20', 'Sudah Digunakan'],
            default => ['bg-surface-50 text-surface-700 ring-surface-600/20', ucfirst($ticket->status)],
        };
    @endphp

    <div class="min-h-screen bg-surface-50 pt-24 pb-12 px-4">
        <div class="max-w-md mx-auto">
            <div class="bg-white rounded-2xl border border-surface-200 shadow-lg overflow-hidden">
                {{-- Header --}}
                <div class="bg-gradient-to-br from-primary-600 to-primary-800 text-white px-6 py-5">
                    <div class="flex items-start justify-between">
                        <div>
                            <p class="text-xs text-primary-200 uppercase tracking-wider font-medium">E-Ticket</p>
                            <h1 class="text-lg font-bold mt-1 leading-snug">{{ $ticket->event->name }}</h1>
                            @if ($ticket->event->vendor)
                                <p class="text-xs text-primary-200 mt-1">{{ $ticket->event->vendor->name }}</p>
                            @endif
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg ring-1 ring-inset {{ $statusCfg[0] }}">
                            {{ $statusCfg[1] }}
                        </span>
                    </div>
                </div>

                {{-- QR Code --}}
                <div class="px-6 py-8 flex flex-col items-center">
                    <div id="qrcode" class="mb-4"></div>
                    <p class="text-xs text-surface-400 mt-1">Tunjukkan QR code ini kepada petugas</p>
                </div>

                {{-- Ticket Code --}}
                <div class="text-center pb-5">
                    <p class="font-mono text-2xl font-bold tracking-[0.2em] text-surface-900">{{ $ticket->ticket_code }}
                    </p>
                </div>

                {{-- Divider --}}
                <div class="relative px-6">
                    <div class="border-t border-dashed border-surface-300"></div>
                    <div class="absolute -left-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-surface-50 rounded-full"></div>
                    <div class="absolute -right-3 top-1/2 -translate-y-1/2 w-6 h-6 bg-surface-50 rounded-full"></div>
                </div>

                {{-- Details --}}
                <div class="px-6 py-5 space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-surface-500">Tipe Tiket</span>
                        <span class="font-semibold text-surface-900">{{ $ticket->sku->name ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-surface-500">Kategori</span>
                        <span class="font-medium text-surface-900">{{ $ticket->sku->category ?? '-' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-surface-500">Harga</span>
                        <span class="font-medium text-surface-900">Rp
                            {{ number_format($ticket->sku->price ?? 0, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-surface-500">Tanggal Event</span>
                        <span
                            class="font-medium text-surface-900">{{ \Carbon\Carbon::parse($ticket->event->start_date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-surface-500">Pembeli</span>
                        <span
                            class="font-medium text-surface-900">{{ $order->user->name ?? auth()->user()->name }}</span>
                    </div>
                </div>
            </div>

            <div class="mt-5 flex items-center justify-center gap-4">
                <a href="/orders/{{ $order->id }}"
                    class="inline-flex items-center gap-1.5 text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Detail Pesanan
                </a>
                <span class="text-surface-300">|</span>
                <a href="/orders" class="text-sm text-surface-500 hover:text-surface-700 font-medium transition-colors">
                    Semua Pesanan
                </a>
            </div>
        </div>
    </div>

    {{-- QR Code using Google Charts API (most reliable, no JS library needed) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const code = @json($ticket->ticket_code);
            const container = document.getElementById('qrcode');
            const img = document.createElement('img');
            img.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(code);
            img.alt = 'QR Code ' + code;
            img.width = 200;
            img.height = 200;
            img.style.borderRadius = '12px';
            container.appendChild(img);
        });
    </script>
</x-layouts.landing>
