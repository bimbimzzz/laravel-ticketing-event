@props([
    'label' => null,
    'name' => '',
    'required' => false,
    'hint' => null,
    'rows' => 4,
])

<div>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-surface-700 mb-1.5">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $name }}"
        rows="{{ $rows }}"
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge([
            'class' => 'block w-full rounded-lg border px-3 py-2.5 text-sm text-surface-900 placeholder:text-surface-400 focus:outline-none focus:ring-2 focus:ring-offset-0 transition-colors resize-y '
                . ($errors->has($name)
                    ? 'border-red-300 focus:border-red-500 focus:ring-red-500/20'
                    : 'border-surface-300 focus:border-primary-500 focus:ring-primary-500/20')
        ]) }}
    >{{ old($name, $slot) }}</textarea>

    @error($name)
        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
    @enderror

    @if($hint && !$errors->has($name))
        <p class="mt-1.5 text-sm text-surface-500">{{ $hint }}</p>
    @endif
</div>
