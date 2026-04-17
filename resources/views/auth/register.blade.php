@props(['title' => 'Daftar - KarcisDigital'])

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
            class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-primary-600 via-primary-700 to-primary-900 relative overflow-hidden">
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
                            Bergabung dengan<br>Komunitas Event
                        </h1>
                        <p class="mt-4 text-lg text-white/70 max-w-md">
                            Daftarkan dirimu dan nikmati kemudahan membeli tiket event favorit. Dari konser, festival,
                            hingga workshop — semua ada di sini.
                        </p>
                    </div>

                    {{-- Features --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Pembelian Aman</div>
                                <div class="text-xs text-white/60">Pembayaran terverifikasi via Xendit</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">E-Ticket Digital</div>
                                <div class="text-xs text-white/60">Tiket langsung di smartphone kamu</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <div class="text-sm font-semibold text-white">Event Seluruh Indonesia</div>
                                <div class="text-xs text-white/60">Jakarta, Bali, Jogja, Bandung & lainnya</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bottom --}}
                <div class="text-sm text-white/40">
                    &copy; {{ date('Y') }} KarcisDigital. All rights reserved.
                </div>
            </div>
        </div>

        {{-- Right Side — Form --}}
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 bg-white">
            <div class="w-full max-w-md">
                {{-- Mobile Logo --}}
                <div class="lg:hidden flex items-center gap-2 mb-8">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-surface-900">KarcisDigital</span>
                </div>

                {{-- Header --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-surface-900">Buat Akun Baru</h2>
                    <p class="mt-2 text-surface-500">Daftar untuk mulai beli tiket event favoritmu</p>
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
                <form method="POST" action="/register" class="space-y-5">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-surface-700 mb-1.5">Nama
                            Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-surface-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="nama@email.com">
                    </div>

                    <div>
                        <label for="password"
                            class="block text-sm font-medium text-surface-700 mb-1.5">Password</label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label for="password_confirmation"
                            class="block text-sm font-medium text-surface-700 mb-1.5">Konfirmasi Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Ulangi password">
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                        Daftar
                    </button>
                </form>

                {{-- Divider --}}
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex-1 h-px bg-surface-200"></div>
                    <span class="text-sm text-surface-400">atau</span>
                    <div class="flex-1 h-px bg-surface-200"></div>
                </div>

                {{-- Links --}}
                <div class="mt-6 text-center space-y-3">
                    <p class="text-sm text-surface-500">
                        Sudah punya akun?
                        <a href="/login"
                            class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Masuk</a>
                    </p>
                    <p class="text-sm text-surface-500">
                        Ingin jadi vendor?
                        <a href="/register/vendor"
                            class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Daftar
                            vendor</a>
                    </p>
                </div>

                {{-- Back to home --}}
                <div class="mt-8 text-center">
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
