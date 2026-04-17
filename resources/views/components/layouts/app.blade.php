@props(['title' => 'Dashboard - JagoEvent'])

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

<body class="font-sans antialiased bg-surface-50 text-surface-900">
    @include('components.layouts.partials.promo-banner')
    <div class="min-h-screen flex" x-data="{ sidebarOpen: false, profileOpen: false }">

        {{-- Mobile sidebar overlay --}}
        <div x-show="sidebarOpen" x-cloak class="lg:hidden fixed inset-0 z-50 flex">
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="transition-opacity ease-linear duration-300"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black/50"></div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-in-out duration-300 transform"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in-out duration-300 transform"
                x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full"
                class="relative flex w-64 flex-col bg-white">
                <div class="flex items-center justify-between px-4 pt-5 pb-4">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold text-surface-900">JagoEvent</span>
                    </div>
                    <button @click="sidebarOpen = false" class="p-2 text-surface-400 hover:text-surface-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <nav class="flex-1 px-3 space-y-1 overflow-y-auto">
                    @if (isset($sidebar))
                        {{ $sidebar }}
                    @else
                        @php $currentRoute = request()->path(); @endphp
                        @include('components.layouts.partials.vendor-nav', [
                            'currentRoute' => $currentRoute,
                        ])
                    @endif
                </nav>
            </div>
        </div>

        {{-- Desktop sidebar --}}
        <aside class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
            <div class="flex flex-col flex-grow bg-white border-r border-surface-200 pt-5 pb-4 overflow-y-auto">
                <div class="flex items-center gap-2 px-4">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold text-surface-900">JagoEvent</span>
                </div>
                <nav class="mt-8 flex-1 px-3 space-y-1">
                    @if (isset($sidebar))
                        {{ $sidebar }}
                    @else
                        @php $currentRoute = request()->path(); @endphp
                        @include('components.layouts.partials.vendor-nav', [
                            'currentRoute' => $currentRoute,
                        ])
                    @endif
                </nav>
            </div>
        </aside>

        {{-- Main area --}}
        <div class="lg:pl-64 flex flex-col flex-1">
            {{-- Topbar --}}
            <header class="sticky top-0 z-40 bg-white border-b border-surface-200">
                <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 text-surface-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    {{-- Breadcrumb / Page info --}}
                    <div class="hidden sm:flex items-center gap-2 text-sm">
                        <a href="/vendor/dashboard"
                            class="text-surface-400 hover:text-surface-600 transition-colors">Vendor</a>
                        <svg class="w-4 h-4 text-surface-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7" />
                        </svg>
                        <span class="font-medium text-surface-700">{{ $header ?? 'Dashboard' }}</span>
                    </div>

                    <div class="flex-1"></div>

                    {{-- Profile Dropdown --}}
                    @auth
                        <div class="relative" @click.outside="profileOpen = false">
                            <button @click="profileOpen = !profileOpen"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl hover:bg-surface-50 transition-colors">
                                <div
                                    class="w-8 h-8 rounded-full bg-primary-600 flex items-center justify-center text-white text-sm font-semibold">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                </div>
                                <div class="hidden sm:block text-left">
                                    <p class="text-sm font-medium text-surface-900 leading-tight">{{ Auth::user()->name }}
                                    </p>
                                    <p class="text-xs text-surface-500 leading-tight">{{ Auth::user()->email }}</p>
                                </div>
                                <svg class="w-4 h-4 text-surface-400 transition-transform"
                                    :class="profileOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
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
                                class="absolute right-0 mt-2 w-60 bg-white rounded-xl shadow-lg border border-surface-200 py-1.5 z-50">
                                <div class="px-4 py-2.5 border-b border-surface-100">
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
                                    <a href="/events"
                                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-surface-700 hover:bg-surface-50 transition-colors">
                                        <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Jelajahi Event
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
                    @endauth

                    {{ $topbar ?? '' }}
                </div>
            </header>

            {{-- Page content --}}
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    <x-ui.toast />
</body>

</html>
