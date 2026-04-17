<x-layouts.app title="Validasi Tiket - JagoEvent">
    <x-slot:header>Validasi Tiket</x-slot:header>
    <div class="max-w-lg mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Validasi Tiket</h1>
            <a href="/vendor/tickets/bulk-check"
                class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-primary-600 bg-primary-50 rounded-lg hover:bg-primary-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
                Bulk Check-in
            </a>
        </div>

        @if (session('success'))
            <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
        @endif

        @if (session('error'))
            <x-ui.alert variant="danger">{{ session('error') }}</x-ui.alert>
        @endif

        <form action="/vendor/tickets/check" method="POST"
            class="bg-white rounded-xl border border-surface-200 shadow-sm p-6 space-y-4">
            @csrf

            <x-form.input label="Kode Tiket" name="ticket_code" :value="old('ticket_code')" required
                placeholder="Masukkan kode tiket" />

            <x-ui.button type="submit" class="w-full">Validasi</x-ui.button>
        </form>
    </div>
</x-layouts.app>
