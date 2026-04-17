@props([
    'label' => null,
    'name' => '',
    'checked' => false,
    'hint' => null,
])

<div x-data="{ on: {{ $checked ? 'true' : 'false' }} }">
    <div class="flex items-center gap-3">
        <button type="button"
                @click="on = !on"
                :class="on ? 'bg-primary-600' : 'bg-surface-200'"
                class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2"
                role="switch"
                :aria-checked="on.toString()">
            <span :class="on ? 'translate-x-5' : 'translate-x-0'"
                  class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out mt-0.5 ml-0.5"></span>
        </button>
        <input type="hidden" name="{{ $name }}" :value="on ? '1' : '0'">

        @if($label)
            <label @click="on = !on" class="text-sm font-medium text-surface-700 cursor-pointer select-none">
                {{ $label }}
            </label>
        @endif
    </div>

    @if($hint)
        <p class="mt-1.5 text-sm text-surface-500 ml-14">{{ $hint }}</p>
    @endif
</div>
