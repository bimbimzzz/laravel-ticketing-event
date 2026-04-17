<x-layouts.app title="Tambah SKU - JagoEvent">
    <x-slot:header>Tambah Tipe Tiket</x-slot:header>
    <div class="max-w-2xl mx-auto space-y-6">
        <div>
            <h1 class="text-2xl font-bold">Tambah SKU</h1>
            <p class="text-surface-500 text-sm">{{ $event->name }}</p>
        </div>

        <form action="/vendor/events/{{ $event->id }}/skus" method="POST"
            class="bg-white rounded-xl border border-surface-200 shadow-sm p-6 space-y-4">
            @csrf

            <x-form.input label="Nama SKU" name="name" :value="old('name')" required placeholder="Contoh: VIP, Regular" />

            <x-form.input label="Kategori" name="category" :value="old('category')" required
                placeholder="Contoh: Premium, Standard" />

            <x-form.input label="Harga" name="price" type="number" :value="old('price')" required />

            <x-form.input label="Stok" name="stock" type="number" :value="old('stock')" required />

            <x-form.select label="Tipe Hari" name="day_type" required>
                <option value="">Pilih Tipe</option>
                <option value="weekday" {{ old('day_type') === 'weekday' ? 'selected' : '' }}>Weekday</option>
                <option value="weekend" {{ old('day_type') === 'weekend' ? 'selected' : '' }}>Weekend</option>
            </x-form.select>

            <div class="flex justify-end">
                <x-ui.button type="submit">Simpan SKU</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.app>
