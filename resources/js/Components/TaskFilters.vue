<script setup>
import { ref, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);

const props = defineProps({
    filters: { type: Object, default: () => ({}) },
    users: { type: Array, default: () => [] },
    errors: { type: Object, default: () => ({}) },
});

const emit = defineEmits(['filter']);

const taskCode = ref(props.filters.task_code || '');
const status = ref(props.filters.status || '');
const severity = ref(props.filters.severity || '');
const assignedTo = ref(props.filters.assigned_to || '');
const dueDateFrom = ref(props.filters.due_date_from || '');
const dueDateTo = ref(props.filters.due_date_to || '');

function search() {
    const f = {};
    if (taskCode.value) f.task_code = taskCode.value;
    if (status.value) f.status = status.value;
    if (severity.value) f.severity = severity.value;
    if (assignedTo.value) f.assigned_to = assignedTo.value;
    if (dueDateFrom.value) f.due_date_from = dueDateFrom.value;
    if (dueDateTo.value) f.due_date_to = dueDateTo.value;
    emit('filter', f);
}

function clearAll() {
    taskCode.value = '';
    status.value = '';
    severity.value = '';
    assignedTo.value = '';
    dueDateFrom.value = '';
    dueDateTo.value = '';
    emit('filter', {});
}

function hasFilters() {
    return !!(taskCode.value || status.value || severity.value || assignedTo.value || dueDateFrom.value || dueDateTo.value);
}
</script>

<template>
    <div class="animate-fade-in rounded-xl border border-surface-200 bg-white p-6 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-6 items-end">
            <!-- Task Code -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Task ID</label>
                <input v-model="taskCode" type="text" placeholder="TASK-0001" @keyup.enter="search"
                    class="h-10 w-full rounded-lg border border-surface-200 bg-surface-50/30 px-3 text-sm text-surface-800 placeholder-surface-400 outline-none focus:border-accent-500 focus:ring-4 focus:ring-accent-100 transition-default" />
            </div>

            <!-- Status -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Status</label>
                <select v-model="status"
                    class="h-10 w-full rounded-lg border border-surface-200 bg-surface-50/30 px-3 text-sm text-surface-800 outline-none appearance-none focus:border-accent-500 focus:ring-4 focus:ring-accent-100 transition-default">
                    <option value="">All Status</option>
                    <option v-for="statusOption in statuses" :key="statusOption.value" :value="statusOption.value">{{ statusOption.label }}</option>
                </select>
            </div>

            <!-- Severity -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Severity</label>
                <select v-model="severity"
                    class="h-10 w-full rounded-lg border border-surface-200 bg-surface-50/30 px-3 text-sm text-surface-800 outline-none appearance-none focus:border-accent-500 focus:ring-4 focus:ring-accent-100 transition-default">
                    <option value="">All Severity</option>
                    <option v-for="severityOption in severities" :key="severityOption.value" :value="severityOption.value">{{ severityOption.label }}</option>
                </select>
            </div>

            <!-- Assigned To -->
            <div v-if="users.length" class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">Assigned</label>
                <select v-model="assignedTo"
                    class="h-10 w-full rounded-lg border border-surface-200 bg-surface-50/30 px-3 text-sm text-surface-800 outline-none appearance-none focus:border-accent-500 focus:ring-4 focus:ring-accent-100 transition-default">
                    <option value="">All Users</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>
            </div>

            <!-- Date Range -->
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">From</label>
                <input v-model="dueDateFrom" type="date"
                    class="h-10 w-full rounded-lg border border-surface-200 bg-surface-50/30 px-3 text-sm text-surface-800 outline-none focus:border-accent-500 focus:ring-4 focus:ring-accent-100 transition-default" />
            </div>
            <div class="flex flex-col gap-1.5">
                <label class="text-[10px] font-bold text-surface-400 uppercase tracking-widest">To</label>
                <div class="relative w-full">
                    <input v-model="dueDateTo" type="date"
                        class="h-10 w-full rounded-lg border border-surface-200 bg-surface-50/30 px-3 text-sm text-surface-800 outline-none focus:border-accent-500 focus:ring-4 focus:ring-accent-100 transition-default"
                        :class="{ 'border-danger-500': errors.due_date_to }" />
                    <p v-if="errors.due_date_to"
                        class="absolute top-11 left-0 text-[10px] text-danger-600 font-bold whitespace-nowrap">{{
                        errors.due_date_to }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center gap-1.5 min-w-fit">
                <button @click="search"
                    class="h-10 px-3 rounded-lg text-xs font-bold text-white bg-accent-600 hover:bg-accent-700 shadow-md transition-default flex items-center justify-center gap-1.5 uppercase">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Search
                </button>
                <button v-if="hasFilters()" @click="clearAll" title="Clear Filters"
                    class="h-10 px-3 rounded-lg text-surface-400 hover:text-surface-700 bg-surface-50 border border-surface-100 transition-default flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 0 0 4.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 0 1-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
