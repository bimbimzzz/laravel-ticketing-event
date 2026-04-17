@props([
    'variant' => 'primary',
])

@php
    $variants = [
        'primary'   => 'bg-primary-50 text-primary-700 ring-primary-600/20',
        'secondary' => 'bg-surface-50 text-surface-600 ring-surface-500/20',
        'success'   => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'warning'   => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'danger'    => 'bg-red-50 text-red-700 ring-red-600/20',
        'info'      => 'bg-sky-50 text-sky-700 ring-sky-600/20',
    ];

    $classes = 'inline-flex items-center px-2 py-1 text-xs font-medium rounded-md ring-1 ring-inset '
        . ($variants[$variant] ?? $variants['primary']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
