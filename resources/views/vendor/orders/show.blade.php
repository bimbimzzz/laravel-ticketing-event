<x-layouts.app title="Detail Pesanan - KarcisDigital">
    <x-slot:header>Detail Pesanan</x-slot:header>
    <div class="max-w-3xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold">Detail Pesanan #{{ $order->id }}</h1>
            <p class="text-surface-500 text-sm">{{ $event->name }}</p>
        </div>

        <div class="bg-white rounded-xl border border-surface-200 shadow-sm p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-surface-500">Buyer</p>
                    <p class="font-medium">{{ $order->user->name }}</p>
                </div>
                <div>
                    <p class="text-surface-500">Status</p>
                    <x-ui.badge :variant="$order->status_payment === 'success' ? 'success' : ($order->status_payment === 'pending' ? 'warning' : 'danger')">
                        {{ ucfirst($order->status_payment) }}
                    </x-ui.badge>
                </div>
                <div>
                    <p class="text-surface-500">Jumlah Tiket</p>
                    <p class="font-medium">{{ $order->quantity }}</p>
                </div>
                <div>
                    <p class="text-surface-500">Total</p>
                    <p class="font-medium">Rp {{ number_format($order->total_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl border border-surface-200 shadow-sm">
            <div class="p-6 border-b border-surface-200">
                <h2 class="text-lg font-semibold">Tiket</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Kode Tiket</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">SKU</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-200">
                        @foreach ($order->orderTickets as $ot)
                            <tr>
                                <td class="px-6 py-4 font-mono">{{ $ot->ticket->ticket_code }}</td>
                                <td class="px-6 py-4">{{ $ot->ticket->sku->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :variant="$ot->ticket->status === 'sold' ? 'success' : ($ot->ticket->status === 'redeem' ? 'info' : 'warning')">
                                        {{ ucfirst($ot->ticket->status) }}
                                    </x-ui.badge>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
