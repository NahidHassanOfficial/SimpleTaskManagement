<script setup>
defineProps({
    links: { type: Array, default: () => [] },
    currentPage: { type: Number, default: 1 },
    lastPage: { type: Number, default: 1 },
    total: { type: Number, default: 0 },
    from: { type: Number, default: 0 },
    to: { type: Number, default: 0 },
});

const emit = defineEmits(['navigate']);

function go(url) {
    if (url) emit('navigate', url);
}
</script>

<template>
    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 animate-fade-in" v-if="lastPage > 1">
        <p class="text-xs text-surface-500">
            Showing <span class="font-semibold text-surface-700">{{ from }}</span>
            to <span class="font-semibold text-surface-700">{{ to }}</span>
            of <span class="font-semibold text-surface-700">{{ total }}</span> tasks
        </p>

        <div class="flex items-center gap-1">
            <template v-for="(link, index) in links" :key="index">
                <button v-if="link.url || link.active" @click="go(link.url)" :disabled="!link.url" class="h-8 min-w-8 px-2.5 rounded-lg text-xs font-medium transition-default
                           disabled:opacity-40 disabled:cursor-not-allowed"
                    :class="link.active
                        ? 'bg-accent-600 text-white shadow-sm'
                        : 'bg-white text-surface-600 hover:bg-surface-100 hover:text-surface-800 border border-surface-200'" v-html="link.label"></button>
                <span v-else class="px-1 text-surface-400 text-xs" v-html="link.label"></span>
            </template>
        </div>
    </div>
</template>
