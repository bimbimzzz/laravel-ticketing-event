@php
    $user = auth()->user();
    $vendor = $user->vendor;
@endphp

{{-- Vendor Info --}}
<div class="px-3 py-4 mb-2">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-primary-600 flex items-center justify-center text-white font-semibold">
            {{ strtoupper(substr($vendor->name ?? $user->name, 0, 1)) }}
        </div>
        <div class="min-w-0">
            <p class="text-sm font-semibold text-surface-900 truncate">{{ $vendor->name ?? $user->name }}</p>
            <div class="flex items-center gap-1.5">
                @php
                    $statusConfig = match($vendor->verify_status ?? 'pending') {
                        'approved' => ['bg-emerald-400', 'Verified'],
                        'pending' => ['bg-amber-400', 'Pending'],
                        'rejected' => ['bg-red-400', 'Ditolak'],
                        default => ['bg-surface-400', '-'],
                    };
                @endphp
                <span class="w-2 h-2 rounded-full {{ $statusConfig[0] }}"></span>
                <span class="text-xs text-surface-500">{{ $statusConfig[1] }}</span>
            </div>
        </div>
    </div>
</div>

<hr class="border-surface-100 mb-2">

{{-- Menu Label --}}
<p class="px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-surface-400">Menu</p>

<a href="/vendor/dashboard"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'vendor/dashboard') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-50 hover:text-surface-900' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1" /></svg>
    Dashboard
</a>

<a href="/vendor/events"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ str_starts_with($currentRoute, 'vendor/events') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-50 hover:text-surface-900' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
    Event Saya
</a>

<a href="/vendor/tickets/check"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('vendor/tickets/check') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-50 hover:text-surface-900' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
    Validasi Tiket
</a>

<a href="/vendor/tickets/bulk-check"
   class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-colors {{ request()->is('vendor/tickets/bulk-check') ? 'bg-primary-50 text-primary-700' : 'text-surface-600 hover:bg-surface-50 hover:text-surface-900' }}">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
    Bulk Check-in
</a>

<div class="pt-4 mt-4 border-t border-surface-100">
    <p class="px-3 mb-1.5 text-[11px] font-semibold uppercase tracking-wider text-surface-400">Lainnya</p>

    <a href="/events"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 hover:bg-surface-50 hover:text-surface-900 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        Jelajahi Event
    </a>

    <a href="/"
       class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium text-surface-600 hover:bg-surface-50 hover:text-surface-900 transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-4 0a1 1 0 01-1-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 01-1 1" /></svg>
        Beranda
    </a>
</div>
