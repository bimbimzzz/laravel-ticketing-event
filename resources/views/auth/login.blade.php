@props(['title' => 'Masuk - JagoEvent'])

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
                    <span class="text-2xl font-bold text-white">JagoEvent</span>
                </a>

                {{-- Center Content --}}
                <div class="space-y-8">
                    <div>
                        <h1 class="text-4xl font-extrabold text-white leading-tight">
                            Temukan Event<br>Seru di Sekitarmu
                        </h1>
                        <p class="mt-4 text-lg text-white/70 max-w-md">
                            Platform marketplace tiket event terpercaya. Beli tiket konser, festival, dan acara
                            favoritmu dengan mudah dan aman.
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex gap-8">
                        <div>
                            <div class="text-3xl font-bold text-white">500+</div>
                            <div class="text-sm text-white/60">Event Aktif</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">50K+</div>
                            <div class="text-sm text-white/60">Tiket Terjual</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold text-white">100+</div>
                            <div class="text-sm text-white/60">Vendor</div>
                        </div>
                    </div>
                </div>

                {{-- Testimonial --}}
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                    <p class="text-white/90 text-sm italic leading-relaxed">
                        "JagoEvent bikin beli tiket jadi gampang banget. Tinggal pilih, bayar, dan langsung dapat
                        e-ticket. Recommended!"
                    </p>
                    <div class="mt-4 flex items-center gap-3">
                        <div
                            class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                            MP</div>
                        <div>
                            <div class="text-sm font-semibold text-white">Mega Putri</div>
                            <div class="text-xs text-white/60">Event Enthusiast</div>
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
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-surface-900">JagoEvent</span>
                </div>

                {{-- Header --}}
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-surface-900">Selamat Datang Kembali</h2>
                    <p class="mt-2 text-surface-500">Masuk ke akun JagoEvent kamu</p>
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
                            <p class="text-sm text-red-700">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                {{-- Form --}}
                <form method="POST" action="/login" class="space-y-5">
                    @csrf

                    <div>
                        <label for="email" class="block text-sm font-medium text-surface-700 mb-1.5">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="nama@email.com">
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="block text-sm font-medium text-surface-700">Password</label>
                        </div>
                        <input type="password" id="password" name="password" required
                            class="w-full px-4 py-3 border border-surface-300 rounded-xl text-surface-900 placeholder-surface-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                            placeholder="Masukkan password">
                    </div>

                    <button type="submit"
                        class="w-full py-3 px-4 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                        Masuk
                    </button>
                </form>

                {{-- Divider --}}
                <div class="mt-8 flex items-center gap-4">
                    <div class="flex-1 h-px bg-surface-200"></div>
                    <span class="text-sm text-surface-400">atau</span>
                    <div class="flex-1 h-px bg-surface-200"></div>
                </div>

                {{-- Register Link --}}
                <div class="mt-6 text-center space-y-3">
                    <p class="text-sm text-surface-500">
                        Belum punya akun?
                        <a href="/register"
                            class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Daftar
                            sekarang</a>
                    </p>
                    <p class="text-sm text-surface-500">
                        Ingin jadi vendor?
                        <a href="/register/vendor"
                            class="font-semibold text-primary-600 hover:text-primary-700 transition-colors">Daftar
                            vendor</a>
                    </p>
                </div>

                {{-- Demo Accounts --}}
                <div class="mt-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex-1 h-px bg-surface-200"></div>
                        <span class="text-xs font-medium text-surface-400 uppercase tracking-wider">Akun Demo</span>
                        <div class="flex-1 h-px bg-surface-200"></div>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button"
                            onclick="document.getElementById('email').value='mega@gmail.com';document.getElementById('password').value='password';"
                            class="flex flex-col items-center gap-1.5 p-3 border border-surface-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-colors cursor-pointer group">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center group-hover:bg-blue-200 transition-colors">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-surface-700">User</span>
                        </button>
                        <button type="button"
                            onclick="document.getElementById('email').value='andi@JagoEvent.com';document.getElementById('password').value='password';"
                            class="flex flex-col items-center gap-1.5 p-3 border border-surface-200 rounded-xl hover:border-emerald-300 hover:bg-emerald-50 transition-colors cursor-pointer group">
                            <div
                                class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center group-hover:bg-emerald-200 transition-colors">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-surface-700">Vendor</span>
                        </button>
                        <button type="button"
                            onclick="document.getElementById('email').value='admin@admin.com';document.getElementById('password').value='password';"
                            class="flex flex-col items-center gap-1.5 p-3 border border-surface-200 rounded-xl hover:border-amber-300 hover:bg-amber-50 transition-colors cursor-pointer group">
                            <div
                                class="w-8 h-8 bg-amber-100 rounded-full flex items-center justify-center group-hover:bg-amber-200 transition-colors">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <span class="text-xs font-semibold text-surface-700">Admin</span>
                        </button>
                    </div>
                    <p class="text-center text-xs text-surface-400 mt-2">Password: <code
                            class="bg-surface-100 px-1.5 py-0.5 rounded text-surface-600">password</code></p>
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
