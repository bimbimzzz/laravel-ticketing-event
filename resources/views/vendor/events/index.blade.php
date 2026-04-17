<x-layouts.app title="Event Saya - JagoEvent">
    <x-slot:header>Event Saya</x-slot:header>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-bold">Event Saya</h1>
            <a href="/vendor/events/create">
                <x-ui.button>Buat Event</x-ui.button>
            </a>
        </div>

        <div class="bg-white rounded-xl border border-surface-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-surface-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Nama</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Kategori</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Tanggal</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Status</th>
                            <th class="px-6 py-3 text-left font-medium text-surface-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-surface-200">
                        @forelse($events as $event)
                            <tr>
                                <td class="px-6 py-4 font-medium">{{ $event->name }}</td>
                                <td class="px-6 py-4">{{ $event->eventCategory->name ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $event->start_date }} - {{ $event->end_date }}</td>
                                <td class="px-6 py-4">
                                    <x-ui.badge :variant="$event->status_variant">{{ $event->status_label }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <a href="/vendor/events/{{ $event->id }}/skus"
                                            class="text-primary-600 hover:underline text-xs">SKU</a>
                                        <a href="/vendor/events/{{ $event->id }}/orders"
                                            class="text-primary-600 hover:underline text-xs">Orders</a>
                                        <a href="/vendor/events/{{ $event->id }}/promos"
                                            class="text-emerald-600 hover:underline text-xs">Promo</a>
                                        <a href="/vendor/events/{{ $event->id }}/edit"
                                            class="text-primary-600 hover:underline text-xs">Edit</a>
                                        <button type="button"
                                            @click="$dispatch('open-delete-modal', { id: {{ $event->id }}, name: '{{ addslashes($event->name) }}' })"
                                            class="text-red-600 hover:underline text-xs">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-8 text-center text-surface-500">Belum ada event</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div>{{ $events->links() }}</div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div x-data="{ open: false, eventId: null, eventName: '' }"
        @open-delete-modal.window="open = true; eventId = $event.detail.id; eventName = $event.detail.name"
        x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center">
        {{-- Backdrop --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="open = false"
            class="absolute inset-0 bg-black/50"></div>
        {{-- Modal --}}
        <div x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-xl max-w-md w-full mx-4 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-surface-900">Hapus Event</h3>
                    <p class="text-sm text-surface-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>
            <p class="text-sm text-surface-700 mb-6">Apakah kamu yakin ingin menghapus event <span class="font-semibold"
                    x-text="eventName"></span>? Semua data SKU, tiket, dan pesanan terkait akan ikut terhapus.</p>
            <div class="flex items-center justify-end gap-3">
                <button @click="open = false"
                    class="px-4 py-2 text-sm font-medium text-surface-700 bg-surface-100 rounded-lg hover:bg-surface-200 transition-colors">Batal</button>
                <form :action="'/vendor/events/' + eventId" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors">Ya,
                        Hapus</button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.app>
