@props([
    'label' => '',
    'value' => '',
    'trend' => null,
    'trendUp' => true,
    'color' => 'primary',
])

@php
    $colorMap = [
        'primary' => 'bg-primary-50 text-primary-600',
        'success' => 'bg-emerald-50 text-emerald-600',
        'warning' => 'bg-amber-50 text-amber-600',
        'danger'  => 'bg-red-50 text-red-600',
        'info'    => 'bg-sky-50 text-sky-600',
    ];
    $iconBg = $colorMap[$color] ?? $colorMap['primary'];
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-surface-200 shadow-sm p-6']) }}>
    <div class="flex items-start justify-between">
        <div>
            <p class="text-sm font-medium text-surface-500">{{ $label }}</p>
            <p class="mt-2 text-3xl font-bold text-surface-900">{{ $value }}</p>
            @if($trend)
                <div class="mt-2 flex items-center gap-1 text-sm {{ $trendUp ? 'text-emerald-600' : 'text-red-600' }}">
                    @if($trendUp)
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" /></svg>
                    @else
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" /></svg>
                    @endif
                    <span>{{ $trend }}</span>
                </div>
            @endif
        </div>
        @if(isset($icon))
            <div class="flex-shrink-0 p-3 rounded-lg {{ $iconBg }}">
                {{ $icon }}
            </div>
        @endif
    </div>
</div>
