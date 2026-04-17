@props(['title' => 'Daftar Vendor - KarcisDigital'])

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen flex">
        {{-- Left Side — Branding --}}
        <div
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 relative overflow-hidden">
            <div class="absolute inset-0">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white/5 rounded-full -translate-x-1/2 -translate-y-1/2">
                </div>
                <div
                    class="absolute bottom-0 right-0 w-80 h-80 bg-white/5 rounded-full translate-x-1/3 translate-y-1/3">
                </div>
                <div class="absolute top-1/2 left-1/3 w-64 h-64 bg-white/5 rounded-full"></div>
            </div>

            <div class="relative z-10 flex flex-col justify-between p-12 w-full">
                {{-- Logo --}}
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-2xl font-bold text-white">KarcisDigital</span>
                </a>

                {{-- Center Content --}}
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl font-extrabold text-white leading-tight">
                            Jual Tiket Event<br>Lebih Mudah
                        </h1>
                        <p class="mt-4 text-lg text-white/70 max-w-md">
                            Bergabung sebagai vendor di KarcisDigital. Kelola event, jual tiket, dan pantau penjualan
                            dalam satu platform.
                        </p>
                    </div>

                    {{-- Benefits --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Dashboard Penjualan</div>
                                <div class="text-xs text-white/60">Pantau tiket terjual & revenue real-time</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">QR Code Scanning</div>
                                <div class="text-xs text-white/60">Validasi tiket pengunjung dengan scan QR</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Pembayaran Otomatis</div>
                                <div class="text-xs text-white/60">Integrasi Midtrans untuk semua metode bayar</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom --}}
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <p class="text-white/90 text-sm italic leading-relaxed">
                        "Sejak pakai KarcisDigital, penjualan tiket event kami naik 3x lipat. Dashboard-nya lengkap dan
                        mudah dipakai."
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            AP</div>
                        <div>
                            <div class="text-sm font-semibold text-white">Andi Pratama</div>
                            <div class="text-xs text-white/60">KarcisDigital Production</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Side — Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-md">
                {{-- Mobile Logo --}}
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-surface-900">KarcisDigital</span>
                </div>

                {{-- Header --}}
                <div class="mb-6">
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-700 rounded-full text-xs font-semibold mb-4">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        Vendor / Organizer
                    </div>
                    <h2 class="text-2xl font-bold text-surface-900">Daftar Sebagai Vendor</h2>
                    <p class="mt-2 text-surface-500">Mulai jual tiket event kamu di KarcisDigital</p>
                </div>

                {{-- Info Banner --}}
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-amber-700">Setelah mendaftar, akun vendor akan diverifikasi oleh admin
                            terlebih dahulu.</p>
                    </div>
                </div>

                {{-- Error Alert --}}
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="/register/vendor" class="space-y-4">
                    @csrf

                    {{-- Section: Data Akun --}}
                    <div class="pb-2">
                        <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider">Data Akun</h3>
                    </div>

                    <div>
                        <label for="name" class="block text-sm font-medium text-surface-700 mb-1.5">Nama
                            Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            placeholder="Nama lengkap Anda">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-surface-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            placeholder="email@contoh.com">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password"
                                class="block text-sm font-medium text-surface-700 mb-1.5">Password</label>
                            <input type="password" id="password" name="password" required
                                class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="Min. 8 karakter">
                        </div>
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-surface-700 mb-1.5">Konfirmasi Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required
                                class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="Ulangi password">
                        </div>
                    </div>

                    {{-- Section: Data Vendor --}}
                    <div class="pt-2 pb-2 border-t border-surface-200">
                        <h3 class="text-sm font-semibold text-surface-500 uppercase tracking-wider">Data Vendor</h3>
                    </div>

                    <div>
                        <label for="vendor_name" class="block text-sm font-medium text-surface-700 mb-1.5">Nama Vendor
                            / Organizer</label>
                        <input type="text" id="vendor_name" name="vendor_name" value="{{ old('vendor_name') }}"
                            required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            placeholder="Nama usaha atau organizer">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="phone" class="block text-sm font-medium text-surface-700 mb-1.5">No.
                                Telepon</label>
                            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                                class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="08xxxxxxxxxx">
                        </div>
                        <div>
                            <label for="city"
                                class="block text-sm font-medium text-surface-700 mb-1.5">Kota</label>
                            <input type="text" id="city" name="city" value="{{ old('city') }}" required
                                class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                                placeholder="Kota domisili">
                        </div>
                    </div>

                    <div>
                        <label for="location" class="block text-sm font-medium text-surface-700 mb-1.5">Alamat
                            Lengkap</label>
                        <input type="text" id="location" name="location" value="{{ old('location') }}" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors"
                            placeholder="Alamat lengkap kantor / usaha">
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-surface-700 mb-1.5">Deskripsi
                            <span class="text-surface-400 font-normal">(opsional)</span></label>
                        <textarea id="description" name="description" rows="3"
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors resize-none"
                            placeholder="Ceritakan tentang usaha atau organizer Anda">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-colors">
                        Daftar Vendor
                    </button>
                </form>

                {{-- Divider --}}
                <div class="mt-6 flex items-center gap-4">
                    <div class="flex-1 h-px bg-surface-200"></div>
                    <span class="text-sm text-surface-400">atau</span>
                    <div class="flex-1 h-px bg-surface-200"></div>
                </div>

                {{-- Links --}}
                <div class="mt-5 text-center space-y-3">
                    <p class="text-sm text-surface-500">
                        Sudah punya akun?
                        <a href="/login"
                            class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Masuk</a>
                    </p>
                    <p class="text-sm text-surface-500">
                        Daftar sebagai pembeli?
                        <a href="/register"
                            class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Daftar
                            biasa</a>
                    </p>
                </div>

                {{-- Back to home --}}
                <div class="mt-6 text-center">
                    <a href="/"
                        class="inline-flex items-center gap-1.5 text-sm text-surface-400 hover:text-surface-600 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke beranda
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
