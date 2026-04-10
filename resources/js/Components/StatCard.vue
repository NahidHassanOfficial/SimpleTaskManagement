<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    label: { type: String, required: true },
    value: { type: [Number, String], required: true },
    color: { type: String, default: 'accent' },
    icon: { type: String, default: 'clipboard' },
    delay: { type: Number, default: 0 },
    href: { type: String, default: null },
});

const iconPaths = {
    clipboard: 'M9 5H7a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-2M9 5a2 2 0 0 0 2 2h2a2 2 0 0 0 2-2M9 5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2',
    clock: 'M12 8v4l3 3m6-3a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',
    loader: 'M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15',
    check: 'M9 12l2 2 4-4m6 2a9 9 0 1 1-18 0 9 9 0 0 1 18 0z',
};
</script>

<template>
    <component :is="href ? Link : 'div'" :href="href" class="group block relative overflow-hidden rounded-xl border border-surface-200
               bg-white p-5 shadow-sm transition-default animate-fade-up"
        :class="href ? 'hover:shadow-md hover:border-surface-300 hover:scale-[1.01]' : 'cursor-default'"
        :style="{ animationDelay: delay + 'ms' }">

        <!-- Gradient glow on hover -->
        <div class="absolute inset-0 opacity-0 group-hover:opacity-100 transition-default pointer-events-none" :class="{
            'bg-gradient-to-br from-accent-50 to-transparent': color === 'accent',
            'bg-gradient-to-br from-success-50 to-transparent': color === 'success',
            'bg-gradient-to-br from-warning-50 to-transparent': color === 'warning',
            'bg-gradient-to-br from-danger-50 to-transparent': color === 'danger',
            'bg-gradient-to-br from-info-50 to-transparent': color === 'info',
        }"></div>

        <p class="relative z-10 text-xs font-medium uppercase tracking-wider text-surface-400 mb-3">{{ label }}</p>

        <div class="relative z-10 flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" :class="{
                'bg-accent-100  text-accent-600': color === 'accent',
                'bg-success-100 text-success-600': color === 'success',
                'bg-warning-100 text-warning-600': color === 'warning',
                'bg-danger-100  text-danger-600': color === 'danger',
                'bg-info-100    text-info-600': color === 'info',
            }">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path :d="iconPaths[icon] || iconPaths.clipboard" />
                </svg>
            </div>
            <span class="text-3xl font-bold text-surface-900 tabular-nums">{{ value }}</span>
        </div>
    </component>
</template>
