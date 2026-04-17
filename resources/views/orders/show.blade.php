<x-layouts.landing title="Detail Pesanan - KarcisDigital" :navDark="true">
    <div class="min-h-screen bg-surface-50 pt-24 pb-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Back --}}
            <a href="/orders"
                class="inline-flex items-center gap-1.5 text-sm text-surface-500 hover:text-surface-700 font-medium mb-6 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali ke Pesanan
            </a>

            {{-- Order Header Card --}}
            <div class="bg-white rounded-xl border border-surface-200 overflow-hidden mb-6">
                {{-- Top Banner --}}
                @php
                    $statusConfig = match ($order->status_payment) {
                        'success', 'paid' => [
                            'bg-emerald-600',
                            'Pembayaran Berhasil',
                            'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                        ],
                        'pending' => [
                            'bg-amber-500',
                            'Menunggu Pembayaran',
                            'bg-amber-50 text-amber-700 ring-amber-600/20',
                        ],
                        'cancel' => ['bg-red-500', 'Pesanan Dibatalkan', 'bg-red-50 text-red-700 ring-red-600/20'],
                        'refund_pending' => [
                            'bg-orange-500',
                            'Menunggu Refund',
                            'bg-orange-50 text-orange-700 ring-orange-600/20',
                        ],
                        'refunded' => ['bg-violet-600', 'Refunded', 'bg-violet-50 text-violet-700 ring-violet-600/20'],
                        default => [
                            'bg-surface-500',
                            $order->status_payment,
                            'bg-surface-50 text-surface-700 ring-surface-600/20',
                        ],
                    };
                @endphp
                <div class="{{ $statusConfig[0] }} px-6 py-3">
                    <div class="flex items-center gap-2 text-white">
                        @if (in_array($order->status_payment, ['success', 'paid']))
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @elseif($order->status_payment === 'pending')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @elseif($order->status_payment === 'refund_pending')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @elseif($order->status_payment === 'refunded')
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                            </svg>
                        @else
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                        <span class="text-sm font-semibold">{{ $statusConfig[1] }}</span>
                    </div>
                </div>

                <div class="p-6">
                    {{-- Event Info --}}
                    <div class="flex flex-col sm:flex-row gap-5">
                        <div class="shrink-0 w-full sm:w-32 h-24 rounded-lg overflow-hidden bg-surface-100">
                            <img src="{{ asset('images/events/' . ($order->event->image ?? 'default.png')) }}"
                                alt="{{ $order->event->name ?? '' }}" class="w-full h-full object-cover"
                                onerror="this.src='https://placehold.co/200x120/e2e8f0/94a3b8?text=Event'" />
                        </div>
                        <div class="flex-1">
                            <h1 class="text-xl font-bold text-surface-900">{{ $order->event->name ?? 'Event' }}</h1>
                            @if ($order->event && $order->event->vendor)
                                <p class="text-sm text-surface-500 mt-1">oleh {{ $order->event->vendor->name }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- Order Details Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-5 border-t border-surface-100">
                        <div>
                            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider">Tanggal Pesan</p>
                            <p class="mt-1 text-sm font-semibold text-surface-900">
                                {{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-surface-500">{{ $order->created_at->format('H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider">Tanggal Event</p>
                            <p class="mt-1 text-sm font-semibold text-surface-900">
                                {{ \Carbon\Carbon::parse($order->event_date)->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider">Jumlah Tiket</p>
                            <p class="mt-1 text-sm font-semibold text-surface-900">{{ $order->quantity }} tiket</p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider">Total Bayar</p>
                            <p class="mt-1 text-lg font-bold text-surface-900">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    {{-- Pay Button --}}
                    @if ($order->status_payment === 'pending' && $order->payment_url)
                        <div class="mt-5 pt-5 border-t border-surface-100">
                            <a href="{{ $order->payment_url }}"
                                class="inline-flex items-center gap-2 px-6 py-3 bg-amber-500 text-white text-sm font-semibold rounded-xl hover:bg-amber-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Bayar Sekarang
                            </a>
                        </div>
                    @endif

                    {{-- Cancel & Refund Info (for refund_pending / refunded) --}}
                    @if (in_array($order->status_payment, ['refund_pending', 'refunded']))
                        <div class="mt-5 pt-5 border-t border-surface-100">
                            <div class="rounded-xl border border-orange-200 bg-orange-50 p-4">
                                <h3 class="text-sm font-semibold text-orange-800 mb-2">Informasi Pembatalan</h3>
                                <div class="space-y-1 text-sm text-orange-700">
                                    <p><span class="font-medium">Alasan:</span> {{ $order->cancel_reason }}</p>
                                    @if ($order->cancelled_at)
                                        <p><span class="font-medium">Tanggal Cancel:</span>
                                            {{ $order->cancelled_at->format('d M Y, H:i') }} WIB</p>
                                    @endif
                                </div>
                            </div>

                            @if ($order->status_payment === 'refunded')
                                <div class="rounded-xl border border-violet-200 bg-violet-50 p-4 mt-3"
                                    x-data="{ lightbox: false }">
                                    <h3 class="text-sm font-semibold text-violet-800 mb-2">Informasi Refund</h3>
                                    <div class="space-y-2 text-sm text-violet-700">
                                        @if ($order->refund_note)
                                            <p><span class="font-medium">Catatan Admin:</span>
                                                {{ $order->refund_note }}</p>
                                        @endif
                                        @if ($order->refunded_at)
                                            <p><span class="font-medium">Tanggal Refund:</span>
                                                {{ $order->refunded_at->format('d M Y, H:i') }} WIB</p>
                                        @endif
                                        @if ($order->refund_proof)
                                            <div class="mt-3">
                                                <p class="font-medium mb-2">Bukti Transfer:</p>
                                                <img src="{{ asset('images/refunds/' . $order->refund_proof) }}"
                                                    alt="Bukti Transfer Refund"
                                                    class="rounded-lg border border-violet-200 max-h-48 object-contain cursor-pointer hover:opacity-80 transition-opacity"
                                                    @click="lightbox = true" />
                                            </div>

                                            {{-- Lightbox --}}
                                            <div x-show="lightbox" x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80"
                                                @click="lightbox = false"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">
                                                <img src="{{ asset('images/refunds/' . $order->refund_proof) }}"
                                                    alt="Bukti Transfer Refund"
                                                    class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
                                                    @click.stop />
                                                <button @click="lightbox = false"
                                                    class="absolute top-4 right-4 w-10 h-10 rounded-full bg-white/20 text-white hover:bg-white/30 flex items-center justify-center transition-colors">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Cancel & Request Refund Button (only for paid orders within deadline) --}}
                    @if (in_array($order->status_payment, ['success', 'paid']))
                        @php
                            $canCancel =
                                $order->event &&
                                now()->lt(
                                    \Carbon\Carbon::parse($order->event->start_date)->subDays(
                                        config('order.cancel_deadline_days', 3),
                                    ),
                                );
                            $deadlineDate = $order->event
                                ? \Carbon\Carbon::parse($order->event->start_date)
                                    ->subDays(config('order.cancel_deadline_days', 3))
                                    ->format('d M Y')
                                : null;
                        @endphp
                        <div class="mt-5 pt-5 border-t border-surface-100" x-data="{ showCancelModal: false }">
                            @if ($canCancel)
                                <button @click="showCancelModal = true"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-50 text-red-600 text-sm font-semibold rounded-xl hover:bg-red-100 border border-red-200 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                    </svg>
                                    Batalkan & Ajukan Refund
                                </button>

                                {{-- Cancel Modal --}}
                                <div x-show="showCancelModal" x-cloak
                                    class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                    {{-- Backdrop --}}
                                    <div class="fixed inset-0 bg-black/50" @click="showCancelModal = false"></div>

                                    {{-- Modal Content --}}
                                    <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 scale-95"
                                        x-transition:enter-end="opacity-100 scale-100"
                                        x-transition:leave="transition ease-in duration-150"
                                        x-transition:leave-start="opacity-100 scale-100"
                                        x-transition:leave-end="opacity-0 scale-95"
                                        @click.away="showCancelModal = false">
                                        <div class="flex items-center gap-3 mb-4">
                                            <div
                                                class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                <svg class="w-5 h-5 text-red-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-bold text-surface-900">Batalkan Pesanan</h3>
                                                <p class="text-sm text-surface-500">Ajukan refund untuk pesanan ini</p>
                                            </div>
                                        </div>

                                        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-4">
                                            <p class="text-xs text-amber-700">Setelah dibatalkan, pengajuan refund akan
                                                direview oleh admin. Dana akan dikembalikan setelah disetujui.</p>
                                        </div>

                                        <form method="POST" action="/orders/{{ $order->id }}/cancel"
                                            x-data="{ reason: '', submitting: false }" @submit="submitting = true">
                                            @csrf
                                            <div class="mb-4">
                                                <label class="block text-sm font-medium text-surface-700 mb-1.5">Alasan
                                                    Pembatalan <span class="text-red-500">*</span></label>
                                                <div class="relative">
                                                    <textarea name="cancel_reason" x-model="reason" rows="3" maxlength="500"
                                                        class="w-full rounded-xl border-surface-200 bg-surface-50 focus:bg-white focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-surface-400 resize-none transition-colors px-4 py-3"
                                                        placeholder="Contoh: Saya tidak bisa hadir karena ada keperluan mendadak..."></textarea>
                                                </div>
                                                <div class="flex items-center justify-between mt-1.5">
                                                    <div class="flex items-center gap-1.5">
                                                        <template x-if="reason.length > 0 && reason.length < 10">
                                                            <p class="text-xs text-amber-500">Minimal 10 karakter</p>
                                                        </template>
                                                        <template x-if="reason.length >= 10">
                                                            <p
                                                                class="text-xs text-emerald-600 flex items-center gap-1">
                                                                <svg class="w-3.5 h-3.5" fill="none"
                                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round" stroke-width="2"
                                                                        d="M5 13l4 4L19 7" />
                                                                </svg>
                                                                Alasan valid
                                                            </p>
                                                        </template>
                                                    </div>
                                                    <p class="text-xs"
                                                        :class="reason.length >= 10 ? 'text-surface-400' : 'text-surface-300'">
                                                        <span x-text="reason.length"></span>/500
                                                    </p>
                                                </div>
                                                @error('cancel_reason')
                                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <div class="flex items-center gap-3 justify-end">
                                                <button type="button" @click="showCancelModal = false"
                                                    class="px-4 py-2 text-sm font-medium text-surface-600 hover:text-surface-800 transition-colors">
                                                    Batal
                                                </button>
                                                <button type="submit" :disabled="reason.length < 10 || submitting"
                                                    class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                    <span x-show="!submitting">Konfirmasi Batalkan</span>
                                                    <span x-show="submitting" class="inline-flex items-center gap-2">
                                                        <svg class="w-4 h-4 animate-spin" fill="none"
                                                            viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12"
                                                                r="10" stroke="currentColor" stroke-width="4">
                                                            </circle>
                                                            <path class="opacity-75" fill="currentColor"
                                                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                        </svg>
                                                        Memproses...
                                                    </span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <p class="text-xs text-surface-400">
                                    Pembatalan tidak tersedia.
                                    @if ($deadlineDate)
                                        Batas cancel sudah terlewat (sebelum {{ $deadlineDate }}).
                                    @endif
                                </p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Tickets Section --}}
            <div class="bg-white rounded-xl border border-surface-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-surface-100 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-surface-900">Tiket ({{ $order->orderTickets->count() }})
                    </h2>
                </div>
                <div class="divide-y divide-surface-100">
                    @foreach ($order->orderTickets as $orderTicket)
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                {{-- Ticket Icon --}}
                                <div
                                    class="shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                                    {{ match ($orderTicket->ticket->status) {
                                        'sold' => 'bg-emerald-50 text-emerald-600',
                                        'redeem' => 'bg-sky-50 text-sky-600',
                                        'booked' => 'bg-amber-50 text-amber-600',
                                        default => 'bg-surface-50 text-surface-400',
                                    } }}">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-surface-900">
                                        {{ $orderTicket->ticket->sku->name ?? 'Tiket' }}</p>
                                    <p class="text-sm text-surface-500">
                                        {{ $orderTicket->ticket->sku->category ?? '' }} &middot; Rp
                                        {{ number_format($orderTicket->ticket->sku->price ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-xs text-surface-400 font-mono mt-1">
                                        {{ $orderTicket->ticket->ticket_code }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                @php
                                    $ticketStatusConfig = match ($orderTicket->ticket->status) {
                                        'sold' => ['bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'Berlaku'],
                                        'redeem' => ['bg-sky-50 text-sky-700 ring-sky-600/20', 'Sudah Digunakan'],
                                        'booked' => ['bg-amber-50 text-amber-700 ring-amber-600/20', 'Menunggu Bayar'],
                                        default => [
                                            'bg-surface-50 text-surface-700 ring-surface-600/20',
                                            ucfirst($orderTicket->ticket->status),
                                        ],
                                    };
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg ring-1 ring-inset {{ $ticketStatusConfig[0] }}">
                                    {{ $ticketStatusConfig[1] }}
                                </span>
                                @if (in_array($orderTicket->ticket->status, ['sold']))
                                    <a href="/tickets/{{ $orderTicket->ticket->id }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        E-Ticket
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    @if ($order->orderTickets->count() === 0)
                        <div class="p-8 text-center text-sm text-surface-500">
                            Tidak ada tiket terkait pesanan ini.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.landing>
