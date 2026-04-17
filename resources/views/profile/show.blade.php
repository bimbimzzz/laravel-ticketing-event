<x-layouts.landing title="Profil - JagoEvent" :navDark="true">
    <div class="max-w-2xl mx-auto py-12 px-4">
        <h1 class="text-2xl font-bold mb-6">Profil Saya</h1>

        @if (session('success'))
            <div class="mb-4">
                <x-ui.alert variant="success">{{ session('success') }}</x-ui.alert>
            </div>
        @endif

        <form action="/profile" method="POST"
            class="bg-white rounded-xl border border-surface-200 shadow-sm p-6 space-y-4">
            @csrf
            @method('PUT')

            <x-form.input label="Nama" name="name" :value="old('name', $user->name)" required />
            <x-form.input label="Email" name="email" type="email" :value="old('email', $user->email)" required />

            <div class="text-sm text-surface-500">
                Status Vendor:
                @if ($user->is_vendor)
                    <x-ui.badge variant="success">Vendor Aktif</x-ui.badge>
                @else
                    <span>Belum terdaftar.</span>
                    <a href="/register/vendor" class="text-primary-600 hover:underline">Daftar sebagai vendor</a>
                @endif
            </div>

            <div class="flex justify-end">
                <x-ui.button type="submit">Simpan</x-ui.button>
            </div>
        </form>
    </div>
</x-layouts.landing>
