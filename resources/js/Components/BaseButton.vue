<script setup>
const props = defineProps({
    variant: { type: String, default: 'primary' }, // primary, secondary, danger, warning, success, ghost
    size: { type: String, default: 'md' },      // sm, md, lg
    loading: { type: Boolean, default: false },
    disabled: { type: Boolean, default: false },
    type: { type: String, default: 'button' },
});

const variants = {
    primary: 'bg-accent-600 text-white hover:bg-accent-700 shadow-md shadow-accent-600/20',
    secondary: 'bg-white text-surface-700 border border-surface-200 hover:bg-surface-50 hover:border-surface-300',
    danger: 'bg-danger-600 text-white hover:bg-danger-700 shadow-md shadow-danger-600/20',
    warning: 'bg-warning-500 text-white hover:bg-warning-600 shadow-md shadow-warning-500/10',
    success: 'bg-success-600 text-white hover:bg-success-700 shadow-md shadow-success-600/20',
    ghost: 'bg-transparent text-surface-500 hover:bg-surface-100 hover:text-surface-700',
};

const sizes = {
    sm: 'h-8 px-3 text-xs font-semibold rounded-lg',
    md: 'h-10 px-5 text-sm font-semibold rounded-xl',
    lg: 'h-12 px-8 text-base font-bold rounded-2xl',
};
</script>

<template>
    <button :type="type" :disabled="disabled || loading"
        class="inline-flex items-center justify-center gap-2 transition-all active:scale-[0.98] disabled:opacity-50 disabled:pointer-events-none"
        :class="[variants[variant], sizes[size]]">
        <svg v-if="loading" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        <slot v-else name="icon" />
        <slot />
    </button>
</template>
