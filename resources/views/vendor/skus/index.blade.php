<x-layouts.app title="SKU - {{ $event->name }}">
    <x-slot:header>Tipe Tiket</x-slot:header>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">SKU & Tiket</h1>
                <p class="text-surface-500 text-sm">{{ $event->name }}</p>
            </div>
            <a href="/vendor/events/{{ $event->id }}/skus/create">
                <x-ui.button>Tambah SKU</x-ui.button>
            </a>
        </div>

        <div class="bg-white rounded-xl border border-surface-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Nama</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Kategori</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Harga</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Stok</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Tipe Hari</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-200">
                        @forelse($skus as $sku)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $sku->name }}</td>
                                <td class="px-6 py-4">{{ $sku->category }}</td>
                                <td class="px-6 py-4">Rp {{ number_format($sku->price, 0, ',', '.') }}</td>
                                <td class="px-6 py-4">{{ $sku->stock }}</td>
                                <td class="px-6 py-4">{{ ucfirst($sku->day_type) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-surface-500">Belum ada SKU</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
