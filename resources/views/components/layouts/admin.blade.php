@props(['title' => 'Admin - KarcisDigital'])

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

<body class="font-sans antialiased bg-surface-50 text-surface-900" x-data="{ sideOpen: false }">
    @include('components.layouts.partials.promo-banner')

    {{-- Mobile Overlay --}}
    <div x-show="sideOpen" x-cloak @click="sideOpen = false"
        class="fixed inset-0 z-40 bg-black/30 backdrop-blur-sm lg:hidden"></div>

    {{-- Sidebar --}}
    <aside :class="sideOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed top-0 left-0 z-50 w-64 h-full bg-gradient-to-b from-white to-surface-50 border-r border-surface-200 transition-transform lg:translate-x-0 lg:z-30 flex flex-col">
        {{-- Logo --}}
        <div class="px-5 py-5 border-b border-surface-100">
            <div class="flex items-center gap-2.5">
                <div
                    class="w-9 h-9 bg-gradient-to-br from-primary-500 to-primary-700 rounded-xl flex items-center justify-center shadow-sm">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                    </svg>
                </div>
                <div>
                    <span class="text-base font-bold text-surface-900">KarcisDigital</span>
                    <span class="block text-[10px] text-primary-500 uppercase tracking-widest font-semibold">Super
                        Admin</span>
                </div>
            </div>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
            <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-surface-400 mb-2">Menu</p>

            <a href="/superadmin" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' =>
                    request()->is('superadmin') && !request()->is('superadmin/*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !(
                    request()->is('superadmin') && !request()->is('superadmin/*')
                ),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                Dashboard
            </a>
            <a href="/superadmin/users" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/users*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/users*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                Users
            </a>
            <a href="/superadmin/vendors" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/vendors*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/vendors*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                Vendors
            </a>
            <a href="/superadmin/events" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/events*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/events*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Events
            </a>
            <a href="/superadmin/orders" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/orders*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/orders*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Orders
            </a>
            <a href="/superadmin/refunds" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/refunds*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/refunds*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6" />
                </svg>
                Refunds
                @php $refundCount = \App\Models\Order::where('status_payment', 'refund_pending')->count(); @endphp
                @if ($refundCount > 0)
                    <span
                        class="ml-auto inline-flex items-center justify-center w-5 h-5 text-[10px] font-bold text-white bg-red-500 rounded-full">{{ $refundCount }}</span>
                @endif
            </a>
            <a href="/superadmin/reports" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/reports*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/reports*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Laporan
            </a>
            <a href="/superadmin/categories" @class([
                'flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-150',
                'bg-primary-50 text-primary-700 shadow-sm border border-primary-100' => request()->is(
                    'superadmin/categories*'),
                'text-surface-500 hover:bg-surface-100 hover:text-surface-800' => !request()->is(
                    'superadmin/categories*'),
            ])>
                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                Kategori
            </a>

            <div class="pt-4 mt-4 border-t border-surface-200">
                <p class="px-3 text-[10px] font-bold uppercase tracking-widest text-surface-400 mb-2">Lainnya</p>
                <a href="/"
                    class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-500 hover:bg-surface-100 hover:text-surface-800 transition-all duration-150">
                    <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Lihat Website
                </a>
            </div>
        </nav>

        {{-- User --}}
        <div class="px-4 py-3 border-t border-surface-200 bg-surface-50/50">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-surface-800 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-surface-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main --}}
    <div class="lg:ml-64 min-h-screen flex flex-col">
        {{-- Topbar --}}
        <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-lg border-b border-surface-200">
            <div class="flex items-center justify-between px-4 sm:px-6 h-14">
                <div class="flex items-center gap-3">
                    <button @click="sideOpen = !sideOpen" class="lg:hidden p-1.5 rounded-lg hover:bg-surface-100">
                        <svg class="w-5 h-5 text-surface-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-sm font-semibold text-surface-700">{{ $header ?? 'Dashboard' }}</h2>
                </div>

                {{-- Admin Profile Dropdown --}}
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                        class="flex items-center gap-2.5 px-2 py-1.5 rounded-lg hover:bg-surface-50 transition-colors">
                        <div
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-500 to-primary-700 flex items-center justify-center text-xs font-bold text-white shadow-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <p class="text-sm font-semibold text-surface-800 leading-tight">{{ auth()->user()->name }}
                            </p>
                            <p class="text-[11px] text-surface-400 leading-tight">Administrator</p>
                        </div>
                        <svg class="w-4 h-4 text-surface-400 hidden sm:block" :class="open && 'rotate-180'"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div x-show="open" @click.outside="open = false"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        x-cloak
                        class="absolute right-0 mt-1 w-56 bg-white rounded-xl border border-surface-200 shadow-lg py-1 z-50">
                        <div class="px-4 py-3 border-b border-surface-100">
                            <p class="text-sm font-semibold text-surface-900">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-surface-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="py-1">
                            <a href="/"
                                class="flex items-center gap-2.5 px-4 py-2 text-sm text-surface-600 hover:bg-surface-50 transition-colors">
                                <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                </svg>
                                Lihat Website
                            </a>
                        </div>
                        <div class="border-t border-surface-100 py-1">
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                    class="flex items-center gap-2.5 w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Content --}}
        <main class="flex-1 p-4 sm:p-6">
            {{-- Flash --}}
            @if (session('success'))
                <div
                    class="mb-4 px-4 py-3 bg-emerald-50 text-emerald-700 text-sm font-medium rounded-xl border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div
                    class="mb-4 px-4 py-3 bg-red-50 text-red-700 text-sm font-medium rounded-xl border border-red-200">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
</body>

</html>
