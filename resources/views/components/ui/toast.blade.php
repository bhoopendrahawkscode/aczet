@props([
    'position' => 'top-right',
])

<div
    x-data="
        @entangle($store.toast.toasts).as('toasts')
    "
    x-init="
        $watch('toasts', (value) => {
            // Auto-dismiss is handled by store
        });
    "
    class="fixed z-[var(--z-toast)] flex flex-col gap-3 p-4 pointer-events-none {{ $position === 'top-right' ? 'top-4 right-4' : '' }} {{ $position === 'top-left' ? 'top-4 left-4' : '' }} {{ $position === 'bottom-right' ? 'bottom-4 right-4' : '' }} {{ $position === 'bottom-left' ? 'bottom-4 left-4' : '' }} {{ $position === 'top-center' ? 'top-4 left-1/2 -translate-x-1/2' : '' }} {{ $position === 'bottom-center' ? 'bottom-4 left-1/2 -translate-x-1/2' : '' }}"
    role="region"
    aria-live="polite"
    aria-label="Notifications"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-full"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-full"
            class="pointer-events-auto min-w-[300px] max-w-md rounded-xl shadow-xl border overflow-hidden flex items-start gap-3"
            :class="
                toast.type === 'success' ? 'bg-white border-emerald-200' :
                toast.type === 'error' ? 'bg-white border-rose-200' :
                toast.type === 'warning' ? 'bg-white border-amber-200' :
                'bg-white border-cyan-200'
            "
            role="alert"
        >
            <div class="flex-shrink-0 mt-0.5" :class="toast.type === 'success' ? 'text-emerald-500' : (toast.type === 'error' ? 'text-rose-500' : (toast.type === 'warning' ? 'text-amber-500' : 'text-cyan-500'))">
                @if(toast.type === 'success')
                    <x-ui.icon name="circle-check" class="h-6 w-6" />
                @elseif(toast.type === 'error')
                    <x-ui.icon name="circle-x" class="h-6 w-6" />
                @elseif(toast.type === 'warning')
                    <x-ui.icon name="triangle-alert" class="h-6 w-6" />
                @else
                    <x-ui.icon name="circle-info" class="h-6 w-6" />
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-[var(--color-text-primary)]">{{ toast.message }}</p>
            </div>
            <button
                @click="$store.toast.dismiss(toast.id)"
                class="flex-shrink-0 p-1 text-[var(--color-text-muted)] hover:text-[var(--color-text-primary)] transition-colors rounded-lg hover:bg-[var(--color-bg-tertiary)]"
                aria-label="Dismiss notification"
            >
                <x-ui.icon name="x" class="h-5 w-5" />
            </button>
        </div>
    </template>
</div>