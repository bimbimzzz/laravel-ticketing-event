<x-layouts.app title="Buat Event - JagoEvent">
    <x-slot:header>Buat Event</x-slot:header>
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Buat Event</h1>

        <form action="/vendor/events" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl border border-surface-200 shadow-sm p-6 space-y-4">
            @csrf

            <x-form.input label="Nama Event" name="name" :value="old('name')" required />

            <x-form.select label="Kategori" name="event_category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('event_category_id') == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}</option>
                @endforeach
            </x-form.select>

            <x-form.textarea label="Deskripsi" name="description" required>{{ old('description') }}</x-form.textarea>

            <x-form.input label="Gambar" name="image" type="file" required />

            <div class="grid grid-cols-2 gap-4">
                <x-form.input label="Tanggal Mulai" name="start_date" type="date" :value="old('start_date')" required />
                <x-form.input label="Tanggal Selesai" name="end_date" type="date" :value="old('end_date')" required />
            </div>

            <div class="flex justify-end">
                <x-ui.button type="submit">Simpan Event</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.app>
