<x-layouts.app title="Pesanan - {{ $event->name }}">
    <x-slot:header>Pesanan</x-slot:header>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Pesanan</h1>
                <p class="text-surface-500 text-sm">{{ $event->name }}</p>
            </div>
            <a href="{{ route('vendor.orders.export', $event->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                Export Excel
            </a>
        </div>

        <div class="bg-white rounded-xl border border-surface-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Buyer</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Jumlah</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Total</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Status</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-200">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4">{{ $order->user->name }}</td>
                                <td class="px-6 py-4">{{ $order->quantity }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :variant="$order->status_payment === 'success' ? 'success' : ($order->status_payment === 'pending' ? 'warning' : 'danger')">
                                        {{ ucfirst($order->status_payment) }}
                                    </x-ui.badge>
                                </td>
                                <td class="px-6 py-4">
                                    <a href="/vendor/events/{{ $event->id }}/orders/{{ $order->id }}" class="text-primary-600 hover:underline text-xs">Detail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-surface-500">Belum ada pesanan</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $orders->links() }}</div>
    </div>
</x-layouts.app>
