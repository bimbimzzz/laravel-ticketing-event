@props([
    'label' => null,
    'name' => '',
    'required' => false,
    'hint' => null,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-surface-700 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <div class="relative">
        <select
            name="{{ $name }}"
            id="{{ $name }}"
            {{ $required ? 'required' : '' }}
            {{ $attributes->merge([
                'class' => 'block w-full rounded-lg border px-3 py-2.5 pr-10 text-sm text-surface-900 appearance-none focus:outline-none focus:ring-2 focus:ring-offset-0 transition-colors '
                    . ($errors->has($name)
                        ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                        : 'border-surface-300 focus:border-primary-500 focus:ring-primary-500/20')
            ]) }}
        >
            {{ $slot }}
        </select>

        {{-- Custom arrow --}}
        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
            <svg class="w-4 h-4 text-surface-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </div>

    @error($name)
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if($hint && !$errors->has($name))
        <p class="mt-1.5 text-sm text-surface-500">{{ $hint }}</p>
    @endif
</div>
