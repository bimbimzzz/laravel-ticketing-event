@props(['title' => 'JagoEvent - Marketplace Tiket Event', 'navDark' => false])

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-white text-surface-900">

    {{-- Fixed wrapper: banner + navbar --}}
    <div class="fixed top-0 inset-x-0 z-50">
        @include('components.layouts.partials.promo-banner')

        {{-- Navbar --}}
        <nav x-data="{ open: false, scrolled: false, profileOpen: false, solid: {{ $navDark ? 'true' : 'false' }} }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 20)"
            :class="(scrolled || solid) ? 'bg-white/95 backdrop-blur-md shadow-sm' : 'bg-transparent'"
            class="transition-all duration-300">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 lg:h-20">
                    {{-- Logo --}}
                    <a href="/" class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold"
                            :class="(scrolled || solid) ? 'text-surface-900' : 'text-white'">JagoEvent</span>
                    </a>

                    {{-- Desktop Nav --}}
                    <div class="hidden lg:flex items-center gap-1">
                        @auth
                            {{-- Nav Links --}}
                            <a href="/events" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                                :class="(scrolled || solid) ?
                                '{{ request()->is('events*') && !request()->is('vendor*') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' }}' :
                                '{{ request()->is('events*') && !request()->is('vendor*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}'">
                                Event
                            </a>
                            <a href="/orders" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                                :class="(scrolled || solid) ?
                                '{{ request()->is('orders*') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' }}' :
                                '{{ request()->is('orders*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}'">
                                Pesanan
                            </a>
                            @if (Auth::user()->is_vendor)
                                <a href="/vendor/dashboard"
                                    class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                                    :class="scrolled
                                        ?
                                        '{{ request()->is('vendor*') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' }}' :
                                        '{{ request()->is('vendor*') ? 'bg-white/15 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}'">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                        Vendor
                                    </span>
                                </a>
                            @endif

                            {{-- Separator --}}
                            <div class="mx-2 h-6 w-px" :class="(scrolled || solid) ? 'bg-surface-200' : 'bg-white/20'">
                            </div>

                            {{-- Profile Dropdown --}}
                            <div class="relative" @click.outside="profileOpen = false">
                                <button @click="profileOpen = !profileOpen"
                                    class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl transition-colors"
                                    :class="(scrolled || solid) ? 'hover:bg-surface-100' : 'hover:bg-white/10'">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold"
                                        :class="(scrolled || solid) ? 'bg-primary-100 text-primary-700' :
                                        'bg-white/20 text-white'">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-left hidden xl:block">
                                        <p class="text-sm font-semibold leading-tight"
                                            :class="(scrolled || solid) ? 'text-surface-900' : 'text-white'">
                                            {{ Auth::user()->name }}</p>
                                        <p class="text-[11px] leading-tight"
                                            :class="(scrolled || solid) ? 'text-surface-500' : 'text-white/50'">
                                            {{ Auth::user()->is_vendor ? 'Vendor' : 'Buyer' }}</p>
                                    </div>
                                    <svg class="w-4 h-4 transition-transform"
                                        :class="[scrolled ? 'text-surface-400' : 'text-white/50', profileOpen ? 'rotate-180' :
                                            ''
                                        ]"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div x-show="profileOpen" x-cloak x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg ring-1 ring-black/5 py-1.5">
                                    <div class="px-4 py-3 border-b border-surface-100">
                                        <p class="text-sm font-semibold text-surface-900">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-surface-500 mt-0.5">{{ Auth::user()->email }}</p>
                                    </div>
                                    <div class="py-1">
                                        <a href="/profile"
                                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-surface-700 hover:bg-surface-50 transition-colors">
                                            <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Profil Saya
                                        </a>
                                        <a href="/orders"
                                            class="flex items-center gap-2.5 px-4 py-2 text-sm text-surface-700 hover:bg-surface-50 transition-colors">
                                            <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                            </svg>
                                            Pesanan Saya
                                        </a>
                                        @if (!Auth::user()->is_vendor)
                                            <a href="/register/vendor"
                                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-surface-700 hover:bg-surface-50 transition-colors">
                                                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                </svg>
                                                Daftar Vendor
                                            </a>
                                        @endif
                                    </div>
                                    <div class="border-t border-surface-100 pt-1">
                                        <form method="POST" action="/logout">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                </svg>
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Nav Links --}}
                            <a href="/events" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                                :class="(scrolled || solid) ? 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' :
                                'text-white/70 hover:bg-white/10 hover:text-white'">
                                Event
                            </a>
                            <a href="#fitur" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                                :class="(scrolled || solid) ? 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' :
                                'text-white/70 hover:bg-white/10 hover:text-white'">
                                Fitur
                            </a>
                            <a href="#cara-kerja" class="px-3.5 py-2 rounded-lg text-sm font-medium transition-colors"
                                :class="(scrolled || solid) ? 'text-surface-600 hover:bg-surface-100 hover:text-surface-900' :
                                'text-white/70 hover:bg-white/10 hover:text-white'">
                                Cara Kerja
                            </a>

                            {{-- Separator --}}
                            <div class="mx-1 h-6 w-px" :class="(scrolled || solid) ? 'bg-surface-200' : 'bg-white/20'">
                            </div>

                            {{-- Auth Buttons --}}
                            <a href="/login" class="px-4 py-2 rounded-xl text-sm font-semibold transition-all"
                                :class="(scrolled || solid) ? 'text-surface-700 hover:bg-surface-100' :
                                'text-white border border-white/30 hover:bg-white/10'">
                                Masuk
                            </a>
                            <a href="/register"
                                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all shadow-sm"
                                :class="(scrolled || solid) ? 'bg-primary-600 text-white hover:bg-primary-700' :
                                'bg-white text-primary-700 hover:bg-white/90'">
                                Daftar Gratis
                            </a>
                        @endauth
                    </div>

                    {{-- Mobile menu button --}}
                    <button @click="open = !open" class="lg:hidden p-2 rounded-lg"
                        :class="(scrolled || solid) ? 'text-surface-700' : 'text-white'">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="open" x-cloak class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Mobile menu --}}
            <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                class="lg:hidden bg-white border-t border-surface-100 shadow-lg">
                <div class="px-4 py-4 space-y-1">
                    @auth
                        <div class="flex items-center gap-3 px-4 py-3 mb-2">
                            <div
                                class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-surface-900">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-surface-500">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <hr class="border-surface-100 mb-2">
                        <a href="/events" @click="open = false"
                            class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Event</a>
                        <a href="/orders" @click="open = false"
                            class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Pesanan
                            Saya</a>
                        <a href="/profile" @click="open = false"
                            class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Profil</a>
                        @if (Auth::user()->is_vendor)
                            <a href="/vendor/dashboard" @click="open = false"
                                class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Dashboard
                                Vendor</a>
                        @endif
                        <hr class="border-surface-100 my-2">
                        <form method="POST" action="/logout">
                            @csrf
                            <button type="submit"
                                class="block w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 rounded-lg">Keluar</button>
                        </form>
                    @else
                        <a href="#fitur" @click="open = false"
                            class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Fitur</a>
                        <a href="#cara-kerja" @click="open = false"
                            class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Cara
                            Kerja</a>
                        <hr class="border-surface-100 my-2">
                        <a href="/login"
                            class="block px-4 py-2.5 text-sm text-surface-700 hover:bg-surface-50 rounded-lg">Masuk</a>
                        <a href="/register"
                            class="block px-4 py-2.5 text-sm font-medium text-center bg-primary-600 text-white rounded-lg hover:bg-primary-700">Daftar</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>

    {{-- Main content --}}
    <main>
        {{ $slot }}
    </main>

    {{-- Toast --}}
    <x-ui.toast />
</body>

</html>
