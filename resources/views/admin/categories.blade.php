<x-layouts.admin title="Kategori - Admin JagoEvent">
    <x-slot:header>Kategori Event</x-slot:header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Add Category --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-surface-200 p-5">
                <h3 class="text-sm font-bold text-surface-900 mb-4">Tambah Kategori</h3>
                <form method="POST" action="/superadmin/categories">
                    @csrf
                    <input type="text" name="name" placeholder="Nama kategori..." required
                        class="w-full px-3 py-2 text-sm border border-surface-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-300 mb-3" />
                    @error('name')
                        <p class="text-xs text-red-600 mb-2">{{ $message }}</p>
                    @enderror
                    <button
                        class="w-full py-2.5 bg-primary-600 text-white text-sm font-semibold rounded-lg hover:bg-primary-700 transition-colors">Tambah</button>
                </form>
            </div>
        </div>

        {{-- Category List --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-surface-200">
                <div class="px-5 py-4 border-b border-surface-100">
                    <h3 class="text-sm font-bold text-surface-900">Daftar Kategori ({{ $categories->count() }})</h3>
                </div>
                <div class="divide-y divide-surface-100">
                    @foreach ($categories as $cat)
                        <div class="px-5 py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-surface-900">{{ $cat->name }}</p>
                                    <p class="text-xs text-surface-500">{{ $cat->events_count }} events</p>
                                </div>
                            </div>
                            @if ($cat->events_count === 0)
                                <form method="POST" action="/superadmin/categories/{{ $cat->id }}"
                                    onsubmit="return confirm('Hapus kategori {{ $cat->name }}?')">
                                    @csrf @method('DELETE')
                                    <button class="text-xs text-red-600 hover:text-red-700 font-medium">Hapus</button>
                                </form>
                            @else
                                <span class="text-xs text-surface-400">Digunakan</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
