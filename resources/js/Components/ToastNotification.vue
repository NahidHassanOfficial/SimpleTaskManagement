<script setup>
import { ref, onMounted, onUnmounted } from 'vue';

const toasts = ref([]);
let nextId = 0;

function addToast(message, type = 'success', duration = 4000) {
    const id = nextId++;
    toasts.value.push({ id, message, type, leaving: false });
    setTimeout(() => removeToast(id), duration);
}

function removeToast(id) {
    const foundToast = toasts.value.find(foundToast => foundToast.id === id);
    if (foundToast) {
        foundToast.leaving = true;
        setTimeout(() => { toasts.value = toasts.value.filter(foundToast => foundToast.id !== id); }, 300);
    }
}

// Expose globally via event bus
function handleToast(e) { addToast(e.detail.message, e.detail.type, e.detail.duration); }
onMounted(() => window.addEventListener('app-toast', handleToast));
onUnmounted(() => window.removeEventListener('app-toast', handleToast));

const typeStyles = {
    success: 'bg-success-50 border-success-200 text-success-700',
    error: 'bg-danger-50 border-danger-200 text-danger-700',
    warning: 'bg-warning-50 border-warning-200 text-warning-700',
    info: 'bg-accent-50 border-accent-200 text-accent-700',
};

const typeIcons = {
    success: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    error: 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',
    warning: 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z',
    info: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
};
</script>

<template>
    <Teleport to="body">
        <div class="fixed top-4 right-4 z-70 flex flex-col gap-2 pointer-events-none">
            <div v-for="toast in toasts" :key="toast.id"
                class="pointer-events-auto max-w-sm w-full rounded-xl border px-4 py-3 shadow-lg flex items-start gap-3 transition-all duration-300"
                :class="[typeStyles[toast.type], toast.leaving ? 'opacity-0 translate-x-4' : 'opacity-100 translate-x-0 animate-fade-in']">
                <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="typeIcons[toast.type]" />
                </svg>
                <p class="text-sm font-medium flex-1">{{ toast.message }}</p>
                <button @click="removeToast(toast.id)" class="shrink-0 opacity-60 hover:opacity-100 transition-default">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </Teleport>
</template>
