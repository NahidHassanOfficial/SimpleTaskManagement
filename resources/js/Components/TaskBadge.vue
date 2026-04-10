<script setup>
import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    type: { type: String, default: 'status' },
    value: { type: String, required: true },
});

const page = usePage();
const enums = computed(() => page.props.enums);

const item = computed(() => {
    const list = props.type === 'severity' ? enums.value.severities : enums.value.statuses;
    return list.find(i => i.value === props.value);
});

const color = computed(() => item.value?.color || 'surface');

function formatLabel(value) {
    return item.value?.label || value.charAt(0).toUpperCase() + value.slice(1);
}
</script>

<template>
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium ring-1 ring-inset capitalize transition-default"
        :class="`bg-${color}-100 text-${color}-700 ring-${color}-200`">
        {{ formatLabel(value) }}
    </span>
</template>
