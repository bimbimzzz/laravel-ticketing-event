<x-layouts.admin title="Vendors - Admin KarcisDigital">
    <x-slot:header>Vendors</x-slot:header>

    <div x-data="{ showModal: false, modalAction: '', modalVendor: '', modalVendorId: '' }" class="relative">
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
                <form method="POST" :action="'/superadmin/vendors/' + modalVendorId + '/status'">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" :value="modalAction">
                    <div class="flex gap-3">
                        <button type="button" @click="showModal = false"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-surface-700 bg-surface-100 rounded-lg hover:bg-surface-200 transition-colors">Batal</button>
                        <button type="submit"
                            :class="modalAction === 'approved' ? 'bg-emerald-600 hover:bg-emerald-700' :
                                'bg-red-600 hover:bg-red-700'"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white rounded-lg transition-colors"
                            x-text="modalAction === 'approved' ? 'Ya, Approve' : 'Ya, Reject'"></button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-surface-200">
            {{-- Filters --}}
            <div class="px-5 py-4 border-b border-surface-100 flex flex-wrap gap-3">
                <form method="GET" class="flex flex-1 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari vendor..."
                        class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                    <select name="status" onchange="this.form.submit()"
                        class="px-3 py-2 text-sm border border-surface-300 rounded-lg bg-white">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved
                        </option>
                        <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected
                        </option>
                    </select>
                    <button
                        class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Cari</button>
                </form>
                <a href="{{ route('admin.vendors.export', request()->query()) }}"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export Excel
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50 text-xs text-surface-500 uppercase tracking-wider">
                        <tr>
                            <th class="px-5 py-3 text-left font-semibold">Vendor</th>
                            <th class="px-5 py-3 text-left font-semibold">Pemilik</th>
                            <th class="px-5 py-3 text-left font-semibold">Kota</th>
                            <th class="px-5 py-3 text-left font-semibold">Events</th>
                            <th class="px-5 py-3 text-left font-semibold">Status</th>
                            <th class="px-5 py-3 text-left font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @foreach ($vendors as $vendor)
                            @php
                                $vsCfg = match ($vendor->verify_status) {
                                    'approved' => ['bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'Approved'],
                                    'rejected' => ['bg-red-50 text-red-700 ring-red-600/20', 'Rejected'],
                                    default => ['bg-amber-50 text-amber-700 ring-amber-600/20', 'Pending'],
                                };
                            @endphp
                            <tr class="hover:bg-surface-50">
                                <td class="px-5 py-3">
                                    <p class="font-medium text-surface-900">{{ $vendor->name }}</p>
                                    <p class="text-xs text-surface-500">{{ $vendor->phone }}</p>
                                </td>
                                <td class="px-5 py-3 text-surface-600 text-xs">
                                    {{ $vendor->user->name ?? '-' }}<br>{{ $vendor->user->email ?? '' }}</td>
                                <td class="px-5 py-3 text-surface-600">{{ $vendor->city }}</td>
                                <td class="px-5 py-3 text-surface-600">{{ $vendor->events_count }}</td>
                                <td class="px-5 py-3">
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-md ring-1 ring-inset {{ $vsCfg[0] }}">{{ $vsCfg[1] }}</span>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="flex items-center gap-1">
                                        @if ($vendor->verify_status !== 'approved')
                                            <button type="button"
                                                @click="showModal = true; modalAction = 'approved'; modalVendor = '{{ $vendor->name }}'; modalVendorId = '{{ $vendor->id }}'"
                                                class="px-2 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded hover:bg-emerald-100">Approve</button>
                                        @endif
                                        @if ($vendor->verify_status !== 'rejected')
                                            <button type="button"
                                                @click="showModal = true; modalAction = 'rejected'; modalVendor = '{{ $vendor->name }}'; modalVendorId = '{{ $vendor->id }}'"
                                                class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-50 rounded hover:bg-red-100">Reject</button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($vendors->hasPages())
                <div class="px-5 py-3 border-t border-surface-100">{{ $vendors->links() }}</div>
            @endif
        </div>
    </div>
</x-layouts.admin>
