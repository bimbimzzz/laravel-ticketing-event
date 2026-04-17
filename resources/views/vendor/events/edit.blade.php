<x-layouts.app title="Edit Event - KarcisDigital">
    <x-slot:header>Edit Event</x-slot:header>
    <div class="max-w-2xl mx-auto space-y-6">
        <h1 class="text-2xl font-bold">Edit Event</h1>

        <form action="/vendor/events/{{ $event->id }}" method="POST" enctype="multipart/form-data"
            class="bg-white rounded-xl border border-surface-200 shadow-sm p-6 space-y-4">
            @csrf
            @method('PUT')

            <x-form.input label="Nama Event" name="name" :value="old('name', $event->name)" required />

            <x-form.select label="Kategori" name="event_category_id" required>
                <option value="">Pilih Kategori</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}"
                        {{ old('event_category_id', $event->event_category_id) == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}</option>
                @endforeach
            </x-form.select>

            <x-form.textarea label="Deskripsi" name="description"
                required>{{ old('description', $event->description) }}</x-form.textarea>

            @if ($event->image)
                <div>
                    <p class="text-sm font-medium text-surface-700 mb-1.5">Gambar Saat Ini</p>
                    <img src="/images/events/{{ $event->image }}" alt="{{ $event->name }}"
                        class="w-40 h-24 object-cover rounded-lg">
                </div>
            @endif

            <x-form.input label="Ganti Gambar (opsional)" name="image" type="file" />

            <div class="grid grid-cols-2 gap-4">
                <x-form.input label="Tanggal Mulai" name="start_date" type="date" :value="old('start_date', $event->start_date)" required />
                <x-form.input label="Tanggal Selesai" name="end_date" type="date" :value="old('end_date', $event->end_date)" required />
            </div>

            <div class="flex justify-end">
                <x-ui.button type="submit">Perbarui Event</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.app>
