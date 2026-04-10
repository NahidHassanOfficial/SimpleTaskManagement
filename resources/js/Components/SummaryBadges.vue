<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

defineProps({
    statusSummary: { type: Object, default: () => ({}) },
    severitySummary: { type: Object, default: () => ({}) },
    totalTasks: { type: Number, default: 0 },
});

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);
</script>

<template>
    <div class="flex flex-wrap items-center gap-y-3 gap-x-4 animate-fade-in">
        <!-- Total Group -->
        <div class="flex items-center">
            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-accent-100 text-accent-700
                         text-xs font-bold ring-1 ring-inset ring-accent-200 shadow-sm">
                Total: {{ totalTasks }}
            </span>
        </div>

        <!-- Status Group -->
        <div class="flex flex-wrap items-center gap-2 border-l border-surface-200 pl-4 ml-0">
            <span v-for="status in statuses" :key="status.value"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white
                       text-[11px] font-semibold text-surface-600 ring-1 ring-inset ring-surface-200 hover:bg-surface-50 transition-colors shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full" :class="`bg-${status.color}-500` || 'bg-surface-400'"></span>
                {{ status.label }}: {{ statusSummary[status.value] || 0 }}
            </span>
        </div>

        <!-- Severity Group -->
        <div class="flex flex-wrap items-center gap-2 border-l border-surface-200 pl-4 ml-0">
            <span v-for="severity in severities" :key="severity.value"
                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg bg-white
                       text-[11px] font-semibold text-surface-600 ring-1 ring-inset ring-surface-200 hover:bg-surface-50 transition-colors shadow-sm">
                <span class="w-1.5 h-1.5 rounded-full" :class="`bg-${severity.color}-500` || 'bg-surface-400'"></span>
                {{ severity.label }}: {{ severitySummary[severity.value] || 0 }}
            </span>
        </div>
    </div>
</template>
