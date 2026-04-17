@props([
    'title' => null,
    'description' => null,
    'padding' => true,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-xl border border-surface-200 shadow-sm']) }}>
    @if($title || $description)
        <div class="px-6 py-4 border-b border-surface-100">
            @if($title)
                <h3 class="text-lg font-semibold text-surface-900">{{ $title }}</h3>
            @endif
            @if($description)
                <p class="mt-1 text-sm text-surface-500">{{ $description }}</p>
            @endif
        </div>
    @endif

    <div @class([$padding ? 'p-6' : ''])>
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 border-t border-surface-100 bg-surface-50 rounded-b-xl">
            {{ $footer }}
        </div>
    @endif
</div>
