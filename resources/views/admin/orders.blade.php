<x-layouts.admin title="Orders - Admin KarcisDigital">
    <x-slot:header>Orders</x-slot:header>

    <div class="bg-white rounded-xl border border-surface-200">
        <div class="px-5 py-4 border-b border-surface-100 flex flex-wrap gap-3">
            <form method="GET" class="flex flex-1 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pembeli..."
                    class="flex-1 min-w-[200px] px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                <select name="status" onchange="this.form.submit()"
                    class="px-3 py-2 text-sm border border-surface-300 rounded-lg bg-white">
                    <option value="">Semua Status</option>
                    <option value="success" {{ request('status') === 'success' ? 'selected' : '' }}>Success</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="cancel" {{ request('status') === 'cancel' ? 'selected' : '' }}>Cancel</option>
                </select>
                <button
                    class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Cari</button>
            </form>
            <a href="{{ route('admin.orders.export', request()->query()) }}"
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
                        <th class="px-5 py-3 text-left font-semibold">#</th>
                        <th class="px-5 py-3 text-left font-semibold">Pembeli</th>
                        <th class="px-5 py-3 text-left font-semibold">Event</th>
                        <th class="px-5 py-3 text-left font-semibold">Qty</th>
                        <th class="px-5 py-3 text-left font-semibold">Total</th>
                        <th class="px-5 py-3 text-left font-semibold">Status</th>
                        <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    @foreach ($orders as $order)
                        @php
                            $sCfg = match ($order->status_payment) {
                                'success' => ['bg-emerald-50 text-emerald-700 ring-emerald-600/20', 'Success'],
                                'pending' => ['bg-amber-50 text-amber-700 ring-amber-600/20', 'Pending'],
                                default => ['bg-red-50 text-red-700 ring-red-600/20', 'Cancel'],
                            };
                        @endphp
                        <tr class="hover:bg-surface-50">
                            <td class="px-5 py-3 text-surface-400 font-mono text-xs">#{{ $order->id }}</td>
                            <td class="px-5 py-3">
                                <p class="font-medium text-surface-900">{{ $order->user->name ?? '-' }}</p>
                                <p class="text-xs text-surface-500">{{ $order->user->email ?? '' }}</p>
                            </td>
                            <td class="px-5 py-3 text-surface-600 max-w-[200px] truncate">
                                {{ $order->event->name ?? '-' }}</td>
                            <td class="px-5 py-3 text-surface-600">{{ $order->quantity }}</td>
                            <td class="px-5 py-3 font-semibold text-surface-900 whitespace-nowrap">Rp
                                {{ number_format($order->total_price, 0, ',', '.') }}</td>
                            <td class="px-5 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-md ring-1 ring-inset {{ $sCfg[0] }}">{{ $sCfg[1] }}</span>
                            </td>
                            <td class="px-5 py-3 text-surface-500 text-xs whitespace-nowrap">
                                {{ $order->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($orders->hasPages())
            <div class="px-5 py-3 border-t border-surface-100">{{ $orders->links() }}</div>
        @endif
    </div>
</x-layouts.admin>
