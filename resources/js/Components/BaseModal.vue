<script setup>
defineProps({
    visible: { type: Boolean, default: false },
    title: { type: String, default: '' },
    size: { type: String, default: 'max-w-lg' },
});

defineEmits(['close']);
</script>

<template>
    <Teleport to="body">
        <Transition name="modal">
            <div v-if="visible" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-surface-900/40 backdrop-blur-sm transition-opacity"
                    @click="$emit('close')"></div>

                <!-- Content -->
                <div
                    :class="['relative w-full rounded-2xl border border-surface-200 bg-white shadow-2xl animate-scale-in overflow-hidden', size]">
                    <!-- Header -->
                    <div v-if="title || $slots.header"
                        class="flex items-center justify-between border-b border-surface-100 px-6 py-4.5">
                        <slot name="header">
                            <h2 class="text-lg font-bold text-surface-900">{{ title }}</h2>
                        </slot>
                        <button @click="$emit('close')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-surface-400 hover:text-surface-700 hover:bg-surface-100 transition-default">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Body -->
                    <div class="overflow-y-auto max-h-[80vh]">
                        <slot />
                    </div>

                    <!-- Footer -->
                    <div v-if="$slots.footer" class="border-t border-surface-100 px-6 py-4 bg-surface-50/50">
                        <slot name="footer" />
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}

.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
