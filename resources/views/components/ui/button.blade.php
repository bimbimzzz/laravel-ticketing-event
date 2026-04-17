@props([
    'variant' => 'primary',
    'size' => 'default',
    'href' => null,
    'type' => 'button',
])

@php
    $variants = [
        'primary'   => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 shadow-sm',
        'secondary' => 'bg-surface-100 text-surface-700 hover:bg-surface-200 focus:ring-surface-500',
        'danger'    => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-sm',
        'success'   => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500 shadow-sm',
        'warning'   => 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500 shadow-sm',
        'ghost'     => 'bg-transparent text-surface-700 hover:bg-surface-100 focus:ring-surface-500',
    ];

    $sizes = [
        'xs'      => 'px-2.5 py-1.5 text-xs',
        'sm'      => 'px-3 py-2 text-sm',
        'default' => 'px-4 py-2.5 text-sm',
        'lg'      => 'px-5 py-3 text-base',
    ];

    $classes = 'inline-flex items-center justify-center gap-2 font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed '
        . ($variants[$variant] ?? $variants['primary']) . ' '
        . ($sizes[$size] ?? $sizes['default']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
