@php
    $types = ['success', 'error', 'warning', 'info'];
    $message = null;
    $type = 'info';

    foreach ($types as $t) {
        if (session($t)) {
            $message = session($t);
            $type = $t;
            break;
        }
    }
@endphp

<div x-data="{ toasts: [] }"
     @toast.window="
        let toast = { id: Date.now(), message: $event.detail.message, type: $event.detail.type || 'info' };
        toasts.push(toast);
        setTimeout(() => toasts = toasts.filter(t => t.id !== toast.id), 5000);
     "
     @if($message)
     x-init="
        let toast = { id: Date.now(), message: '{{ addslashes($message) }}', type: '{{ $type }}' };
        toasts.push(toast);
        setTimeout(() => toasts = toasts.filter(t => t.id !== toast.id), 5000);
     "
     @endif
     class="fixed top-4 right-4 z-[60] space-y-2 w-80">

    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="true"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-8"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-8"
             :class="{
                'bg-emerald-50 text-emerald-800 border-emerald-200': toast.type === 'success',
                'bg-red-50 text-red-800 border-red-200': toast.type === 'error',
                'bg-amber-50 text-amber-800 border-amber-200': toast.type === 'warning',
                'bg-sky-50 text-sky-800 border-sky-200': toast.type === 'info',
             }"
             class="flex items-center gap-3 p-4 rounded-lg border shadow-lg">
            <span class="text-sm flex-1" x-text="toast.message"></span>
            <button @click="toasts = toasts.filter(t => t.id !== toast.id)" class="opacity-70 hover:opacity-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </template>
</div>
