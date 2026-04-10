<script setup>
import { ref, watch } from 'vue';
import BaseModal from './BaseModal.vue';
import BaseButton from './BaseButton.vue';

const props = defineProps({
    visible: { type: Boolean, default: false },
    title: { type: String, default: 'Are you sure?' },
    message: { type: String, default: 'This action cannot be undone.' },
    confirmText: { type: String, default: 'Confirm' },
    cancelText: { type: String, default: 'Cancel' },
    variant: { type: String, default: 'danger' },
});

const emit = defineEmits(['confirm', 'cancel']);

const loading = ref(false);

watch(() => props.visible, (val) => {
    if (val) { loading.value = false; }
});

function proceed() {
    loading.value = true;
    emit('confirm');
}
</script>

<template>
    <BaseModal :visible="visible" @close="$emit('cancel')" size="max-w-md">
        <div class="px-6 pt-8 pb-6 text-center">
            <div class="mx-auto w-14 h-14 rounded-full flex items-center justify-center mb-5"
                :class="variant === 'danger' ? 'bg-danger-50 text-danger-600' : 'bg-warning-50 text-warning-600'">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>

            <h3 class="text-lg font-bold text-surface-900 mb-2">{{ title }}</h3>
            <p class="text-sm text-surface-500 leading-relaxed">{{ message }}</p>
        </div>

        <template #footer>
            <div class="flex items-center gap-3">
                <BaseButton variant="secondary" class="flex-1" @click="$emit('cancel')">{{ cancelText }}</BaseButton>
                <BaseButton :variant="variant" class="flex-1" :loading="loading" @click="proceed">
                    {{ confirmText }}
                </BaseButton>
            </div>
        </template>
    </BaseModal>
</template>
