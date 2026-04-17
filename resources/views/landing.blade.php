<x-layouts.landing>

    {{-- Hero Section --}}
    <section
        class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-primary-700 via-primary-600 to-primary-800">

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32 lg:py-40">
            <div class="text-center max-w-4xl mx-auto">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white leading-tight tracking-tight">
                    Temukan Event Terbaik,
                    <span class="block text-primary-200">Jual Tiket dengan Mudah</span>
                </h1>
                <p class="mt-6 text-lg sm:text-xl text-primary-100 max-w-2xl mx-auto">
                    Platform marketplace tiket event terpercaya. Beli tiket event favorit atau jual tiket event Anda ke
                    ribuan pembeli.
                </p>

                {{-- Dual CTA --}}
                <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                    <a href="/events"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold rounded-xl bg-white text-primary-700 hover:bg-primary-50 transition-colors shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        Cari Event
                    </a>
                    <a href="@auth{{ Auth::user()->is_vendor ? '/vendor/dashboard' : '/register/vendor' }}@else{{ '/register' }} @endauth"
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold rounded-xl bg-primary-500/20 text-white border-2 border-white/30 hover:bg-primary-500/30 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        Jual Tiket
                    </a>
                </div>
            </div>

            {{-- Stats bar --}}
            <div class="mt-20 grid grid-cols-3 gap-8 max-w-3xl mx-auto">
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold text-white">1000+</p>
                    <p class="mt-1 text-sm text-primary-200">Events</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold text-white">50K+</p>
                    <p class="mt-1 text-sm text-primary-200">Tiket Terjual</p>
                </div>
                <div class="text-center">
                    <p class="text-3xl sm:text-4xl font-bold text-white">500+</p>
                    <p class="mt-1 text-sm text-primary-200">Event Organizer</p>
                </div>
            </div>
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 120L60 110C120 100 240 80 360 70C480 60 600 60 720 65C840 70 960 80 1080 85C1200 90 1320 90 1380 90L1440 90V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                    fill="white" />
            </svg>
        </div>
    </section>

    {{-- Mobile App Preview --}}
    <section class="py-20 lg:py-28 bg-surface-50 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-primary-50 text-primary-700 rounded-full">Mobile
                    App</span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-surface-900">Tersedia di Mobile App</h2>
                <p class="mt-4 text-lg text-surface-500">Nikmati pengalaman membeli dan mengelola tiket event langsung
                    dari smartphone kamu</p>
            </div>

            {{-- Phone Mockups --}}
            <style>
                .phone-row {
                    display: flex;
                    align-items: flex-end;
                    justify-content: center;
                    gap: 12px;
                }

                .phone-item {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    transition: transform 0.4s ease;
                }

                .phone-item:hover {
                    transform: rotate(0deg) !important;
                    scale: 1.05;
                }

                .phone-frame {
                    width: 150px;
                    background: #1a1a2e;
                    border-radius: 28px;
                    padding: 8px;
                    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
                    position: relative;
                }

                .phone-frame.highlight {
                    width: 170px;
                    box-shadow: 0 25px 60px rgba(59, 130, 246, 0.3);
                    border: 2px solid rgba(59, 130, 246, 0.3);
                }

                .phone-notch {
                    width: 50px;
                    height: 5px;
                    background: #2a2a3e;
                    border-radius: 3px;
                    margin: 0 auto 6px;
                }

                .phone-screen {
                    border-radius: 20px;
                    overflow: hidden;
                    background: #000;
                }

                .phone-screen img {
                    width: 100%;
                    display: block;
                }

                .phone-home {
                    width: 36px;
                    height: 4px;
                    background: #3a3a4e;
                    border-radius: 2px;
                    margin: 6px auto 0;
                }

                .phone-label {
                    margin-top: 10px;
                    text-align: center;
                    font-size: 12px;
                    font-weight: 500;
                }

                /* Hide outer phones on small screens */
                .phone-item.hide-mobile {
                    display: none;
                }

                @media (min-width: 640px) {
                    .phone-row {
                        gap: 20px;
                    }

                    .phone-frame {
                        width: 180px;
                        border-radius: 32px;
                        padding: 10px;
                    }

                    .phone-frame.highlight {
                        width: 210px;
                    }

                    .phone-notch {
                        width: 60px;
                        height: 6px;
                        margin-bottom: 8px;
                    }

                    .phone-screen {
                        border-radius: 22px;
                    }

                    .phone-home {
                        width: 40px;
                        margin-top: 8px;
                    }

                    .phone-item.hide-mobile {
                        display: flex;
                    }
                }

                @media (min-width: 1024px) {
                    .phone-row {
                        gap: 32px;
                    }

                    .phone-frame {
                        width: 200px;
                    }

                    .phone-frame.highlight {
                        width: 230px;
                    }
                }
            </style>

            <div class="phone-row">
                @php
                    $screens = [
                        [
                            'img' => 'Screenshot_1773211382.png',
                            'label' => 'Explore Event',
                            'rotate' => '-8',
                            'mt' => '24px',
                            'hide' => true,
                        ],
                        [
                            'img' => 'Screenshot_1773211407.png',
                            'label' => 'Detail Event',
                            'rotate' => '-4',
                            'mt' => '12px',
                        ],
                        [
                            'img' => 'Screenshot_1773211416.png',
                            'label' => 'Pilih Tiket',
                            'rotate' => '0',
                            'mt' => '0px',
                            'highlight' => true,
                        ],
                        [
                            'img' => 'Screenshot_1773211492.png',
                            'label' => 'Daftar Pesanan',
                            'rotate' => '4',
                            'mt' => '12px',
                        ],
                        [
                            'img' => 'Screenshot_1773211495.png',
                            'label' => 'E-Ticket',
                            'rotate' => '8',
                            'mt' => '24px',
                            'hide' => true,
                        ],
                    ];
                @endphp

                @foreach ($screens as $screen)
                    <div class="phone-item {{ !empty($screen['hide']) ? 'hide-mobile' : '' }}"
                        style="transform: rotate({{ $screen['rotate'] }}deg); margin-top: {{ $screen['mt'] }};">
                        <div class="phone-frame {{ !empty($screen['highlight']) ? 'highlight' : '' }}">
                            <div class="phone-notch"></div>
                            <div class="phone-screen">
                                <img src="/images/events/mobile/{{ $screen['img'] }}" alt="{{ $screen['label'] }}"
                                    loading="lazy">
                            </div>
                            <div class="phone-home"></div>
                        </div>
                        <p class="phone-label"
                            style="color: {{ !empty($screen['highlight']) ? '#2563eb' : '#6b7280' }};">
                            {{ $screen['label'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- App Features --}}
            <div class="mt-16 grid sm:grid-cols-3 gap-6 max-w-3xl mx-auto">
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-surface-900">Browse & Beli</h4>
                    <p class="mt-1 text-xs text-surface-500">Cari event dan beli tiket langsung dari app</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-surface-900">E-Ticket di Genggaman</h4>
                    <p class="mt-1 text-xs text-surface-500">Simpan e-ticket dengan QR code di smartphone</p>
                </div>
                <div class="text-center">
                    <div
                        class="w-12 h-12 bg-amber-100 text-amber-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                    <h4 class="text-sm font-semibold text-surface-900">Kelola Event (Vendor)</h4>
                    <p class="mt-1 text-xs text-surface-500">Vendor bisa kelola event & scan tiket dari app</p>
                </div>
            </div>

            {{-- Download APK Button --}}
            <div class="mt-12 text-center">
                <a href="https://play.google.com/store/apps/details?id=com.jagoflutter.event" target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-3 px-8 py-4 bg-surface-900 text-white font-semibold rounded-2xl hover:bg-surface-800 transition-colors shadow-lg hover:shadow-xl">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M17.523 2H6.477L0 12l6.477 10h11.046L24 12 17.523 2zM9.9 16.2L8 14.3l3.3-3.3L8 7.7l1.9-1.9L15.1 11l-5.2 5.2z" />
                    </svg>
                    <span>
                        <span class="block text-sm">Download</span>
                        <span class="block text-xs opacity-75">Google Play Store</span>
                    </span>
                </a>
                <p class="mt-3 text-xs text-surface-400">Tersedia untuk Android. Coba langsung di smartphone kamu!</p>
            </div>
        </div>
    </section>

    {{-- Fitur Section --}}
    <section id="fitur" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-primary-50 text-primary-700 rounded-full">Fitur
                    Unggulan</span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-surface-900">Semua yang Anda Butuhkan</h2>
                <p class="mt-4 text-lg text-surface-500">Platform lengkap untuk pembeli dan penjual tiket event</p>
            </div>

            {{-- Buyer Features --}}
            <div class="mb-12">
                <h3 class="text-sm font-bold uppercase tracking-widest text-primary-600 mb-6">Untuk Pembeli</h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-primary-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-primary-50 text-primary-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-primary-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Cari & Filter Event</h3>
                        <p class="mt-2 text-sm text-surface-500">Cari event berdasarkan nama, kategori, rentang harga,
                            dan status (upcoming/past).</p>
                    </div>

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-emerald-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Pembayaran Aman</h3>
                        <p class="mt-2 text-sm text-surface-500">Bayar via Xendit dengan berbagai metode: VA, e-wallet,
                            kartu kredit, dan lainnya.</p>
                    </div>

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-amber-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">E-Ticket & QR Code</h3>
                        <p class="mt-2 text-sm text-surface-500">Dapatkan e-ticket PDF dengan QR code unik langsung di
                            email setelah pembayaran.</p>
                    </div>

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-rose-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-rose-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Promo & Diskon</h3>
                        <p class="mt-2 text-sm text-surface-500">Gunakan kode promo dari event organizer untuk
                            mendapatkan potongan harga tiket.</p>
                    </div>

                </div>
            </div>

            {{-- Vendor Features --}}
            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest text-emerald-600 mb-6">Untuk Event Organizer
                </h3>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-violet-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-violet-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Kelola Event</h3>
                        <p class="mt-2 text-sm text-surface-500">Buat event dengan multiple tipe tiket (VIP, Regular,
                            dll) dan atur stok & harga.</p>
                    </div>

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-sky-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-sky-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Dashboard & Analitik</h3>
                        <p class="mt-2 text-sm text-surface-500">Pantau revenue harian, top SKU terlaris, dan grafik
                            penjualan real-time.</p>
                    </div>

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-teal-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-teal-50 text-teal-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-teal-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Bulk Check-in</h3>
                        <p class="mt-2 text-sm text-surface-500">Validasi banyak tiket sekaligus saat event. Scan QR
                            code atau input kode tiket massal.</p>
                    </div>

                    <div
                        class="group p-6 rounded-2xl border border-surface-200 hover:border-orange-200 hover:shadow-lg transition-all duration-300">
                        <div
                            class="w-12 h-12 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-orange-100 transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-surface-900">Export Excel</h3>
                        <p class="mt-2 text-sm text-surface-500">Download data pesanan, laporan revenue, dan statistik
                            event dalam format Excel.</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- Platform Highlights --}}
    <section class="py-20 lg:py-28 bg-surface-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-primary-50 text-primary-700 rounded-full">Platform
                    Lengkap</span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-surface-900">Lebih dari Sekedar Jual Tiket</h2>
                <p class="mt-4 text-lg text-surface-500">Fitur-fitur canggih yang membuat pengalaman event Anda lebih
                    baik</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                {{-- Email Notifikasi --}}
                <div class="bg-white rounded-2xl p-8 border border-surface-200 hover:shadow-lg transition-shadow">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-blue-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-2">Email Notifikasi Otomatis</h3>
                    <p class="text-surface-500 text-sm leading-relaxed">Konfirmasi pesanan, e-ticket dengan QR code,
                        dan notifikasi status vendor dikirim otomatis ke email.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">Konfirmasi
                            Order</span>
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">E-Ticket
                            PDF</span>
                        <span class="px-2.5 py-1 bg-blue-50 text-blue-700 text-xs font-medium rounded-lg">Status
                            Vendor</span>
                    </div>
                </div>

                {{-- Invoice & Refund --}}
                <div class="bg-white rounded-2xl p-8 border border-surface-200 hover:shadow-lg transition-shadow">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-emerald-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-2">Invoice PDF & Pembatalan</h3>
                    <p class="text-surface-500 text-sm leading-relaxed">Download invoice resmi dalam format PDF.
                        Batalkan pesanan dengan pengembalian stok tiket otomatis.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span
                            class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg">Download
                            Invoice</span>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg">Batal
                            Pesanan</span>
                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 text-xs font-medium rounded-lg">Auto
                            Refund Stok</span>
                    </div>
                </div>

                {{-- Promo Code --}}
                <div class="bg-white rounded-2xl p-8 border border-surface-200 hover:shadow-lg transition-shadow">
                    <div
                        class="w-14 h-14 bg-gradient-to-br from-violet-500 to-violet-600 rounded-2xl flex items-center justify-center mb-5 shadow-lg shadow-violet-500/20">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-surface-900 mb-2">Sistem Promo Code</h3>
                    <p class="text-surface-500 text-sm leading-relaxed">Event organizer bisa buat kode promo dengan
                        diskon persentase atau nominal tetap, lengkap dengan batas penggunaan.</p>
                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-medium rounded-lg">Diskon
                            %</span>
                        <span class="px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-medium rounded-lg">Diskon
                            Nominal</span>
                        <span class="px-2.5 py-1 bg-violet-50 text-violet-700 text-xs font-medium rounded-lg">Batas
                            Penggunaan</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Cara Kerja Section --}}
    <section id="cara-kerja" class="py-20 lg:py-28 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <span
                    class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-primary-50 text-primary-700 rounded-full">Cara
                    Kerja</span>
                <h2 class="mt-4 text-3xl sm:text-4xl font-bold text-surface-900">Mudah & Cepat</h2>
                <p class="mt-4 text-lg text-surface-500">Hanya beberapa langkah untuk memulai</p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16">
                {{-- Buyer flow --}}
                <div>
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-primary-600 text-white rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-surface-900">Untuk Pembeli</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-bold">
                                1</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Cari & Filter Event</h4>
                                <p class="mt-1 text-sm text-surface-500">Browse event berdasarkan kategori, nama,
                                    rentang harga, atau status upcoming/past.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-bold">
                                2</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Pilih Tiket & Gunakan Promo</h4>
                                <p class="mt-1 text-sm text-surface-500">Pilih tipe tiket (VIP, Regular, dll) dan
                                    masukkan kode promo untuk diskon.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-bold">
                                3</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Bayar & Terima E-Ticket</h4>
                                <p class="mt-1 text-sm text-surface-500">Bayar via Xendit, langsung terima email
                                    konfirmasi + e-ticket PDF dengan QR code.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-primary-100 text-primary-700 rounded-full flex items-center justify-center text-sm font-bold">
                                4</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Download Invoice & Kelola</h4>
                                <p class="mt-1 text-sm text-surface-500">Download invoice PDF, atau batalkan pesanan
                                    jika berubah rencana.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Vendor flow --}}
                <div>
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 bg-emerald-600 text-white rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-surface-900">Untuk Event Organizer</h3>
                    </div>
                    <div class="space-y-6">
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-bold">
                                1</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Daftar & Verifikasi</h4>
                                <p class="mt-1 text-sm text-surface-500">Buat akun vendor dan tunggu verifikasi admin.
                                    Terima notifikasi email saat disetujui.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-bold">
                                2</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Buat Event & Promo</h4>
                                <p class="mt-1 text-sm text-surface-500">Tambah event, atur tipe tiket, dan buat kode
                                    promo untuk menarik pembeli.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-bold">
                                3</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Pantau & Analisa</h4>
                                <p class="mt-1 text-sm text-surface-500">Lihat grafik revenue harian, top SKU, dan
                                    export data pesanan ke Excel.</p>
                            </div>
                        </div>
                        <div class="flex gap-4">
                            <div
                                class="flex-shrink-0 w-8 h-8 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-sm font-bold">
                                4</div>
                            <div>
                                <h4 class="font-semibold text-surface-900">Check-in di Venue</h4>
                                <p class="mt-1 text-sm text-surface-500">Validasi tiket satu per satu atau bulk
                                    check-in banyak tiket sekaligus saat event.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JagoFlutter AFC Section --}}
    <section class="py-20 lg:py-28 bg-surface-900 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div
                style="background-image: radial-gradient(circle at 20% 50%, rgba(59,130,246,0.3) 0%, transparent 50%), radial-gradient(circle at 80% 50%, rgba(168,85,247,0.3) 0%, transparent 50%); width:100%; height:100%;">
            </div>
        </div>
        <div class="relative max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                {{-- Left: Text --}}
                <div>
                    <span
                        class="inline-flex items-center px-3 py-1 text-xs font-semibold bg-primary-500/20 text-primary-300 rounded-full mb-4">JagoFlutter
                        AFC</span>
                    <h2 class="text-3xl sm:text-4xl font-bold text-white leading-tight">Mau Bikin Aplikasi Seperti Ini?
                    </h2>
                    <p class="mt-4 text-lg text-surface-300 leading-relaxed">
                        JagoEvent adalah salah satu project di <strong class="text-white">Advanced Flutter Class
                            (AFC)</strong> JagoFlutter. Dapatkan full source code Laravel backend + Flutter mobile app,
                        video bedah kode, dan akses grup diskusi member.
                    </p>
                    <ul class="mt-6 space-y-3">
                        <li class="flex items-center gap-3 text-surface-300">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Source code diberikan di pertemuan Zoom pertama
                        </li>
                        <li class="flex items-center gap-3 text-surface-300">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Full source code Laravel + Flutter
                        </li>
                        <li class="flex items-center gap-3 text-surface-300">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Payment Gateway (Xendit) integration
                        </li>
                        <li class="flex items-center gap-3 text-surface-300">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            E-Ticket dengan QR Code & Email
                        </li>
                        <li class="flex items-center gap-3 text-surface-300">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Admin panel, vendor dashboard, & deploy production
                        </li>
                        <li class="flex items-center gap-3 text-surface-300">
                            <svg class="w-5 h-5 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Grup diskusi & support sesama member
                        </li>
                    </ul>
                    <div class="mt-8">
                        <a href="https://jagoflutter.com/JagoEvent" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 px-8 py-4 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-500 transition-colors shadow-lg shadow-primary-600/30">
                            Dapatkan Full Source Code
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Right: Tech Stack Card --}}
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-2xl p-8">
                    <h3 class="text-lg font-semibold text-white mb-6">Tech Stack yang Dipelajari</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <div
                                class="w-10 h-10 mx-auto mb-2 rounded-lg bg-sky-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-sky-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Flutter</p>
                            <p class="text-xs text-surface-400 mt-1">Mobile App</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <div
                                class="w-10 h-10 mx-auto mb-2 rounded-lg bg-red-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Laravel</p>
                            <p class="text-xs text-surface-400 mt-1">Backend API</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <div
                                class="w-10 h-10 mx-auto mb-2 rounded-lg bg-emerald-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Xendit</p>
                            <p class="text-xs text-surface-400 mt-1">Payment Gateway</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-4 text-center">
                            <div
                                class="w-10 h-10 mx-auto mb-2 rounded-lg bg-violet-500/20 flex items-center justify-center">
                                <svg class="w-6 h-6 text-violet-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-white">Production</p>
                            <p class="text-xs text-surface-400 mt-1">VPS Deploy</p>
                        </div>
                    </div>
                    <a href="https://jagoflutter.com/JagoEvent" target="_blank" rel="noopener noreferrer"
                        class="mt-6 flex items-center justify-center gap-2 w-full py-3 bg-primary-600 text-white font-semibold rounded-xl hover:bg-primary-500 transition-colors text-sm">
                        Dapatkan Full Source Code
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    <div class="mt-4 pt-4 border-t border-white/10 text-center">
                        <p class="text-surface-400 text-sm">Bagian dari program</p>
                        <p class="text-white font-bold text-lg mt-1">Advanced Flutter Class</p>
                        <p class="text-primary-400 text-sm mt-1">jagoflutter.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 lg:py-28 bg-gradient-to-r from-primary-600 to-primary-800 relative overflow-hidden">
        <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold text-white">Mulai Sekarang</h2>
            <p class="mt-4 text-lg text-primary-100 max-w-2xl mx-auto">
                Bergabung dengan ribuan pengguna yang sudah mempercayai JagoEvent sebagai platform tiket event
                pilihan
                mereka.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="/register"
                    class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 text-base font-semibold rounded-xl bg-white text-primary-700 hover:bg-primary-50 transition-colors shadow-lg">
                    Daftar Gratis
                </a>
                <a href="https://play.google.com/store/apps/details?id=com.jagoflutter.event" target="_blank"
                    rel="noopener noreferrer"
                    class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 text-base font-semibold rounded-xl bg-white/10 text-white border-2 border-white/30 hover:bg-white/20 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M12 4v12m0 0l-4-4m4 4l4-4" />
                    </svg>
                    Download App
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-surface-900 text-surface-400 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-2">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-white">JagoEvent</span>
                    </div>
                    <p class="mt-4 text-sm max-w-sm">Platform marketplace tiket event terpercaya di Indonesia. Beli dan
                        jual tiket event dengan mudah dan aman.</p>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Platform</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#fitur" class="hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#cara-kerja" class="hover:text-white transition-colors">Cara Kerja</a></li>
                        <li><a href="/events" class="hover:text-white transition-colors">Cari Event</a></li>
                        <li><a href="/register" class="hover:text-white transition-colors">Daftar</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-sm font-semibold text-white mb-4">Support</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="mailto:support@jagon8n.com"
                                class="hover:text-white transition-colors">support@jagon8n.com</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-surface-800 text-sm text-center">
                &copy; {{ date('Y') }} JagoEvent. All rights reserved.
            </div>
        </div>
    </footer>

</x-layouts.landing>
