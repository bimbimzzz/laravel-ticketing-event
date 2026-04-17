<x-layouts.admin title="Refund Requests - Admin KarcisDigital">
    <x-slot:header>Refund Requests</x-slot:header>

    @if (session('success'))
        <div class="mb-4 px-4 py-3 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-200 text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-4 px-4 py-3 bg-red-50 text-red-700 rounded-lg border border-red-200 text-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 mb-4">
        <a href="{{ route('admin.refunds', ['tab' => 'pending', 'search' => request('search')]) }}"
            class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $tab === 'pending' ? 'bg-primary-600 text-white' : 'bg-white text-surface-600 border border-surface-200 hover:bg-surface-50' }}">
            Menunggu Approval
            @if ($counts['pending'] > 0)
                <span
                    class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full {{ $tab === 'pending' ? 'bg-white/20 text-white' : 'bg-orange-100 text-orange-700' }}">{{ $counts['pending'] }}</span>
            @endif
        </a>
        <a href="{{ route('admin.refunds', ['tab' => 'history', 'search' => request('search')]) }}"
            class="px-4 py-2 text-sm font-semibold rounded-lg transition-colors {{ $tab === 'history' ? 'bg-primary-600 text-white' : 'bg-white text-surface-600 border border-surface-200 hover:bg-surface-50' }}">
            Riwayat Refund
            <span
                class="ml-1 inline-flex items-center justify-center w-5 h-5 text-xs font-bold rounded-full {{ $tab === 'history' ? 'bg-white/20 text-white' : 'bg-surface-100 text-surface-500' }}">{{ $counts['history'] }}</span>
        </a>
    </div>

    <div class="bg-white rounded-xl border border-surface-200">
        <div class="px-5 py-4 border-b border-surface-100 flex flex-wrap gap-3">
            <form method="GET" class="flex flex-1 gap-3">
                <input type="hidden" name="tab" value="{{ $tab }}">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pembeli..."
                    class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                <button
                    class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Cari</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            @if ($tab === 'pending')
                {{-- Pending Refunds Table --}}
                <table class="w-full text-sm">
                    <thead class="bg-surface-50 text-xs text-surface-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">#</th>
                            <th class="px-5 py-3 text-left font-semibold">Pembeli</th>
                            <th class="px-5 py-3 text-left font-semibold">Event</th>
                            <th class="px-5 py-3 text-left font-semibold">Total</th>
                            <th class="px-5 py-3 text-left font-semibold">Alasan Cancel</th>
                            <th class="px-5 py-3 text-left font-semibold">Tanggal Cancel</th>
                            <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-surface-50" x-data="{ showApprove: false, showReject: false }">
                                <td class="px-5 py-3 text-surface-400 font-mono text-xs">#{{ $order->id }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-surface-900">{{ $order->user->name ?? '-' }}</p>
                                    <p class="text-xs text-surface-500">{{ $order->user->email ?? '' }}</p>
                                </td>
                                <td class="px-5 py-3 text-surface-600 max-w-[200px] truncate">
                                    {{ $order->event->name ?? '-' }}</td>
                                <td class="px-5 py-3 font-semibold text-surface-900 whitespace-nowrap">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3 text-surface-600 max-w-[250px]">
                                    <p class="truncate" title="{{ $order->cancel_reason }}">
                                        {{ $order->cancel_reason ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3 text-surface-500 text-xs whitespace-nowrap">
                                    {{ $order->cancelled_at ? $order->cancelled_at->format('d M Y H:i') : '-' }}
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex gap-2">
                                        <button @click="showApprove = true"
                                            class="px-3 py-1.5 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                                            Approve
                                        </button>
                                        <button @click="showReject = true"
                                            class="px-3 py-1.5 bg-red-600 text-white text-xs font-semibold rounded-lg hover:bg-red-700 transition-colors">
                                            Reject
                                        </button>
                                    </div>

                                    {{-- Approve Modal --}}
                                    <template x-teleport="body">
                                        <div x-show="showApprove" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                            <div class="fixed inset-0 bg-black/50" @click="showApprove = false"></div>
                                            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6"
                                                @click.away="showApprove = false"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-emerald-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-lg font-bold text-surface-900">Approve Refund
                                                            #{{ $order->id }}</h3>
                                                        <p class="text-sm text-surface-500">Upload bukti transfer refund
                                                        </p>
                                                    </div>
                                                </div>

                                                <form method="POST"
                                                    action="{{ route('admin.refunds.approve', $order->id) }}"
                                                    enctype="multipart/form-data" x-data="{ preview: null, submitting: false }"
                                                    @submit="submitting = true">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label
                                                            class="block text-sm font-medium text-surface-700 mb-1.5">Bukti
                                                            Transfer <span class="text-red-500">*</span></label>
                                                        <input type="file" name="refund_proof"
                                                            accept="image/jpeg,image/png,image/jpg,image/webp" required
                                                            @change="if($event.target.files[0]) { preview = URL.createObjectURL($event.target.files[0]) }"
                                                            class="w-full text-sm border border-surface-300 rounded-lg file:mr-3 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100" />
                                                        <p class="text-xs text-surface-400 mt-1">Format: JPG, PNG, WebP.
                                                            Maks 5MB.</p>
                                                        <template x-if="preview">
                                                            <img :src="preview"
                                                                class="mt-2 rounded-lg border border-surface-200 max-h-48 object-contain" />
                                                        </template>
                                                    </div>

                                                    <div class="mb-4">
                                                        <label
                                                            class="block text-sm font-medium text-surface-700 mb-1.5">Catatan
                                                            Admin <span class="text-red-500">*</span></label>
                                                        <textarea name="refund_note" rows="3" required minlength="5"
                                                            class="w-full rounded-xl border-surface-200 bg-surface-50 focus:bg-white focus:border-primary-500 focus:ring-primary-500 text-sm resize-none px-4 py-3"
                                                            placeholder="Contoh: Refund telah ditransfer ke rekening BCA a/n ..."></textarea>
                                                    </div>

                                                    <div class="flex items-center gap-3 justify-end">
                                                        <button type="button" @click="showApprove = false"
                                                            class="px-4 py-2 text-sm font-medium text-surface-600 hover:text-surface-800">Batal</button>
                                                        <button type="submit" :disabled="submitting"
                                                            class="px-5 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-xl hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                            <span x-show="!submitting">Approve & Upload Bukti</span>
                                                            <span x-show="submitting"
                                                                class="inline-flex items-center gap-2">
                                                                <svg class="w-4 h-4 animate-spin" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                                                    </path>
                                                                </svg>
                                                                Memproses...
                                                            </span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>

                                    {{-- Reject Modal --}}
                                    <template x-teleport="body">
                                        <div x-show="showReject" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4"
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                            <div class="fixed inset-0 bg-black/50" @click="showReject = false"></div>
                                            <div class="relative bg-white rounded-2xl shadow-xl max-w-md w-full p-6"
                                                @click.away="showReject = false"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 scale-95"
                                                x-transition:enter-end="opacity-100 scale-100">
                                                <div class="flex items-center gap-3 mb-4">
                                                    <div
                                                        class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center">
                                                        <svg class="w-5 h-5 text-red-600" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <h3 class="text-lg font-bold text-surface-900">Tolak Refund
                                                            #{{ $order->id }}</h3>
                                                        <p class="text-sm text-surface-500">Tiket akan dikembalikan ke
                                                            buyer</p>
                                                    </div>
                                                </div>

                                                <form method="POST"
                                                    action="{{ route('admin.refunds.reject', $order->id) }}"
                                                    x-data="{ submitting: false }" @submit="submitting = true">
                                                    @csrf
                                                    <div class="mb-4">
                                                        <label
                                                            class="block text-sm font-medium text-surface-700 mb-1.5">Alasan
                                                            Penolakan <span class="text-red-500">*</span></label>
                                                        <textarea name="refund_note" rows="3" required minlength="5"
                                                            class="w-full rounded-xl border-surface-200 bg-surface-50 focus:bg-white focus:border-primary-500 focus:ring-primary-500 text-sm resize-none px-4 py-3"
                                                            placeholder="Contoh: Refund tidak bisa diproses karena ..."></textarea>
                                                    </div>

                                                    <div class="flex items-center gap-3 justify-end">
                                                        <button type="button" @click="showReject = false"
                                                            class="px-4 py-2 text-sm font-medium text-surface-600 hover:text-surface-800">Batal</button>
                                                        <button type="submit" :disabled="submitting"
                                                            class="px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                                                            <span x-show="!submitting">Tolak Refund</span>
                                                            <span x-show="submitting"
                                                                class="inline-flex items-center gap-2">
                                                                <svg class="w-4 h-4 animate-spin" fill="none"
                                                                    viewBox="0 0 24 24">
                                                                    <circle class="opacity-25" cx="12"
                                                                        cy="12" r="10" stroke="currentColor"
                                                                        stroke-width="4"></circle>
                                                                    <path class="opacity-75" fill="currentColor"
                                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z">
                                                                    </path>
                                                                </svg>
                                                                Memproses...
                                                            </span>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-8 text-center text-surface-400">Tidak ada permintaan
                                    refund.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                {{-- History Table --}}
                <table class="w-full text-sm">
                    <thead class="bg-surface-50 text-xs text-surface-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">#</th>
                            <th class="px-5 py-3 text-left font-semibold">Pembeli</th>
                            <th class="px-5 py-3 text-left font-semibold">Event</th>
                            <th class="px-5 py-3 text-left font-semibold">Total</th>
                            <th class="px-5 py-3 text-left font-semibold">Status</th>
                            <th class="px-5 py-3 text-left font-semibold">Catatan Admin</th>
                            <th class="px-5 py-3 text-left font-semibold">Bukti</th>
                            <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($orders as $order)
                            <tr class="hover:bg-surface-50" x-data="{ showProof: false }">
                                <td class="px-5 py-3 text-surface-400 font-mono text-xs">#{{ $order->id }}</td>
                                <td class="px-5 py-3">
                                    <p class="font-medium text-surface-900">{{ $order->user->name ?? '-' }}</p>
                                    <p class="text-xs text-surface-500">{{ $order->user->email ?? '' }}</p>
                                </td>
                                <td class="px-5 py-3 text-surface-600 max-w-[180px] truncate">
                                    {{ $order->event->name ?? '-' }}</td>
                                <td class="px-5 py-3 font-semibold text-surface-900 whitespace-nowrap">Rp
                                    {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">
                                    @if ($order->status_payment === 'refunded')
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg ring-1 ring-inset bg-violet-50 text-violet-700 ring-violet-600/20">Approved</span>
                                    @else
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-lg ring-1 ring-inset bg-red-50 text-red-700 ring-red-600/20">Rejected</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-surface-600 max-w-[200px]">
                                    <p class="truncate" title="{{ $order->refund_note }}">
                                        {{ $order->refund_note ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3">
                                    @if ($order->refund_proof)
                                        <button @click="showProof = true"
                                            class="px-2.5 py-1 text-xs font-medium text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">
                                            Lihat Bukti
                                        </button>

                                        {{-- Proof Lightbox --}}
                                        <template x-teleport="body">
                                            <div x-show="showProof" x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/80"
                                                @click="showProof = false"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="transition ease-in duration-150"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0">
                                                <div class="relative max-w-2xl w-full" @click.stop>
                                                    <img src="{{ asset('images/refunds/' . $order->refund_proof) }}"
                                                        alt="Bukti Transfer Refund #{{ $order->id }}"
                                                        class="w-full max-h-[80vh] object-contain rounded-lg shadow-2xl" />
                                                    <div class="mt-3 bg-white rounded-lg p-3 text-sm">
                                                        <p class="font-semibold text-surface-900">Refund
                                                            #{{ $order->id }} — {{ $order->user->name ?? '-' }}</p>
                                                        <p class="text-surface-500 mt-1">{{ $order->refund_note }}</p>
                                                    </div>
                                                    <button @click="showProof = false"
                                                        class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-white shadow text-surface-600 hover:text-surface-900 flex items-center justify-center">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                    @else
                                        <span class="text-xs text-surface-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-surface-500 text-xs whitespace-nowrap">
                                    @if ($order->status_payment === 'refunded' && $order->refunded_at)
                                        {{ $order->refunded_at->format('d M Y H:i') }}
                                    @elseif($order->cancelled_at)
                                        {{ $order->cancelled_at->format('d M Y H:i') }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-surface-400">Belum ada riwayat
                                    refund.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>

        @if ($orders->hasPages())
            <div class="px-5 py-3 border-t border-surface-100">{{ $orders->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
