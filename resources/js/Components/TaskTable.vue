<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import TaskBadge from './TaskBadge.vue';

const page = usePage();
const statuses = computed(() => page.props.enums.statuses);
const severities = computed(() => page.props.enums.severities);

defineProps({
    tasks: { type: Array, default: () => [] },
});

defineEmits(['view', 'edit', 'delete', 'markComplete', 'changeStatus', 'changeSeverity']);

// Track which row has an open inline dropdown
const editingStatus = ref(null);
const editingSeverity = ref(null);

function openStatusEdit(taskId) {
    editingStatus.value = editingStatus.value === taskId ? null : taskId;
    editingSeverity.value = null;
}
function openSeverityEdit(taskId) {
    editingSeverity.value = editingSeverity.value === taskId ? null : taskId;
    editingStatus.value = null;
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
}


</script>

<template>
    <div class="space-y-4">
        <!-- Desktop Table View (lg screens and above) -->
        <div
            class="hidden lg:block animate-fade-in rounded-xl border border-surface-200 bg-white shadow-sm overflow-hidden text-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-surface-200 bg-surface-50">
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-surface-500">
                                ID</th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-surface-500">
                                Title</th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-surface-500">
                                Assigned To</th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-surface-500">
                                Severity</th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-surface-500">
                                Status</th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-left text-xs font-semibold uppercase tracking-wider text-surface-500">
                                Due Date</th>
                            <th
                                class="whitespace-nowrap px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-surface-500 w-[140px]">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="task in tasks" :key="task.id"
                            class="border-b border-surface-100 hover:bg-accent-50/50 transition-default group">
                            <td class="whitespace-nowrap px-4 py-3 font-mono text-[11px] text-surface-400">{{
                                task.task_code }}</td>
                            <td class="px-4 py-3">
                                <Link :href="`/tasks/${task.id}`"
                                    class="text-accent-600 hover:text-accent-700 font-medium text-left transition-default hover:underline underline-offset-2">
                                    {{ task.title }}
                                </Link>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-surface-600">{{ task.assigned_user?.name || '—'
                                }}</td>
                            <td class="whitespace-nowrap px-4 py-3 relative">
                                <button @click="openSeverityEdit(task.id)"
                                    class="transition-default hover:ring-2 hover:ring-surface-200 rounded-md">
                                    <TaskBadge type="severity" :value="task.severity" />
                                </button>
                                <div v-if="editingSeverity === task.id"
                                    class="absolute z-20 mt-1 left-4 w-36 rounded-xl border border-surface-200 bg-white shadow-lg py-1 animate-scale-in">
                                    <button v-for="severityOption in severities" :key="severityOption.value"
                                        @click="editingSeverity = null; if (severityOption.value !== task.severity) $emit('changeSeverity', task, severityOption.value)"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-surface-50 transition-default flex items-center justify-between"
                                        :class="severityOption.value === task.severity ? 'text-accent-600 font-medium bg-accent-50/50' : 'text-surface-600'">
                                        {{ severityOption.label }}
                                        <svg v-if="severityOption.value === task.severity" class="w-4 h-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 relative">
                                <button @click="openStatusEdit(task.id)"
                                    class="transition-default hover:ring-2 hover:ring-surface-200 rounded-md">
                                    <TaskBadge type="status" :value="task.status" />
                                </button>
                                <div v-if="editingStatus === task.id"
                                    class="absolute z-20 mt-1 left-4 w-40 rounded-xl border border-surface-200 bg-white shadow-lg py-1 animate-scale-in">
                                    <button v-for="statusOption in statuses" :key="statusOption.value"
                                        @click="editingStatus = null; if (statusOption.value !== task.status) $emit('changeStatus', task, statusOption.value)"
                                        class="w-full text-left px-3 py-2 text-sm hover:bg-surface-50 transition-default flex items-center justify-between"
                                        :class="statusOption.value === task.status ? 'text-accent-600 font-medium bg-accent-50/50' : 'text-surface-600'">
                                        {{ statusOption.label }}
                                        <svg v-if="statusOption.value === task.status" class="w-4 h-4" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                            <td class="whitespace-nowrap px-4 py-3 text-surface-500 text-xs">{{
                                formatDate(task.due_date) }}</td>
                            <td class="whitespace-nowrap px-4 py-3">
                                <div class="flex items-center justify-center gap-1">
                                    <button @click="$emit('view', task)" title="View"
                                        class="w-8 h-8 rounded-lg flex items-center justify-center text-surface-400 hover:text-accent-600 hover:bg-accent-50 transition-default">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                    <button v-if="task.status !== 'completed'" @click="$emit('markComplete', task)"
                                        title="Complete"
                                        class="w-8 h-8 rounded-lg text-surface-400 hover:text-success-600 hover:bg-success-50 transition-default flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </button>
                                    <button v-if="task.status !== 'completed'" @click="$emit('edit', task)" title="Edit"
                                        class="w-8 h-8 rounded-lg text-surface-400 hover:text-warning-600 hover:bg-warning-50 transition-default flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    <button v-if="task.status === 'pending'" @click="$emit('delete', task)"
                                        title="Delete"
                                        class="w-8 h-8 rounded-lg text-surface-400 hover:text-danger-600 hover:bg-danger-50 transition-default flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="!tasks.length">
                            <td colspan="7" class="px-4 py-16 text-center text-surface-400">No tasks found</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Grid View (Hidden on large screens) -->
        <div class="lg:hidden grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="task in tasks" :key="task.id"
                class="bg-white rounded-xl border border-surface-200 p-5 shadow-sm space-y-4">
                <div class="flex items-start justify-between">
                    <div class="space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="font-mono text-[10px] text-surface-400">{{ task.task_code }}</span>
                            <div class="flex items-center gap-1.5">
                                <TaskBadge type="severity" :value="task.severity" />
                                <TaskBadge type="status" :value="task.status" />
                            </div>
                        </div>
                        <h3 class="font-bold text-surface-900 leading-snug">{{ task.title }}</h3>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4 text-xs">
                    <div>
                        <p class="text-surface-400 font-medium mb-1 uppercase tracking-wider">Assigned</p>
                        <p class="text-surface-800 font-semibold">{{ task.assigned_user?.name || '—' }}</p>
                    </div>
                    <div>
                        <p class="text-surface-400 font-medium mb-1 uppercase tracking-wider">Due Date</p>
                        <p class="text-surface-800 font-semibold">{{ formatDate(task.due_date) }}</p>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 pt-4 border-t border-surface-100">
                    <BaseButton variant="secondary" size="sm" @click="$emit('view', task)">View</BaseButton>
                    <BaseButton v-if="task.status !== 'completed'" variant="warning" size="sm"
                        @click="$emit('edit', task)">Edit</BaseButton>
                    <BaseButton v-if="task.status === 'pending'" variant="danger" size="sm"
                        @click="$emit('delete', task)">Delete</BaseButton>
                    <BaseButton v-if="task.status !== 'completed'" variant="success" size="sm"
                        @click="$emit('markComplete', task)">Done</BaseButton>
                </div>
            </div>

            <div v-if="!tasks.length"
                class="bg-white rounded-xl border border-surface-200 p-12 text-center text-surface-400">
                No tasks found
            </div>
        </div>
    </div>
</template>
