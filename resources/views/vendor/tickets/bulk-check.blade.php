<x-layouts.app title="Bulk Check-in - KarcisDigital">
    <x-slot:header>Bulk Check-in</x-slot:header>
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold">Bulk Check-in</h1>
            <p class="text-surface-500 text-sm">Masukkan beberapa kode tiket sekaligus (satu per baris)</p>
        </div>

        <div class="bg-white rounded-xl border border-surface-200 p-5">
            <form method="POST" action="{{ route('vendor.tickets.bulk-check.post') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-surface-700 mb-2">Kode Tiket</label>
                    <textarea name="ticket_codes" rows="8" required placeholder="TKT-ABC12345&#10;TKT-DEF67890&#10;TKT-GHI11223"
                        class="w-full px-3 py-2 text-sm font-mono border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300"></textarea>
                </div>
                <button type="submit"
                    class="px-6 py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700">
                    Check-in Semua
                </button>
            </form>
        </div>

        @if (session('results'))
            @php $results = session('results'); @endphp
            <div class="space-y-4">
                @if (count($results['success']) > 0)
                    <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4">
                        <h3 class="text-sm font-bold text-emerald-800 mb-2">Berhasil di-redeem
                            ({{ count($results['success']) }})</h3>
                        <div class="space-y-1">
                            @foreach ($results['success'] as $code)
                                <p class="text-sm text-emerald-700 font-mono">{{ $code }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (count($results['failed']) > 0)
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                        <h3 class="text-sm font-bold text-red-800 mb-2">Gagal ({{ count($results['failed']) }})</h3>
                        <div class="space-y-1">
                            @foreach ($results['failed'] as $fail)
                                <p class="text-sm text-red-700"><span class="font-mono">{{ $fail['code'] }}</span> —
                                    {{ $fail['reason'] }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-layouts.app>
