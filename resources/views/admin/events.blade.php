<x-layouts.admin title="Events - Admin JagoEvent">
    <x-slot:header>Events</x-slot:header>

    <div class="bg-white rounded-xl border border-surface-200">
        <div class="px-5 py-4 border-b border-surface-100">
            <form method="GET" class="flex gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari event..."
                    class="flex-1 px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                <button
                    class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Cari</button>
            </form>
            <a href="{{ route('admin.events.export', request()->query()) }}"
                class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors mt-3 sm:mt-0">
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
                        <th class="px-5 py-3 text-left font-semibold">Event</th>
                        <th class="px-5 py-3 text-left font-semibold">Vendor</th>
                        <th class="px-5 py-3 text-left font-semibold">Kategori</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                        <th class="px-5 py-3 text-left font-semibold">SKU</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @foreach ($events as $event)
                        @php
                            $sCfg = match ($event->status) {
                                'ongoing' => ['bg-emerald-50 text-emerald-700', 'Berlangsung'],
                                'past' => ['bg-surface-100 text-surface-600', 'Selesai'],
                                default => ['bg-primary-50 text-primary-700', 'Akan Datang'],
                            };
                        @endphp
                        <tr class="hover:bg-surface-50">
                            <td class="px-5 py-3">
                                <p class="font-medium text-surface-900 max-w-[250px] truncate">{{ $event->name }}</p>
                            </td>
                            <td class="px-5 py-3 text-surface-600 text-xs">{{ $event->vendor->name ?? '-' }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="px-2 py-0.5 text-xs font-medium bg-surface-100 text-surface-600 rounded-md">{{ $event->eventCategory->name ?? '-' }}</span>
                            </td>
                            <td class="px-5 py-3 text-surface-600 text-xs whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($event->start_date)->format('d M Y') }}
                                @if ($event->end_date)
                                    — {{ \Carbon\Carbon::parse($event->end_date)->format('d M Y') }}
                                @endif
                            </td>
                            <td class="px-5 py-3 text-surface-600">{{ $event->skus_count }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="px-2 py-0.5 text-xs font-semibold rounded-md {{ $sCfg[0] }}">{{ $sCfg[1] }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($events->hasPages())
            <div class="px-5 py-3 border-t border-surface-100">{{ $events->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
