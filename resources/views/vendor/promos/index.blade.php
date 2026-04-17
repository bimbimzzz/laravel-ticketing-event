<x-layouts.app title="Promo Codes - {{ $event->name }}">
    <x-slot:header>Promo Codes</x-slot:header>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold">Promo Codes</h1>
                <p class="text-surface-500 text-sm">{{ $event->name }}</p>
            </div>
            <a href="/vendor/events" class="text-sm text-primary-600 hover:underline">&larr; Kembali ke Event</a>
        </div>

        {{-- Create Form --}}
        <div class="bg-white rounded-xl border border-surface-200 p-5">
            <h2 class="text-sm font-bold text-surface-900 mb-4">Tambah Promo Baru</h2>
            <form method="POST" action="{{ route('vendor.promos.store', $event->id) }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @csrf
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1">Kode Promo</label>
                    <input type="text" name="code" required placeholder="DISKON10" class="w-full px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 uppercase" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1">Tipe Diskon</label>
                    <select name="discount_type" class="w-full px-3 py-2 text-sm border border-surface-300 rounded-lg bg-white">
                        <option value="percentage">Persentase (%)</option>
                        <option value="fixed">Nominal (Rp)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1">Nilai Diskon</label>
                    <input type="number" name="discount_value" required min="1" placeholder="10" class="w-full px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1">Maks. Penggunaan</label>
                    <input type="number" name="max_usage" required min="1" placeholder="100" class="w-full px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                </div>
                <div>
                    <label class="block text-xs font-medium text-surface-500 mb-1">Berlaku Sampai</label>
                    <input type="date" name="expires_at" required class="w-full px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300" />
                </div>
                <div class="flex items-end">
                    <button type="submit" class="px-4 py-2 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">Tambah</button>
                </div>
            </form>
        </div>

        {{-- List --}}
        <div class="bg-white rounded-xl border border-surface-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50">
                        <tr>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Kode</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Diskon</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Penggunaan</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Berlaku Sampai</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Status</th>
                            <th class="px-5 py-3 text-left font-medium text-surface-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-100">
                        @forelse($promos as $promo)
                            <tr>
                                <td class="px-5 py-3 font-mono font-bold">{{ $promo->code }}</td>
                                <td class="px-5 py-3">
                                    @if($promo->discount_type === 'percentage')
                                        {{ $promo->discount_value }}%
                                    @else
                                        Rp {{ number_format($promo->discount_value, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-5 py-3">{{ $promo->used_count }} / {{ $promo->max_usage }}</td>
                                <td class="px-5 py-3">{{ $promo->expires_at->format('d M Y') }}</td>
                                <td class="px-5 py-3">
                                    @if($promo->isValid())
                                        <span class="px-2 py-0.5 text-xs font-semibold bg-emerald-50 text-emerald-700 rounded-md">Aktif</span>
                                    @else
                                        <span class="px-2 py-0.5 text-xs font-semibold bg-red-50 text-red-700 rounded-md">Expired</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <form method="POST" action="{{ route('vendor.promos.destroy', [$event->id, $promo->id]) }}">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-700 text-xs font-semibold">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-surface-500">Belum ada promo code</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
