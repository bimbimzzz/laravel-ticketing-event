<x-layouts.admin title="Dashboard - Admin JagoEvent">
    <x-slot:header>Dashboard</x-slot:header>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        @foreach ([['Total Users', $stats['users'], 'bg-primary-50', 'text-primary-600', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'], ['Total Vendors', $stats['vendors'], 'bg-emerald-50', 'text-emerald-600', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'], ['Total Events', $stats['events'], 'bg-amber-50', 'text-amber-600', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'], ['Tiket Terjual', $stats['ticketsSold'], 'bg-sky-50', 'text-sky-600', 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z']] as [$label, $value, $bg, $color, $icon])
            <div class="bg-white rounded-xl border border-surface-200 p-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-8 h-8 {{ $bg }} rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $icon }}" />
                        </svg>
                    </div>
                    <span
                        class="text-xs font-medium text-surface-400 uppercase tracking-wider">{{ $label }}</span>
                </div>
                <p class="text-2xl font-extrabold text-surface-900">{{ number_format($value) }}</p>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Total Pesanan</p>
            <p class="text-2xl font-extrabold text-surface-900">{{ number_format($stats['orders']) }}</p>
        </div>
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Revenue</p>
            <p class="text-xl font-extrabold text-surface-900">Rp {{ number_format($stats['revenue'], 0, ',', '.') }}
            </p>
        </div>
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Vendor Pending</p>
            <p
                class="text-2xl font-extrabold {{ $stats['pendingVendors'] > 0 ? 'text-amber-600' : 'text-surface-900' }}">
                {{ $stats['pendingVendors'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-surface-200 p-4">
            <p class="text-xs font-medium text-surface-400 uppercase tracking-wider mb-1">Order Pending</p>
            <p
                class="text-2xl font-extrabold {{ $stats['pendingOrders'] > 0 ? 'text-amber-600' : 'text-surface-900' }}">
                {{ $stats['pendingOrders'] }}</p>
        </div>
    </div>

    <div x-data="{ showModal: false, modalAction: '', modalVendor: '', modalFormId: '' }" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Confirmation Modal --}}
        <div x-show="showModal" x-transition.opacity x-cloak
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div @click.outside="showModal = false" x-transition
                class="bg-white rounded-xl shadow-xl border border-surface-200 w-full max-w-sm mx-4 p-6">
                <div class="text-center mb-4">
                    <div :class="modalAction === 'approved' ? 'bg-emerald-50' : 'bg-red-50'"
                        class="w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3">
                        <svg :class="modalAction === 'approved' ? 'text-emerald-600' : 'text-red-600'" class="w-6 h-6"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="modalAction === 'approved'" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M5 13l4 4L19 7" />
                            <path x-show="modalAction !== 'approved'" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                    <h3 class="text-base font-bold text-surface-900"
                        x-text="modalAction === 'approved' ? 'Approve Vendor?' : 'Reject Vendor?'"></h3>
                    <p class="text-sm text-surface-500 mt-1">Apakah Anda yakin ingin <span
                            :class="modalAction === 'approved' ? 'text-emerald-600 font-semibold' : 'text-red-600 font-semibold'"
                            x-text="modalAction === 'approved' ? 'approve' : 'reject'"></span> vendor <span
                            class="font-semibold text-surface-900" x-text="modalVendor"></span>?</p>
                </div>
                <div class="flex gap-3">
                    <button @click="showModal = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-surface-700 bg-surface-100 rounded-lg hover:bg-surface-200 transition-colors">Batal</button>
                    <button @click="document.getElementById(modalFormId).submit()"
                        :class="modalAction === 'approved' ? 'bg-emerald-600 hover:bg-emerald-700' :
                            'bg-red-600 hover:bg-red-700'"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-white rounded-lg transition-colors"
                        x-text="modalAction === 'approved' ? 'Ya, Approve' : 'Ya, Reject'"></button>
                </div>
            </div>
        </div>
        {{-- Recent Orders --}}
        <div class="bg-white rounded-xl border border-surface-200">
            <div class="px-5 py-4 border-b border-surface-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-surface-900">Pesanan Terbaru</h3>
                <a href="/superadmin/orders" class="text-xs text-primary-600 hover:text-primary-700 font-medium">Lihat
                    Semua</a>
            </div>
            <div class="divide-y divide-surface-100">
                @forelse($recentOrders as $order)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-surface-900 truncate">{{ $order->user->name ?? '-' }}
                            </p>
                            <p class="text-xs text-surface-500 truncate">{{ $order->event->name ?? '-' }}</p>
                        </div>
                        <div class="text-right shrink-0 ml-3">
                            <p class="text-sm font-bold text-surface-900">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</p>
                            @php
                                $sCfg = match ($order->status_payment) {
                                    'success' => 'text-emerald-600',
                                    'pending' => 'text-amber-600',
                                    default => 'text-red-600',
                                };
                            @endphp
                            <p class="text-xs font-medium {{ $sCfg }}">{{ ucfirst($order->status_payment) }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-surface-400">Belum ada pesanan</div>
                @endforelse
            </div>
        </div>

        {{-- Pending Vendors --}}
        <div class="bg-white rounded-xl border border-surface-200">
            <div class="px-5 py-4 border-b border-surface-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-surface-900">Vendor Menunggu Verifikasi</h3>
                <a href="/superadmin/vendors?status=pending"
                    class="text-xs text-primary-600 hover:text-primary-700 font-medium">Lihat Semua</a>
            </div>
            <div class="divide-y divide-surface-100">
                @forelse($pendingVendors as $vendor)
                    <div class="px-5 py-3 flex items-center justify-between">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-surface-900">{{ $vendor->name }}</p>
                            <p class="text-xs text-surface-500">{{ $vendor->user->email ?? '-' }} &middot;
                                {{ $vendor->city }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 ml-3">
                            <form id="dash-approve-{{ $vendor->id }}" method="POST"
                                action="/superadmin/vendors/{{ $vendor->id }}/status">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="approved">
                                <button type="button"
                                    @click="showModal = true; modalAction = 'approved'; modalVendor = '{{ $vendor->name }}'; modalFormId = 'dash-approve-{{ $vendor->id }}'"
                                    class="px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-lg hover:bg-emerald-100 transition-colors">Approve</button>
                            </form>
                            <form id="dash-reject-{{ $vendor->id }}" method="POST"
                                action="/superadmin/vendors/{{ $vendor->id }}/status">
                                @csrf @method('PATCH')
                                <input type="hidden" name="status" value="rejected">
                                <button type="button"
                                    @click="showModal = true; modalAction = 'rejected'; modalVendor = '{{ $vendor->name }}'; modalFormId = 'dash-reject-{{ $vendor->id }}'"
                                    class="px-2.5 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">Reject</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-sm text-surface-400">Tidak ada vendor yang menunggu
                        verifikasi</div>
                @endforelse
            </div>
        </div>
    </div>
</x-layouts.admin>
