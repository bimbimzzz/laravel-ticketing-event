<x-layouts.landing title="Design System - EventKu">

    <div class="pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="mb-12">
                <h1 class="text-3xl font-bold text-surface-900">Design System</h1>
                <p class="mt-2 text-surface-500">Preview semua komponen UI yang tersedia</p>
            </div>

            {{-- Buttons --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Buttons</h2>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-medium text-surface-500 mb-3">Variants</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <x-ui.button variant="primary">Primary</x-ui.button>
                            <x-ui.button variant="secondary">Secondary</x-ui.button>
                            <x-ui.button variant="danger">Danger</x-ui.button>
                            <x-ui.button variant="success">Success</x-ui.button>
                            <x-ui.button variant="warning">Warning</x-ui.button>
                            <x-ui.button variant="ghost">Ghost</x-ui.button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-surface-500 mb-3">Sizes</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <x-ui.button size="xs">Extra Small</x-ui.button>
                            <x-ui.button size="sm">Small</x-ui.button>
                            <x-ui.button size="default">Default</x-ui.button>
                            <x-ui.button size="lg">Large</x-ui.button>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-sm font-medium text-surface-500 mb-3">States</h3>
                        <div class="flex flex-wrap items-center gap-3">
                            <x-ui.button variant="primary" disabled>Disabled</x-ui.button>
                            <x-ui.button variant="primary" href="#">As Link</x-ui.button>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Badges --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Badges</h2>
                <div class="flex flex-wrap items-center gap-3">
                    <x-ui.badge variant="primary">Primary</x-ui.badge>
                    <x-ui.badge variant="secondary">Secondary</x-ui.badge>
                    <x-ui.badge variant="success">Success</x-ui.badge>
                    <x-ui.badge variant="warning">Warning</x-ui.badge>
                    <x-ui.badge variant="danger">Danger</x-ui.badge>
                    <x-ui.badge variant="info">Info</x-ui.badge>
                </div>
            </section>

            {{-- Alerts --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Alerts</h2>
                <div class="space-y-4 max-w-2xl">
                    <x-ui.alert variant="info">Ini adalah alert informasi untuk pengguna.</x-ui.alert>
                    <x-ui.alert variant="success">Operasi berhasil dilakukan!</x-ui.alert>
                    <x-ui.alert variant="warning">Perhatian! Ada sesuatu yang perlu diperhatikan.</x-ui.alert>
                    <x-ui.alert variant="danger">Terjadi kesalahan pada proses.</x-ui.alert>
                    <x-ui.alert variant="info" dismissible>Alert yang bisa di-dismiss oleh pengguna.</x-ui.alert>
                </div>
            </section>

            {{-- Cards --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Cards</h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <x-ui.card title="Card dengan Title" description="Deskripsi singkat card ini">
                        <p class="text-sm text-surface-600">Konten card ditaruh di sini. Bisa berisi apapun.</p>
                    </x-ui.card>

                    <x-ui.card title="Card dengan Footer">
                        <p class="text-sm text-surface-600">Card ini memiliki footer section.</p>
                        <x-slot:footer>
                            <div class="flex justify-end gap-2">
                                <x-ui.button variant="ghost" size="sm">Batal</x-ui.button>
                                <x-ui.button variant="primary" size="sm">Simpan</x-ui.button>
                            </div>
                        </x-slot:footer>
                    </x-ui.card>
                </div>
            </section>

            {{-- Stat Cards --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Stat Cards</h2>
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <x-ui.stat-card label="Total Event" value="1,234" trend="+12%" :trendUp="true" color="primary">
                        <x-slot:icon>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </x-slot:icon>
                    </x-ui.stat-card>

                    <x-ui.stat-card label="Tiket Terjual" value="50K" trend="+8%" :trendUp="true" color="success">
                        <x-slot:icon>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </x-slot:icon>
                    </x-ui.stat-card>

                    <x-ui.stat-card label="Revenue" value="Rp 2.5M" trend="-3%" :trendUp="false" color="warning">
                        <x-slot:icon>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </x-slot:icon>
                    </x-ui.stat-card>

                    <x-ui.stat-card label="Vendor Aktif" value="528" color="info">
                        <x-slot:icon>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </x-slot:icon>
                    </x-ui.stat-card>
                </div>
            </section>

            {{-- Modal --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Modal</h2>
                <x-ui.button variant="primary" @click="$dispatch('open-modal', 'demo-modal')">
                    Open Modal
                </x-ui.button>

                <x-ui.modal name="demo-modal" title="Contoh Modal">
                    <p class="text-sm text-surface-600">Ini adalah konten modal. Bisa berisi form, informasi, atau konfirmasi apapun.</p>
                    <x-slot:footer>
                        <x-ui.button variant="ghost" size="sm" @click="$dispatch('close-modal', 'demo-modal')">Batal</x-ui.button>
                        <x-ui.button variant="primary" size="sm">Konfirmasi</x-ui.button>
                    </x-slot:footer>
                </x-ui.modal>
            </section>

            {{-- Form Components --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Form Components</h2>
                <div class="max-w-lg space-y-6">
                    <x-form.input label="Nama Event" name="event_name" placeholder="Masukkan nama event" required hint="Nama event akan ditampilkan di halaman publik" />

                    <x-form.input label="Email" name="email" type="email" placeholder="email@example.com" />

                    <x-form.select label="Kategori" name="category" required hint="Pilih kategori event">
                        <option value="">Pilih kategori...</option>
                        <option value="music">Musik</option>
                        <option value="sport">Olahraga</option>
                        <option value="tech">Teknologi</option>
                        <option value="art">Seni & Budaya</option>
                    </x-form.select>

                    <x-form.textarea label="Deskripsi" name="description" placeholder="Tulis deskripsi event..." hint="Minimal 50 karakter" />

                    <x-form.toggle label="Publish Event" name="is_published" :checked="true" hint="Event akan terlihat oleh publik" />

                    <x-form.toggle label="Enable Notifications" name="notifications" />
                </div>
            </section>

            {{-- Toast Demo --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Toast Notifications</h2>
                <div class="flex flex-wrap gap-3">
                    <x-ui.button variant="success" size="sm"
                        @click="$dispatch('toast', { message: 'Berhasil menyimpan data!', type: 'success' })">
                        Success Toast
                    </x-ui.button>
                    <x-ui.button variant="danger" size="sm"
                        @click="$dispatch('toast', { message: 'Terjadi kesalahan!', type: 'error' })">
                        Error Toast
                    </x-ui.button>
                    <x-ui.button variant="warning" size="sm"
                        @click="$dispatch('toast', { message: 'Perhatian! Stok tinggal sedikit.', type: 'warning' })">
                        Warning Toast
                    </x-ui.button>
                    <x-ui.button variant="primary" size="sm"
                        @click="$dispatch('toast', { message: 'Info: Update tersedia.', type: 'info' })">
                        Info Toast
                    </x-ui.button>
                </div>
            </section>

            {{-- Color Palette --}}
            <section class="mb-16">
                <h2 class="text-xl font-semibold text-surface-900 mb-6 pb-2 border-b border-surface-200">Color Palette</h2>
                <div class="space-y-6">
                    <div>
                        <h3 class="text-sm font-medium text-surface-500 mb-3">Primary (Blue)</h3>
                        <div class="flex gap-1">
                            <div class="w-16 h-16 rounded-lg bg-primary-50 flex items-end p-1"><span class="text-[10px] text-surface-600">50</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-100 flex items-end p-1"><span class="text-[10px] text-surface-600">100</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-200 flex items-end p-1"><span class="text-[10px] text-surface-600">200</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-300 flex items-end p-1"><span class="text-[10px] text-surface-700">300</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-400 flex items-end p-1"><span class="text-[10px] text-white">400</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-500 flex items-end p-1"><span class="text-[10px] text-white">500</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-600 flex items-end p-1"><span class="text-[10px] text-white">600</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-700 flex items-end p-1"><span class="text-[10px] text-white">700</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-800 flex items-end p-1"><span class="text-[10px] text-white">800</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-900 flex items-end p-1"><span class="text-[10px] text-white">900</span></div>
                            <div class="w-16 h-16 rounded-lg bg-primary-950 flex items-end p-1"><span class="text-[10px] text-white">950</span></div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-surface-500 mb-3">Surface (Neutral)</h3>
                        <div class="flex gap-1">
                            <div class="w-16 h-16 rounded-lg bg-surface-50 border border-surface-200 flex items-end p-1"><span class="text-[10px] text-surface-600">50</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-100 flex items-end p-1"><span class="text-[10px] text-surface-600">100</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-200 flex items-end p-1"><span class="text-[10px] text-surface-600">200</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-300 flex items-end p-1"><span class="text-[10px] text-surface-700">300</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-400 flex items-end p-1"><span class="text-[10px] text-white">400</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-500 flex items-end p-1"><span class="text-[10px] text-white">500</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-600 flex items-end p-1"><span class="text-[10px] text-white">600</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-700 flex items-end p-1"><span class="text-[10px] text-white">700</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-800 flex items-end p-1"><span class="text-[10px] text-white">800</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-900 flex items-end p-1"><span class="text-[10px] text-white">900</span></div>
                            <div class="w-16 h-16 rounded-lg bg-surface-950 flex items-end p-1"><span class="text-[10px] text-white">950</span></div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

</x-layouts.landing>
